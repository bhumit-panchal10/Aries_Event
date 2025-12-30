<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Reset Password</title>
</head>

<body style="margin:0; padding:0; background-color:#f4f4f4; font-family:Arial, sans-serif;">
    <table width="100%" bgcolor="#f4f4f4" cellpadding="0" cellspacing="0">
        <tr>
            <td align="center" style="padding: 30px 15px;">
                <table width="600" cellpadding="0" cellspacing="0" bgcolor="#ffffff" style="border-radius: 10px; overflow: hidden; box-shadow: 0 0 5px rgba(0,0,0,0.1);">
                    <!-- Header -->
                    <tr>
                        <td bgcolor="#ff6f61" align="center" style="padding: 25px 15px;">
                            <h2 style="color:#fff; font-size: 24px; margin: 0;">Reset Your Password</h2>
                        </td>
                    </tr>

                    <!-- Body -->
                    <tr>
                        <td style="padding: 30px; color: #333;">
                            <p style="font-size: 16px; margin: 0 0 20px;">Hi {{ $user->customername ?? 'User' }},</p>

                            <p style="font-size: 16px; margin: 0 0 20px;">
                                You requested a password reset. Click the button below to set a new password. This link is valid for 5 minutes.
                            </p>

                            <p style="text-align: center; margin: 30px 0;">
                                <a href="{{ $resetLink }}"
                                   style="background-color: #ff6f61; color: #ffffff; padding: 12px 25px; text-decoration: none; font-size: 16px; border-radius: 5px; display: inline-block;">
                                    Reset Password
                                </a>
                            </p>

                            <p style="font-size: 14px; color: #666;">
                                If you didn’t request a password reset, please ignore this email or contact support.
                            </p>

                            <p style="font-size: 14px; margin-top: 30px;">
                                Thanks,<br>
                                <strong>{{ config('app.name') }}</strong>
                            </p>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td bgcolor="#f8f8f8" align="center" style="padding: 20px; font-size: 12px; color: #888;">
                            &copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>

</html>
