<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\PaymentMethod;

class CheckoutController extends Controller
{
    public function index()
    {
        return view('checkout', [
            'paymentMethods' => PaymentMethod::active()->orderBy('sort_order')->get(),
        ]);
    }

    public function success(string $code)
    {
        $order = Order::where('order_code', $code)
            ->where('user_id', auth()->id())
            ->with('items')
            ->firstOrFail();

        return view('order-success', ['order' => $order]);
    }
}
