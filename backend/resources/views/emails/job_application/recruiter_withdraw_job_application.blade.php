<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Application Withdrawn - Recruiter Notice</title>
    <style>
        .email-container { background-image: linear-gradient(135deg, #6953f7, #cd4ff7); padding: 20px; }
        .content-container { background: #302d43; padding: 30px; border-radius: 40px; max-width: 500px; margin: 30px auto; font-family: Arial, sans-serif; color: #eee; }
        .info-box { background: #3d3a52; padding: 15px; border-radius: 10px; margin: 15px 0; }
        p { font-size: 16px; line-height: 1.5; margin-bottom: 1.2rem; }
        .highlight { color: #ff6b6b; font-weight: bold; }
    </style>
</head>
<body class="email-container">
    <div class="content-container">
        <h2>Candidate Withdrawal</h2>
        <p>Hi {{ $recruiterName }},</p>
        <p>A candidate has withdrawn their application for the following position:</p>

        <div class="info-box">
            <p><strong>Job Title:</strong> <span class="highlight">{{ $jobTitle }}</span></p>
            <p><strong>Candidate:</strong> {{ $candidateName }}</p>
            <p><strong>Application ID:</strong> #{{ $applicationId }}</p>
        </div>

        <p>This application has been marked as "Withdrawn" in your dashboard. No further action is required.</p>
        <p>Thanks,<br>The Team</p>
    </div>
</body>
</html>