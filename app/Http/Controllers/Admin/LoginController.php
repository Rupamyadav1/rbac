<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\OtpMail;
use App\Models\Admin;
use App\Services\OtpService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function sendOtp(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
        ]);

        $admin = Admin::where('email', $request->email)->first();



        $otp = OtpService::generate('admin', $admin->id);
        Mail::to($admin->email)->send(new OtpMail($otp));

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

        $admin = Admin::where('email', $request->email)->first();

        if (!$admin) {
            return response()->json([
                'status' => false,
                'message' => 'Admin not found'
            ], 404);
        }

        $result = OtpService::verify('admin', $admin->id, $request->otp);

        if (!$result['status']) {
            return response()->json($result, 422);
        }

        // ✅ PASSWORDLESS ADMIN LOGIN
        Auth::guard('admin')->login($admin);

        return response()->json([
            'status' => true,
            'message' => 'Login successful',
            'redirect' => route('admin.dashboard')
        ]);
    }
}
