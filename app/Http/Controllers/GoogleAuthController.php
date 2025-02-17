<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Laravel\Socialite\Facades\Socialite;

class GoogleAuthController extends Controller
{
    public function redirectChoose() {
        return Socialite::driver('google')->redirect();
    }
    
    public function callbackGoogle() {
        try {
            $google_user = Socialite::driver('google')->user();
    
            // Retrieve the user from the database by Google ID
            $user = User::where('google_id', $google_user->getId())->first();
    
            // If the user doesn't exist, create a new user
            if(!$user) {
                // Fetch phone number from Google People API
                $accessToken = $google_user->token;
                $userInfo = $this->getGoogleUserInfo($accessToken);
    
                // Get the primary phone number (if available)
                $phone_number = null;
                if (isset($userInfo['phoneNumbers'])) {
                    $phone_number = $userInfo['phoneNumbers'][0]['value'];
                }
    
                // Create a new user with the retrieved data
                $new_user = User::create([
                    'username' => $google_user->getName(),
                    'email' => $google_user->getEmail(),
                    'phone_number' => $phone_number,  // Store the phone number if available
                    'google_id' => $google_user->getId(),
                    'password' => Hash::make(uniqid()),  // Generate a random password
                ]); 
    
                Auth::login($new_user);  // Log in the new user
                return redirect()->intended('/home');
            } else {
                // Log in the existing user
                Auth::login($user);
                return redirect()->intended();
            }
    
        } catch (\Throwable $th) {
            // Handle error and redirect
            return redirect()->intended();
        }
    }
    
    // Helper function to fetch user info (including phone number) from Google People API
    public function getGoogleUserInfo($accessToken)
    {
        $response = Http::withToken($accessToken)->get('https://people.googleapis.com/v1/people/me', [
            'personFields' => 'phoneNumbers',
        ]);
    
        return $response->json();
    }
    
}
