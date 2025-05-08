<!DOCTYPE html>
<html>
<head>
    <title>Password Reset</title>
</head>
<body>
    <p>Hello,</p>
    <p>You requested a password reset. Here is your OTP: {{ $otp }}</p>
    <p>Click the following link to reset your password:</p>
    <a href="{{ url('/password/reset', $token) }}">Reset Password</a>
    <p>If you did not request a password reset, please ignore this email.</p>
</body>
</html>
