<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <style>
        p {
            font-size: 12px;
        }

        pre {
            font-weight: bold;
            font-size: 18px;
        }
    </style>
    <title> Rigel Tech </title>
</head>
<body>
<p>Hello, This is a mail from Rigel,</p>
<p> Welcome to Rigel School Management System 😉 </p>
<p>Here is your password: </p>

<pre>
    {{ $password }}
</pre>

<p>
    Please change this password after you login:
    <a href="http://127.0.0.1:8000/login">Login</a>
</p>

<p>Steps:</p>
<ul>
    <li> Login to your account</li>
    <li> Click on your profile</li>
    <li> On the password field, enter your current password, new password and confirm</li>
</ul>
</body>
</html>
