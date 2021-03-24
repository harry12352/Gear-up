<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $fillable = ['user_id', 'product_id', 'content', 'rating'];

    public static function reviewExists(User $user, Product $product)
    {
        return Review::whereUserId($user['id'])->whereProductId($product['id'])->exists();
    }

    public function user(){
        return $this->belongsTo('App\Models\User');
    }
}
