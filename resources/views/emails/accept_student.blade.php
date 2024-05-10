<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{$data['subject']}}</title>
</head>
<body>
        <p>Dear Student,</p>
        <p>You can now access SUPER. Your new password is:</p>
        <p class="password">{{$data['password']}}</p>
        <p class="warning">Please keep this password confidential and do not share it with anyone.</p>
        <p>Best regards,</p>
        <p>SUPER</p>
    
</body>
</html>
