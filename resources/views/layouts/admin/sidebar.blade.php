
<nav class="sidebar sidebar-offcanvas" id="sidebar">
    <ul class="nav">
       
        <li class="nav-item" id="dashboard" >
            <a class="nav-link" href="{{ route('admin.dashboard') }}">
                <i class="icon-support menu-icon"></i>
                <span class="menu-title">{{ tr('dashboard')}}</span>
            </a>
        </li>

        <li class="nav-item" id="users">
            <a class="nav-link" data-toggle="collapse" href="#users-sidebar" aria-expanded="false" aria-controls="users-sidebar">
                <i class="icon-user menu-icon"></i>
                <span class="menu-title">{{ tr('users') }}</span>
            </a>

            <div class="collapse" id="users-sidebar">

                <ul class="nav flex-column sub-menu">
                    <li class="nav-item"> 
                        <a class="nav-link" id="users-create" href="{{route('admin.users.create')}} "> {{ tr('add_user') }} </a>
                    </li>
                    <li class="nav-item"> 
                        <a class="nav-link" id="users-view" href=" {{route('admin.users.index')}} "> {{ tr('view_users') }}  </a>
                    </li>                    
                </ul>
            </div>

        </li> 

        <li class="nav-item" id="providers">
            <a class="nav-link" data-toggle="collapse" href="#providers-sidebar" aria-expanded="false" aria-controls="providers-sidebar">
                <i class="icon-people menu-icon"></i>
                <span class="menu-title">{{ tr('providers') }}</span>
            </a>

            <div class="collapse" id="providers-sidebar">

                <ul class="nav flex-column sub-menu">
                    <li class="nav-item"> 
                        <a class="nav-link" id="providers-create" href="{{route('admin.providers.create')}} "> {{ tr('add_provider') }} </a>
                    </li>
                   
                    <li class="nav-item"> 
                        <a class="nav-link" id="providers-view" href=" {{route('admin.providers.index')}} "> {{ tr('view_providers') }}  </a>
                    </li>  
                  
                </ul>
            </div>

        </li>

        <li class="nav-item nav-item-header">

            <a class="nav-link">
                <i class="fa fa-down-arrow menu-icon"></i>
                <span class="menu-title text-uppercase">{{ tr('host_management')}}</span>
            </a>
            
        </li>

        <li class="nav-item" id="categories">
            <a class="nav-link" data-toggle="collapse" href="#categories-sidebar" aria-expanded="false" aria-controls="categories-sidebar">
                <i class="icon-list menu-icon"></i>
                <span class="menu-title">{{ tr('categories') }}</span>
            </a>

            <div class="collapse" id="categories-sidebar">

                <ul class="nav flex-column sub-menu">
                    <li class="nav-item"> 
                        <a class="nav-link" id="categories-create" href="{{route('admin.categories.create')}} "> {{ tr('add_category') }} </a>
                    </li>
                    <li class="nav-item"> 
                        <a class="nav-link" id="categories-view" href=" {{route('admin.categories.index')}} "> {{ tr('view_categories') }}  </a>
                    </li>                    
                </ul>
            </div>
        </li>

        <li class="nav-item" id="sub_categories">
            <a class="nav-link" data-toggle="collapse" href="#sub_categories-sidebar" aria-expanded="false" aria-controls="sub_categories-sidebar">
                <i class="icon-list menu-icon"></i>
                <span class="menu-title">{{ tr('sub_categories') }}</span>
            </a>

            <div class="collapse" id="sub_categories-sidebar">

                <ul class="nav flex-column sub-menu">
                    <li class="nav-item"> 
                        <a class="nav-link" id="sub_categories-create" href="{{route('admin.sub_categories.create')}} "> {{ tr('add_sub_category') }} </a>
                    </li>
                    <li class="nav-item"> 
                        <a class="nav-link" id="sub_categories-view" href=" {{route('admin.sub_categories.index')}} "> {{ tr('view_sub_categories') }}  </a>
                    </li>                    
                </ul>
            </div>

        </li>

    

        <li class="nav-item" id="hosts">

            <a class="nav-link" data-toggle="collapse" href="#hosts-sidebar" aria-expanded="false" aria-controls="hosts-sidebar">
                <i class="icon-home menu-icon"></i>
                <span class="menu-title">{{ tr('hosts') }}</span>
            </a>

            <div class="collapse" id="hosts-sidebar">

                <ul class="nav flex-column sub-menu">
                    <li class="nav-item"> 
                        <a class="nav-link" id="hosts-create" href="{{route('admin.hosts.create')}} "> {{ tr('add_host') }} </a>
                    </li>
                    <li class="nav-item"> 
                        <a class="nav-link" id="hosts-view" href=" {{route('admin.hosts.index')}} "> {{ tr('view_hosts') }}  </a>
                    </li>

                                     
                </ul>
            </div>

        </li>

        <li class="nav-item nav-item-header">

            <a class="nav-link">
                <i class="fa fa-down-arrow menu-icon"></i>
                <span class="menu-title text-uppercase">Booking Management</span>
            </a>
            
        </li>

        <li class="nav-item">
            <a class="nav-link" id="bookings" href="{{ route('admin.bookings.index') }}">
                <i class="icon-calendar menu-icon"></i>
                <span class="menu-title">{{ tr('bookings') }}</span>
            </a>
        </li>


        <li class="nav-item">
            <a class="nav-link" id="bookings-payments" href="{{ route('admin.bookings.payments') }}">
                <i class="icon-credit-card menu-icon"></i>
                <span class="menu-title">{{ tr('bookings_payments') }}</span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link" id="reviews" href="{{ route('admin.reviews.users') }}">
                <i class="icon-credit-card menu-icon"></i>
                <span class="menu-title">{{ tr('reviews') }}</span>
            </a>
        </li>

        <li class="nav-item nav-item-header">

            <a class="nav-link">
                <i class="fa fa-down-arrow menu-icon"></i>
                <span class="menu-title text-uppercase">Settings Management</span>
            </a>
            
        </li>

        <li class="nav-item" id="settings">
            <a class="nav-link" href="{{ route('admin.settings') }}" id="settings-view">
                <i class="icon-settings menu-icon"></i>
                <span class="menu-title">{{ tr('settings') }}</span>
            </a>
        </li>

        <li class="nav-item" id="static_pages">
            <a class="nav-link" data-toggle="collapse" href="#static_pages-sidebar" aria-expanded="false" aria-controls="static_pages-sidebar">
                <i class="icon-book-open menu-icon"></i>
                <span class="menu-title">{{ tr('static_pages') }}</span>
            </a>

            <div class="collapse" id="static_pages-sidebar">

                <ul class="nav flex-column sub-menu">
                    <li class="nav-item"> 
                        <a class="nav-link" id="static_pages-create" href="{{route('admin.static_pages.create')}} "> {{ tr('add_static_page') }} </a>
                    </li>
                    <li class="nav-item"> 
                        <a class="nav-link" id="static_pages-view" href=" {{route('admin.static_pages.index')}} "> {{ tr('view_static_pages') }}  </a>
                    </li>                    
                </ul>

            </div>

        </li> 


        <li class="nav-item" id="help">
            <a class="nav-link" href="{{ route('admin.help') }}">
                <i class="icon-directions menu-icon"></i>
                <span class="menu-title">{{ tr('help') }}</span>
            </a>
        </li>

        <li class="nav-item" id="logout">
            <a class="nav-link" href="{{ route('admin.logout') }}">
                <i class="fa fa-power-off menu-icon"></i>
                <span class="menu-title">{{ tr('logout') }}</span>
            </a>
        </li>

    </ul>
</nav>