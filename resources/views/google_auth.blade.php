<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Email Verification - Community App</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f4f4f4;
            padding: 20px;
            margin: 0;
        }
        .container {
            max-width: 600px;
            background: #ffffff;
            border-radius: 8px;
            padding: 30px;
            margin: 40px auto;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        .header {
            text-align: center;
            color: #333333;
            font-size: 24px;
            font-weight: bold;
        }
        .content {
            margin-top: 20px;
            font-size: 16px;
            color: #555555;
            line-height: 1.6;
        }
        .code-box {
            background-color: #f0f0f0;
            padding: 15px;
            border-radius: 6px;
            text-align: center;
            font-size: 20px;
            font-weight: bold;
            color: #007BFF;
            margin: 20px 0;
            letter-spacing: 2px;
        }
        .footer {
            margin-top: 30px;
            font-size: 14px;
            color: #888888;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">Welcome to the Community Application</div>
        <div class="content">
            <p>Thank you for signing up!</p>
            <p>Your verification code is:</p>
            <div class="code-box">{{ $verificationCode }}</div>
            <p>Please enter this code on the "Verify Email" screen in the app to complete your registration.</p>
            <p>If you did not initiate this request, please ignore this email.</p>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} Community App. All rights reserved.
        </div>
    </div>
</body>
</html>
