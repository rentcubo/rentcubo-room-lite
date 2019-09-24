@extends('layouts.admin') 

@section('title', tr('settings'))

@section('breadcrumb')

<li class="breadcrumb-item active" aria-current="page">

    <span>{{ tr('settings') }}</span>
</li>

@endsection 

@section('styles')

<style>
    
/*  rental tab */

div.rental-tab-container{
    z-index: 10;
    background-color: #ffffff;
    padding: 0 !important;
    border-radius: 4px;
    -moz-border-radius: 4px;
    border:1px solid #ddd;
    margin-top: 20px;
    margin-left: 50px;
    -webkit-box-shadow: 0 6px 12px rgba(3, 169, 243, 0.5);
    box-shadow: 0 6px 12px rgba(3, 169, 243, 0.5);
    -moz-box-shadow: 0 6px 12px rgba(3, 169, 243, 0.5);
    background-clip: padding-box;
    opacity: 0.97;
    filter: alpha(opacity=97);

}

div.rental-tab-menu{
    padding-right: 0;
    padding-left: 0;
    padding-bottom: 0;

}

div.rental-tab-menu div.list-group{
    margin-bottom: 0;
}

div.rental-tab-menu div.list-group>a{
    margin-bottom: 0;
}

div.rental-tab-menu div.list-group>a .glyphicon,
div.rental-tab-menu div.list-group>a .fa {
    color: #18cabe;
}

div.rental-tab-menu div.list-group>a:first-child{
    border-top-right-radius: 0;
    -moz-border-top-right-radius: 0;
}

div.rental-tab-menu div.list-group>a:last-child{
    border-bottom-right-radius: 0;
    -moz-border-bottom-right-radius: 0;
}

div.rental-tab-menu div.list-group>a.active,
div.rental-tab-menu div.list-group>a.active .glyphicon,
div.rental-tab-menu div.list-group>a.active .fa{
    background-color: #18cabe;
    background-image: #18cabe;
    color: #ffffff;
    border: 2px dashed;
}

div.rental-tab-menu div.list-group>a.active:after{
    content: '';
    position: absolute;
    left: 100%;
    top: 50%;
    margin-top: -13px;
    border-left: 0;
    border-bottom: 13px solid transparent;
    border-top: 13px solid transparent;
    border-left: 10px solid #18cabe;

}

div.rental-tab-content{
    background-color: #ffffff;
    /* border: 1px solid #eeeeee; */
    padding-left: 20px;
    padding-top: 10px;

}

.box-body {
    padding: 0px;
}

div.rental-tab div.rental-tab-content:not(.active){
    display: none;
}

.sub-title {
    width: fit-content;
    color: #2c648c;
    font-size: 18px;
    /*border-bottom: 2px dashed #285a86;*/
    padding-bottom: 5px;

}

hr {
    margin-top: 15px;
    margin-bottom: 15px;
}

</style>

@endsection

@section('content')

<div class="row">

    <div class="col-lg-10 col-md-10 col-sm-10 col-xs-10 rental-tab">
        
        <!-- Site section -->
        
        <div class="rental-tab-content active">

           <form id="site_settings_save" action="{{ route('admin.settings.save') }}" method="POST" enctype="multipart/form-data" role="form">

                @csrf

                <div class="box-body">

                    <div class="row">

                        <div class="col-md-12">

                            <h5 class="settings-sub-header text-uppercase" style="color: #f30660;"><b>{{tr('site_settings')}}</b></h5>

                            <hr>

                        </div>

                        <div class="col-md-6">

                            <div class="form-group">
                                <label for="site_name">{{tr('site_name')}} *</label>
                                <input type="text" class="form-control" id="site_name" name="site_name" placeholder="Enter {{tr('site_name')}}" value="{{Setting::get('site_name')}}">
                            </div>

                            <div class="form-group">
                                <label for="site_logo">{{tr('site_logo')}} *</label>
                                <p class="txt-warning">{{tr('png_image_note')}}</p>
                                <input type="file" class="form-control" id="site_logo" name="site_logo" accept="image/png" placeholder="{{tr('site_logo')}}">
                            </div>
                            
                            @if(Setting::get('site_logo'))

                                <img class="img img-thumbnail m-b-20" style="width: 40%" src="{{Setting::get('site_logo')}}" alt="{{Setting::get('site_name')}}"> 

                            @endif

                        </div>

                        <div class="col-lg-6">

                            <div class="form-group">

                                <label for="tag_name">{{tr('tag_name')}} *</label>

                                <input type="text" class="form-control" id="tag_name" name="tag_name" placeholder="{{tr('tag_name')}}" value="{{Setting::get('tag_name')}}">

                            </div>

                            <div class="form-group">

                                <label for="site_icon">{{tr('site_icon')}} *</label>

                                <p class="txt-warning">{{tr('png_image_note')}}</p>

                                <input type="file" class="form-control" id="site_icon" name="site_icon" accept="image/png" placeholder="{{tr('site_icon')}}">

                            </div>

                                @if(Setting::get('site_icon'))

                                    <img class="img img-thumbnail m-b-20" style="width: 20%" src="{{Setting::get('site_icon')}}" alt="{{Setting::get('site_name')}}"> 

                                @endif
                        </div>

                    </div>

                </div>

                <!-- /.box-body -->

                <div class="box-footer">

                    <button type="reset" class="btn btn-warning">{{tr('reset')}}</button>

                    @if(Setting::get('admin_delete_control') == 1)
                        <button type="submit" class="btn btn-primary pull-right" disabled>{{tr('submit')}}</button>
                    @else
                        <button type="submit" class="btn btn-success pull-right">{{tr('submit')}}</button>
                    @endif
                </div>
            
            </form>
            <br>
        
        </div>

    </div>

</div>

@endsection

@section('scripts')

@endsection