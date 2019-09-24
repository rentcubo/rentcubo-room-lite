@extends('layouts.admin') 

@section('title', tr('add_provider'))

@section('breadcrumb')

    <li class="breadcrumb-item">
    	<a href="{{ route('admin.providers.index') }}">{{tr('provider')}}</a>
    </li>
    
    <li class="breadcrumb-item active" aria-current="page">
    	<span>{{tr('add_provider')}}</span>
    </li>
           
@endsection 

@section('content') 
	
	@include('admin.providers._form') 

@endsection