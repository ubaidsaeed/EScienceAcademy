<!--<h2>Account Created Successfully!</h2>-->

<!--<p>Your account has been created successfully. Please verify your email using the link below:</p>-->

<!--<p><strong>Verification Link:</strong></p>-->

<!--<a href="{{ route('email.verify', ['id' => $user->id, 'hash' => sha1($user->email)]) }}" -->
<!--   style="display: inline-block; padding: 10px 20px; color: white; background-color: green; text-decoration: none; border-radius: 5px;">-->
<!--    Click Here to Verify Your Email-->
<!--</a>-->
<!--<p>If you did not sign up for an account, please ignore this email.</p>-->

<!--<p>Thank you for registering with {{'eScience Academy'}}!</p>-->

<!--<h2>Account Created Successfully!</h2>-->

<!--<p>Your account has been created successfully. Please verify your email using the link below:</p>-->

<!--<p><strong>Verification Link:</strong></p>-->
<!--{{$user->email}}-->
<!--<a href="{{ route('email.verify', ['id' => $user->id, 'hash' => sha1($user->email)]) }}" -->
<!--   style="display: inline-block; padding: 10px 20px; color: white; background-color: green; text-decoration: none; border-radius: 5px;">-->
<!--    Click Here to Verify Your Email-->
<!--</a>-->
<!--<p>If you did not sign up for an account, please ignore this email.</p>-->

<!--<p>Thank you for registering with {{'eScience Academy'}}!</p>-->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

     <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: aliceblue;
            margin: 0;
            padding: 0;
        }

        .container {
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 40px 20px;
        }

        .email-container {
            background-color: #ffffff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            max-width: 600px;
            width: 100%;
            box-sizing: border-box;
        }

        .greeting {
            font-size: 18px;
            margin-bottom: 20px;
        }

        .content {
            margin-bottom: 25px;
        }

        .reset-button {
            display: inline-block;
            margin: 20px 0;
            padding: 12px 24px;
            background-color: black;
            color: white !important;
            text-decoration: none;
            border-radius: 4px;
            font-weight: bold;
            text-align: center;
        }

        .expiry-note,
        .no-action,
        .regards {
            margin: 15px 0;
        }

        .footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #eeeeee;
            font-size: 14px;
            color: #777;
        }

        .copyright {
            font-size: 12px;
            color: #999;
            margin-top: 20px;
            text-align: center;
        }

        @media (max-width: 600px) {
            .email-container {
                padding: 20px;
            }

            .reset-button {
                width: 100%;
                text-align: center;
            }
        }
    </style>
</head>
<body>
    <div class="container">
    <div class="email-container">
            <div class="greeting">Hi {{$user->name}}!</div>
        
        <div class="content">
          Your account has been created successfully. Please verify your email using the link below:
        </div>
        
        <div class="class" style="text-align: center;">
            <a href="{{ route('email.verify', ['id' => $user->id, 'hash' => sha1($user->email)]) }}1" class="reset-button" style="color:white !important">Click Here to Verify Your Email</a>
        </div>
        
        <div class="expiry-note">
           If you did not sign up for an account, please ignore this email.
        </div>
        
        
        <div class="regards">
            Regards,<br>
            eScience-Academy
        </div>
        
        <div class="footer">
           
        </div>
        
        <div class="copyright">
            {{ now()->year }} eScience-Academy. All rights reserved
        </div>
    </div>
    </div>
</body>
</html>