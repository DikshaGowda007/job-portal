<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <title>Your OTP Code</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <style>
        .email-container {
            background-image: linear-gradient(135deg, #6953f7, #cd4ff7);
            padding: 20px;
        }

        .otp-container {
            background: #302d43;
            padding: 30px;
            border-radius: 40px;
            max-width: 400px;
            margin: 30px auto;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
            font-family: Arial, sans-serif;
            color: #eee;
            text-align: center;
        }

        .otp-code {
            font-weight: bold;
            color: #0d6efd;
            margin: 20px 0 30px;
            user-select: all;
        }

        p {
            font-size: 16px;
            line-height: 1.5;
            margin-bottom: 1.2rem;
        }

        a {
            color: #0d6efd;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body class="email-container">
    <div class="otp-container">
        <h2>Your OTP Code</h2>
        <p>Hi,</p>
        <p>Please use the following One-Time Password (OTP) to verify your phone number. This code is valid for 10
            minutes.</p>

        <div class="otp-code">{{ $otp }}</div>

        <p>If you didn't request this code, please ignore this email.</p>

        <p>Thanks,<br>The {{ config('app.name') }} Team</p>
    </div>
</body>

</html>