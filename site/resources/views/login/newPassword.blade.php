<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="..\..\css\app.css">
    <title>Forgot the password</title>
</head>

<body>
    <section class="container">
        <section class="remember-container">
            <section class="remember-section">
                <h1 class="remember-title">Have you forgotten the password?</h1>
                <p class="remember-content">Have you forgotten the password? Don't worry. Enter your new password and we will update you new password.
                    <br><br>If you want to return press <a href={{route('login.index')}}>Login</a>
                </p>
                <form method='POST'action="{{ route('login.rememberpassw') }}">
                    @csrf
                    <input type="email" class="rememberpassw" placeholder="Introduce your email" name="email">
                    <input type="password" class="rememberpassw" placeholder="Introduce your new password" name="remember-password">
                    <input type="password" class="rememberpassw" placeholder="Repeat your new passsword" name="repeat-password">
                
                    <button class="btn-password">UPDATE PASSWORD</button>

                </form>
            </section>
        </section>
    </section>
</body>

</html>
