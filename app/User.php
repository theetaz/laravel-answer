<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function getUrlAttribute()
    {
        return route("questions.show", $this->id);
    }

    public function Questions()
    {
        return $this->hasMany(Question::class);
    }

    public function answers()
    {
        return $this->hasMany(Answer::class);
    }

    public function getAvatarAttribute()
    {
        $email = $this->email;
        $size = 32;
        return "https://www.gravatar.com/avatar/" . md5( strtolower( trim( $email ) ) ) . "?s=" . $size;

    }

    public function favourites()
    {
        return $this->belongsToMany(Question::class, 'favourites')->withTimestamps();
    }

    public function voteQuestions()
    {
        return $this->morphedByMany(Question::class, 'votable');
    }

    public function voteAnswers()
    {
        return $this->morphedByMany(Answer::class, 'votable');
    }

    public function voteQuestion(Question $question, $vote)
    {
        $vote_questions = $this->voteQuestions();

        if($vote_questions->where('votable_id', $question->id)->exists()){
            $vote_questions->updateExistingPivot($question, ['vote' => $vote]);
        }else{
            $vote_questions->attach($question, ['vote' => $vote]);
        }

        $question->load('votes');
        $down_votes = (int) $question->downVotes()->sum('vote');
        $up_votes = (int) $question->upVotes()->sum('vote');

        $question->votes_count = $up_votes + $down_votes;
        $question->save();
    }
}
