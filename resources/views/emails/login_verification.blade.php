<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>{{ $data['subject'] ?? 'Login Verification' }}</title>
    <style>
        body {
            background-color: #f4f4f4;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 20px;
        }

        .container {
            max-width: 600px;
            background-color: #ffffff;
            margin: auto;
            border: 3px solid #2a7d3e;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 0 12px rgba(42, 125, 62, 0.2);
        }

        /* Header same as checkout order */
        .header {
            text-align: center;
            color: #000;
            padding: 30px;
            background-color: #fff;
        }

        .header img {
            width: 180px;
            margin-bottom: 10px;
        }

        .header h2 {
            margin: 0;
            font-size: 24px;
            color: #2a7d3e;
        }

        .header p {
            margin: 5px 0 0;
            font-size: 14px;
            color: #666;
        }

        .divider {
            border: 1px solid #d4af37;
            margin: 0;
        }

        .content {
            padding: 30px;
            color: #444;
        }

        .content p {
            font-size: 15px;
            line-height: 1.7;
            margin-bottom: 20px;
        }

        .otp-box {
            display: inline-block;
            padding: 12px 20px;
            background: linear-gradient(135deg, #2a7d3e, #8bc34a);
            color: white;
            border-radius: 6px;
            font-size: 20px;
            letter-spacing: 2px;
            font-weight: 600;
            margin: 15px 0;
        }

        .button {
            display: inline-block;
            background: linear-gradient(135deg, #2a7d3e, #8bc34a);
            color: #fff !important;
            text-decoration: none;
            padding: 12px 25px;
            border-radius: 5px;
            margin-top: 10px;
            font-weight: 500;
            transition: 0.3s;
        }

        .button:hover {
            background-color: #256b35;
        }

        .footer {
            background-color: #f6f6f6;
            padding: 20px;
            text-align: center;
            font-size: 13px;
            color: #555;
        }

        .footer .strong {
            color: #2a7d3e;
        }
    </style>
</head>

<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <img src="https://www.getdemo.in/oro_veda/assets/images/logo.png" alt="OroVeda Logo">
            <h2> Login Verification </h2>
            <p>{{ config('app.name') ?? 'ORO Veda' }}</p>
        </div>
        <hr class="divider">

        <!-- Content -->
        <div class="content">
            <p>Hi {{ $data['name'] ?? 'User' }},</p>

            <p>We received a request to log in to your account using this email address.</p>

            @if (isset($data['otp']))
                <p>Please use the OTP below to complete your login:</p>
                <div class="otp-box">{{ $data['otp'] }}</div>
            @else
                <p>{{ $data['message'] ?? 'Thank you for using our service!' }}</p>
            @endif

            <p>If you did not initiate this request, please ignore this email.</p>
        </div>

        <!-- Footer -->
        <div class="footer">
            Regards,<br>
            <span class="strong">Team OroVeda</span><br>
            <small>Need help? Call us at <strong>+91 81560 88203</strong></small>
        </div>
    </div>
</body>

</html>
