<?php
namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $products = Product::paginate(9); // 9 productos por página
        return view('dashboard', compact('products'));
    }
}