<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Throwable;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        try {
            $validate = Validator::make($request->all(), [
                'username' => 'required',
                'email' => 'required|unique:users,email',
                'password' => 'required|min:8',
                'no_ktp' => 'required|unique:users,no_ktp',
                'date_birth' => 'required|date',
                'description' => 'required|max:255',
                'phone' => 'required|numeric',
            ]);
            if ($validate->fails()) {
                return response()->json([
                    'msg' => $validate->errors(),
                    'status' => false
                ]);
            }

            $user = User::create([
                'username' => $request->input('username'),
                'email' => $request->input('email'),
                'password' => $request->input('password'),
                'no_ktp' => $request->input('no_ktp'),
                'date_birth' => $request->input('date_birth'),
                'phone' => $request->input('phone'),
                'description' => $request->input('description'),
            ]);

            return response()->json([
                'msg' => 'berhasil membuat akun',
                'data' => $user,
                'token' => $user->createToken('Api Token')->plainTextToken
            ]);
        } catch (Throwable $th) {
            return response()->json([
                'msg' => $th->getMessage()
            ]);
        }
    }

    public function login(Request $request)
    {
        try {
            if (!Auth::attempt($request->only('email', 'password'))) {
                return response()->json([
                    'msg' => 'Unauthorization'
                ]);
            }
            $user = User::where('email', $request->email)->firstOrFail();
            return response()->json([
                'msg' => 'Berhasil Login',
                'data' => $user,
                'token' => $user->createToken('Api Token')->plainTextToken
            ]);
        } catch (Exception $e) {
            return response()->json([
                'msg' => 'Gagal Login',
                'error' => $e->getMessage()
            ]);
        }
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();
        return response()->json([
            'msg' => 'Berhasil Logout'
        ]);
    }

    public function index()
    {
        $user = User::all();
        return response()->json($user);
    }

    public function forbidden()
    {
        return response()->json([
            'msg' => 'Unauthorization'
        ], 401);
    }
}
