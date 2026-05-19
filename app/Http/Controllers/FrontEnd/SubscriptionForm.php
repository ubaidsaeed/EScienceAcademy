<?php

namespace App\Http\Controllers\FrontEnd;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Subscriptionplans;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Mail as FacadesMail;
use Illuminate\Support\Str;

use Exception;
use Illuminate\Support\Carbon;
use InvalidArgumentException;
use Spatie\Permission\Models\Role;

class SubscriptionForm extends Controller
{
    public $data = [];
    public $page_type = 'subform';
    public $plan_id;

    // public function render()
    // {
    //     return view('livewire.front-end.subscription-form');
    // }
    public function subscribes($slug, $levelslug = null)
    {
        $level = DB::table('levels')->where('slug', $levelslug)->pluck('id')->first();
        $subcription = Subscriptionplans::where(['slug' => $slug, 'level_id' => $level])->first();

        $this->data['subscription'] = $subcription;
        $plan_id = $subcription->id; // Directly set the plan_id property
        // $this->render();
        if (Auth::check()) {


            // $findsubcription = DB::table('subscriptions')->where(['user_id' => Auth::id() ,'status' => 'active'])->first();
            $findsubcriptionInactive = DB::table('subscriptions')->where(['user_id' => Auth::id(), 'status' => 'unactive'])->first();

            $user = DB::table('users')->where('id', Auth::id())->first();
            // if($user->role_id != null)
            // {
            //  $subscription = Subscriptionplans::where('slug', $slug)->first();
            $subscription = [
                'duration' => $subcription->duration,
                'duration_type' => $subcription->duration_type,
            ];
            $result = $this->calculateSubscriptionEndDate($subscription['duration'], $subscription['duration_type']);
            // $this->plan_id = session('pack_slug');
            //$id = Auth::id();

            $users = User::find($user->id);

            $role = Role::where('name', 'student')->first();
            // dd($users);
            $users->assignRole($role->id);

            // If inactive subscription exists, update it instead of inserting new
            if ($findsubcriptionInactive) {
                DB::table('subscriptions')->where('id', $findsubcriptionInactive->id)->update([
                    'plan_id' => $plan_id,
                    'board_id' => $subcription->board_id,
                    'level_id' => $subcription->level_id,
                    'subject_id' => $subcription->subject_id,
                    'plan_price' => $subcription->price,
                    'payment_method_id' => 1,
                    'payment_status' => 'pending',
                    'status' => 'unactive',
                    'updated_at' => now(),
                ]);
            }
            // No inactive subscription exists, insert new one
            else {
                DB::table('subscriptions')->insert([
                    'user_id' => $user->id,
                    'plan_id' => $plan_id,
                    'board_id' => $subcription->board_id,
                    'level_id' => $subcription->level_id,
                    'subject_id' => $subcription->subject_id,
                    'plan_price' => $subcription->price,
                    'payment_method_id' => 1,
                    'payment_status' => 'pending',
                    'status' => 'unactive',
                    // 'start_date' => $result['start_date'],
                    // 'end_date' => $result['end_date'],
                    'created_at' => now(),
                ]);
            }

            //  $this->dispatch('alertSuccess', 'Operation completed successfully!');
            //  session('pack_slug');
            return redirect()->intended('student/payment-method');
        } else {

            return view('front-end.subscription-form');
        }
    }
    public function redirectToFacebook($slug = null)
    {
        session(['pack_slug' => $slug]);
        return Socialite::driver('facebook')->redirect();
    }
    public function handleFacebookCallback()
    {
        try {
            // Retrieve the Facebook user data
            $facebookUser = Socialite::driver('facebook')->user();
            $plan_id = session('pack_slug');
            // Validate the Facebook user data
            if (!$facebookUser || !$facebookUser->getId()) {
                throw new \Exception('Invalid Facebook user data.');
            }
            // Check if the user already exists in the database
            $user = User::where('facebook_id', $facebookUser->getId())->first();
            $subcription = DB::table('subscription_plans')->where('slug', $this->plan_id)->first();
            if ($user) {
                $checkSubscribe = DB::table('subscriptions')->where('user_id', $user->id)->exists();
                if (!$checkSubscribe) {

                    return redirect()->route('frontend.packages', $user->id);
                }
                if ($user->role_id == null) {
                    Auth::login($user);
                    // session('pack_slug');
                    return redirect()->intended('student/payment-method');
                }
                // Log in the existing user
                // Auth::login($user);
            } else {
                // Validate email (Facebook may not always provide an email)
                $email = $facebookUser->getEmail();
                if (!$email) {
                    throw new \Exception('Email is required but not provided by Facebook.');
                }

                // Check if a user with the same email already exists
                $existingUser = User::where('email', $email)->first();
                if ($existingUser) {
                    // Update the existing user with Facebook ID
                    $existingUser->update(['facebook_id' => $facebookUser->getId()]);
                    $user = $existingUser;
                } else {
                    // Create a new user
                    $user = User::create([
                        'name' => $facebookUser->getName(),
                        'email' => $email,
                        'facebook_id' => $facebookUser->getId(),
                        'password' => encrypt('123456dummy'),
                        'email_verified_at' => now()
                    ]);
                    DB::table('subscriptions')->insert([
                        'user_id' => $user->id,
                        'plan_id' => $subcription->id,
                        'board_id' => $subcription->board_id,
                        'level_id' => $subcription->level_id,
                        'subject_id' => $subcription->subject_id,
                        'plan_price' => $subcription->price,
                        'payment_method_id' => 1,
                        'payment_status' => 'pending',
                        'status' => 'unactive',
                        'created_at' => now(),
                        // 'start_date' => ,
                        // 'end_date' => 
                    ]);
                }
                Auth::login($user);
            }
            session('pack_slug');
            return redirect()->intended('student.payment.method');
        } catch (\Exception $e) {
            // dd($e->getMessage());
            return redirect()->route('login');
        }
    }


