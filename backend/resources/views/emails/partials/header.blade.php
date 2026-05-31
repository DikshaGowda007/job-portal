<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>{{ $title ?? config('app.name') }}</title>
</head>
<body style="margin:0;padding:0;background-color:#f3f4f6;font-family:'Segoe UI',Arial,sans-serif;">

    <table width="100%" cellpadding="0" cellspacing="0" style="background-color:#f3f4f6;padding:40px 16px;">
        <tr>
            <td align="center">
                <table width="100%" cellpadding="0" cellspacing="0" style="max-width:520px;background:#ffffff;border:1px solid #e5e7eb;border-radius:16px;box-shadow:0 1px 4px rgba(0,0,0,0.06);overflow:hidden;">

                    <tr>
                        <td align="center" style="padding:32px 32px 24px;border-bottom:1px solid #f3f4f6;">
                            <div style="display:inline-flex;align-items:center;justify-content:center;width:48px;height:48px;background-color:#4f46e5;border-radius:12px;margin-bottom:16px;">
                                <span style="font-size:22px;line-height:1;">{{ $icon ?? '💼' }}</span>
                            </div>
                            <h1 style="margin:0 0 4px;font-size:22px;font-weight:700;color:#111827;">{{ $title }}</h1>
                            <p style="margin:0;font-size:14px;color:#6b7280;">{{ $subtitle }}</p>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding:28px 32px 32px;">
