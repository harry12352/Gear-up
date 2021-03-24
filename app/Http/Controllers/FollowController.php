<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Follower;

class FollowController extends Controller
{
    public function __construct()
    {
        $this->middleware("auth");
    }

    public function follow(User $user)
    {
        $followedUser = $user;
        $followingUser = Auth::user();

        if (count($this->isFollowing($followingUser, $followedUser))) {
            return response()->json(['message' => "You are already following this user"], 403);
        }
        // Creating database record of follower.
        return $this->addNewFollower($followingUser, $followedUser);
    }

    public function unFollow(User $user)
    {
        $followedUser = $user;
        $followingUser = Auth::user();

        if (!count($this->isFollowing($followingUser, $followedUser))) {
            return back(302)
                ->with("error", "You cannot unfollow user who you don't follow.");
        }
        // Creating database record of follower.
        $this->removeFollower($followingUser, $followedUser);


        return response()->json(['message' => "You unfollowed <b>{$followedUser->firstname} {$followedUser->lastname}</b>"], 200);

    }

    protected function isFollowing($followingUser, $followedUser)
    {
        return $followingUser->following()->where("user_id", $followedUser->id)->get();
    }

    protected function removeFollower($followingUser, $followedUser)
    {
        // Deleting the follow relationship between user.
        $followingUser->following()->where("user_id", $followedUser->id)->delete();
    }

    protected function addNewFollower($followingUser, $followedUser)
    {
        if ($followingUser['id'] != $followedUser['id']) {
            Follower::create([
                "user_id" => $followedUser->id,
                "follower_id" => $followingUser->id
            ]);
            NotificationController::newFollowerNotification($followingUser, $followedUser);
            return response()->json(['message' => "You are now following " . $followedUser['first_name'] . " " . $followedUser['last_name']], 200);
        }
        return response()->json(['error' => true, 'message' => "You can not follow yourself"], 403);
    }
}
