<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name', 'last_name', 'email', 'password', 'username', 'type'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Get the phone record associated with the user.
     */

    public function shipping()
    {
        return $this->hasOne('App\Models\ShippingInformation');
    }

    public function profileImage()
    {
        return $this->hasOne('App\Models\File', 'resource_id')->where('resource_name','user');
    }

    public function followers()
    {
        return $this->hasMany('App\Models\Follower');
    }

    public function isUserFollowing($user)
    {
        $userFollowers = $this->followers;
        return !$userFollowers->where('follower_id', $user->id)->isEmpty();
    }

    public function following()
    {
        return $this->hasMany('App\Models\Follower', "follower_id");
    }

    public function products()
    {
        return $this->hasMany('App\Models\Product');
    }

    public function likes()
    {
        return $this->hasMany('App\Models\Like');
    }

    public function shares()
    {
        return $this->hasMany('App\Models\Share');
    }

    public function followedCategories()
    {
        return $this->hasMany('App\Models\FollowCategory');
    }

    public function followedBrands()
    {
        return $this->hasMany('App\Models\FollowBrand');
    }

    public function comments()
    {
        return $this->hasMany('App\Models\Comment');
    }

    public function reviews()
    {
        return $this->hasMany('App\Models\Review');
    }

    public function messages()
    {
        return $this->belongsToMany('App\Models\Message');
    }

    public function offers()
    {
        return $this->hasMany('App\Models\Offer');
    }

    public static function hasUserMadeOffer($user, $product)
    {
        $userOffers = $user->offers;
        return !$userOffers->where('user_id', $user->id)->where('product_id', $product->id)->isEmpty();
    }

    public static function popularityMeter(User $user)
    {
        $products = $user->products;
        $likes = [];
        $shares = [];
        $likesCount = 0;
        $sharesCount = 0;
        foreach ($products as $product) {
            $likes[] = Product::find($product['id'])->likes;
            $likesCount += count($likes[0]);
            $shares[] = Product::find($product['id'])->shares;
            $sharesCount += count($shares[0]);
            $likes = [];
            $shares = [];
        }
        $average = ($sharesCount + $likesCount) / 2;
        return $average;
    }

    public static function attachMessage($message)
    {
        $users = User::all();
        foreach ($users as $user) {
            $user->messages()->attach($message);
        }
    }
}
