<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;


class OrderItem extends Model
{
    protected $fillable = ['order_id', 'product_id', 'product_name', 'quantity', 'unit_price', 'total_price', 'options', 'product_image'];
    protected function casts(): array
    {
        return [
            'options' => 'array',
            'unit_price' => 'decimal:0',
            'total_price' => 'decimal:0',
        ];
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
