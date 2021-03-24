<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = ['order_id', 'transaction_id', 'product_id', 'user_id', "transaction_details"];

    protected $casts = [ 'transaction_details' => 'json'];
}
