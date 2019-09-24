@extends('layouts.admin') 

@section('title', tr('view_host'))

@section('breadcrumb')

    <li class="breadcrumb-item"><a href="{{route('admin.hosts.index')}}">{{tr('hosts')}}</a></li>

    <li class="breadcrumb-item active" aria-current="page">
        <span>{{tr('view_host')}}</span>
    </li>
           
@endsection

@section('content')

	
	<div class="row user-profile">
            
		<div class="col-lg-12 side-right stretch-card">
			
			<div class="card">
				
				<div class="card-body">
					<div class="wrapper d-block d-sm-flex align-items-center justify-content-between">
						<div class="d-lg-flex flex-row text-center text-lg-left">
							<img src="{{ $host->provider_image ?: asset('placeholder.jpg') }}" class="img-sm rounded" alt="image"/>
							<div class="ml-lg-3">
								<p class="mt-2 text-success font-weight-bold">
									<a href="{{route('admin.providers.view', ['provider_id' => $host->provider_id])}}">{{$host->provider_name}}
									</a>
								</p>
							</div>
						</div>
						
						<ul class="nav nav-tabs tab-solid tab-solid-primary mb-0" id="hostDetails" role="tablist">
							<li class="nav-item">
								<a class="nav-link active" id="step1-tab" data-toggle="tab" href="#step1" role="tab" aria-controls="step1" aria-expanded="true" style="padding: 10px;">{{tr('details')}}</a>
                      		</li>
                      		<li class="nav-item">
                        		<a class="nav-link" id="step2-tab" data-toggle="tab" href="#step2" role="tab" aria-controls="step2" style="padding: 10px;">{{tr('description')}}</a>
                      		</li>

                    	</ul>
                  	</div>
                  	<div class="wrapper">
                    	<hr>
                    	<div class="tab-content" id="hostDetailsView">

                      		<div class="tab-pane fade show active" id="step1" role="tabpanel" aria-labelledby="step1">

                      			<div class="row">

						            <div class="col-md-6 grid-margin">

						              	<div class="card">

						                	<div class="card-body">
						                  	
						                  		<div class="template-demo">

							                        <table class="table mb-0">

							                            <tbody>
							                            	<tr>
							                                    <td class="pl-0"><b>{{ tr('host_name') }}</b></td>
							                                    <td class="pr-0 text-right">
							                                        <div>{{$host->host_name}}</div>
							                                    </td>
							                                </tr>

							                                <tr>
							                                    <td class="pl-0"><b>{{ tr('host_type') }}</b></td>
							                                    <td class="pr-0 text-right">
							                                        <div>{{$host->host_type}}</div>
							                                    </td>
							                                </tr>

							                                <tr>
							                                    <td class="pl-0"><b>{{ tr('category') }}</b></td>
							                                    <td class="pr-0 text-right">
							                                        <div><p class="card-text"><a href="{{ route('admin.categories.view', ['category_id' => $host->category_id] ) }}">{{ $host->category_name }}</a> </p></div>
							                                    </td>
							                                </tr>

							                                <tr>
							                                    <td class="pl-0">
							                                        <b>{{tr('sub_category')}} </b></td>
							                                    <td class="pr-0 text-right">
							                                        <div><p class="card-text"><a href="{{route('admin.sub_categories.view' ,['sub_category_id' => $host->sub_category_id] ) }}">{{$host->sub_category_name}}</a></p></div>
							                                    </td>
							                                </tr>

							                                <tr>
							                                    <td class="pl-0">
							                                        <b>{{tr('provider')}} </b></td>
							                                    <td class="pr-0 text-right">
							                                        <div><p class="card-text"><a href="{{route('admin.providers.view' ,['provider_id' => $host->provider_id] ) }}">{{$host->provider_name}}</a></p></div>
							                                    </td>
							                                </tr>

							                                <tr>
							                                    <td class="pl-0"><b>{{ tr('location') }}</b></td>
							                                    <td class="pr-0 text-right">
							                                    	{{$host->full_address}}
							                                    </td>
							                                </tr>

							                                <tr>
							                                    <td class="pl-0"> <b> {{ tr('created_at') }} </b> </td>
							                                    <td class="pr-0  text-right">
							                                        <div> {{ common_date($host->created_at) }} </div>
							                                    </td>
							                                </tr>

							                                <tr>
							                                    <td class="pl-0"> <b> {{ tr('updated_at')}} </b> </td>
							                                    <td class="pr-0  text-right">
							                                        <div>{{ common_date($host->updated_at) }} </div>
							                                    </td>
							                                </tr>


							                            </tbody>

							                        </table>

							                    </div>

						                	</div>

						              	</div>

						            </div>
						            <div class="col-md-6 grid-margin">
							            <div class="card">

							                <div class="card-body">
							                  	
							                  	<div class="template-demo">

							                        <table class="table mb-0">

							                            <tbody>

							                            	<tr>
							                                    <td class="pl-0"><b>{{ tr('base_price') }}</b></td>
							                                    <td class="pr-0 text-right">
							                                        <div>{{formatted_amount($host->base_price)}}</div>
							                                    </td>
							                                </tr>
							                                <tr>
							                                    <td class="pl-0"><b>{{ tr('total_guests') }}</b></td>
							                                    <td class="pr-0 text-right">
							                                        <div>{{$host->total_guests}}</div>
							                                    </td>
							                                </tr>
							                                <tr>                                
							                                    <td class="pl-0"><b>{{ tr('host_admin_status') }}</b></td>

							                                     <td class="pr-0 text-right">
							                                        @if($host->admin_status == ADMIN_HOST_APPROVED)

							                                        <span class="badge badge-outline-success text-uppercase">{{ tr('ADMIN_HOST_APPROVED') }}</span> 

							                                        @else

							                                        <span class="badge badge-outline-warning text-uppercase">{{ tr('ADMIN_HOST_PENDING') }} </span>

							                                        @endif
							                                    </td>
							                                </tr>
							                               
							                            </tbody>

							                        </table>
							                    </div>
						                	</div>


										</div>
										<div>
                        				@if(Setting::get('is_demo_control_enabled') == NO)

				                            <a href="{{ route('admin.hosts.edit', ['host_id' => $host->id] ) }}" class="btn btn-primary"><i class="mdi mdi-border-color"></i>{{tr('edit')}}</a>

				                            <a onclick="return confirm(&quot;{{tr('host_delete_confirmation' , $host->host_name)}}&quot;);" href="{{ route('admin.hosts.delete', ['host_id' => $host->id] ) }}"  class="btn btn-danger"><i class="mdi mdi-delete"></i>{{tr('delete')}}</a>

				                        @else
				                            <a href="javascript:;" class="btn btn-primary"><i class="mdi mdi-border-color"></i>{{tr('edit')}}</a>

				                            <a href="javascript:;" class="btn btn-danger"><i class="mdi mdi-delete"></i>{{tr('delete')}}</a>

				                        @endif

				                        @if($host->admin_status == APPROVED)

				                            <a class="btn btn-info" href="{{ route('admin.hosts.status', ['host_id' => $host->id] ) }}" onclick="return confirm(&quot;{{$host->host_name}} - {{tr('host_decline_confirmation')}}&quot;);"> <i class="mdi mdi-loop"></i>
				                                {{tr('decline')}}
				                            </a>

				                        @else

				                            <a class="btn btn-success" href="{{ route('admin.hosts.status', ['host_id' => $host->id] ) }}"><i class="mdi mdi-loop"></i>
				                                {{tr('approve')}}
				                            </a>
				                                                   
				                        @endif
				                        				                    		
                        				</div>

                      				</div>
                        		</div>
                      		</div><!-- tab content ends -->
                      		<div class="tab-pane fade" id="step2" role="tabpanel" aria-labelledby="step2-tab">
                      			<div class="card-group">
	                        		<div class="card mb-4">
					                    <!-- Card image -->
					                    <div class="view overlay">
					                        <img class="card-img-top" src="{{ $host->picture }}">
					                        <a href="#!">
					                            <div class="mask rgba-white-slight"></div>
					                        </a>
					                    </div>
					                </div>
					                <div class="card mb-4">
					                    <div class="card-body">
						                    <!-- Card content -->
						                    <div class="custom-card">

						                        <!-- Title -->
						                        <h4 class="card-title">{{ tr('description') }}</h4>
						                        <!-- Text -->
						                        <p class="card-text"><?= trim($host->description); ?></p>
						                        
						                    </div>
					                    	<!-- Card content -->
					                   	</div>

					                </div>
					            </div>
                      		</div>

                    	</div>

                  	</div>

                </div>
            
            </div>

		</div>

	</div>

@endsection