<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;

class AuthUserController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'firstName' => 'required|string',
            'lastName' => 'required|string',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|min:5',
        ]);

       // dd($validator);

        if ($validator->fails()) {
            $errors = collect($validator->errors()->messages())
                ->map(function ($error, $field) {
                    return [
                        'field' => $field,
                        'message' => $error[0],
                    ];
                })
                ->values()
                ->toArray();

            return response()->json(['errors' => $errors], 422);
        }

        try {
            $user = User::create([
                'firstName' => $request->firstName,
                'lastName' => $request->lastName,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'phone' => $request->phone,
            ]);

           // dd($user);


            $token = $user->createToken('Personal Access Token')->accessToken;

            return response()->json([
                'status' => 'success',
                'message' => 'Registration successful',
                'data' => [
                    'accessToken' => $token,
                    'user' => $user
                ]
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'Bad request',
                'message' => 'Registration unsuccessful',
                'statusCode' => 400
            ], 400);
        }
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()], 422);
        }

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        $token = $user->createToken('Personal Access Token')->accessToken;

        return response()->json([
            'status' => 'success',
            'message' => 'Login successful',
            'data' => [
                'accessToken' => $token,
                'user' => $user
            ]
        ]);
    }

    public function destroy(Request $request)
    {
       
        $token = $request->user()->token();
        $token->revoke();
        return [
            'success' => true,
            'message' => 'Logged out successfully',
        ];
    }
}
