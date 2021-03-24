<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $fillable = ['content'];

    public function users()
    {
        return $this->belongsToMany('App\Models\User');
    }
}
