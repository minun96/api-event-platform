<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function login(Api\LoginRequest $request) {

        $credentials = $request->validated();

        // user e controllo user e hash della password
        $user = User::where('email', $credentials['email'])->first();
        if (!$user || !Hash::check($credentials['password'], $user->password)) {
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

    public function logout(Request $request)
    {
        // Revoca il bearer token che l'utente sta usando per questa richiesta
        $user = $request->user();
        $user->tokens()->where('id', $user->currentAccessToken()->id)->delete();

        return response()->json([
            'message' => 'Logout effettuato con successo.'
        ], 200);
    }

    public function register(Api\RegisterRequest $request) {

        $data = $request->validated();

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
