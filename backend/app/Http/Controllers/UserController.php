<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Hash;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
    /**
     * @param \Illuminate\Http\Request $request
     * @return JsonResponse
     */
    public function register(Request $request): JsonResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'type' => $request->type,       
            'password' => bcrypt($request->password),
        ]);

        return response()->json($user);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @return JsonResponse|mixed
     */
    public function login(Request $request):JsonResponse
    {
        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Login ou senha incorretos']
            ]);
        }

        return response()->json([
            'token' => $user->createToken('api-token')->plainTextToken
        ]);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @return JsonResponse|mixed
     */
    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Logout realizado com sucesso']);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @return mixed
     */
    public function show(Request $request): mixed
    {
        return $request->user();
    }
}