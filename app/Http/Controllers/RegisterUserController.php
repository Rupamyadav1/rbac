<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\OtpService; 
use Illuminate\Support\Facades\Mail;
use App\Mail\OtpMail;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class RegisterUserController extends Controller
{
     public function sendOtp(Request $request)
    {
        // Check if admin already exists
        if (User::where('email', $request->email)->exists()) {
            return response()->json([
                'status' => false,
                'message' => 'User already exists'
            ], 409);
        }

        // Create the admin first
        $user = User::create([
            'name'  => $request->name,
            'email' => $request->email,

        ]);


        $otp = OtpService::generate('user', $user->id);


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

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'Admin not found'
            ], 404);
        }

        $result = OtpService::verify('admin', $user->id, $request->otp);

        if (!$result['status']) {
            return response()->json($result, 422);
        }

        Auth::guard('admin')->login($user);

        return response()->json([
            'status'   => true,
            'redirect' => route('admin.dashboard')
        ]);
    }
}
?>