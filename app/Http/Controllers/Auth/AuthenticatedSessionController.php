<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }
    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();
        $request->session()->regenerate();
       $role = DB::table('roles')->where('name','student')->first();
        // $user = DB::table('users')->where('id',Auth::user()->id)->first();
        $user = Auth::user();
        if($user->role_id == $role->id)
        {
            return redirect()->intended(route('student.dashboard', false)); 
        }elseif($user->role_id == null){
            return redirect()->intended(route('payment.method', false));
        }
        else{
            return redirect()->intended(route('admin.dashboard',  false));
        }
    }
    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}
