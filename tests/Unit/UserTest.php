<?php

use App\Models\User;
use App\Models\Order;

test('user can create order', function () {
    $user = User::factory()->create();
    $order = Order::factory()->create([
        'user_id' => $user->id,
        'total' => 100,
        'status' => 'pendiente'
    ]);

    expect($user->orders)->toHaveCount(1);
    expect($user->orders->first()->total)->toBe(100);
});

test('user can be created with address and contact', function () {
    $user = User::factory()->create([
        'address' => '123 Test St',
        'contact' => '1234567890'
    ]);

    expect($user->address)->toBe('123 Test St');
    expect($user->contact)->toBe('1234567890');
});
