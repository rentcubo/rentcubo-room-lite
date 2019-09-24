@extends('layouts.admin') 

@section('title', tr('dashboard'))

@section('breadcrumb')

    <li class="breadcrumb-item active" aria-current="page">
    	<span>{{tr('dashboard')}}</span>
    </li>
           
@endsection 

@section('content') 

	<div class="row">

	    <div class="col-md-6 col-lg-3 grid-margin stretch-card">
	        <div class="card general-box general-box-info">
	            <div class="card-body">
	            	<a href="{{route('admin.users.index')}}" target="_blank" class="a-tag">
		                <div class="d-flex align-items-center justify-content-md-center">
		                    <i class="icon-user icon-lg text-white"></i>
		                    <div class="ml-3">
		                        <h4>{{ tr('users') }}</h4>
		                        <h6>{{ $data->total_users }}</h6>
		                    </div>
		                </div>
		            </a>
	            </div>
	        </div>
	    </div>

	    <div class="col-md-6 col-lg-3 grid-margin stretch-card">
	        <div class="card general-box general-box-maroon">
	            <div class="card-body">

	            	<a href="{{route('admin.providers.index')}}" target="_blank" class="a-tag">

		                <div class="d-flex align-items-center justify-content-md-center">
		                    <i class="fa fa-group icon-lg"></i>
		                    <div class="ml-3">
		                        <h4>{{ tr('providers') }}</h4>
		                        <h6>{{ $data->total_providers }}</h6>
		                    </div>
		                </div>
		            </a>
	            </div>
	        </div>
	    </div>

	    <div class="col-md-6 col-lg-3 grid-margin stretch-card">
	        <div class="card general-box general-box-warning">
	            <div class="card-body">
	            	<a href="{{route('admin.hosts.index')}}" target="_blank" class="a-tag">

		                <div class="d-flex align-items-center justify-content-md-center">
		                    <i class="mdi mdi-wallet icon-lg"></i>
		                    <div class="ml-3">
		                        <h4>{{ tr('total_listings') }}</h4>
		                        <h6>{{ $data->total_hosts }}</h6>
		                    </div>
		                </div>
		            </a>
	            </div>
	        </div>
	    </div>

	    <div class="col-md-6 col-lg-3 grid-margin stretch-card">
	        <div class="card general-box general-box-success">
	            <div class="card-body">
	            	
	            	<a href="{{route('admin.bookings.index')}}" target="_blank" class="a-tag">

		                <div class="d-flex align-items-center justify-content-md-center">
		                    <i class="fa fa-money icon-lg"></i>
		                    <div class="ml-3">
		                        <h4>{{ tr('total_bookings') }}</h4>
		                        <h6>{{ $data->total_bookings}}</h6>
		                    </div>
		                </div>
		            </a>
	            </div>
	        </div>
	    </div>

	</div>

	<!-- <div class="row">

		<div class="col-md-6 stretch-card">
		    <div class="row flex-grow">
		        <div class="col-12 grid-margin stretch-card">
		            <div class="card">
		                <div class="card-body">
		                    <h4 class="card-title mb-0">{{tr('today_profit')}}</h4>
		                    <div class="d-flex justify-content-between align-items-center">
		                        <div class="d-inline-block pt-3">
		                            <div class="d-lg-flex">
		                                <h2 class="mb-0">$ {{$data->today_revenue}}</h2>
		                                <div class="d-flex align-items-center ml-lg-2">
		                                    <i class="mdi mdi-clock text-muted"></i>
		                                    <small class="ml-1 mb-0">Updated: {{date('h:i A')}}</small>
		                                </div>
		                            </div>
		                            <small class="text-gray">Raised from 89 orders.</small>
		                        </div>
		                        <div class="d-inline-block">
		                            <div class="bg-success box-shadow-success px-3 px-md-4 py-2 rounded">
		                                <i class="mdi mdi-buffer text-white icon-lg"></i>
		                            </div>
		                        </div>
		                    </div>
		                </div>
		            </div>
		        </div>
		        <div class="col-12 grid-margin stretch-card">
		            <div class="card">
		                <div class="card-body">
		                    <h4 class="card-title mb-0">{{tr('total_profit')}}</h4>
		                    <div class="d-flex justify-content-between align-items-center">
		                        <div class="d-inline-block pt-3">
		                            <div class="d-lg-flex">
		                                <h2 class="mb-0">{{ formatted_amount($data->total_revenue) }}</h2>
		                                <div class="d-flex align-items-center ml-lg-2">
		                                    <i class="mdi mdi-clock text-muted"></i>
		                                    <small class="ml-1 mb-0">Updated: {{date('h:i A')}}</small>
		                                </div>
		                            </div>
		                            <small class="text-gray">hey, let’s have lunch together</small>
		                        </div>
		                        <div class="d-inline-block">
		                            <div class="bg-warning box-shadow-warning px-3 px-md-4 py-2 rounded">
		                                <i class="mdi mdi-wallet text-white icon-lg"></i>
		                            </div>
		                        </div>
		                    </div>
		                </div>
		            </div>
		        </div>
		    </div>
		</div> -->

		<!-- hosts_survey details start -->

		<!-- <div class="col-md-6 col-lg-6 grid-margin stretch-card">
		    <div class="card">
		        <div class="card-body">
		            <h6 class="card-title text-uppercase">{{tr('hosts_survey')}}</h6>
		            <div class="w-75 mx-auto">
		                <div class="d-flex justify-content-between text-center">
		                    <div class="wrapper">
		                        <h4>{{ $hosts_count['total'] }}</h4>
		                        <small class="text-muted">{{ tr('total_hosts')}}</small>
		                    </div>
		                    <div class="wrapper">
		                        <h4>{{ $hosts_count['verified_count'] }}</h4>
		                        <small class="text-muted">{{ tr('veried_hosts')}}</small>
		                    </div>

		                    <div class="wrapper">
		                        <h4>{{ $hosts_count['unverified_count'] }}</h4>
		                        <small class="text-muted">{{ tr('unveried_hosts')}}</small>
		                    </div>
		                </div>
		                <div id="dashboard-donut-chart" style="height:250px"></div>
		            </div>
		            <div id="legend" class="donut-legend"></div>
		        </div>
		    </div>
		
		</div> -->

		<!-- hosts_survey details end -->

	<!-- </div> -->

	<!-- <div class="row">
	    <div class="col-12 grid-margin">
	        <div class="card">
	            <div class="card-body">
	                <h6 class="card-title text-uppercase">{{tr('last_10_days_analytics')}}</h6>
	                <p class="card-description">Products that are creating the most revenue and their sales throughout the year and the variation in behavior of sales.</p>
	                <div id="js-legend" class="chartjs-legend mt-4 mb-5"></div>
	                <div class="demo-chart">
	                    <canvas id="dashboard-monthly-analytics"></canvas>
	                </div>
	            </div>
	        </div>
	    </div>
	</div> -->

	<div class="row">

	    <div class="col-md-6 grid-margin stretch-card">

	        <div class="card">

	        	<div class="card-header general-box-warning">
	                <h4>{{tr('recent_users')}}</h4>
	        	</div>

	            <div class="card-body">

	                @if(count($recent_users) > 0)

		                @foreach($recent_users as $i => $user_details)
		                
			               	<a href="{{ route('admin.users.view', ['user_id' => $user_details->id])}}" class="nav-link">

				                <div class="wrapper d-flex align-items-center py-2 border-bottom">
				                    
				                    <img class="img-sm rounded-circle" src="{{ $user_details->picture }}" alt="profile">

				                    <div class="wrapper ml-3">
				                        <h6 class="ml-1 mb-1">
				                        	{{$user_details->name}} 
				                        </h6>

				                        <small class="text-muted mb-0">
				                        	<i class="icon icon-envelope-open mr-1"></i>
				                        	{{ $user_details->email }}
				                        </small>

				                    </div>
				                    
				                    @if($user_details->is_verified == USER_EMAIL_VERIFIED)
					                    <div class="badge badge-pill badge-info ml-auto px-1 py-1">
					                    	<i class="mdi mdi-check font-weight-bold"></i>
					                    </div>
				                    @endif
				                </div>
			                </a>

		               @endforeach

		           	@else

		           		<p class="text-muted">{{tr('no_result_found')}}</p>

		           	@endif

	            </div>

	            <div class="card-footer text-center">
	            	<a href="{{route('admin.users.index')}}" class="text-uppercase btn btn-warning">{{tr('view_all')}}</a>
	            </div>
	        </div>

	    </div>

	    <div class="col-md-6 col-lg-6 grid-margin stretch-card">

		    <div class="card">

	        	<div class="card-header general-box-success">
	                <h4>{{tr('recent_providers')}}</h4>
	        	</div>

		        <div class="card-body">

		        	@if(count($recent_providers) > 0)

		                @foreach($recent_providers as $i => $provider_details)
		           			
		           			<a href="{{ route('admin.providers.view', ['provider_id' => $provider_details->id])}}" class="nav-link">

				            <div class="list d-flex align-items-center border-bottom py-3">

				                <img class="img-sm rounded-circle" src="{{ $provider_details->picture ?: asset('placeholder.jpg')}}" alt="">

				                <div class="wrapper w-100 ml-3">

				                    <p class="mb-0"><b>{{$provider_details->name}} </b></p>

				                    <div class="d-flex justify-content-between align-items-center">

				                        <div class="d-flex align-items-center">
				                        	<i class="icon icon-envelope-open text-muted mr-1"></i>

				                            <p class="mb-0 text-muted">{{$provider_details->email}}</p>
				                        </div>

				                        <small class="text-muted ml-auto">{{$provider_details->created_at->diffForHumans()}}</small>
				                    </div>
				                </div>
				            
				            </div>

				            </a>

				        @endforeach

				    @endif
		        
		        </div>

		        <div class="card-footer text-center">
	            	<a href="{{route('admin.providers.index')}}" class="text-uppercase btn btn-success">{{tr('view_all')}}</a>
	            </div>
		    </div>
		
		</div>

	</div>

