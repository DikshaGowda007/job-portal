<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Your OTP Code</title>
</head>
<body style="margin:0;padding:0;background-color:#f3f4f6;font-family:'Segoe UI',Arial,sans-serif;">

    <table width="100%" cellpadding="0" cellspacing="0" style="background-color:#f3f4f6;padding:40px 16px;">
        <tr>
            <td align="center">
                <table width="100%" cellpadding="0" cellspacing="0" style="max-width:480px;background:#ffffff;border:1px solid #e5e7eb;border-radius:16px;box-shadow:0 1px 4px rgba(0,0,0,0.06);overflow:hidden;">

                    {{-- Header --}}
                    <tr>
                        <td align="center" style="padding:32px 32px 24px;">
                            <div style="display:inline-flex;align-items:center;justify-content:center;width:48px;height:48px;background-color:#4f46e5;border-radius:12px;margin-bottom:16px;">
                                <span style="font-size:22px;line-height:1;">💼</span>
                            </div>
                            <h1 style="margin:0 0 4px;font-size:22px;font-weight:700;color:#111827;">Verify your account</h1>
                            <p style="margin:0;font-size:14px;color:#6b7280;">Use the OTP below to complete verification</p>
                        </td>
                    </tr>

                    {{-- Body --}}
                    <tr>
                        <td style="padding:0 32px 32px;">
                            <p style="margin:0 0 20px;font-size:15px;color:#374151;">Hi,</p>
                            <p style="margin:0 0 24px;font-size:15px;color:#374151;line-height:1.6;">
                                Please use the following One-Time Password to verify your account.
                                This code is valid for <strong style="color:#111827;">10 minutes</strong>.
                            </p>

                            {{-- OTP Box --}}
                            <div style="background-color:#eef2ff;border:1px solid #c7d2fe;border-radius:12px;padding:24px;text-align:center;margin-bottom:24px;">
                                <p style="margin:0 0 6px;font-size:12px;font-weight:600;color:#6b7280;letter-spacing:0.08em;text-transform:uppercase;">One-Time Password</p>
                                <span style="font-size:40px;font-weight:700;letter-spacing:12px;color:#4f46e5;">{{ $otp }}</span>
                            </div>

                            <p style="margin:0 0 24px;font-size:14px;color:#6b7280;line-height:1.6;">
                                If you didn't request this code, you can safely ignore this email.
                            </p>

                            <p style="margin:0;font-size:15px;color:#374151;">
                                Thanks,<br>
                                <strong style="color:#111827;">The {{ config('app.name') }} Team</strong>
                            </p>
                        </td>
                    </tr>

                    {{-- Footer --}}
                    <tr>
                        <td style="padding:16px 32px;border-top:1px solid #e5e7eb;text-align:center;">
                            <p style="margin:0;font-size:12px;color:#9ca3af;">This is an automated message. Please do not reply.</p>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>

</body>
</html>
