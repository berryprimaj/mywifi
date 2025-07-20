<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use App\Models\User;

class OtpController extends Controller
{
    // Request OTP
    public function requestOtp(Request $request)
    {
        $request->validate([
            'phone' => 'required|string'
        ]);

        $otp = rand(100000, 999999);
        $phone = $request->phone;

        // Simpan OTP ke cache (atau database)
        Cache::put('otp_' . $phone, $otp, now()->addMinutes(5));

        // Kirim OTP ke WhatsApp user via API (misal Fonte)
        $apiKey = setting('apiKeys.fonteApiKey'); // Ambil dari database/settings
        $deviceId = setting('apiKeys.fonteDeviceId');
        $message = "Kode OTP Anda: $otp";

        // Contoh request ke Fonte (ganti sesuai provider)
        Http::withHeaders([
            'Authorization' => $apiKey
        ])->post('https://api.fonte.id/send-message', [
            'device_id' => $deviceId,
            'number' => $phone,
            'message' => $message
        ]);

        return response()->json(['status' => true, 'message' => 'OTP dikirim ke WhatsApp']);
    }

    // Verifikasi OTP
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'phone' => 'required|string',
            'otp' => 'required|string'
        ]);

        $phone = $request->phone;
        $otp = $request->otp;
        $cachedOtp = Cache::get('otp_' . $phone);

        if ($cachedOtp !== $otp) {
            return response()->json(['status' => false, 'message' => 'OTP salah atau sudah expired'], 401);
        }

        // Login/daftarkan user
        $user = User::firstOrCreate(
            ['phone' => $phone],
            ['name' => 'User ' . $phone, 'email' => $phone . '@otp.local', 'password' => bcrypt('otp-login'), 'role' => 'member']
        );

        $token = $user->createToken('auth-token')->accessToken;

        // Hapus OTP dari cache
        Cache::forget('otp_' . $phone);

        return response()->json([
            'status' => true,
            'message' => 'Login berhasil',
            'data' => [
                'user' => $user,
                'token' => $token,
                'token_type' => 'Bearer'
            ]
        ]);
    }
} 