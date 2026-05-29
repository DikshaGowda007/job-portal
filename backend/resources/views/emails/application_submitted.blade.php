<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <title>New Job Application</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <style>
        .email-container {
            background-image: linear-gradient(135deg, #6953f7, #cd4ff7);
            padding: 20px;
        }

        .content-container {
            background: #302d43;
            padding: 30px;
            border-radius: 40px;
            max-width: 500px;
            margin: 30px auto;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
            font-family: Arial, sans-serif;
            color: #eee;
        }

        .info-box {
            background: #3d3a52;
            padding: 15px;
            border-radius: 10px;
            margin: 15px 0;
        }

        p {
            font-size: 16px;
            line-height: 1.5;
            margin-bottom: 1.2rem;
        }

        .highlight {
            color: #0d6efd;
            font-weight: bold;
        }
    </style>
</head>

<body class="email-container">
    <div class="content-container">
        <h2>New Job Application Received</h2>
        <p>Hi {{ $recruiterName }},</p>
        <p>You have received a new application for the position:</p>

        <div class="info-box">
            <p><strong>Job Title:</strong> <span class="highlight">{{ $jobTitle }}</span></p>
            <p><strong>Candidate:</strong> {{ $candidateName }}</p>
            <p><strong>Email:</strong> {{ $candidateEmail }}</p>
            <p style="margin-bottom: 0;"><strong>Application ID:</strong> #{{ $applicationId }}</p>
        </div>

        <p>Please review the application at your earliest convenience.</p>
        <p>Thanks,<br>The {{ config('app.name') }} Team</p>
    </div>
</body>

</html>
