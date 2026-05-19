

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
        Thank you for applying. We have reviewed your application and would like to inform you that:
        </div>
        
      
        
        <div class="expiry-note">
         Status : Under Review
        </div>
        
        <div class="no-action">
           If approved, further instructions and documentation will be sent shortly.
        </div>
        
        <div class="regards">
            Regards,<br>
            eScience-Academy
        </div>
          <div class="no-action">
            connect@escienceacademy.com
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