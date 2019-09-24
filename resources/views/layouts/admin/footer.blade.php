
<footer class="footer">
    <div class="container-fluid clearfix">
        <span class="text-muted d-block text-center text-sm-left d-sm-inline-block">
        	{{ tr(('copyright'))}} © {{ date('Y') }} 
        	<a href="#">{{ Setting::get('site_name') }} </a>. 
        	{{ tr('all_right_reserved') }}
        </span>
        <span class="float-none float-sm-right d-block mt-1 mt-sm-0 text-center">
        	{{ Setting::get('site_name') }}
        	<i class="mdi mdi-heart text-danger"></i>
        </span>
    </div>
</footer>