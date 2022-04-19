<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function login (Request $request) {
        $credentials = $request->all(['email', 'password']);

        $token = auth('api')->attempt($credentials);

        if($token) { //authenticated
            return response()->json(['token' => $token], 200);
        } else { // email or password error
            return response()->json(['error' => 'Usuário ou senha inválido!'], 403);
        }
    }

    public function logout () {
        auth('api')->logout(); //needs a valid jwt
        return response()->json(['msg' => 'Logout realizado com sucesso!']);
    }

    public function refresh () {
        $token = auth('api')->refresh(); //needs a valid jwt

        return response()->json(['token' => $token], 200);
    }

    public function me () {
        return response()->json(auth()->user());
    }
}
