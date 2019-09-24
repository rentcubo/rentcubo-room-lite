@extends('layouts.admin')

@section('title', tr('edit_sub_category'))

@section('breadcrumb')
	
    <li class="breadcrumb-item"><a href="{{ route('admin.sub_categories.index') }}">{{tr('sub_categories')}}</a></li>
    
    <li class="breadcrumb-item active" aria-current="page">
    	<span>{{ tr('edit_sub_category') }}</span>
    </li>
           
@endsection 

@section('content')

	@include('admin.sub_categories._form')

@endsection