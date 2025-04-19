<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // Register User
    public function register(Request $request) {

        // Validate
        $fields = $request->validate([
            'username' => ['required', 'max:255'],
            'email' => ['required', 'max:255', 'email', 'unique:users'],
            'password' => [
                'required',
                'min:8',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*[@$!%*#?&])[A-Za-z\d@#!%#?&]{8,}$/'
            ]
        ], [
            'password.required' => 'Password is required.',
            'password.min' => 'Password must be at least 8 characters.',
            'password.regex' => 'Password requires A-Z, a-z & special char.',
        ]);


        // Register
        $user = User::create($fields);

        // Login
        Auth::login($user);

        // Redirect
        return redirect()->intended('/home');
    }

    // Login User
    public function login(Request $request) {
        // Valid User Input
        $fields = $request->validate([
            'email' => ['required', 'max:255', 'email'],
            'password' => ['required']
        ]);
    
        if (Auth::attempt($fields)) {
            $request->session()->regenerate();
            $user = Auth::user();
            $role = $user->role;
    
            // Redirect based on user role
            switch ($role) {
                case 'Teacher':
                    return redirect()->route('teacher-home');
                case 'Student':
                    return redirect()->intended(route('student-home'));
                default:
                    return redirect()->intended(route('home'));
            }
        } else {
            return back()->withErrors([
                'failed' => 'Your provided credentials do not match our records.'
            ]);
        }
    }
    

    // Logout User
    public function logout(Request $request) {

        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
