<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\User;

class ProfileController extends Controller {

    public function allFollowers(User $user) {
        return view("profile.followers")->with(["user" => $user]);
    }

    public function allFollowing(User $user) {
        return view("profile.following")->with(["user" => $user]);
    }

    public function allLikes(User $user) {
        $likes = $user->likes()->latest()->paginate(12);
        return view('profile.likes')->with(['user' => $user, 'likes' => $likes]);
    }

}
