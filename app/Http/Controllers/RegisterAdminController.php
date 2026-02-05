<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Services\OtpService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\OtpMail;
use Illuminate\Support\Facades\Auth;

class RegisterAdminController extends Controller
{
    public function sendOtp(Request $request)
    {
        // Check if admin already exists
        if (Admin::where('email', $request->email)->exists()) {
            return response()->json([
                'status' => false,
                'message' => 'Admin already exists'
            ], 409);
        }

        // Create the admin first
        $admin = Admin::create([
            'name'  => $request->name,
            'email' => $request->email,

        ]);


        $otp = OtpService::generate('admin', $admin->id);


        Mail::to($request->email)->send(new OtpMail($otp));

        return response()->json([
            'status' => true,
            'message' => 'OTP sent to admin email'
        ]);
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'otp'   => 'required|digits:6',
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

        Auth::guard('admin')->login($admin);

        return response()->json([
            'status'   => true,
            'redirect' => route('admin.dashboard')
        ]);
    }
}
