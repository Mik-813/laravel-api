<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8',
        ]);

        $user = User::create([
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return response()->json([
            'user' => $user,
            'token' => 'Bearer ' . $user->createToken('auth_token')->plainTextToken,
        ], 201);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        return response()->json([
            'user' => $user,
            'token' => 'Bearer ' . $user->createToken('auth_token')->plainTextToken,
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Logged out']);
    }

    public function sendVerificationEmail(Request $request)
    {
        $request->validate([
            'verification_url' => 'required|url',
        ]);

        $user = $request->user();
        $token = Str::random(64);

        DB::table('email_verification_tokens')->updateOrInsert(
            ['email' => $user->email],
            ['token' => $token, 'created_at' => now()]
        );

        $url = $request->verification_url . (str_contains($request->verification_url, '?') ? '&' : '?') . 'token=' . $token;

        Mail::raw("Please verify your email by clicking this link: {$url}", function ($message) use ($user) {
            $message->to($user->email)->subject('Verify Your Email');
        });

        return response()->json(['message' => 'Verification email sent']);
    }

    public function verify(Request $request)
    {
        $request->validate(['token' => 'required|string']);

        $record = DB::table('email_verification_tokens')->where('token', $request->token)->first();

        if (! $record) {
            return response()->json(['message' => 'Invalid or expired token'], 422);
        }

        $user = User::where('email', $record->email)->first();
        if ($user) {
            $user->email_verified_at = now();
            $user->save();
        }

        DB::table('email_verification_tokens')->where('email', $record->email)->delete();

        return response()->json(['message' => 'Email verified successfully']);
    }
}