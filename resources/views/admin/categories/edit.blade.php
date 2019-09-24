@extends('layouts.admin')

@section('title', tr('edit_category'))

@section('breadcrumb')

    <li class="breadcrumb-item"><a href="{{ route('admin.categories.index') }}">{{tr('categories')}}</a></li>
    
    <li class="breadcrumb-item active" aria-current="page">
    	<span>{{ tr('edit_category') }}</span>
    </li>
           
@endsection 

@section('content')

	@include('admin.categories._form')

@endsection