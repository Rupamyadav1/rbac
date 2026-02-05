<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Mail\OtpMail;
use App\Models\User;
use App\Services\OtpService;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
class UserLoginController extends Controller
{
    public function sendOtp(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
        ]);

        $user = User::where('email', $request->email)->first();

        $otp = OtpService::generate('user', $user->id);
        Mail::to($user->email)->send(new OtpMail($otp));

        return response()->json([
            'status' => true,
            'message' => 'OTP sent'
        ]);
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'otp'   => 'required|digits:6'
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'Admin not found'
            ], 404);
        }

        $result = OtpService::verify('user', $user->id, $request->otp);

        if (!$result['status']) {
            return response()->json($result, 422);
        }

        // ✅ PASSWORDLESS ADMIN LOGIN
        Auth::guard('web')->login($user);

        return response()->json([
            'status' => true,
            'message' => 'Login successful',
            'redirect' => route('user.dashboard')
        ]);
    }
    
}
?>