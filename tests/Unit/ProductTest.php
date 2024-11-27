<?php

use App\Models\Product;
use App\Models\OrderDetail;
use Illuminate\Support\Facades\Storage;

test('product can get photo url', function () {
    Storage::fake('public');
    $product = Product::factory()->create([
        'photo' => 'products/test.jpg'
    ]);

    expect($product->photo_url)->toBe(Storage::url('products/test.jpg'));
});

test('product can update stock', function () {
    $product = Product::factory()->create([
        'quantity_in_stock' => 10
    ]);

    OrderDetail::factory()->create([
        'product_id' => $product->id,
        'quantity' => 3
    ]);

    expect($product->fresh()->quantity_in_stock)->toBe(7);
});
