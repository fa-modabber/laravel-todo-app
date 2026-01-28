<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class ForgetPasswordController extends Controller
{
    public function forgetPassword()
    {
        return view('auth.forget-password');
    }

    public function forgetPasswordPost(Request $request)
    {
        // dd($request->email);
        $request->validate([
            'email' => 'required|email|exists:users',
        ]);

        $emailExists = DB::table('password_reset_tokens')->where('email', $request->email)->first();
        if ($emailExists) {
            return redirect()->back()->with('error', 'forget-password email is already sent!');
        }

        $token = str()->random(64);
        DB::table('password_reset_tokens')->insert([
            'email' => $request->email,
            'token' => $token,
            'created_at' => Carbon::now()
        ]);

        Mail::send('emails.forget-password',['token' => $token], function($message) use($request){
            $message->to($request->email);
            $message->subject('Reset Password');
        });

        return redirect()->back()->with('success','we sent reset password email');
    }

    public function resetPassword($token)
    {
        return view('auth.reset-password', compact('token'));
    }

    public function resetPasswordPost(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'password' => 'required|min:5|confirmed',
        ]);

        $resetPasswordRecord = DB::table('password_reset_tokens')->where([
            'token' => $request->token
        ])->first();

        if (!$resetPasswordRecord) {
            return redirect()->back()->with('error', 'Invalid Data');
        }

        User::where('email', $resetPasswordRecord->email)->update([
            'password' => Hash::make($request->password)
        ]);

        DB::table('password_reset_tokens')->where([
            'token' => $request->token
        ])->delete();
        return redirect()->route('login');
    }
}
