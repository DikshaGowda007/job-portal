@include('emails.partials.header', [
    'icon'     => '📤',
    'title'    => 'Candidate Withdrawal',
    'subtitle' => 'A candidate has withdrawn their application',
])

<p style="margin:0 0 20px;font-size:15px;color:#374151;">Hi {{ $recruiterName }},</p>
<p style="margin:0 0 24px;font-size:15px;color:#374151;line-height:1.6;">
    A candidate has withdrawn their application for the following position.
</p>

<table width="100%" cellpadding="0" cellspacing="0" style="background-color:#f9fafb;border:1px solid #e5e7eb;border-radius:12px;margin-bottom:24px;">
    <tr>
        <td style="padding:16px 20px;">
            <p style="margin:0 0 12px;font-size:12px;font-weight:600;color:#6b7280;text-transform:uppercase;letter-spacing:0.06em;">Application Details</p>
            <table width="100%" cellpadding="0" cellspacing="0">
                <tr>
                    <td style="padding:5px 0;font-size:14px;color:#6b7280;width:130px;">Job Title</td>
                    <td style="padding:5px 0;font-size:14px;font-weight:600;color:#111827;">{{ $jobTitle }}</td>
                </tr>
                <tr>
                    <td style="padding:5px 0;font-size:14px;color:#6b7280;">Candidate</td>
                    <td style="padding:5px 0;font-size:14px;color:#374151;">{{ $candidateName }}</td>
                </tr>
                <tr>
                    <td style="padding:5px 0;font-size:14px;color:#6b7280;">Application ID</td>
                    <td style="padding:5px 0;font-size:14px;color:#374151;">#{{ $applicationId }}</td>
                </tr>
            </table>
        </td>
    </tr>
</table>

<p style="margin:0;font-size:14px;color:#6b7280;line-height:1.6;">
    This application has been marked as "Withdrawn" in your dashboard. No further action is required.
</p>

@include('emails.partials.footer')
