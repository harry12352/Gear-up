<?php

namespace App\Models;

use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class Follower extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'follower_id'
    ];

    public function followerUser()
    {
        return $this->belongsTo('App\Models\User', 'follower_id');
    }

    public function followingUser()
    {
        return $this->belongsTo('App\Models\User', 'user_id');
    }

    public static function getFollowingsProducts(User $user, int $currentOffset, int $limitProducts)
    {
        return $products = Product::join('followers', 'followers.user_id', '=', 'products.user_id')
            ->where('followers.follower_id', '=', $user['id'])
            ->where('products.status', '=', 'published')
            ->select('products.*')
            ->offset($currentOffset)
            ->limit($limitProducts)->get();

    }

}
