<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Mail\VerificationEmail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = User::all();
        return response()->json($user);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email:rfc,dns|unique:users,email',
            'phone_number' => 'required|string|regex:/(8)[0-9]{8}/|max:15|unique:users,phone_number',
            'username' => 'required|unique:users,username',
            'password' => 'required',
        ]);

        if ($validate->fails()) {
            return $this->sendError('Validation Error.', $validate->errors());
        }
        // Generate and send OTP to the provided phone number
        // $otp = $otpService->generateOtp($request->phone);
        $verification_token = Str::random(32);

        // Generate a unique verification token
        do {
            $verification_token = Str::random(40);
        } while (User::where('verification_token', $verification_token)->exists());


        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'username' => $request->username,
            'password' => bcrypt($request->password),
            'verification_token' => $verification_token,
        ]);

        // Send verification email to the customer
        $verificationEmail = new VerificationEmail($user);
        Mail::to($user->email)->send($verificationEmail);

        $success = $user;
        $success['token'] = $user->createToken('ReservasiKafeAuth')->plainTextToken;
        // return response()->json($user, 200);
        return $this->sendResponse($success, 'User register successfully.');
    }

    public function login(Request $request)
    {
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = Auth::user();
            $success =  $user;
            
            /** @var \App\Models\User $user **/
            $user->tokens()->where('tokenable_id', $user->id)->delete();
            $success['token'] =  $user->createToken('ReservasiKafeAuth')->plainTextToken;
            return $this->sendResponse($success, 'User login successfully.');
        } else {
            return $this->sendError('Unauthorised.', ['error' => 'Unauthorised']);
        }
    }

    public function verifyEmail($token)
    {
        $user = User::where('verification_token', $token)->first();

        if (!$user) {
            return $this->sendError('Unauthorised.', ['error' => 'Invalid verification token']);
        }

        if ($user->verified) {
            return $this->sendError('Unauthorised.', ['error' => 'Email already verified']);
        }

        // Update the user's verification token in the database
        $user->email_verified_at = now();
        $user->verification_token = null;
        $user->save();

        return $this->sendResponse($user, 'Email verified successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::find($id);

        if (!$user) {
            return $this->sendError('Show Error.', 'User not found');
        }

        return $this->sendResponse($user, 'Show User successfully.');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user = User::find($id);

        if (!$user) {
            return $this->sendError('Update Error.', 'User not found');
        }

        if ($user->id != $request->user()->id) {
            return $this->sendError('Update Error', 'Unauthorized');
        }

        $validate = Validator::make($request->all(), [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email:rfc,dns|unique:users,email,' . $user->id,
            'phone_number' => 'required|string|regex:/(8)[0-9]{8}/|max:15|unique:users,phone_number,' . $user->id,
            'username' => 'required|unique:users,username,' . $user->id,
            'password' => 'required',
        ]);

        if ($validate->fails()) {
            return $this->sendError('Update Error.', $validate->errors());
        }

        $user->update([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'username' => $request->username,
            'password' => bcrypt($request->password),
        ]);

        return $this->sendResponse($user, 'Update User successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::find($id);

        if (!$user) {
            return $this->sendError('Delete Error.', 'User not found');
        }

        $user->delete();

        return $this->sendResponse($user, 'Deleted User successfully.');
    }
}