    public function redirectToGoogle($slug = null)
    {
        session(['pack_slug' => $slug]);
        return Socialite::driver('google')->redirect();
    }
    public function handleGoogleCallback(Request $request)
    {
        $plan_id = session('pack_slug');

        try {
            // Retrieve the Facebook user data
            $googleUser = Socialite::driver('google')->user();
            // Validate the Facebook user data
            if (!$googleUser || !$googleUser->getId()) {
                throw new \Exception('Invalid Facebook user data.');
            }
            // Check if the user already exists in the database
            $user = User::where('google_id', $googleUser->getId())->first();

            $subcription = DB::table('subscription_plans')->where('slug', $this->plan_id)->first();
            if ($user) {
                $checkSubscribe = DB::table('subscriptions')->where('user_id', $user->id)->exists();
                if (!$checkSubscribe) {

                    return redirect()->route('frontend.packages', $user->id);
                }
                if ($user->role_id == null) {
                    Auth::login($user);
                    // session('pack_slug');
                    return redirect()->intended('student.payment.method');
                }
            } else {
                // Validate email (Google may not always provide an email)
                $email = $googleUser->getEmail();
                if (!$email) {
                    throw new \Exception('Email is required but not provided by Google.');
                }

                // Check if a user with the same email already exists
                $existingUser = User::where('email', $email)->first();
                if ($existingUser) {
                    // Update the existing user with Facebook ID
                    $existingUser->update(['google_id' => $googleUser->getId()]);
                    $user = $existingUser;
                } else {
                    // Create a new user
                    $user = User::create([
                        'name' => $googleUser->getName(),
                        'email' => $email,
                        'google_id' => $googleUser->getId(),
                        'password' => encrypt('123456dummy'),
                        'email_verified_at' => now()
                    ]);
                    DB::table('subscriptions')->insert([
                        'user_id' => $user->id,
                        'plan_id' => $subcription->id,
                        'board_id' => $subcription->board_id,
                        'level_id' => $subcription->level_id,
                        'subject_id' => $subcription->subject_id,
                        'plan_price' => $subcription->price,
                        'payment_method_id' => 1,
                        'payment_status' => 'pending',
                        'status' => 'unactive',
                        'created_at' => now(),
                        // 'start_date' => ,
                        // 'end_date' => 
                    ]);
                }
                Auth::login($user);
            }

            // Redirect to the intended page after login
            return redirect()->intended('student/payment-method');
        } catch (\Exception $e) {
            // Log the error for debugging
            // \Log::error('Facebook callback error: ' . $e->getMessage());
            return redirect()->route('login');
            // Redirect back with an error message
            // return redirect()->route('login')->withErrors([
            //     'facebook_error' => 'An error occurred while logging in with Facebook. Please try again.',
            // ]);
        }
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        return redirect()->intended(route('admin.dashboard', absolute: false));
    }
    public function emailSend(Request $request, $email, $token)
    {
        return view('auth.email-send', compact('email', 'token'));
    }
    public function resendVerificationEmail(Request $request)
    {
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json(['message' => 'User not found.'], 404);
        }

        // Generate a new verification token
        $token = Str::random(64);
        FacadesMail::send('emails.emailVerificationEmail', ['user' => $user, 'token' => $token], function ($message) use ($user) {
            $message->to($user->email);
            $message->subject('Email Verification Mail');
        });

        return response()->json(['message' => 'Verification email resent successfully!']);
    }

    public function emailVerify($id, $hash)
    {
       
        try {
            if ($id != null) {
                $updated = DB::table('users')
                    ->where('id', $id)
                    ->update([
                        'email_verified_at' => date('Y-m-d'),
                        'remember_token' => $hash
                    ]);
                if ($updated) {
                    // return redirect('/email/verified/',$id,$hash);
                    return redirect()->route('email.verified');
                    // return redirect('/login')->with('alertSuccess', 'Your email has been successfully verified. You can now log in.');
                } else {
                    // return redirect('/login')->with('alertError', 'User not found or already verified.');
                }
            }
        } catch (\Exception $e) {
            dd('error', $e);
            // return redirect('/login')->with('alertError', 'An error occurred: ' . $e->getMessage());
        }
    }

    public function emailVerified()
    {
        return view('auth.verified');
    }



    public function calculateSubscriptionEndDate($duration, $durationType)
    {
        // Ensure duration is numeric
        if (!is_numeric($duration)) {
            throw new InvalidArgumentException("Duration must be a numeric value. Given: $duration");
        }

        // Cast duration to an integer or float
        $duration = (int) $duration;

        // Start date
        $startDate = Carbon::now();

        // Calculate end date based on duration type
        switch (strtolower($durationType)) {
            case 'days':
                $endDate = $startDate->copy()->addDays($duration);
                break;
            case 'months':
                $endDate = $startDate->copy()->addMonths($duration);
                break;
            case 'years':
                $endDate = $startDate->copy()->addYears($duration);
                break;
            default:
                throw new InvalidArgumentException("Invalid duration type: $durationType. Allowed values are 'day', 'month', 'year'.");
        }

        return [
            'start_date' => $startDate->format('Y-m-d'),
            'end_date' => $endDate->format('Y-m-d'),
        ];
    }
}
