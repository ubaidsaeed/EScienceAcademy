<x-guest-layout>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        }

        body {
            display: flex;
            flex-direction: column;
            align-items: center;
            min-height: 100vh;
            background-color: #f3f4f6;
            padding: 40px 20px;
        }

        .logo {
            width: 200px;
            font-size: 2.5rem;
            font-weight: bold;
            color: #008060;
            margin-bottom: 0px;
            text-transform: lowercase;
        }

        .login-container {
            background: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            width: 500px;
        }

        h1 {
            font-size: 24px;
            margin-bottom: 0px;
            color: #212b36;
            text-align: left;
        }

        .btn-primary {
            background: #008060;
            color: white;
            width: 100%;
            padding: 14px;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            font-weight: 500;
            cursor: pointer;
            margin-bottom: 20px;
            transition: background 0.3s ease;
        }

        .btn-primary:hover {
            background: #006e52;
        }

        .divider {
            text-align: center;
            margin: 20px 0;
            color: #637381;
            position: relative;
        }

        .divider::before,
        .divider::after {
            content: "";
            display: inline-block;
            width: 20%;
            height: 1px;
            background: #e0e0e0;
            vertical-align: middle;
            margin: 0 10px;
        }

        .newdiv::before,
        .newdiv::after {
            content: "";
            display: inline-block;
            /* width: 20%; */
            height: 1px;
            background: #e0e0e0;
            vertical-align: middle;
            margin: 0 10px;
        }

        .login-option {
            display: flex;
            align-items: center;
            gap: 10px;
            width: 100%;
            padding: 12px;
            border: 1px solid #e0e0e0;
            border-radius: 4px;
            background: white;
            color: #212b36;
            font-size: 16px;
            font-weight: 500;
            text-decoration: none;
            text-align: left;
            margin-bottom: 15px;
            cursor: pointer;
            transition: border-color 0.3s ease;
        }

        .login-social {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 10px;
            width: 20%;
            padding: 12px;
            border: 1px solid #e0e0e0;
            border-radius: 4px;
            background: white;
            color: #212b36;
            font-size: 16px;
            font-weight: 500;
            text-decoration: none;
            text-align: left;
            margin-bottom: 15px;
            cursor: pointer;
            transition: border-color 0.3s ease;
        }

        .login-button {
            display: flex;

            align-items: center;
            gap: 10px;
            width: 100%;
            padding: 12px;
            border: 1px solid #e0e0e0;
            border-radius: 4px;
            /* background: white; */
            color: white;
            justify-content: center;
            font-size: 16px;
            font-weight: 500;
            text-decoration: none;
            text-align: center;
            margin-bottom: 15px;
            cursor: pointer;
            transition: border-color 0.3s ease;
        }

        .login-option:hover {
            border-color: #008060;
        }

        .social-login {
            display: flex;
            flex-direction: column;
            gap: 12px;
            margin-top: 20px;
        }

        .social-login .login-option {
            margin-bottom: 0;
        }

        .social-icon {
            font-size: 18px;
            width: 20px;
        }

        .google {
            color: #DB4437;
        }

        .facebook {
            color: #1877F2;
        }

        .apple {
            color: #000000;
        }

        .signup-text {
            text-align: center;
            margin-top: 30px;
            color: #637381;
        }

        .signup-text a {
            color: #008060;
            text-decoration: none;
            font-weight: 500;
        }

        .footer {
            margin-top: auto;
            padding: 20px;
            text-align: center;
            color: #637381;
            font-size: 14px;
        }

        .footer a {
            color: #637381;
            text-decoration: none;
            margin: 0 10px;
        }
        .login-img{
    background-color:#c2aafa;
}
    </style>
     <!-- Session Status -->
    @if (session('status'))
        <div class="alert alert-success mb-4">
            {{ session('status') }}
        </div>
    @endif
    <div class="logo">
        <a href="{{ route('home') }}"><img
                src="{{asset('build/assets/frontend/images/escience-logo.svg')}}"></a>
    </div>
    <div class="login-container">
        
            <h1>Reset Password</h1>
    <form method="POST" action="{{ route('password.store') }}"  class="card-body pt-3">
        @csrf

        <!-- Password Reset Token -->
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email"  class="login-option" type="email" name="email" :value="old('email', $request->email)" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input id="password"  class="login-option" type="password" name="password" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />

            <x-text-input id="password_confirmation"  class="login-option"
                                type="password"
                                name="password_confirmation" required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <button type="submit" class="btn btn-success login-button md-sm-2">
                {{ __('Reset Password') }}
            </button>
        </div>
    </form>
    </div>
</x-guest-layout>
