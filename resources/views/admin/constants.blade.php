@extends('layouts.admin') 

@section('title', tr('help'))

@section('breadcrumb')

    <li class="breadcrumb-item active" aria-current="page">
    	<span>{{tr('help')}}</span>
    </li>
           
@endsection 

@section('content') 

<div class="col-lg-12 grid-margin stretch-card">

    <div class="row flex-grow">

        <div class="col-6 grid-margin">

    		<div class="card card-body">

				<h2><b>Host Steps</b></h2>

				<p>The .table class adds basic styling (light padding and only horizontal dividers) to a table:</p>

				<table class="table">
				    <thead>
				        <tr>
				            <th>Key</th>
				            <th>Value</th>
				        </tr>
				    </thead>
				    <tbody>

				    	@foreach($host_steps as $host_step_key => $host_step)
					        <tr>
					            <td>{{$host_step_key}}</td>
					            <td>{{$host_step}}</td>
					        </tr>
				        @endforeach
				        
				    </tbody>
				
				</table>

			</div>

			<hr>

			<div class="card card-body">

				<h2><b>INPUT TYPES</b></h2>

				<p>The .table class adds basic styling (light padding and only horizontal dividers) to a table:</p>

				<table class="table">
				    <thead>
				        <tr>
				            <th>Key</th>
				            <th>Value</th>
				        </tr>
				    </thead>
				    <tbody>

				    	@foreach($input_types as $input_type_key => $input_type)
					        <tr>
					            <td>{{$input_type_key}}</td>
					            <td>{{$input_type}}</td>
					        </tr>
				        @endforeach
				        
				    </tbody>
				
				</table>

			</div>

		</div>

        <div class="col-6 grid-margin">

    		<div class="card card-body">

				<h2><b>Home Page Types</b></h2>

				<p>The .table class adds basic styling (light padding and only horizontal dividers) to a table:</p>

				<table class="table">
				    <thead>
				        <tr>
				            <th>Key</th>
				            <th>Value</th>
				        </tr>
				    </thead>
				    <tbody>

				    	@foreach($page_types as $page_type_key => $page_type)
					        <tr>
					            <td>{{$page_type_key}}</td>
					            <td>{{$page_type}}</td>
					        </tr>
				        @endforeach
				        
				    </tbody>
				
				</table>

			</div>

			<hr>

    		<div class="card card-body">

				<h2><b>HOME API - URL TYPES</b></h2>

				<p>The .table class adds basic styling (light padding and only horizontal dividers) to a table:</p>

				<table class="table">
				    <thead>
				        <tr>
				            <th>Key</th>
				            <th>Value</th>
				        </tr>
				    </thead>
				    <tbody>

				    	@foreach($url_types as $url_type_key => $url_type)
					        <tr>
					            <td>{{$url_type_key}}</td>
					            <td>{{$url_type}}</td>
					        </tr>
				        @endforeach
				        
				    </tbody>
				
				</table>

			</div>

		</div>

	</div>	

</div>	

@endsection