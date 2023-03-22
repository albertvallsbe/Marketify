<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="..\..\css\app.css">
    <script type ="module" src="{{ asset('js/app.js') }}"></script>
    <title>@yield('title')</title>
</head>

<body>
    @include('components.header')
    <div id="content">
        @yield('content')
    </div>
    @include('components.navigation')
</body>

</html>
