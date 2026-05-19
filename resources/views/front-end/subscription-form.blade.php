<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', $lang ?? app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> eScience Academy </title>
        <link rel="shortcut icon" href="{{asset('build/assets/frontend/images/escience-logo.svg')}}" type="image/x-icon">

    {{-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"> --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <title> eScience Academy </title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        }

        body {
            display: grid;
            flex-direction: column;
            justify-content: center;
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
         @media screen and (max-width: 600px) {
    .login-container{
        width: max-content;
    }
    </style>
</head>

<body>
     @php $sugment =request()->segment(3); @endphp
            {{-- <div class="logo">Escienceacademy</div> --}}
            <div class="logo">
                <a href="{{route('home')}}"><img src="{{asset('build/assets/frontend/images/escience-logo.svg')}}"></a>
            </div>
            <div class="login-container">
                <h1>Sign up</h1>
                <p>Continue to eScience Academy</p>
                <form method="POST" action="{{ route('register.store') }}">
                @csrf
                <input type="text" placeholder="Name" value="{{old('name')}}" class=" login-option" name="name">
                @error('name')
                <div class="text-danger mt-2">{{ $message }}</div>
                @enderror
                <input type="email" placeholder="Ex@example.com" value="{{old('email')}}" class=" login-option" name="email">
                @error('email')
                <div class="text-danger mt-2">{{ $message }}</div>
                @enderror
                <input  placeholder="Password" value="{{old('password')}}" class="login-option"
                type="password"
                            name="password"
                            required autocomplete="new-password"
                >
                @error('password')
                <div class="text-danger mt-2">{{ $message }}</div>
                @enderror
                <input  placeholder="Password Confirmation" value="{{old('password_confirmation')}}" class="login-option"
                type="password"
                            name="password_confirmation" required autocomplete="new-password"
                >
                @error('password_confirmation')
                <div class="text-danger mt-2">{{ $message }}</div>
                @enderror
                <input type="hidden" name="pack" value="{{request()->segment(3)}}">
                <input type="hidden" name="level" value="{{request()->segment(4)}}">
                <button class="btn btn-success login-button md-sm-2" type="submit">
                    Sign up
                </button>
            </form>
                <p class="signup-text">
                    Already registered? <a href="{{route('login')}}">Log in</a>
                </p>
                <!--<div class="divider">or</div>-->
        
                <!--<div class="social-login">-->
                <!--    <div class="row d-flex justify-content-center gap-2">-->
                <!--        <a href="{{ route('login.google', $sugment)}}" class="login-social">-->
                <!--            <i class="fab fa-google social-icon google"></i>-->
                <!--        </a>-->
                       
                <!--        <a href="{{ route('login.facebook',$sugment) }}" class="login-social">-->
                <!--            <i class="fab fa-facebook-f social-icon facebook" style="background: white !important"></i>-->
                <!--        </a>-->
        
                <!--    </div>-->
                <!--</div>-->
        
        
            </div>

    <script>
        // Add click handlers for social buttons
        document.querySelectorAll('.login-option').forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const service = this.textContent.trim().toLowerCase();
                console.log(`Login with ${service} clicked`);

                // Add specific login logic for each service
                if (service.includes('google')) {
                    // Implement Google login
                } else if (service.includes('facebook')) {
                    // Implement Facebook login
                } else if (service.includes('apple')) {
                    // Implement Apple login
                }
            });
        });
    </script>
</body>
</html>
