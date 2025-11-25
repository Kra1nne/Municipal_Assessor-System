<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Throwable;
use App\Models\Log;

class GoogleAuthController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Handle the callback from Google.
     */
    public function callback()
    {
        try {
            $user = Socialite::driver('google')->user();
        } catch (Throwable $e) {
            return redirect('/')->with('error', 'Google authentication failed.');
        }
        $existingUser = User::where('email', $user->email)->whereNull('deleted_at')->first();

        if ($existingUser) {
            // Log the user in if they already exist
            $logData = [
              'user_id' => $existingUser->id,
              'action' => 'Login',
              'table_name' => 'Users',
              'description' => 'Successfully login',
              'ip_address' => request()->ip(),
              'created_at' => now(),
            ];

            $resultLogs = Log::insert($logData);
            Auth::login($existingUser);

            return redirect('/dashboard');
        } else {
          return redirect()->route('booking')->with('show_modal', true);
        }

    }
}
