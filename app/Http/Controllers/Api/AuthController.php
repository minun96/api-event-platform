<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function login(Request $request) {

        // dati da validare
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required',
        ]);

        // user e controllo user e hash della password
        $user = User::where('email', $request->email)->first();
        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => 'Invalid credentials'
            ]);
        }

        // creo il token
        $token = $user->createToken('api-token')->plainTextToken;
        
        // rispondo con il token
        return response()->json([
            'user' => $user,
            'token' => $token,
        ]);
    }

    public function register(Request $request) {

        // regola locale e produzione
        $localPassRule = 'required|string|min:8';
        $prodPassRule = ['required', 'confirmed',
            Password::min(8)
                ->mixedCase()
                ->numbers()
                ->symbols()
                ->uncompromised()
        ];

        // dati da validare
        $data = $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|email|unique:users',
            'password' => App::environment('local') ? $localPassRule : $prodPassRule,
        ]);

        // transazione db con dati
        $result = DB::transaction(function() use ($data) {
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
            ]);

            // creo il token
            $token = $user->createToken('api-token')->plainTextToken;
            return ['user' => $user, 'token' => $token];

        });

        // rispondo 201
        return response()->json([
            'user' => $result['user'],
            'token' => $result['token'],
        ], 201);
        
    }

    
}
