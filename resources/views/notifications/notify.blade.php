@if(Session::has('flash_error'))

    <div class="col-sm-12 col-xs-12 col-md-12 alert alert-danger">
        <button type="button" class="close" data-dismiss="alert">×</button>
        {{Session::get('flash_error')}}
    </div>
@endif


@if(Session::has('flash_success'))
    <div class="col-sm-12 col-xs-12 col-md-12 alert alert-success" >
        <button type="button" class="close" data-dismiss="alert">×</button>
        {{Session::get('flash_success')}}
    </div>
@endif

@if (session()->has('success'))
  <div class="alert alert-success text-center animated fadeIn">
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
      <span aria-hidden="true">&times;</span>
    </button>
    <strong>
      {!! session()->get('success') !!}
    </strong>
  </div>
@endif