@endsection

@section('scripts')

<script type="text/javascript">
	
	if ($("#dashboard-monthly-analytics").length) {

      var ctx = document.getElementById('dashboard-monthly-analytics').getContext("2d");

      var myChart = new Chart(ctx, {
        type: 'line',
        data: {        	
          // labels: ['Jan', 'Feb', 'Mar', 'Arl', 'May', 'Jun', 'Jul', 'Aug'],
          labels: [
		<?php foreach($views['get'] as $date) { echo "'".date('Y-m-d', strtotime($date->created_at))."'". ",";} ?>
			],
          datasets: [{
              label: "Visit counts",
              // borderColor: 'rgba(171, 140 ,228, 0.8)',
              backgroundColor: 'rgba(171, 140 ,228, 0.8)',
              pointRadius: 0,
              fill: true,
              borderWidth: 1,
              fill: 'origin',
              // data: [0, 0, 30, 0, 0, 0, 50, 0]
              data: [
              <?php foreach($views['get'] as $counts) { echo $counts->count .",";} ?>
			
              ]
            }
          ]
        },
        options: {
          maintainAspectRatio: false,
          legend: {
            display: false,
            position: "top"
          },
          scales: {
            xAxes: [{
              ticks: {
                display: true,
                beginAtZero: true,
                fontColor: 'rgba(0, 0, 0, 1)'
              },
              gridLines: {
                display: false,
                drawBorder: false,
                color: 'transparent',
                zeroLineColor: '#eeeeee'
              }
            }],
            yAxes: [{
              gridLines: {
                drawBorder: true,
                display: true,
                color: '#eeeeee',
              },
              categoryPercentage: 0.5,
              ticks: {
                display: true,
                beginAtZero: true,
                stepSize: 20,
                max: 80,
                fontColor: 'rgba(0, 0, 0, 1)'
              }
            }]
          },
        },
        elements: {
          point: {
            radius: 0
          }
        }
      });
      document.getElementById('js-legend').innerHTML = myChart.generateLegend();
    }

    if ($("#dashboard-donut-chart").length) {
      $(function() {
       
        var total = <?php echo $hosts_count['total'] ?>;
     
        var browsersChart = Morris.Donut({
          element: 'dashboard-donut-chart',
          data: [
          	{
              label: "Verified Hosts",
              value: <?php echo $hosts_count['verified_count'] ?>
            },
            {
              label: "Unverified Hosts",
              value: <?php echo $hosts_count['unverified_count'] ?>
            }
          ],
          
          resize: true,
          
          colors: ['#03a9f3', '#00c292'],

          formatter: function(value, data) {

            return Math.floor(value / total * 100) + '%';
          }

        });

        browsersChart.options.data.forEach(function(label, i) {
          var legendItem = $('<span></span>').text(label['label']).prepend('<span>&nbsp;</span>');

          legendItem.find('span')
            .css('backgroundColor', browsersChart.options.colors[i]);
          $('#legend').append(legendItem)
        });

      });

    }
    

</script>

@endsection