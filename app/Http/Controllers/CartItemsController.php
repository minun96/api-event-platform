<?php

namespace App\Http\Controllers;

use App\Models\CartItems;
use App\Models\Event;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartItemsController extends Controller
{

    public function index(User $user) {

        dd(Auth::user());
        $query = CartItems::where('user_id', $user->id);
    }

    public function add(Request $request) {

        $request->validate([
            'event_id' => 'required|exists:events,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $event = Event::find($request->event_id);
    }

    public function remove() {
        // valida con event_id ma usa sometimes (https://laravel.com/docs/12.x/validation#validating-when-present) per la quantità
    }
}
