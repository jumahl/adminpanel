<?php

use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\User;

test('order can calculate total', function () {
    $order = Order::factory()->create();
    OrderDetail::factory()->create([
        'order_id' => $order->id,
        'quantity' => 2,
        'unit_price' => 50
    ]);

    OrderDetail::factory()->create([
        'order_id' => $order->id,
        'quantity' => 1,
        'unit_price' => 100
    ]);

    $order->updateTotal();

    expect($order->fresh()->total)->toBe(200);
});

test('order belongs to user', function () {
    $user = User::factory()->create();
    $order = Order::factory()->create(['user_id' => $user->id]);

    expect($order->user)->toBeInstanceOf(User::class);
    expect($order->user->id)->toBe($user->id);
});
