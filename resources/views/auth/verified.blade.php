<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Verified</title>
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
    <h2>Email Verified Successfully ✅</h2>
    <p>Thank you for verifying your email. Your account is now active.</p>
    <p>You can now log in and start using your account.</p>

    <div class="btn-container">
        <a href="{{ route('login') }}" class="btn">Login Now</a>
    </div>

    <div class="info-message">
        <p>If you need any assistance, feel free to contact our support team.</p>
    </div>
</div>
    <!-- Bootstrap JS and Popper.js -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
</body>
</html>