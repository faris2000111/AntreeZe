<?php

namespace App\Http\Controllers\Mobile;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{

    public function register(Request $request)
    {
        try {

            $request->validate([
                'nama_pembeli' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'unique:users'],
                'username' => ['required', 'string', 'max:255'],
                'password' => ['required', 'string'],
                'phone_number' => ['required', 'string', 'max:255'],
                'phone_token' => ['required', 'string']
            ]);

            User::create([
                'nama_pembeli' => $request->nama_pembeli,
                'email' => $request->email,
                'username' => $request->username,
                'password' => Hash::make($request->password),
                'phone_number' => $request->phone_number,
                'phone_token' => $request->phone_token
            ]);

            $user = User::where('email', $request->email)->first();
            return ResponseFormatter::success([
                'user' => $user
            ], 'Authenticated');
        } catch (Exception $error) {
            return ResponseFormatter::error([
                'message' => 'ada yang error',
                'error' => $error,
            ], 'Authentication Failed', 500);
        }
    }

    public function login(Request $request)
    {
        try {

            $request->validate(['username' => 'required', 'password' => 'required']);
            $credetials = request(['username', 'password']);
            if (!Auth::attempt($credetials)) {
                return ResponseFormatter::error(['message' => 'Unauthorized'], 'Authentacation Failed', 500);
            }

            $user = User::where('username', $request->username)->first();
            if (!Hash::check($request->password, $user->password, [])) {
                throw new \Exception('Invalid Credentials');
            }
            return ResponseFormatter::success([
                'user' => $user
            ], 'Authenticated');
        } catch (Exception $error) {
            return ResponseFormatter::error([
                'message' => 'ada yang error',
                'error' => $error,
            ], 'Authentication Failed', 500);
        }
    }

    public function changePassword(Request $request)
    {
        try {
            $request->validate([
                'id_users' => 'required',
                'old_password' => 'required',
                'new_password' => 'required'
            ]);
            $user = User::find($request->id_users);
            if (!$user) {
                return ResponseFormatter::error(['message' => 'User not found'], 'Failed', 404);
            }
            if (!Hash::check($request->old_password, $user->password)) {
                return ResponseFormatter::error(['message' => 'Old password does not match'], 'Failed', 401);
            }
            $user->password = Hash::make($request->new_password);
            $user->save();

            return ResponseFormatter::success([
                'user' => $user
            ], 'Password has been changed');
        } catch (Exception $error) {
            return ResponseFormatter::error(['message' => 'Password failed to be changed', 'error' => $error], 'Failed', 500);
        }
    }

    public function updateProfile(Request $request)
    {
        try {
            $request->validate([
                'id_users' => 'required',
                'nama_pembeli' => 'required',
                'phone_number' => 'required'
            ]);

            $user = User::find($request->id_users);
            if (!$user) {
                return ResponseFormatter::error(['message' => 'User not found!'], 'Failed', 401);
            }

            $user->nama_pembeli = $request->nama_pembeli;
            $user->phone_number = $request->phone_number;
            $user->save();

            return ResponseFormatter::success([
                'user' => $user
            ], 'Data user has been changed');
        } catch (Exception $error) {
            return ResponseFormatter::error(['message' => 'Data user failed to be changed', 'error' => $error], 'Failed', 500);
        }
    }
}
