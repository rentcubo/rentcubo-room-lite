<!-- footer -->
<div class="footer-height"></div>

<div class="footer">
    <div class="site-content">
        <div class="top-bottom-spacing-footer">

            <p class="overview-line"></p>

            <div class="row">

                <div class="col-4">
                
                    <h5 class="captalize m-0"><i class="far fa-copyright small1"></i>{{Setting::get('site_name', 'RentCubo')}} {{date('Y')}}</h5>

                </div>

                <div class="col-6">

                    <div class="footer-pages" style="float: right;">

                        <a href="{{route('user.static_pages', ['page_type' => 'terms'])}}" class="bold-cls">{{tr('terms')}}</a>

                        <a href="{{route('user.static_pages', ['page_type' => 'privacy'])}}" class="bold-cls">{{tr('privacy')}}</a>

                        <a href="{{route('user.static_pages', ['page_type' => 'help'])}}" class="bold-cls">{{tr('help')}}</a>

                    </div>                      
                    
                </div>

            </div>
        </div>
    </div>
</div>
<!-- footer -->