<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Rules\SchoolNameMatchesEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{

    public function updateRole(Request $request)
    {
        $userId = Auth::id();
        $request->validate([
            'role' => 'required|in:Teacher,Student',
        ]);

        $user = User::where('id', $userId)->first();
    
        $user->role = $request->role;
        $user->save();
    
        if ($user->role === 'Student') {
            return redirect()->intended(route('student-home'));
        } else {
            return redirect()->intended(route('teacher-home')); 
        }
    }

    public function updateName(Request $request)
    {
        $userId = Auth::id();
        $user = User::where('id', $userId)->first();
        
        $validatedName = $request->validate([
            'changeName' => [
                'required',
                'regex:/^[A-Z][a-z]+,\s?.*$/',
                new SchoolNameMatchesEmail($user->email)
            ],
        ], [
            'changeName.regex' => 'The Fullname you provided is invalid!'
        ]);

        
        $user->school_name = $validatedName['changeName'];
        $user->save();

        return response()->json(['message' => 'Name updated successfully!']);
    }

}