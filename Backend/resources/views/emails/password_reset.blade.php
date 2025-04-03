<!DOCTYPE html>
<html>
<head>
    <title>Password Reset</title>
    <style>
        .logo {
            max-width: 150px;
            height: auto;
        }
        .email-container {
            font-family: Arial, sans-serif;
            color: #333;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
            max-width: 600px;
            margin: 0 auto;
        }
        .email-header {
            text-align: center;
            margin-bottom: 20px;
        }
        .email-body {
            line-height: 1.6;
        }
        .email-footer {
            margin-top: 20px;
            text-align: center;
            font-size: 12px;
            color: #777;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="email-header">
            <h1>Bitchest</h1>
            <h1>Password Reset Successful</h1>
        </div>

        <!-- Email body -->
        <div class="email-body">
            <p>Hello {{ $name }},</p>
            <p>Your password has been reset successfully. Your new password is:</p>
            <p><strong>{{ $newPassword }}</strong></p>
            <p>Please log in with the new password. If you did not request this, please contact support immediately.</p>
        </div>

        <!-- Footer -->
        <div class="email-footer">
            <p>This email was sent automatically. Please do not reply.</p>
        </div>
    </div>
</body>
</html>
