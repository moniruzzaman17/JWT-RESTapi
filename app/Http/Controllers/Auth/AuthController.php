<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Requests\UserRegistrationRequest;

class AuthController extends Controller
{
    public function register(UserRegistrationRequest $request)
    {
        // Validate incoming request data
        $validatedData = $request->validated();

        // Create a new user and hash the password
        $user = User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => bcrypt($validatedData['password']),
        ]);

        // Logged in and generate a JWT token for the newly registered user
        $token = $this->guard()->login($user);

        // Return a response with the token
        return $this->respondWithToken($token);
    }

    public function login(Request $request)
    {
        // Retrieve login credentials from the request
        $credentials = $request->only('email', 'password');

        // Attempt to login and generate a token if successful
        if ($token = $this->guard()->attempt($credentials)) {
            return $this->respondWithToken($token);
        }

        // Return an unauthorized error if credentials are invalid
        return response()->json(['error' => 'Unauthorized'], 401);
    }
    
    public function me()
    {
        // Return the authenticated user's details
        return response()->json($this->guard()->user());
    }
    
    public function logout(Request $request)
    {
        // Logout the user and invalidate the token
        $this->guard()->logout();
        $token = $request->header('Authorization');
        if ($token) {
            $token = str_replace('Bearer ', '', $token);
            $this->guard()->invalidate($token);
        }

        return response()->json(['message' => 'Successfully logged out']);
    }
    
    public function refresh()
    {
        // Generate a new token for the user
        return $this->respondWithToken($this->guard()->refresh());
    }
    
    protected function respondWithToken($token)
    {
        // Return the access token
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => $this->guard()->factory()->getTTL() * 60
        ]);
    }
    
    public function guard()
    {
        // Return the API authentication guard
        return Auth::guard('api');
    }
}
