<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Category extends Model
{
    protected $fillable = ['name', 'description'];

    public function file()
    {
        return $this->hasOne('App\Models\File', 'resource_id')->where('resource_name', 'category');
    }

    public function products()
    {
        return $this->belongsToMany('App\Models\Product')->whereStatus('published');
    }

    public static function isCategoryFollowed(Category $category)
    {
        if (FollowCategory::whereUserId(Auth::id())->whereCategoryId($category->id)->exists()) {
            return true;
        }
        return false;
    }
}
