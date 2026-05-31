@include('emails.partials.header', [
    'icon'     => '🔔',
    'title'    => 'Application Status Updated',
    'subtitle' => 'Your application status has changed',
])

<p style="margin:0 0 20px;font-size:15px;color:#374151;">Hi {{ $candidateName }},</p>
<p style="margin:0 0 24px;font-size:15px;color:#374151;line-height:1.6;">
    Your application for <strong style="color:#111827;">{{ $jobTitle }}</strong> at
    <strong style="color:#111827;">{{ $companyName }}</strong> has been updated.
</p>

<div style="background-color:#eef2ff;border:1px solid #c7d2fe;border-radius:12px;padding:20px;text-align:center;margin-bottom:24px;">
    <p style="margin:0 0 8px;font-size:12px;font-weight:600;color:#6b7280;letter-spacing:0.08em;text-transform:uppercase;">Status Change</p>
    <p style="margin:0;font-size:16px;font-weight:700;color:#4f46e5;">
        {{ strtoupper(str_replace('_', ' ', $oldStatus)) }} &rarr; {{ strtoupper(str_replace('_', ' ', $newStatus)) }}
    </p>
</div>

@if($recruiterNotes)
<table width="100%" cellpadding="0" cellspacing="0" style="background-color:#f9fafb;border:1px solid #e5e7eb;border-radius:12px;margin-bottom:24px;">
    <tr>
        <td style="padding:16px 20px;">
            <p style="margin:0 0 8px;font-size:12px;font-weight:600;color:#6b7280;text-transform:uppercase;letter-spacing:0.06em;">Recruiter Notes</p>
            <p style="margin:0;font-size:14px;color:#374151;line-height:1.6;">{{ $recruiterNotes }}</p>
        </td>
    </tr>
</table>
@endif

<p style="margin:0;font-size:14px;color:#6b7280;line-height:1.6;">
    Thank you for your continued interest. We wish you the best!
</p>

@include('emails.partials.footer')
