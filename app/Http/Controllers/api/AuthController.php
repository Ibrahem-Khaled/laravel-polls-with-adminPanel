<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\userPushToken;
use Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'phone' => 'required',
            'password' => 'required',
        ]);

        $credentials = $request->only('phone', 'password');
        if (!$token = JWTAuth::attempt($credentials)) {
            return response()->json(['error' => 'Invalid login details'], 401);
        }
        $user = Auth::user();
        return response()->json([
            'token' => $token,
            'user' => $user,
        ], 200);
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:255',
            'password' => 'required|string|min:8',
            'role' => 'required|string|max:255',
        ]);

        $user = User::create([
            'name' => $request->name,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'role' => $request->role
        ]);

        $token = JWTAuth::fromUser($user);

        return response()->json([
            'token' => $token,
            'user' => $user,
        ], 201);
    }

    public function update(Request $request)
    {
        $user = auth()->guard('api')->user();
        $request->validate([
            'name' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:255',
            'expo_push_token' => 'nullable|string',
        ]);

        if ($request->hasFile('image')) {
            if ($user->image) {
                Storage::disk('public')->delete($user->image);
            }
            $user->image = $request->file('image')->store('images', 'public');
        }

        if ($request->hasFile('identity')) {
            if ($user->identity) {
                Storage::disk('public')->delete($user->identity);
            }
            $user->identity = $request->file('identity')->store('identities', 'public');
        }
        $dataToUpdate = array_filter($request->only(['name', 'phone', 'email', 'address', 'description', 'image', 'identity']), function ($value) {
            return $value !== null;
        });

        $user->update($dataToUpdate);

        if ($request->filled('expo_push_token')) {
            userPushToken::updateOrCreate(
                ['user_id' => $user->id],
                ['expo_push_token' => $request->expo_push_token]
            );
        }

        return response()->json($user, 200);
    }

    public function user()
    {
        $user = auth()->guard('api')->user();
        if ($user) {
            return response()->json($user, 200);
        }
        return response()->json(['error' => 'Unauthenticated.'], 401);
    }

    public function logout()
    {
        auth()->guard('api')->logout();
        return response()->json(['message' => 'Successfully logged out']);
    }

    public function deleteAccount()
    {
        $user = auth()->guard('api')->user();
        $user->delete();
        return response()->json(['message' => 'Account deleted successfully.'], 200);
    }
}
