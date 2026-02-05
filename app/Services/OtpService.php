<?php

namespace App\Services;

use App\Models\Otp;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;

class OtpService
{
    public static function generate(string $userType, int $userId)
    {
        $otpCode = rand(100000, 999999);

        Otp::where('user_type', $userType)
            ->where('user_id', $userId)
            ->where('status', 'pending')
            ->update(['status' => 'expired']);  //expires all previously pending otps

        Otp::create([
            'user_type'  => $userType,
            'user_id'    => $userId,
            'otp_code'   => Hash::make($otpCode),
            'status'     => 'pending',
            'attempts'   => 0,
            'expires_at' => now()->addMinutes(5),
        ]); //creates a new row for otp 

        return $otpCode;
    }

    public static function verify(string $userType, int $userId, string $otpInput)
    {
        $otp = Otp::where('user_type', $userType)
            ->where('user_id', $userId)
            ->where('status', 'pending')
            ->latest()
            ->first(); //fetches the latest otp Sabse latest (last generated) OTP

        if (!$otp) {
            return ['status' => false, 'message' => 'No OTP found'];
        }

        if ($otp->attempts >= 3) {
            $otp->update(['status' => 'expired']);
            return ['status' => false, 'message' => 'OTP attempts exceeded'];
        }

        if (Carbon::now()->gt($otp->expires_at)) {
            $otp->update(['status' => 'expired']);
            return ['status' => false, 'message' => 'OTP expired'];
        }

        $otp->increment('attempts'); //increment the attempt

        if (Hash::check($otpInput, $otp->otp_code)) {
            $otp->update(['status' => 'verified']);
            return ['status' => true, 'message' => 'OTP verified successfully'];
        }  //User ki dali hui OTP  Database me stored hashed OTP  Dono ko compare karta hai

        return ['status' => false, 'message' => 'Invalid OTP'];
    }

}

?>