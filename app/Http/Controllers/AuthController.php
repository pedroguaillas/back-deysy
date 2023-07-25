<?php

namespace App\Http\Controllers;

use App\Models\ClienteAuditwhole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'user' => 'required|unique:users',
            'email' => 'required|email|unique:users',
            'rol' => 'required',
            'password' => 'required|min:6',
            'salary' => 'required'
        ]);

        $register = User::create([
            'name' => $request->input('name'),
            'user' => $request->input('user'),
            'rol' => $request->input('rol'),
            'email' => $request->input('email'),
            'salary' => $request->input('salary'),
            'password' => Hash::make($request->input('password'))
        ]);

        if ($register) {
            return response()->json([
                'success' => true,
                'message' => 'Register Success!',
                'data' => $register,
            ], 201);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Register Fail!',
                'data' => '',
            ], 401);
        }
    }

    public function api_login(Request $request)
    {
        //validate incoming request 
        $this->validate($request, [
            'user' => 'required|string',
            'password' => 'required|string',
        ]);

        $credentials = $request->only(['user', 'password']);

        if (!$token = Auth::attempt($credentials)) {
            return response()->json(['message' => 'Usuario o contraseña incorrecto'], 401);
        }

        return response()->json([
            'token' => $token,
            'token_type' => 'bearer',
            'expires_in' => 3600,
            'user' => JWTAuth::user()
        ]);
    }

    public function login(Request $request)
    {
        $user = $request->input('user');
        $password = $request->input('password');

        $u = User::where('user', $user)->first();

        if (Hash::check($password, $u->password)) {
            // $apiToken = base64_encode(str_random(40));

            // $u->update([
            //     'remember_token' => $apiToken
            // ]);

            if ($u->rol === 'admin') {
                // $customers = ClienteAuditwhole::all(['ruc', 'razonsocial', 'nombrecomercial', 'phone', 'mail', 'direccion', 'diadeclaracion', 'sri', 'representantelegal', 'iess1', 'iess2', 'mt', 'mrl', 'super', 'contabilidad']);
                // Solo clientes Auditwhole
                $customers = ClienteAuditwhole::select(['ruc', 'razonsocial', 'nombrecomercial', 'phone', 'mail', 'direccion', 'diadeclaracion', 'sri', 'representantelegal', 'iess1', 'iess2', 'mt', 'mrl', 'super', 'contabilidad'])
                    // Menos clientes Victor
                    ->where('user_id', '<>', 15)->get();
            } elseif ($u->rol === 'asesor') {
                $customers = $u->clienteauditwholes()->get(['ruc', 'razonsocial', 'nombrecomercial', 'phone', 'mail', 'direccion', 'diadeclaracion', 'sri', 'representantelegal', 'iess1', 'iess2', 'mt', 'mrl', 'super', 'contabilidad']);
            }

            return response()->json([
                'success' => true,
                'user' => $u,
                // 'remember_token' => $apiToken,
                'customers' => $customers
            ], 201);
        } else {
            return response()->json([
                'success' => false,
                'data' => ''
            ], 401);
        }
    }

    public function update(int $id, Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'user' => 'required|unique:users',
            'email' => 'required|email|unique:users',
            'rol' => 'required',
            'password' => 'required|min:6',
        ]);

        $user = User::find($id);
        $user->update($request->all());
    }

    public function refreshToken()
    {
        $refreshed = JWTAuth::refresh(JWTAuth::getToken());
        JWTAuth::setToken($refreshed)->toUser();
        return response()->json([
            'token' => $refreshed
        ]);
    }

    public function logout()
    {
        Auth::logout();
        return response()->json(['logout' => true]);
    }
}
