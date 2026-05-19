<?php

namespace App\Http\Controllers\Auth;


use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail as FacadesMail;
use Illuminate\Support\Str;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);
        $level = DB::table('levels')->where('slug',$request->level)->first();
        $subcription = DB::table('subscription_plans')
        ->where(function($query) use ($request, $level) {
            $query->where('slug', $request->pack)
                  ->where('level_id', $level->id);
        })
        ->first();
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // event(new Registered($user));

        // Auth::login($user);

        // return redirect(route('admin.dashboard', false));
         session()->put('pack_slug', $request->pack);
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
            // 'start_date' => ,
            // 'end_date' => 
        ]);
        // event(new Registered($user));
         $token = Str::random(64);
        FacadesMail::send('emails.emailVerificationEmail', ['user' => $user,'token'=>$token], function($message) use($user){

        // Mail::send('email.emailVerificationEmail', ['token' => $token], function($message) use($request){

            $message->to($user->email);

            $message->subject('Email Verification Mail');

        });
        // return redirect('/email/send');
             return redirect()->route('email.send', [
           'email' => $user->email,
           'token' => $token
            ]);
    }
}
