<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CartItemsController extends Controller
{

    //->PRENDO IL CARRELLO
    public function index(Request $request) {
        $user = $request->user(); 
        $cartItems = CartItem::where('user_id', $user->id)->get();
        Log::debug($user);
        Log::info('Cart: ', ['cart_items' => $cartItems]);
        return response()->json($cartItems, 200);
    }

    //->AGGIUNGO ITEM AL CARRELLO
    public function add(Request $request) {
        $data = $request->validate([
            'event_id' => 'required|exists:events,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $user = $request->user();
        $existingCartItem = CartItem::where('user_id', $user->id)
                                     ->where('event_id', $data['event_id'])
                                     ->first();

        // Se esiste il carrello aggiorno
        if ($existingCartItem) {
            $existingCartItem->increment('quantity', $data['quantity']);
            return response()->json([
                'message' => 'Cart updated!',
                 'data' => $existingCartItem->fresh(),
                ], 200);
        
        }
        // Altrimenti creo
        else {
            $cartItem = CartItem::create([
                'user_id' => $user->id,
                'event_id' => $data['event_id'],
                'quantity' => $data['quantity'],
            ]);

            Log::info('New item in cart.', ['user_id' => $user->id, 'event_id' => $data['event_id']]);

            return response()->json([
                'message' => 'Event added to cart!', 
                'data' => $cartItem
            ], 201); //uso 201  perché lo ho creato stavolta
        }

    }

    //->ELIMINO ITEM DAL CARRELLO O AGGIIORNO QUANTITÀ
    public function remove(Request $request) {
        $data = $request->validate([
            'event_id' => 'required|exists:events,id',
            'quantity' => 'sometimes|integer|min:1',
        ]);
        $user = $request->user();
        $existingCartItem = CartItem::where('user_id', $user->id)
                                     ->where('event_id', $data['event_id'])
                                     ->first();
        $quantityToRemove = $data['quantity'] ?? null;
                                     
        if (!$existingCartItem) {
        return response()->json(['message' => 'Item non found.'], 404);
        }
        
        // Se quantità è maggiore elimino carrello
        if ($quantityToRemove === null || $quantityToRemove >= $existingCartItem->quantity) {
            $existingCartItem->delete();
            return response()->json([
                'message' => 'Cart deleted!',
                 'data' => $existingCartItem,
                ], 200);

        } 
        // Altrimenti aggiorno
        else {
            $existingCartItem->decrement('quantity', $quantityToRemove);
            return response()->json([
                'message' => 'Cart updated!',
                 'data' => $existingCartItem->fresh(),
                ], 200);
        }

    }
}
