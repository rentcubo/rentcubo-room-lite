@extends('layouts.provider')  

@section('content') 

<div class="main">
    <div class="container-fluid">
        <div class="rooms top-bottom-spacing">
            <!-- Room Head Starts -->
            <div class="rooms-head">
                <h3 class="room-head-tit">{{$hosts->total()}} {{tr('hosts')}}</h3>
                <h5><a href="{{route('provider.hosts.create')}}" class="btn btn-success"><b>{{tr('add_host')}}</b></a></h5>
            </div>
            <!-- Room Head Ends -->
            <!-- Room Content Starts -->
            <div class="room-content">

                <div class="rooms-table table-responsive">
                    
                    @if($hosts->total())

                        <table class="cmn-table table">
                            <thead>
                                <tr>
                                    <th>{{tr('s_no')}}</th>
                                    <th scope="col">{{tr('host_name')}}</th>
                                    <th scope="col">{{tr('location')}}</th>
                                    <th scope="col">{{tr('total_guests')}}</th>
                                    <th scope="col">{{tr('status')}}</th>
                                    <th scope="col">{{tr('action')}}</th>
                                </tr>
                            </thead>
                            <tbody>

                                @foreach($hosts as $b => $host_details)
                                    <tr>
                                        <td>
                                            <a href="{{ route('provider.hosts.view', ['host_id' => $host_details->id]) }}">
                                                {{$b+1}}
                                            </a>
                                        </td>
                                        <td>

                                            <div>
                                                <a href="{{ route('provider.hosts.view', ['host_id' => $host_details->id]) }}" class="room-list-img">
                                                    <img src="{{$host_details->picture}}">
                                                </a>
                                                
                                                <div class="room-list-content">
                                                    <a href="{{ route('provider.hosts.view', ['host_id' => $host_details->id]) }}" class="room-list-tit">&nbsp;{{$host_details->host_name}}</a>
                                                </div>

                                                <p>&nbsp;{{common_date($host_details->updated_at)}}</p>
                                            </div>

                                        </td>

                                        <td>{{$host_details->full_address}}</td>
                                        <td>{{$host_details->total_guests}}</td>

                                        <td>
                                            @if($host_details->admin_status == ADMIN_HOST_APPROVED) 

                                                <span class="badge status-btn badge-success">
                                                    {{ tr('approved') }} 
                                                </span>

                                            @else

                                                <span class="badge status-btn badge-danger">
                                                    {{ tr('pending') }} 
                                                </span>

                                            @endif
                                        </td>

                                        <td>
                                            <ul class="action-menu nav">
                                                <li class="nav-item dropdown">
                                                    <a class="dropdown-toggle action-menu-icon" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                        <img src="{{ asset('menu.svg')}}">
                                                    </a>
                                                    <div class="dropdown-menu dropdown-menu-right animate slideIn" aria-labelledby="navbarDropdown">
                                                        <a class="dropdown-item" href="{{ route('provider.hosts.edit', ['host_id' => $host_details->id]) }}"><i class="far fa-edit"></i>
                                                            {{tr('edit')}}
                                                        </a>
                                                        <a class="dropdown-item" onclick="return confirm(&quot;{{tr('host_delete_confirmation' , $host_details->host_name)}}&quot;);" href="{{ route('provider.hosts.delete', ['host_id' => $host_details->id]) }}"><i class="fas fa-trash-alt"></i>
                                                            {{ tr('delete') }}
                                                        </a>

                                                        <a class="dropdown-item" href="{{ route('provider.hosts.view', ['host_id' => $host_details->id]) }}"><i class="fas fa-eye"></i>
                                                            {{tr('view')}}
                                                        </a>
                                                        
                                                    </div>
                                                </li>
                                            </ul>
                                        </td>                                      
                                    </tr>

                                @endforeach
                                
                            </tbody>
                        
                        </table>

                        <div class="col-md-12">

                            <ul class="home-pagination">

                                {{ $hosts->links() }}

                            </ul>

                        </div>

                    @else 

                    <p>{{tr('no_result_found')}}</p>

                    @endif
                </div>
            </div>
            <!-- Room Content Ends -->
        </div>
    </div>
</div>

@endsection