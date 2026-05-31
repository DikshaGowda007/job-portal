@include('emails.partials.header', [
    'icon'     => '📋',
    'title'    => 'New Application Received',
    'subtitle' => 'A candidate has applied for one of your positions',
])

<p style="margin:0 0 20px;font-size:15px;color:#374151;">Hi {{ $recruiterName }},</p>
<p style="margin:0 0 24px;font-size:15px;color:#374151;line-height:1.6;">
    You have received a new application for the position listed below. Please review it at your earliest convenience.
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
                    <td style="padding:5px 0;font-size:14px;color:#6b7280;">Email</td>
                    <td style="padding:5px 0;font-size:14px;color:#374151;">{{ $candidateEmail }}</td>
                </tr>
                <tr>
                    <td style="padding:5px 0;font-size:14px;color:#6b7280;">Application ID</td>
                    <td style="padding:5px 0;font-size:14px;color:#374151;">#{{ $applicationId }}</td>
                </tr>
            </table>
        </td>
    </tr>
</table>

@include('emails.partials.footer')
