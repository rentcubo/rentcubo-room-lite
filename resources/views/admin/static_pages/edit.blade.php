@extends('layouts.admin')

@section('title', tr('edit_static_page'))

@section('breadcrumb')

    <li class="breadcrumb-item"><a href="{{ route('admin.static_pages.index') }}">{{tr('static_pages')}}</a></li>

    <li class="breadcrumb-item active" aria-current="page">
        <span>{{ tr('edit_static_page') }}</span>
    </li>
           
@endsection 

@section('content')

    @include('admin.static_pages._form')

@endsection
