<!--<h2>Dear {{$user->name}},</h2>-->
<!--<p>We confirm that your subscription to [{{$plan->name}}] has been successfully cancelled as of [{{ now()->format('Y-m-d H:i:s') }}].</p>-->
<!--<p>You will retain access until the end of your billing period on [{{$subscription->end_date}}]. After that, your account will be downgraded to the free plan (if applicable).</p>-->

<!--<p>We’re sorry to see you go! If there’s anything we could have done better, we’d love your feedback: <a href="{{route('contact-us')}}">Contact Us</a>-->
<!--</p>-->

<!--<p>ou’re welcome back anytime.-->
<!--</p>-->

<!--<p>Warm regards,</p>-->
<!--<p>eScience Academy </p>-->
<!--<p>Support: <a href="{{route('contact-us')}}">Contact Us</a></p>-->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            display:flex;
            justify-content:center;
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: aliceblue;
        }
        
        .email-container {
            display: inline-block;
            margin-left: 300px;
            margin-top: 100px;
            margin-bottom:100px;
            background-color: #ffffff;
            padding: 30px;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
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
            color: white;
            text-decoration: none;
            border-radius: 4px;
            font-weight: bold;
            text-align: center;
        }
        
        .expiry-note {
            margin: 15px 0;
        }
        
        .no-action {
            margin-bottom: 25px;
        }
        
        .regards {
            margin: 25px 0;
        }
        
        .footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #eeeeee;
            font-size: 14px;
            color: #777777;
        }
        
        .url {
            word-break: break-all;
            font-size: 14px;
            color: #666666;
            margin: 15px 0;
            line-height: 1.5;
        }
        
        .copyright {
            font-size: 12px;
            color: #999999;
            margin-top: 20px;
            text-align: center;
        }
        .container{
            display:flex;
            justify-content: center;
            background-color:aliceblue;
          
        }
        .a{
            color:white !important;
        }
    </style>
</head>
<body>
    <div class="container">
    <div class="email-container">
            <div class="greeting">Dear {{$user->name}}!</div>
        
        <div class="content">
          We confirm that your subscription to [{{$plan->name}}] has been successfully cancelled as of [{{ now()->format('Y-m-d H:i:s') }}].
        </div>
        
        <div class="expiry-note">
           You will retain access until the end of your billing period on [{{$subscription->end_date}}]. After that, your account will be downgraded to the free plan (if applicable).
        </div>
        
        <div class="no-action">
            We’re sorry to see you go! If there’s anything we could have done better, we’d love your feedback: <a href="{{route('contact-us')}}">Contact Us</a>
        </div>
        <div class="no-action">
           <a href="{{route('contact-us')}}">Feedback</a>
        </div>
        
        <div class="no-action">
          You’re welcome back anytime.
        </div>
        <div class="regards">
            Warm regards,<br>
            eScience-Academy
        </div>
        
        <div class="footer">
           
        </div>
        
        <div class="copyright">
            {{ now()->year }}eScience-Academy. All rights reserved
        </div>
    </div>
    </div>
</body>
</html>