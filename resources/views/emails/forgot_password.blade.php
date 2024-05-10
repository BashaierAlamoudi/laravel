<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{$data['subject']}}</title>
    <style>
                .password {
            font-weight: bold;
            color: #007bff; /* Blue color for the password */
        }
    </style>
</head>
<body>
    <p> Below is your new password:</p>
    <p class="password">{{$data['password']}} </p>
    <p>Best regards,</p>
    <p>SUPER</p>


    
</body>
</html>
