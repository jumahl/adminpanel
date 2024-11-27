<?php

use App\Models\OrderDetail;
use App\Models\Order;
use App\Models\Product;

test('order detail belongs to order and product', function () {
    $order = Order::factory()->create();
    $product = Product::factory()->create();
    $orderDetail = OrderDetail::factory()->create([
        'order_id' => $order->id,
        'product_id' => $product->id
    ]);

    expect($orderDetail->order)->toBeInstanceOf(Order::class);
    expect($orderDetail->product)->toBeInstanceOf(Product::class);
});

test('order detail can calculate subtotal', function () {
    $orderDetail = OrderDetail::factory()->create([
        'quantity' => 3,
        'unit_price' => 50
    ]);

    expect($orderDetail->subtotal)->toBe(150);
});
