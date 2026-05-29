<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <title>Application Status Update</title>
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

        .status-box {
            background: #3d3a52;
            padding: 15px;
            border-radius: 10px;
            margin: 15px 0;
            text-align: center;
        }

        .status-change {
            color: #0d6efd;
            font-size: 18px;
            font-weight: bold;
        }

        .notes-box {
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
    </style>
</head>

<body class="email-container">
    <div class="content-container">
        <h2>Application Status Update</h2>
        <p>Hi {{ $candidateName }},</p>
        <p>Your application for <strong>{{ $jobTitle }}</strong> at <strong>{{ $companyName }}</strong> has been updated.</p>

        <div class="status-box">
            <p class="status-change" style="margin-bottom: 0;">
                {{ strtoupper(str_replace('_', ' ', $oldStatus)) }} &rarr; {{ strtoupper(str_replace('_', ' ', $newStatus)) }}
            </p>
        </div>

        @if($recruiterNotes)
        <div class="notes-box">
            <p><strong>Notes from Recruiter:</strong></p>
            <p style="margin-bottom: 0;">{{ $recruiterNotes }}</p>
        </div>
        @endif

        <p>Thank you for your continued interest!</p>
        <p>Best regards,<br>The {{ config('app.name') }} Team</p>
    </div>
</body>

</html>
