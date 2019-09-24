@extends('layouts.admin') 

@section('title', tr('view_host'))

@section('breadcrumb')

    <li class="breadcrumb-item"><a href="{{route('admin.hosts.index')}}">{{tr('hosts')}}</a></li>

    <li class="breadcrumb-item"><a href="{{route('admin.hosts.view', ['host_id' => $host_detail->id])}}">{{tr('view_host')}}</a></li>
    <li class="breadcrumb-item active" aria-current="page">
        <span>{{tr('availability')}}</span>
    </li>
           
@endsection
@section('scripts')

<script type="text/javascript">
	(function($) {
    'use strict';
    	$(function() {
    		var today = new Date();

        	if ($('#calendar').length) {
            	$('#calendar').fullCalendar({
	                header: {
	                    left: 'prev,next today',
	                    center: 'title',
	                    right: 'month,basicWeek,basicDay'
	                },
	                defaultDate: today,
	                navLinks: true, // can click day/week names to navigate views
	                editable: true,
	                eventLimit: true, // allow "more" link when too many events
	                events: [
	                	<?php foreach ($hosts_availability as $key => $value) { ?>
	                		{
		                        title: 'Not Available',
		                        color: 'red',
		                        start: '<?php echo $value->available_date; ?>'
		                    },
		                <?php } ?>
                	]
            	})
        	}
    	});
	})(jQuery);
</script>
@endsection
@section('content')
	<div>
		<div class="row">
			<div class="col-lg-12">
				<div class="card px-3">
				  <div class="card-body">
				      <h4 class="card-title">{{tr('availability')}}</h4>
				      <div id="calendar"></div>
				  </div>
				</div>
            </div>
		</div>
	</div>
@endsection
