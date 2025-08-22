<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\ConfirmRequest;
use App\Jobs\ProcessPayment;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ConfirmController extends Controller
{
    public function store(ConfirmRequest $request) {
        $data = $request->validated();
        $user = $request->user();
        $cartItems = CartItem::with('event')->where('user_id', $user->id)->get();

        if ($cartItems->isEmpty()) {
            return response()->json(['message' => 'Il carrello è vuoto.'], 422);
        }

        $payment = DB::transaction(
            function() use ($data, $user, $cartItems) {

                // il totale dell'ordine
                $totalAmount = $cartItems->sum(function ($item) {
                    return $item->quantity * $item->event->price;
                });

                // creo l'ordine
                $order = Order::create([
                    'user_id' => $user->id,
                    'status' => 'pending',
                    'total_amount' => $totalAmount,
                    'address_id' => $data['address_id'],
                ]);

                // copio gli articoli presenti nel carrello
                foreach ($cartItems as $cartItem) {
                    $order->orderItems()->create([
                        'event_id' => $cartItem->event_id,
                        'quantity' => $cartItem->quantity,
                        'price' => $cartItem->event->price,
                    ]);
                }

                // svuoto gli items temporanei nel carrello
                $user->cartItems()->delete();

                // creo il pagamento
                $payment = Payment::create([
                    'order_id' => $order->id,
                    'amount' => $totalAmount,
                    'method' => $data['payment_method'],
                    'status' => 'pending',
                ]);

                return $payment;
            }
        );

        // avvio il job
        ProcessPayment::dispatch($payment)->afterCommit(); // afterCommit per essere sicuri che le transaction siano terminate

        return response()->json([
            'message' => 'Ordine creato con successo. Il pagamento è in elaborazione.',
            'data' => $payment->order()->with('orderItems.event')->first(),
        ], 201);
    }
}
