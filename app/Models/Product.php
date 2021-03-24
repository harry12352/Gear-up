<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = ['title', 'brand', 'category', 'title', 'price', 'status', 'description'];

    protected $with = ['Colors', 'Files', 'Shares', 'Likes', 'Brand', 'Categories', 'size'];

    public function brand()
    {
        return $this->belongsTo('App\Models\Brand');
    }

    public function size()
    {
        return $this->belongsTo('App\Models\Size');
    }

    public function categories()
    {
        return $this->belongsToMany('App\Models\Category');
    }

    public function files()
    {
        return $this->hasMany('App\Models\File', 'resource_id')->whereResourceName('product');
    }

    public function hasUserLiked(User $user)
    {
        $productLikes = $this->likes;
        return !$productLikes->where('user_id', $user->id)->isEmpty();
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function likes()
    {
        return $this->hasMany('App\Models\Like');
    }

    public function shares()
    {
        return $this->hasMany('App\Models\Share');

    }

    public function comments()
    {
        return $this->hasMany('App\Models\Comment');
    }

    public function colors()
    {
        return $this->belongsToMany('App\Models\Color');
    }

    public function reviews()
    {
        return $this->hasMany('App\Models\Review');
    }

    public function offers()
    {
        return $this->hasMany('App\Models\Offer')->whereStatus('pending');
    }

    public static function isProductLiked(User $user, Product $product)
    {
        if (Like::whereUserId($user->id)->whereProductId($product->id)->exists()) {
            return true;
        }
        return false;
    }
}
