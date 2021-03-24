<?php

namespace App\Models;

use App\Models\FollowBrand;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Brand extends Model
{
    protected $fillable = ['name', 'description'];

    public function products()
    {
        return $this->hasMany('App\Models\Product');
    }

    public function file()
    {
        return $this->hasOne('App\Models\File', 'resource_id')->where('resource_name', 'brand');
    }

    public static function isBrandFollowed(Brand $brand)
    {
        if (FollowBrand::whereUserId(Auth::id())->whereBrandId($brand->id)->exists()) {
            return true;
        }
        return false;

    }
}
