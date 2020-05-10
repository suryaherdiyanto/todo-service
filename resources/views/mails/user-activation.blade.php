<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Email Verification</title>
    <style>

        .button-verify {
            padding: 10px 13px;
            border: none;
            border-radius: 4px;
            background-color: green;
            color: #f1f1f1;
            font-size: 16px;
            cursor: pointer;
        }

        .wrapper {
            width: 100%;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

    </style>
</head>
<body>
    <div class="wrapper">
        <button class="button-verify">Thankyou for registering to our app</button> <br>
        this is your verification code: {{ hash_hmac('sha256', $user->id.''.$user->email, env('APP_KEY')) }}
    </div>
</body>
</html>