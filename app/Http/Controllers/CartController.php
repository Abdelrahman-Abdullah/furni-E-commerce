<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        return view('Front.cart', ['cartProducts' => session('cart') ?? []]);
    }

    public function store(Request $request)
    {
        $productId = $request->id;

        // If the product is already in the cart, increment the quantity
        $cart = session('cart') ?? [];
        if (isset($cart[$request->id])) {
            $cart[$request->id]['quantity']++;
            session(['cart' => $cart]);
            return true;
        }
        // Find the product; if not found, return an error response
        $product = Product::select('id', 'name', 'price', 'image')->find($productId);
        if (!$product) {
            return  'Product not found';
        }
        $cart[$request->id] = [
            'id' => $product->id,
            'title' => $product->name,
            'price' => $product->price,
            'imageUrl' => $product->image_url,
            'quantity' => 1
        ];
        session(['cart' => $cart]);
        return "Product Added Successfully";
    }

    public function update(Request $request)
    {
        $productId = $request->id;
        // If the product is already in the cart, increment the quantity
        $cart = session('cart') ?? [];
        if (isset($cart[$request->id])) {
            return $this->updateCart($productId, $request->increment);
        }
        return false;
    }

    private function updateCart($productId, $isIncrement)
    {
        $cart = session('cart') ?? [];
        if ($isIncrement == 'true' && $cart[$productId]['quantity'] >= 1) {
            $cart[$productId]['quantity']++;
            session(['cart' => $cart]);
            return true;
        } else if ($isIncrement == 'false' && $cart[$productId]['quantity'] > 1) {
            $cart[$productId]['quantity']--;
            session(['cart' => $cart]);
            return true;
        }
    }

}