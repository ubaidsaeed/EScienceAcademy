<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Verification Sent</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
         body {
            background: url('https://c4.wallpaperflare.com/wallpaper/992/950/442/white-gray-gradation-blur-wallpaper-thumb.jpg') no-repeat center center/cover;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            position: relative;
        }
        .verification-card {
            max-width: 500px;
            padding: 30px;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        .verification-card h2 {
            margin-bottom: 20px;
            font-weight: 600;
            color: #333;
        }
        .verification-card p {
            font-size: 16px;
            color: #555;
            margin-bottom: 20px;
        }
        .verification-card .btn {
            background-color: #007bff;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            font-weight: 500;
            transition: background-color 0.3s ease;
        }
        .verification-card .btn:hover {
            background-color: #0056b3;
        }
        .verification-card .resend-link {
            margin-top: 20px;
            font-size: 14px;
            color: #007bff;
            cursor: pointer;
        }
        .verification-card .resend-link:hover {
            text-decoration: underline;
        }
        .button-group {
    display: flex;
    justify-content: space-between;
    margin-top: 20px;
        }
        .btn-logout {
    background: none;
    border: none;
    color: #6c757d;
    font-size: 14px;
    cursor: pointer;
}
    </style>
</head>
<body>
    <div class="verification-card">
        <h2>Email Verification Sent</h2>
        <p>We've sent a verification email to <strong>{{ $email }}</strong>. Please check your inbox and click the link to verify your email address.</p>
        <p>If you didn't receive the email, click the button below to resend it.</p>
        <div class="">
        <button class="btn" onclick="resendVerificationEmail('{{$email}}')">Resend Verification Email</button>
         <!--<form method="POST" action="{{ route('logout') }}">-->
         <!--           @csrf-->
         <!--           <button type="submit" class="btn-logout">-->
         <!--               {{ __('Log Out') }}-->
         <!--           </button>-->
         <!--       </form>-->
        </div>
        <!--<div class="resend-link" onclick="resendVerificationEmail({{$email}}')">Didn't receive the email? Click here to resend.</div>-->
    </div>

    <!-- Bootstrap JS and Popper.js -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
         function resendVerificationEmail(email) {
        $.ajax({
            url: "{{ route('verification.resend') }}",
            type: "POST",
            data: {
                email: email,
                _token: "{{ csrf_token() }}"
            },
            success: function(response) {
                alert(response.message);
            },
            error: function(xhr) {
                alert(xhr.responseJSON.message);
            }
        });
    }
    </script>
</body>
</html>