<x-guest-layout>
    <div class="verification-container">
        <div class="verification-card">
            <!--<p class="text-muted">-->
            <!--    {{ __('Thanks for signing up! Before getting started, please verify your email address by clicking on the link we just sent to your inbox. If you didn\'t receive the email, we will gladly send you another.') }}-->
            <!--</p>-->
 <h2>Email Verification Sent</h2>
        <p>We've sent a verification email. Please check your inbox and click the link to verify your email address.</p>
        <p>If you didn't receive the email, click the button below to resend it.</p>
            @if (session('status') == 'verification-link-sent')
                <div class="alert-success">
                    {{ __('A new verification link has been sent to the email address you provided during registration.') }}
                </div>
            @endif

            <div class="">
                <form method="POST" action="{{ route('verification.send') }}">
                    @csrf
                    <button type="submit" class="btn-primary">
                        {{ __('Resend Verification Email') }}
                    </button>
                </form>

                <!--<form method="POST" action="{{ route('logout') }}">-->
                <!--    @csrf-->
                <!--    <button type="submit" class="btn-logout">-->
                <!--        {{ __('Log Out') }}-->
                <!--    </button>-->
                <!--</form>-->
            </div>
        </div>
    </div>

    <style>
        body {
            background: url('https://c4.wallpaperflare.com/wallpaper/992/950/442/white-gray-gradation-blur-wallpaper-thumb.jpg') no-repeat center center/cover;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .verification-container {
            display: flex;
            justify-content: center;
            align-items: center;
            width: 100%;
        }

        .verification-card {
            max-width: 500px;
            padding: 30px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .text-muted {
            font-size: 16px;
            color: #555;
        }

        .alert-success {
            background-color: #d4edda;
            color: #155724;
            padding: 12px;
            border-radius: 5px;
            margin: 15px 0;
        }

        .button-group {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
        }

        .btn-primary {
            background-color: #4a90e2;
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 6px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .btn-primary:hover {
            background-color: #357abd;
        }

        .btn-logout {
            background: none;
            border: none;
            color: #6c757d;
            font-size: 14px;
            cursor: pointer;
        }

        .btn-logout:hover {
            color: #333;
        }
    </style>
</x-guest-layout>
