<?php

namespace App\Http\Controllers;

use App\Answer;
use Illuminate\Auth\Access\AuthorizationException;

class AcceptAnswerController extends Controller
{
    public function __invoke(Answer $answer)
    {
        try {

            $this->authorize('accept', $answer);
            $answer->question->acceptBestAnswer($answer);

        } catch (AuthorizationException $e) {

            return $e->getMessage();
        }



        return redirect()->back();
    }
}
