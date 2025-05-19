<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Reset Login Attempt</title>
</head>
<body>
    <p>Hello {{ $name }},</p>
    <p>We noticed multiple failed login attempts on your account.</p>
    <p>
        <a href="{{ $resetUrl }}">Click here to reset your login attempts</a>
    </p>
    <p>If you did not request this, please ignore this email.</p>
</body>
</html>
