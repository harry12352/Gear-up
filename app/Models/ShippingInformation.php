<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShippingInformation extends Model
{

    /**
     * Get the user that owns the phone.
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'address', 'zip_code', 'city', 'state', 'country',
    ];
}
