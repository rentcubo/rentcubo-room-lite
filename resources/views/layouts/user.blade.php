<!DOCTYPE html>

<html>

<head>

    <title>{{Setting::get('site_name')}}</title>

    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">

    <meta name="viewport" content="width=device-width, initial-scale=1">

    @include('layouts.user.sub_layouts.head')

    @yield('styles')

</head>

<body>

    <div class="wrapper">

        <!-- Header section -->
        @include('layouts.user.header') 

        <div class="container">

            <!-- Notification -->

            @include('notifications.notify')

        </div>
        
        @yield('content')

        <!-- Footer section -->

        @include('layouts.user.footer') 

    </div>

    <!-- Scripts section -->

    @include('layouts.user.scripts')

    @yield('scripts')

</body>

</html>