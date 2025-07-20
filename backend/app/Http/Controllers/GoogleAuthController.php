<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Laravel\Passport\TokenRepository;

class GoogleAuthController extends Controller
{
    // Redirect ke Google OAuth
    public function redirect(Request $request)
    {
        $clientId = setting('apiKeys.googleClientId');
        $redirectUri = setting('apiKeys.googleRedirectUri');
        $state = Str::random(40);
        session(['google_oauth_state' => $state]);
        $url = 'https://accounts.google.com/o/oauth2/v2/auth?' . http_build_query([
            'client_id' => $clientId,
            'redirect_uri' => $redirectUri,
            'response_type' => 'code',
            'scope' => 'openid email profile',
            'state' => $state,
            'access_type' => 'offline',
            'prompt' => 'consent',
        ]);
        return redirect($url);
    }

    // Handle callback dari Google
    public function callback(Request $request)
    {
        $clientId = setting('apiKeys.googleClientId');
        $clientSecret = setting('apiKeys.googleClientSecret');
        $redirectUri = setting('apiKeys.googleRedirectUri');
        $state = $request->input('state');
        if ($state !== session('google_oauth_state')) {
            return response()->json(['status' => false, 'message' => 'Invalid state'], 401);
        }
        $code = $request->input('code');
        // Exchange code for access token
        $tokenResponse = Http::asForm()->post('https://oauth2.googleapis.com/token', [
            'client_id' => $clientId,
            'client_secret' => $clientSecret,
            'redirect_uri' => $redirectUri,
            'grant_type' => 'authorization_code',
            'code' => $code,
        ]);
        if (!$tokenResponse->ok()) {
            return response()->json(['status' => false, 'message' => 'Failed to get Google token'], 401);
        }
        $accessToken = $tokenResponse['access_token'];
        // Get user info from Google
        $userInfo = Http::withHeaders([
            'Authorization' => 'Bearer ' . $accessToken
        ])->get('https://www.googleapis.com/oauth2/v3/userinfo');
        if (!$userInfo->ok()) {
            return response()->json(['status' => false, 'message' => 'Failed to get Google user info'], 401);
        }
        $googleUser = $userInfo->json();
        // Login/daftar user
        $user = User::firstOrCreate(
            ['email' => $googleUser['email']],
            [
                'name' => $googleUser['name'] ?? $googleUser['email'],
                'google_id' => $googleUser['sub'] ?? null,
                'role' => 'member',
                'password' => bcrypt(Str::random(16)),
            ]
        );
        $token = $user->createToken('auth-token')->accessToken;
        return response()->json([
            'status' => true,
            'message' => 'Login Google berhasil',
            'data' => [
                'user' => $user,
                'token' => $token,
                'token_type' => 'Bearer',
                'google_user' => $googleUser
            ]
        ]);
    }
} 