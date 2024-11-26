<?php
namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Auth::user()->orders()->with('orderDetails.product')->get();
        return view('orders.index', compact('orders'));
    }

    public function addToCart(Request $request, Product $product)
    {
        $order = Auth::user()->orders()->where('status', 'carrito')->first();

        if (!$order) {
            $order = Auth::user()->orders()->create([
                'order_date' => now(),
                'total' => 0,
                'status' => 'carrito',
            ]);
        }

        $orderDetail = $order->orderDetails()->where('product_id', $product->id)->first();

        if ($orderDetail) {
            $orderDetail->increment('quantity', $request->quantity);
        } else {
            $order->orderDetails()->create([
                'product_id' => $product->id,
                'quantity' => $request->quantity,
                'unit_price' => $product->price,
            ]);
        }

        $order->updateTotal();

        return redirect()->route('orders.index');
    }

    public function removeFromCart(OrderDetail $orderDetail)
    {
        $order = $orderDetail->order;
        $orderDetail->delete();
        $order->updateTotal();

        return redirect()->route('orders.index');
    }

    public function checkout(Order $order)
    {
        $order->update(['status' => 'pendiente']);
        return redirect()->route('orders.index');
    }
}