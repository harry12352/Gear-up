<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'resource_id', 'path', 'resource_name'
    ];


    public function product()
    {
        return $this->belongsTo('App\Models\Product', 'resource_id');

    }

    public function profileImage()
    {
        return $this->belongsTo('App\Models\User', 'resource_id');
    }

    public function brand()
    {
        return $this->belongsTo('App\Models\Brand', 'resource_id');
    }

    public function category()
    {
        return $this->belongsTo('App\Models\Category', 'resource_id');
    }
    public function page()
    {
        return $this->belongsTo('App\Models\Page', 'resource_id');
    }
}
