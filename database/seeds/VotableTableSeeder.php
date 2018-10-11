<?php

use Illuminate\Database\Seeder;

class VotableTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \Illuminate\Support\Facades\DB::table('votables')->where('votable_type', 'App\Question')->delete();

        $users = \App\User::all();
        $numbers_of_users = $users->count();
        $votes = [-1, 1];

        foreach (\App\Question::all() as $question)
        {
            for($i = 0; $i < rand(1, $numbers_of_users); $i++){
                $user = $users[$i];
                $user->voteQuestion($question, $votes[rand(0,1)]);
            }
        }
    }
}
