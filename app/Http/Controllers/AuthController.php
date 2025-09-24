<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function register(Request $request)
    {
        $fields = $request->validate([
            'name' => 'required|string|max:255|regex:/^(?=.*[\p{Arabic}a-zA-Z])[\p{Arabic}a-zA-Z0-9\s]+$/u',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);



        $user = User::create($fields);


        Auth::login($user);

        return redirect('/index')->with('status', 'تم ارسال طلبك بنجاح و سيتم مراجعته قريبا');
    }




    public function login(Request $request)
    {
        $credentials=$request->validate([
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:8',
        ]);


        // محاولة تسجيل الدخول
        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            // 🔒 إعادة إنشاء السيشن بعد تسجيل الدخول
            $request->session()->regenerate();

            return redirect()->intended('/index')->with('success', 'تم تسجيل الدخول بنجاح.');
        }

        // لو كلمة المرور غلط
        return back()->withErrors([
            'email' => 'بيانات الدخول غير صحيحة.',
        ])->onlyInput('email');
    }



    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/index')->with('success', 'Logged out successfully.');
    }
}
