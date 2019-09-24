<!DOCTYPE html>
<html lang="en">
<head>

    <title>{{ Setting::get('site_name') }}</title>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link rel="stylesheet" href="{{ asset('admin-assets/css/style.css') }}">

    <link rel="shortcut icon" href="{{ Setting::get('site_icon') }}" />

</head>

<body>

    @yield('content')

</body>

</html>