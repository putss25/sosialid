<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>OTP Verification</title>
</head>
<body style="font-family: sans-serif; text-align: center; padding: 40px;">

    <div style="max-width: 600px; margin: auto; border: 1px solid #ddd; padding: 20px; border-radius: 10px;">
        <h2>Email Verification</h2>
        <p>Thank you for registering. Please use the following One-Time Password (OTP) to verify your email address.</p>
        <div style="font-size: 36px; font-weight: bold; letter-spacing: 10px; margin: 20px 0; padding: 15px; background-color: #f2f2f2; border-radius: 5px;">
            {{ $otp }}
        </div>
        <p>This OTP is valid for {{ $expiresAt }} minutes.</p>
        <p>If you did not request this, please ignore this email.</p>
    </div>

</body>
</html>
