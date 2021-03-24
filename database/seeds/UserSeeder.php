<?php

use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = factory(App\Models\User::class, 30)->create();
        factory(App\Models\User::class, 50)->create()->each(function ($user) use ($users) {
            // Getting random number of user between 1 to 10
            $userFollowers = $users->random(rand(1, 10));
            // Asigning users as followers.
            foreach ($userFollowers as $userFollower){
                $user->followers()->save(\App\Models\Follower::create([
                    "user_id" => $user->id,
                    "follower_id" => $userFollower->id,
                ]));
            }

        });
    }
}
