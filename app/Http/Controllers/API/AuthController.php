<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Login;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\API\ResponseFormater;
use Exception;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthController extends Controller
{

    public function login(Request $request)
    {
        try {
            $request->validate([
                'nip' => 'required',
                'password' => 'required'
            ]);

            $credentials = request(['nip', 'password']);
            if (!Auth::attempt($credentials)) {
                return ResponseFormater::error(
                    [
                        'message' => 'Unauthorized'
                    ],
                    'Authentication Failed',
                    500
                );
            }

            $user = User::where('nip', $request->nip)->first();
            if(! Hash::check($request->password, $user->password, [])){
                throw new \Exception('Invalid Credentials');
            }

            $tokenResult = $user->createToken('authToken')->plainTextToken;

            return ResponseFormater::success([
                'access_token' => $tokenResult,
                'token_type' => 'Bearer',
                'user' => $user
            ], 'Authenticated');

        } catch (Exception $error) {
            return ResponseFormater::error([
                'message' => 'Something went wrong',
                'error' => $error
            ], 'Authentication Failed', 500);
        }
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return ResponseFormater::success([
            'message' => 'Token Revoked'
        ], 'Token Revoked');
    }

    public function fetch(Request $request)
    {
        return ResponseFormater::success($request->user(), 'Data profile user berhasil diambil');
    }

    public function updateProfile(Request $request)
    {
        $data = $request->all();
        $user = Auth::user();
        $user->update($data);

        return ResponseFormater::success($user, 'Profile berhasil diperbarui');
    }

}
