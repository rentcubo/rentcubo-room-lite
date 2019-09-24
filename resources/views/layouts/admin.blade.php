<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title>{{ Setting::get('site_name')}} - @yield('title')</title>

    <link rel="stylesheet" href="{{ asset('admin-assets/node_modules/mdi/css/materialdesignicons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin-assets/node_modules/simple-line-icons/css/simple-line-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('admin-assets/node_modules/flag-icon-css/css/flag-icon.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin-assets/node_modules/perfect-scrollbar/dist/css/perfect-scrollbar.min.css') }}">
    <!-- endinject -->
    <!-- plugin css for this page -->
    <link rel="stylesheet" href="{{ asset('admin-assets/node_modules/font-awesome/css/font-awesome.min.css') }}" />
    

    <link rel="stylesheet" href="{{ asset('admin-assets/node_modules/jquery-bar-rating/dist/themes/fontawesome-stars.css') }}">

    <link rel="stylesheet" href="{{ asset('admin-assets/node_modules/datatables.net-bs4/css/dataTables.bootstrap4.css')}}" />

    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />

    <link rel="stylesheet" href="{{ asset('admin-assets/node_modules/summernote/dist/summernote-bs4.css')}}">

    <link rel="stylesheet" href="{{ asset('admin-assets/css/style.css') }}">

    <link rel="stylesheet" href="{{ asset('admin-assets/css/custom.css') }}">


    <link rel="shortcut icon" href="{{ Setting::get('site_icon')}}" />

    <link rel="stylesheet" href="{{ asset('admin-assets/node_modules/jquery-bar-rating/dist/themes/css-stars.css') }}">
    
    <link rel="stylesheet" href="{{ asset('admin-assets/node_modules/jquery-bar-rating/dist/themes/fontawesome-stars-o.css')}} ">

    <link rel="stylesheet" href="{{ asset('admin-assets/node_modules/jquery-bar-rating/dist/themes/fontawesome-stars.css') }}">
  
    <link rel="stylesheet" href="{{ asset('admin-assets/node_modules/morris.js/morris.css') }}" />

    <link rel="stylesheet" href="{{ asset('admin-assets/node_modules/lightgallery/dist/css/lightgallery.min.css') }}">

    <link rel="stylesheet" href="{{ asset('admin-assets/node_modules/fullcalendar/dist/fullcalendar.min.css') }}" />

    <style>
        label {
            text-transform: uppercase;
        }
    </style>

    @yield('styles')

</head>

<body class="sidebar-fixed">

    <div class="container-scroller">

        @include('layouts.admin.header')

        <!-- partial -->
        <div class="container-fluid page-body-wrapper">

            <div class="row row-offcanvas row-offcanvas-right">

                <!-- partial:../../partials/_settings-panel -->
                @include('layouts.admin.rightbar')
                <!-- partial -->

                <!-- partial:_sidebar-->
                @include('layouts.admin.sidebar')
                <!-- partial -->

                <!-- content-wrapper -->
                <div class="content-wrapper">

                    <!-- partial:_breadcrum -->
                    <div class="col-md-12 grid-margin stretch-card">

                        <div class="template-demo">

                            <nav aria-label="breadcrumb" role="navigation">
                                
                                <ol class="breadcrumb breadcrumb-custom">

                                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{tr('home')}}</a></li>
                                    
                                    @yield('breadcrumb')
                                    
                                </ol>

                            </nav>

                        </div>

                    </div>
                    <!-- partial -->

                    @include('notifications.notify')

                    @yield('content')

                </div>
                <!-- content-wrapper ends -->

                <!-- partial:_footer -->

                @include('layouts.admin.footer')

                <!-- partial -->

            </div>
            <!-- row-offcanvas ends -->
        </div>
        <!-- page-body-wrapper ends -->
    </div>
    <!-- container-scroller -->

    <!-- plugins:js -->

    @include('layouts.admin.scripts')


    <script src="{{ asset('sparkleHover.js')}}"></script>
    
    <script type="text/javascript">

        @if(isset($page)) $("#{{$page}}").addClass("mainactive"); @endif
        
        @if(isset($sub_page)) $("#{{$sub_page}}").addClass("subactive");
        @endif

        $('#visit-website').sparkleHover({
            colors : ['maroon', 'rgba(255, 99, 71, 0.4)', 'pink', 'teal', 'grey', 'orange'],
            num_sprites: 200,
            lifespan: 3000,
            radius: 800,
            sprite_size: 15,
            shape: "triangle", // circle, square
        });
        
    </script>
    
    <script type="text/javascript">
        
        $(document).ready(function() {
        $('#expiry_date').datepicker({
            autoclose:true,
            format : 'dd-mm-yyyy',
            startDate: 'today',
        });
    });
    </script>

    @yield('scripts')

    <!-- End custom js for this page-->
</body>

</html>