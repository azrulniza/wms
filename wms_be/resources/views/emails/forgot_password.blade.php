<!-- <!DOCTYPE html>
<html>

<head>
    <title>Reset Password</title>
    <style>
        .button {
            display: inline-block;
            padding: 10px 20px;
            font-size: 16px;
            color: #ffffff;
            background-color: #007bff;
            border-radius: 5px;
            text-decoration: none;
        }
    </style>
</head>

<body>
    <h1>{{ $details['title'] }}</h1>
    <p>{{ $details['body'] }}</p>
    <p>
        <a href="{{ $details['reset_url'] }}" class="button">Reset Password</a>
    </p>
    <p>If you did not request a password reset, no further action is required.</p>
    <p>Thank you</p>
</body>

</html> -->

<!DOCTYPE html>
<html>

<head>
    <title>Reset Password</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
            color: #333333;
        }

        .container {
            width: 100%;
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .header {
            text-align: center;
            padding: 20px;
            border-bottom: 1px solid #dddddd;
        }

        .header img {
            max-width: 150px;
        }

        .content {
            padding: 20px;
        }

        .button {
            display: inline-block;
            padding: 10px 20px;
            font-size: 16px;
            color: #ffffff;
            background-color: #007bff;
            border-radius: 5px;
            text-decoration: none;
        }

        .footer {
            text-align: center;
            padding: 20px;
            border-top: 1px solid #dddddd;
            font-size: 12px;
            color: #777777;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <img src="{{ asset('image/logo-dark.png') }}" alt="Company Logo">
        </div>
        <div class="content">
            <h1>{{ $details['title'] }}</h1>
            <p>{{ $details['body'] }}</p>
            <p>
                <a href="{{ $details['reset_url'] }}" class="button">Reset Password</a>
            </p>
            <p>If you did not request a password reset, no further action is required.</p>
            <p>Thank you,<br>The XOXTech Team</p>
        </div>
        <div class="footer">
            <p> XOX Technology Berhad - 199901007872 (482772-D) | Petaling Jaya, Selangor | +6010-8799292</p>
            <p>&copy; {{ date('Y') }} XOX Technology Berhad. All rights reserved.</p>
        </div>
    </div>
</body>

</html>