<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FollowBrand extends Model
{
    protected $fillable = ['user_id', 'brand_id'];

    public static function getBrandProducts(User $user, $currentOffset, $limitProducts)
    {
        return $products = Product::join('follow_brands', 'follow_brands.brand_id', '=', 'products.brand_id')
            ->where('follow_brands.user_id', '=', $user['id'])
            ->where('products.status', '=', 'published')
            ->select('products.*')->offset($currentOffset)
            ->limit($limitProducts)->get();
    }
}
