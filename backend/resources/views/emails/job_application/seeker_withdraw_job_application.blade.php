@include('emails.partials.header', [
    'icon'     => '✅',
    'title'    => 'Withdrawal Confirmed',
    'subtitle' => 'Your application has been successfully withdrawn',
])

<p style="margin:0 0 20px;font-size:15px;color:#374151;">Hi {{ $candidateName }},</p>
<p style="margin:0 0 24px;font-size:15px;color:#374151;line-height:1.6;">
    This is a confirmation that you have successfully withdrawn your application for:
</p>

<table width="100%" cellpadding="0" cellspacing="0" style="background-color:#f9fafb;border:1px solid #e5e7eb;border-radius:12px;margin-bottom:24px;">
    <tr>
        <td style="padding:16px 20px;">
            <p style="margin:0 0 12px;font-size:12px;font-weight:600;color:#6b7280;text-transform:uppercase;letter-spacing:0.06em;">Application Details</p>
            <table width="100%" cellpadding="0" cellspacing="0">
                <tr>
                    <td style="padding:5px 0;font-size:14px;color:#6b7280;width:130px;">Position</td>
                    <td style="padding:5px 0;font-size:14px;font-weight:600;color:#111827;">{{ $jobTitle }}</td>
                </tr>
                <tr>
                    <td style="padding:5px 0;font-size:14px;color:#6b7280;">Reference</td>
                    <td style="padding:5px 0;font-size:14px;color:#374151;">#{{ $applicationId }}</td>
                </tr>
            </table>
        </td>
    </tr>
</table>

<p style="margin:0;font-size:14px;color:#6b7280;line-height:1.6;">
    We have notified the recruiter. We're sorry this role wasn't the right fit — we wish you the best in your continued job search!
</p>

@include('emails.partials.footer')
