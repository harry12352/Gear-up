<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class Share extends Model
{
    protected $fillable = ['user_id', 'product_id'];

    public function user(){
        return $this->belongsTo('App\Models\User');
    }

    public static function getFollowingsSharedProducts(User $user, int $currentOffset, int $limitProducts)
    {
        return Product::join('shares', 'shares.product_id', '=', 'products.id')
            ->join('followers', 'followers.user_id', '=', 'shares.user_id')
            ->join('users', 'users.id', '=', 'shares.user_id')
            ->where('followers.follower_id', '=', $user['id'])
            ->where('products.status', '=', 'published')
            ->select('products.*', 'shares.created_at', 'users.username', 'users.first_name', 'users.last_name', 'users.profile_picture')->offset($currentOffset)
            ->limit($limitProducts)->get();
    }

    public static function getSharedProducts(User $user, int $currentOffset, int $limitProducts)
    {
        return Product::join('shares', 'shares.product_id', '=', 'products.id')
            ->where('shares.user_id', '=', $user['id'])
            ->join('users', 'users.id', '=', 'shares.user_id')
            ->where('products.status', '=', 'published')
            ->select('products.*', 'shares.created_at', 'users.username', 'users.first_name', 'users.last_name', 'users.profile_picture')->offset($currentOffset)
            ->limit($limitProducts)->get();
    }
}
