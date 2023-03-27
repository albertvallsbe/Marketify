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
        <section class="section-container">
            <section class="section-content">
                <h1 class="password-title">Have you forgotten the password?</h1>
                <p class="content">Have you forgotten the password? Don't worry. Enter your email and we will send you an email to recover your password.
                    <br><br>If you want to return press <a href={{route('login.index')}}>Login</a>
                </p>
                <form method='POST'action="{{ route('login.remember') }}">
                    @csrf
                    
                    <input type="email" class="input-mail-password" placeholder="example@gmail.com" name="remember-password">
                    @if ($errors->has('login-email'))
                        <span><strong>{{ $errors->first('login-email') }}</strong></span>
                    @endif
                    @if(session()->has('status'))
                    
                        <p> {{ session()->get('status') }} </p>
                    
                    @endif
                    <button class="btn-password">SEND EMAIL</button>

                </form>
            </section>
        </section>
    </section>
</body>

</html>
