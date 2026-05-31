@include('emails.partials.header', [
    'icon'     => '💼',
    'title'    => 'Verify your account',
    'subtitle' => 'Use the OTP below to complete verification',
])

<p style="margin:0 0 20px;font-size:15px;color:#374151;">Hi,</p>
<p style="margin:0 0 24px;font-size:15px;color:#374151;line-height:1.6;">
    Please use the following One-Time Password to verify your account.
    This code is valid for <strong style="color:#111827;">10 minutes</strong>.
</p>

<div style="background-color:#eef2ff;border:1px solid #c7d2fe;border-radius:12px;padding:24px;text-align:center;margin-bottom:24px;">
    <p style="margin:0 0 6px;font-size:12px;font-weight:600;color:#6b7280;letter-spacing:0.08em;text-transform:uppercase;">One-Time Password</p>
    <span style="font-size:40px;font-weight:700;letter-spacing:12px;color:#4f46e5;">{{ $otp }}</span>
</div>

<p style="margin:0;font-size:14px;color:#6b7280;line-height:1.6;">
    If you didn't request this code, you can safely ignore this email.
</p>

@include('emails.partials.footer')
