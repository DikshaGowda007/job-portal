<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Withdrawal Confirmation</title>
    <style>
        /* Keeping consistency with your brand colors */
        .email-container { background-image: linear-gradient(135deg, #6953f7, #cd4ff7); padding: 20px; }
        .content-container { background: #302d43; padding: 30px; border-radius: 40px; max-width: 500px; margin: 30px auto; font-family: Arial, sans-serif; color: #eee; }
        .info-box { background: #3d3a52; padding: 15px; border-radius: 10px; margin: 15px 0; }
        p { font-size: 16px; line-height: 1.5; margin-bottom: 1.2rem; }
        .highlight { color: #50fa7b; font-weight: bold; } /* Green for success/confirmation */
    </style>
</head>
<body class="email-container">
    <div class="content-container">
        <h2>Application Withdrawn</h2>
        <p>Hi {{ $candidateName }},</p>
        <p>This is a confirmation that you have successfully withdrawn your application for:</p>

        <div class="info-box">
            <p><strong>Position:</strong> <span class="highlight">{{ $jobTitle }}</span></p>
            <p><strong>Company:</strong> {{ $applicationId }}</p>
            <p><strong>Reference:</strong> #{{ $applicationId }}</p>
        </div>

        <p>We have notified the recruiter. We're sorry this role wasn't the right fit, and we wish you the best in your continued job search!</p>
        <p>Best regards,<br>The  Team</p>
    </div>
</body>
</html>