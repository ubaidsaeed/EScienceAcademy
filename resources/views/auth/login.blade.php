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
 @media screen and (max-width: 600px) {
    .login-container{
        width: max-content;
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
        
            <h1>Log in</h1>
            <p>Continue to eScience Academy</p>
        
        <form method="POST" action="{{ route('login') }}" id="login" class="card-body pt-3">
            @csrf

            <!-- Email Address -->
            {{-- <label for="email" class="form-label">{{ __('Email') }}</label> --}}
            <div class="input-group mb-4">
                <input id="email" type="email" name="email" value="{{ old('email') }}" class="login-option"
                    required autofocus autocomplete="username" placeholder="Ex@example.com">
                @error('email')
                    <div class="text-danger mt-2">{{ $message }}</div>
                @enderror
            </div>
            <!-- Password -->
                <div class="input-group mb-4">
                        <input id="password" type="password" name="password" class="login-option" required
                            autocomplete="current-password" placeholder="Password">
                        @error('password')
                            <div class="text-danger mt-2">{{ $message }}</div>
                        @enderror
                    </div>
                      
            <!-- Submit Button -->
            <div class="input-group mb-4">
            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{url('forgot-password') }}">
                    {{ __('Forgot your password?') }}
                </a>
                </div>
           <!-- <div class="submit">
                <button type="submit" class="btn btn-success login-button md-sm-2 mt-3">
                    {{ __('Sign in') }}
                </button>
            </div> -->
            <div class="submit text-center">
                <button type="submit" class="btn btn-success login-button md-sm-2 mt-3" id="loginBtn">
                    <span class="btn-text">{{ __('Sign in') }}</span>
                    <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                </button>
            </div>
              <p class="signup-text">
                      Don't have an account? <a href="{{ route('frontend.page','register') }}">Register</a>
                </p>
            <div class="social-login">
                @php $sugment = request()->segment(2) ?? ''; @endphp

                <!--<div class="row d-flex justify-content-center gap-2">-->
                <!--    <a href="{{ route('login.google', $sugment)}}" class="login-social">-->
                <!--        <i class="fab fa-google social-icon google"></i>-->
                <!--    </a>-->
                   
                <!--    <a href="{{ route('login.facebook',$sugment) }}" class="login-social">-->
                <!--        <i class="fab fa-facebook-f social-icon facebook" style="background: white !important"></i>-->
                <!--    </a>-->
    
                <!--</div>-->
            </div>
        </form>
    </div>
    <script>
        document.getElementById('login').addEventListener('submit', function() {
            let btn = document.getElementById('loginBtn');
            btn.disabled = true; // disable button
            btn.querySelector('.btn-text').textContent = "Please wait...";
            btn.querySelector('.spinner-border').classList.remove('d-none');
        });
    </script>
</x-guest-layout>