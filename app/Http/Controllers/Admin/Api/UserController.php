<?php

namespace App\Http\Controllers\Admin\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Http\Requests\Admin\Api\LoginRequest;
use App\Http\Requests\Admin\Api\RegisterRequest;
use App\Http\Requests\Admin\Api\ForgetRequest;
use App\Models\OtpRequest;
use App\Http\Resources\Admin\Api\UserResource;
use App\Helpers\Classes\ResponseHelpers;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use App\Mail\SendOtpMail;

/**
 * @group Auth
 * Auth-related endpoints
 */
class UserController extends Controller
{
   /**
    * 
    @group Auth
     * User login
     *
     * @bodyParam email string required The user's email address. Example: user@example.com
     * @bodyParam password string required The user's password. Example: password123
     *
     * @response 200 {
     *   "message": "Login successful",
     *   "data": {
     *     "token": "example_token",
     *     "user": {
     *       "id": 1,
     *       "name": "John Doe",
     *       "email": "user@example.com",
     *       "phone_number": "123456789",
     *       "image": "http://example.com/image.jpg"
     *     }
     *   }
     * }
     * @response 401 {
     *   "message": "Login failed",
     *   "errors": {
     *     "email": ["Invalid credentials"]
     *   }
     * }
     */
    public function login(LoginRequest $request)
    {
        $data = $request->validated();

        $user = User::query()->where('email', $data['email'])->first();
        if (!$user) {
            return ResponseHelpers::unauthorized(__('auth.failed'), [
                'email' => [
                    __('auth.failed')
                ],
            ]);
        }

        if (!Hash::check($data['password'], $user->password)) {
            return ResponseHelpers::unauthorized(__('auth.failed'), [
                'password' => [
                    __('auth.failed')
                ],
            ]);
        }

        $token = $user->createToken('admin_auth_token')->plainTextToken;

        return ResponseHelpers::success(__('auth.login_success'), [
            'token' => $token,
            'user' => UserResource::make($user)
        ]);
    }
   /**
    * @group Auth
     * User logout
     *
     * @header Authorization Bearer {token}
     *
     * @response 200 {
     *   "message": "Logout successful"
     * }
     * @response 400 {
     *   "message": "An unknown error occurred"
     * }
     */
    public function logout()
    {
        if (auth()->user()?->currentAccessToken()->delete()):
            return ResponseHelpers::success(__('auth.logout_success'));
        endif;
        return ResponseHelpers::error(__('messages.unknown_error'));
    }
   /**
    * @group Auth
     * Get logged-in user info
     *
     * @header Authorization Bearer {token}
     *
     * @response 200 {
     *   "message": "Retrieved successfully",
     *   "data": {
     *     "user": {
     *       "id": 1,
     *       "name": "John Doe",
     *       "email": "user@example.com",
     *       "phone_number": "123456789",
     *       "image": "http://example.com/image.jpg"
     *     }
     *   }
     * }
     */
    public function me()
    {
        return ResponseHelpers::success(__('messages.retrieved_successfully'), [
            'user' => UserResource::make(auth()->user()),
        ]);
    } 
   /**
    * @group Auth
     * Create a new account
     *
     * @bodyParam name string required The name of the user. Example: John Doe
     * @bodyParam email string required The email of the user. Example: user@example.com
     * @bodyParam password string required The password for the user. Example: password123
     * @bodyParam phone_number string required The user's phone number. Example: 123456789
     * @bodyParam image file required The user's profile picture.
     *
     * @response 201 {
     *   "message": "Registration successful",
     *   "data": {
     *     "token": "example_token",
     *     "user": {
     *       "id": 1,
     *       "name": "John Doe",
     *       "email": "user@example.com",
     *       "phone_number": "123456789",
     *       "image": "http://example.com/image.jpg"
     *     }
     *   }
     * }
     */
    public function createAccount(RegisterRequest $request)
    {
        $data = $request->validated();

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']), 
            'phone_number' => $data['phone_number'],
            // 'image' => $data['image']

        ]);

        $token = $user->createToken('admin_auth_token')->plainTextToken;

        return ResponseHelpers::success(__('auth.registration_success'), [
            'token' => $token,
            'user' => UserResource::make($user),
        ]);
    }
      /**
       * @group Auth
     * Forgot password - send OTP
     *
     * @bodyParam email string required The email address to send the OTP to. Example: user@example.com
     *
     * @response 200 {
     *   "message": "OTP sent successfully",
     *   "data": {
     *     "id": 1,
     *     "email": "user@example.com",
     *     "otp": 123456,
     *     "expires_at": "2024-11-23T10:00:00Z"
     *   }
     * }
     */
     public function forgotPassword(ForgetRequest $request)
    {
        $validated = $request->validated();

        $otp = rand(100000, 999999); 

        Mail::to($validated['email'])->send(new SendOtpMail($otp));

        $otpRequest = OtpRequest::create([
            'email' => $validated['email'],
            'otp' => $otp,
            'expires_at' => Carbon::now()->addMinutes(10),  
        ]);

        return ResponseHelpers::success(__('auth.otp_sent_successfully'), $otpRequest);
    }
}
