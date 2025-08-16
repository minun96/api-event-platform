<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CartItem;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    public function summary(Request $request) {
        $user = $request->user();
        $cartItems = CartItem::with('event')->where('user_id', $user->id)->get();

        if ($cartItems->isEmpty()) {
            return response()->json([
                'message' => 'Il tuo carrello è vuoto.'
            ], 422);
        }

        $totalAmount = $cartItems->sum(function ($item) {
            return $item->quantity * $item->event->price;
        });

            return response()->json([
                'message' => 'Your summary', 
                'data' => [
                    'items' => $cartItems,
                    'total' => $totalAmount,
                    ] 
            ], 200);
        
    }
}
