<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    protected $fillable = ['name', 'description','slug'];

    public function files()
    {
        return $this->hasMany('App\Models\File', 'resource_id')->where('resource_name','=','page');
    }
}
