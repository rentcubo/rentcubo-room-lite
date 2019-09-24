@extends('layouts.admin')

@section('title', tr('edit_user'))

@section('breadcrumb')

    <li class="breadcrumb-item"><a href="{{ route('admin.users.index') }}">{{tr('users')}}</a></li>
    
    <li class="breadcrumb-item active" aria-current="page">
    	<span>{{ tr('edit_user') }}</span>
    </li>
           
@endsection 

@section('content')

	@include('admin.users._form')

@endsection