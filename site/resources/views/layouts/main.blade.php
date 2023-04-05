<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="..\..\css\app.css">
    <title>@yield('title')</title>
</head>

<body>
    @include('components.header')
    <div id="content">
        @yield('content')
    </div>
    @include('components.navigation')
    @include('components.footer')
</body>

</html>
