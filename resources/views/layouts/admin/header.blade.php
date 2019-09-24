
<nav class="navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
    
    <div class="text-center navbar-brand-wrapper d-flex align-items-top justify-content-center">

        <a href="javascript:;" class="main-header-logo">{{Setting::get('site_name')}}</a>
        
    </div>

    <div class="navbar-menu-wrapper d-flex align-items-center">
        <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-toggle="minimize">
            <span class="icon-menu"></span>
        </button>
        
        <ul class="navbar-nav navbar-nav-right">

            <li class="nav-item d-none d-lg-block">
                <a class="btn btn-inverse-light btn-fw" href="{{url('/')}}" target="_blank">
                    Visit Website
                </a>
            </li>
        
            <li class="nav-item dropdown d-none d-lg-flex">

                <a class="nav-link dropdown-toggle" id="languageDropdown" href="#" data-toggle="dropdown">
                    <!-- <i class="flag-icon flag-icon-gb"></i>  -->
                  <img alt="" style="width: auto; max-height: 50px" class="img-circle img-responsive" src="{{ Auth::guard('admin')->user() ? Auth::guard('admin')->user()->picture : '' }}" />
                    {{ Auth::guard('admin')->user() ? Auth::guard('admin')->user()->name :'' }}
                </a>

                <div class="dropdown-menu navbar-dropdown" aria-labelledby="languageDropdown">
                    <a class="dropdown-item font-weight-medium" href="{{ route('admin.profile')}}">
                        <i class="icon-user menu-icon"></i>  
                        {{tr('profile')}}
                    </a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item font-weight-medium" href="{{ route('admin.settings')}}">
                        <i class="icon-settings menu-icon"></i> 
                        {{tr('settings')}}
                    </a>
                  
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item font-weight-medium" href="{{ route('admin.logout') }}">
                        <i class="fa fa-power-off menu-icon"></i>  
                        {{tr('logout')}}
                    </a>
                </div>
            </li>

            <?php /** <li class="nav-item dropdown" style="display: none;">
                <a class="nav-link count-indicator dropdown-toggle" id="notificationDropdown" href="#" data-toggle="dropdown">
                    <i class="icon-bell mx-0"></i>
                    <span class="count"></span>
                </a>
                <div class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list" aria-labelledby="notificationDropdown">
                    <a class="dropdown-item">
                        <p class="mb-0 font-weight-normal float-left">You have 4 new notifications
                        </p>
                        <span class="badge badge-pill badge-warning float-right">View all</span>
                    </a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item preview-item">
                        <div class="preview-thumbnail">
                            <div class="preview-icon bg-success">
                                <i class="icon-info mx-0"></i>
                            </div>
                        </div>
                        <div class="preview-item-content">
                            <h6 class="preview-subject font-weight-medium">Application Error</h6>
                            <p class="font-weight-light small-text">
                                Just now
                            </p>
                        </div>
                    </a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item preview-item">
                        <div class="preview-thumbnail">
                            <div class="preview-icon bg-warning">
                                <i class="icon-speech mx-0"></i>
                            </div>
                        </div>
                        <div class="preview-item-content">
                            <h6 class="preview-subject font-weight-medium">Settings</h6>
                            <p class="font-weight-light small-text">
                                Private message
                            </p>
                        </div>
                    </a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item preview-item">
                        <div class="preview-thumbnail">
                            <div class="preview-icon bg-info">
                                <i class="icon-envelope mx-0"></i>
                            </div>
                        </div>
                        <div class="preview-item-content">
                            <h6 class="preview-subject font-weight-medium">New user registration</h6>
                            <p class="font-weight-light small-text">
                                2 days ago
                            </p>
                        </div>
                    </a>
                </div>
            </li>
            <li class="nav-item dropdown" style="display: none;">
                <a class="nav-link count-indicator dropdown-toggle" id="messageDropdown" href="#" data-toggle="dropdown" aria-expanded="false">
                    <i class="icon-envelope mx-0"></i>
                    <span class="count"></span>
                </a>
                <div class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list" aria-labelledby="messageDropdown">
                    <div class="dropdown-item">
                        <p class="mb-0 font-weight-normal float-left">You have 7 unread mails
                        </p>
                        <span class="badge badge-info badge-pill float-right">View all</span>
                    </div>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item preview-item">
                        <div class="preview-thumbnail">
                            <img src="{{ asset('images/faces/face4.jpg') }}" alt="image" class="profile-pic">
                        </div>
                        <div class="preview-item-content flex-grow">
                            <h6 class="preview-subject ellipsis font-weight-medium">David Grey
                    <span class="float-right font-weight-light small-text">1 Minutes ago</span>
                  </h6>
                            <p class="font-weight-light small-text">
                                The meeting is cancelled
                            </p>
                        </div>
                    </a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item preview-item">
                        <div class="preview-thumbnail">
                            <img src="{{ asset('images/faces/face2.jpg') }}" alt="image" class="profile-pic">
                        </div>
                        <div class="preview-item-content flex-grow">
                            <h6 class="preview-subject ellipsis font-weight-medium">Tim Cook
                    <span class="float-right font-weight-light small-text">15 Minutes ago</span>
                  </h6>
                            <p class="font-weight-light small-text">
                                New product launch
                            </p>
                        </div>
                    </a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item preview-item">
                        <div class="preview-thumbnail">
                            <img src="{{ asset('images/faces/face3.jpg') }}" alt="image" class="profile-pic">
                        </div>
                        <div class="preview-item-content flex-grow">
                            <h6 class="preview-subject ellipsis font-weight-medium"> Johnson
                    <span class="float-right font-weight-light small-text">18 Minutes ago</span>
                  </h6>
                            <p class="font-weight-light small-text">
                                Upcoming board meeting
                            </p>
                        </div>
                    </a>
                </div>
            </li> */ ?>


            <!-- <li class="nav-item nav-settings d-none d-lg-block">
                <a class="nav-link" href="#">
                    <i class="icon-grid"></i>
                </a>
            </li> -->
        </ul>
        <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-toggle="offcanvas">
            <span class="icon-menu"></span>
        </button>
    </div>
</nav>

