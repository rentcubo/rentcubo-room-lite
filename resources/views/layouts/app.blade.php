<!DOCTYPE html>
<html>
<head>
    <title>{{Setting::get('site_name')}}</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    @include('layouts.user.sub_layouts.head')

</head>

<body>

    <div class="wrapper">
            <!-- Header section -->

            @include('layouts.user.sub_layouts.header') 

            <!-- Main section -->
            @include('notifications.notify')
            
            @yield('content')
    </div>

    <!-- Scripts section -->

    @include('layouts.user.sub_layouts.scripts')

    @yield('scripts')

</body>

</html>