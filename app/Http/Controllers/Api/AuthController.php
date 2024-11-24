<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function logout()
    {
        try {
            auth('sanctum')->user()->currentAccessToken()->delete();

            return $this->success('Logged out successfully.');
        } catch (Exception $e) {
            $this->errorLog($e, 'error while logging out.');

            return $this->failed('some error occurred while register.');
        }
    }

    public function login(LoginRequest $request)
    {
        try {
            $user = User::with('roles')->where('email', $request->email)->first();

            if (! $user || ! Hash::check($request->password, $user->password)) {
                return $this->failed('bad email or password', [], 400);
            }

            $token = $user->createToken('token', ['*'], now()->addMinutes(60))->plainTextToken;

            return $this->success('Logged in successfully.', ['user' => new UserResource($user), 'token' => $token]);
        } catch (Exception $e) {
            $this->errorLog($e, 'error while logging in');

            return $this->failed('some error occurred while logging in');
        }
    }
}
