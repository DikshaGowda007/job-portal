@include('emails.partials.header', [
    'icon'     => '🔒',
    'title'    => 'Reset your password',
    'subtitle' => 'Use the OTP below to complete the reset',
])

<p style="margin:0 0 20px;font-size:15px;color:#374151;">Hello {{ $userName }},</p>
<p style="margin:0 0 24px;font-size:15px;color:#374151;line-height:1.6;">
    We received a request to reset your password. Use the OTP below to complete the process.
    This code is valid for <strong style="color:#111827;">15 minutes</strong>.
</p>

<div style="background-color:#eef2ff;border:1px solid #c7d2fe;border-radius:12px;padding:24px;text-align:center;margin-bottom:24px;">
    <p style="margin:0 0 6px;font-size:12px;font-weight:600;color:#6b7280;letter-spacing:0.08em;text-transform:uppercase;">One-Time Password</p>
    <span style="font-size:40px;font-weight:700;letter-spacing:12px;color:#4f46e5;">{{ $otp }}</span>
</div>

<p style="margin:0;font-size:14px;color:#6b7280;line-height:1.6;">
    If you didn't request a password reset, please ignore this email or contact support if you have concerns.
</p>

@include('emails.partials.footer')
