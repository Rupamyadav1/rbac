<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
    'order_number',
    'user_id',
    'order_date'
];

public function orderItem()
{
    return $this->hasOne(OrderItem::class);
}

}
