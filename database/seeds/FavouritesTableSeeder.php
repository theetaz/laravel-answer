<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FavouritesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('favourites')->delete();

        $users = \App\User::pluck('id')->all();
        $numbers_of_users = count($users);

        foreach (\App\Question::all() as $question)
        {
            for($i = 0; $i < rand(1, $numbers_of_users); $i++){

                $user = $users[$i];

                $question->favourites()->attach($user);
            }
        }

    }
}
