@extends('layouts.admin')

@section('title', tr('add_static_page'))

@section('breadcrumb')

    <li class="breadcrumb-item">
    	<a href="{{ route('admin.static_pages.index') }}">{{tr('static_pages')}}</a>
    </li>
    <li class="breadcrumb-item active" aria-current="page">
    	<span>{{tr('add_static_page')}}</span>
    </li>
           
@endsection 

@section('content')
    
    @include('admin.static_pages._form')

@endsection

@section('scripts')

<script src="https://cdn.ckeditor.com/ckeditor5/11.1.1/classic/ckeditor.js"></script>

<script>
    ClassicEditor
        .create( document.querySelector( '#ckeditor' ) )
        .catch( error => {
            console.error( error );
        } );
</script>

@endsection
