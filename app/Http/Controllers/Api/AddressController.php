<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\AddressRequest;
use App\Models\Address;

class AddressController extends Controller
{
    public function store(AddressRequest $request) {
        $user = $request->user();
        $data = $request->validated();
        $address = Address::create([
            'user_id' => $user->id,
            'recipient_name' => $data['recipient_name'],
            'street' => $data['street'],
            'city' => $data['city'],
            'zip_code' => $data['zip_code'],
            'country' => $data['country'],
        ]);

        return response()->json([
            'message' => 'Address correctly submitted',
            'data' => $address,
        ], 201);
    }
}
