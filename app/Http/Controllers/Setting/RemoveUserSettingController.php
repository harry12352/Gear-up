<?php

namespace App\Http\Controllers\Setting;

use App\Bid;
use App\Models\File;
use App\FollowCategory;
use App\Follower;
use App\Like;
use App\Models\Product;
use App\Review;
use App\Share;
use App\ShippingInformation;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class RemoveUserSettingController extends SettingsController
{
    protected $view = "settings.delete";

    protected $validationRules = [];

    public static function deleteAccount(){
        $user = User::find(Auth::id());
        Auth::logout();
        if ($user->delete()) {
            ShippingInformation::where('user_id',$user['id'])->delete();
            File::where('resource_id',$user['id'])->delete();
            Follower::where('user_id',$user['id'])->delete();
            Follower::where('follower_id',$user['id'])->delete();
            Product::where('user_id',$user['id'])->delete();
            Like::where('user_id',$user['id'])->delete();
            Share::where('user_id',$user['id'])->delete();
            FollowCategory::where('user_id',$user['id'])->delete();
            Review::where('user_id',$user['id'])->delete();
            Bid::where('user_id',$user['id'])->delete();
            return redirect('/')->with('info', 'Your account has been deleted');
        }
    }
}
