<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\PaymentRequest;

class PaymentController extends Controller
{
    public function payment(PaymentRequest $request)
    {
        $data = $request->validated();

        // in un vero ecommerce un gateway validerebbe i dati della carta

        return response()->json([
            'message' => 'Metodo di pagamento accettato. Riceverai la conferma del tuo ordine',
            'data' => [
                'payment_method' => $data['payment_method'],
            ]
        ]);
    }
}
