<?php

namespace App\Http\Controllers\Mobile;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Validation\ValidationException;
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
                'username' => ['required', 'string', 'max:255', 'unique:users'],
                'password' => ['required', 'string'],
                'phone_number' => ['required', 'string', 'max:255'],
                'phone_token' => ['required', 'string']
            ]);
            $user = User::create([
                'nama_pembeli' => $request->nama_pembeli,
                'email' => $request->email,
                'username' => $request->username,
                'password' => Hash::make($request->password),
                'phone_number' => $request->phone_number,
                'phone_token' => $request->phone_token,
                'avatar' => $request->avatar
            ]);

            return ResponseFormatter::success([
                'user' => $user
            ], 'Authenticated');
        } catch (ValidationException $e) {
            $errors = $e->validator->errors();
            if ($errors->has('email')) {
                return ResponseFormatter::error([
                    'message' => 'Email telah digunakan',
                ], 'Validation Error', 401);
            }

            if ($errors->has('username')) {
                return ResponseFormatter::error([
                    'message' => 'Username telah digunakan',
                ], 'Validation Error', 400);
            }

            return ResponseFormatter::error([
                'message' => 'Ada kesalahan pada input',
                'errors' => $errors,
            ], 'Validation Error', 422);
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
            // Tambahkan pengecekan apakah user sudah terverifikasi
            if ($user->verified_user == 0) {
                return ResponseFormatter::error(['message' => 'User belum terverifikasi.'], 'Authentication Failed', 403);
            }
            if (!Hash::check($request->password, $user->password, [])) {
                throw new \Exception('Invalid Credentials');
            }
            if ($user->device_token && $user->device_token !== $request->device_token) {
                return ResponseFormatter::error(['message' => 'User sedang masuk diakun'], 'Authentication Failed', 403);
            }

            // Periksa apakah phone_token kosong, jika ya, update phone_token
            if ($user->phone_token != $request->phone_token) {
                $user->update(['phone_token' => $request->phone_token]);
            }
            // Update device_token
            $user->update(['device_token' => $request->device_token]);
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

    public function logout(Request $request)
    {
        try {
            $user = User::find($request->id_users);

            // Menghapus token perangkat
            $user->update(['device_token' => null, 'phone_token' => null]);

            return ResponseFormatter::success([], 'Successfully logged out');
        } catch (Exception $error) {
            return ResponseFormatter::error(['message' => 'Logout failed', 'error' => $error], 'Logout Failed', 500);
        }
    }

    public function verifiedUser(Request $request)
    {
        try {
            $user = User::find($request->id_users);
            $user->update(['verified_user' => 1]);

            return ResponseFormatter::success([], 'Successfully verfied user');
        } catch (Exception $error) {
            return ResponseFormatter::error(['message' => 'verifikasi gagal', 'error' => $error], 'verifikasi user gagal', 500);
        }
    }

    public function checkUserExist(Request $request)
    {
        try {
            $user = User::where('email', $request->email)->first();
            if ($user) {
                return ResponseFormatter::success(['user' => $user, 'message' => 'user tersedia'], 'Successfully get user');
            } else {
                return ResponseFormatter::error(['message' => 'user tidak tersedia'], 'Failed get user', 404);
            }
        } catch (Exception $error) {
            return ResponseFormatter::error(['message' => 'user tidak tersedia', 'error' => $error], 'Failed get user', 404);
        }
    }

    public function forgetPassword(Request $request)
    {
        try {

            $request->validate([
                'email' => 'required',
                'new_password' => 'required'
            ]);

            $user = User::where('email', $request->email)->first();
            $user->password = Hash::make($request->new_password);
            $user->save();

            return ResponseFormatter::success([
                'user' => $user
            ], 'Password has been changed');
        } catch (Exception $error) {
            return ResponseFormatter::error(['message' => 'Password failed to be changed', 'error' => $error], 'Failed', 500);
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
