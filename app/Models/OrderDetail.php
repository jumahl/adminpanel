<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'product_id',
        'quantity',
        'unit_price',
    ];

    protected $appends = ['subtotal'];

    public function getSubtotalAttribute()
    {
        return $this->quantity * $this->unit_price;
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
    protected static function booted()
    {
        static::created(function ($orderDetail) {
            $product = $orderDetail->product;
            $product->decrement('quantity_in_stock', $orderDetail->quantity);
        });
    }
}