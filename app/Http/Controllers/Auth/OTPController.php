<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Http; // Import Laravel HTTP client

class OTPController extends Controller
{
    public function sendOTP(Request $request)
    {
        // Validate the mobile number input
        $request->validate([
            'phone' => 'required|numeric|digits:10|exists:users,phone',  // Ensure phone exists in the database
        ]);
    
        // Find or create the user based on the phone number
        $user = User::where('phone', $request->phone)->first();
    
        if (!$user) {
            // If the user doesn't exist, create a new user
            $user = User::create([
                'phone' => $request->phone,
                'name' => 'Guest', // Default name for new user
            ]);
        }
    
        // Generate a 6-digit OTP
        $otp = rand(100000, 999999);
    
        // Save OTP and phone in the session for verification
        Session::put('otp', $otp);
        Session::put('phone', $request->phone);
    
        // Call the SMS API to send OTP
        try {
            $smsSent = $this->sendSms($request->phone, $otp);
    
            if ($smsSent) {
                return response()->json(['success' => true, 'message' => 'OTP sent successfully!']);
            } else {
                return response()->json(['success' => false, 'message' => 'Failed to send OTP. Please try again.'], 500);
            }
        } catch (\Exception $e) {
            // Catch any error that occurred during SMS sending
            return response()->json(['success' => false, 'message' => 'An error occurred: ' . $e->getMessage()], 500);
        }
    }
    
    // Function to send SMS via an external API
    private function sendSms($phone, $otp)
    {
        $apiUrl = 'https://rest.qikberry.ai/v1/sms/messages';
        $apiKey = '5c3876075d901cd1468c03949153b358'; // Replace with your actual API key
    
        $message = "Dear Guest, Welcome Aboard! Your Travels2020.com login OTP is ${otp}. This OTP will be valid only for 10 mins. -Travels2020 Team";
    
        $data = [
            'to' => '+91' . $phone, // Ensure you prefix with country code
            'sender' => 'TR2020',
            'service' => 'SI',
            'template_id' => '1707173769422420228',
            'message' => $message,
        ];
    
        try {
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Authorization' => "Bearer $apiKey"
            ])->post($apiUrl, $data);
    
            // Check if the API request was successful
            if ($response->successful()) {
                return true;
            } else {
                // Log or handle failed API response
                return false;
            }
        } catch (\Exception $e) {
            // Handle exceptions from HTTP request
            return false;
        }
    }
    

    // Step 2: Verify OTP
    public function verifyOTP(Request $request)
    {
        // Validate the OTP input
        $request->validate([
            'otp' => 'required|digits:6',
            'phone' => 'required|numeric|digits:10|exists:users,phone',  // Ensure phone exists in the database
        ]);

        // Retrieve OTP and phone from session
        $sessionOtp = Session::get('otp');
        $sessionPhone = Session::get('phone');

        if (!$sessionOtp || !$sessionPhone) {
            return response()->json(['success' => false, 'message' => 'Session expired. Please try again.'], 400);
        }

        // Check if the OTP and phone match
        if ($request->otp == $sessionOtp && $request->phone == $sessionPhone) {
            // Fetch the user by phone
            $user = User::where('phone', $sessionPhone)->first();

            if (!$user) {
                // If user is not found, create a new user
                $user = User::create([
                    'phone' => $sessionPhone,
                    'name' => 'Guest', // Default name for new user
                ]);
            }

            if ($user) {
                // Log in the user
                Auth::login($user);

                // Clear OTP and phone from the session
                Session::forget(['otp', 'phone']);

                // Redirect the user to the intended URL or homepage
                $redirectUrl = session()->pull('url.intended', url('/'));

                if (in_array($redirectUrl, [url('/'), route('login'), route('auth.register')])) {
                    $redirectUrl = url('/');
                }

                return response()->json(['success' => true, 'redirect' => $redirectUrl]);
            } else {
                return response()->json(['success' => false, 'message' => 'User not found.'], 404);
            }
        }

        return response()->json(['success' => false, 'message' => 'Invalid OTP. Please try again.'], 400);
    }
}
