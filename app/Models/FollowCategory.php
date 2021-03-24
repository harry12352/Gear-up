<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class FollowCategory extends Model
{
    protected $fillable = ['user_id', 'category_id'];

    public static function getCategoryProducts(User $user, int $currentOffset, int $limitProducts)
    {
        return $products = Product::join('category_product', 'category_product.product_id', '=', 'products.id')
            ->join('follow_categories', 'follow_categories.category_id', '=', 'category_product.category_id')
            ->where('follow_categories.user_id', '=', $user['id'])
            ->where('products.status', '=', 'published')
            ->select('products.*')->offset($currentOffset)
            ->limit($limitProducts)->get();
    }
}
