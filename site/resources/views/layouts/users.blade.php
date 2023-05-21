<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="..\..\css\app.css">
    <title>@yield('title')</title>
</head>

<body>
    <div id="content">
        @yield('content')
    </div>
    @include('components.footer')
</body>

</html>
