<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\OtpService;

class OtpController extends Controller
{
    /**
     * Generate OTP for user/admin
     */
    public function generate(Request $request)
    {
        $request->validate([
            'user_type' => 'required|in:user,admin',
            'user_id' => 'required|integer',
        ]);

        $otp = OtpService::generate($request->user_type, $request->user_id);

        // TODO: Send $otp via SMS or Email
        // Example: Mail::to($user->email)->send(new OtpMail($otp));

        return response()->json([
            'status' => true,
            'message' => 'OTP sent successfully',
            'otp' => $otp // remove this in production, only for testing
        ]);
    }

    /**
     * Verify OTP for user/admin
     */
    public function verify(Request $request)
    {
        $request->validate([
            'user_type' => 'required|in:user,admin',
            'user_id' => 'required|integer',
            'otp' => 'required|digits:6',
        ]);

        $result = OtpService::verify($request->user_type, $request->user_id, $request->otp);

        return response()->json($result);
    }
}
