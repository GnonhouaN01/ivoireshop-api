<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Order extends Model
{
    /** @use HasFactory<\Database\Factories\OrderFactory> */
    use HasFactory, SoftDeletes;
    protected $fillable = ['user_id', 'order_number', 'status', 'payment_method', 'payment_reference', 'payment_status', 'subtotal', 'delivery_fee', 'discount_amount', 'total', 'notes', 'shipping_address', 'paid_at', 'shipped_at', 'delivered_at'];
    protected function casts(): array
    {
        return [
            'shipping_address' => 'array',
            'subtotal' => 'decimal:0',
            'delivery_fee' => 'decimal:0',
            'discount_amount' => 'decimal:0',
            'total' => 'decimal:0',
            'paid_at' => 'datetime',
            'shipped_at' => 'datetime',
            'delivered_at' => 'datetime',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}
