@extends('user.main')
@section('content')
<h4 class="mt-2"><i class="fa fa-users mr-1 "></i> Your request history</h4>
@if (Session::has('success'))
<div class="alert alert-success">
    {{ Session::get('success') }}
</div>
@endif
@if (Session::has('error'))
<div class="alert alert-danger">
    {{ Session::get('error') }}
</div>  
@endif
<hr>
<nav>
    <div class="nav nav-tabs" id="nav-tab" role="tablist">
      <a class="nav-link active" id="nav-profile-tab" data-toggle="tab" href="#nav-all" role="tab" aria-controls="nav-profile" aria-selected="false">All</a>
      <a class="nav-link " id="nav-home-tab" data-toggle="tab" href="#nav-pending" role="tab" aria-controls="nav-home" aria-selected="true">Pending</a>
      <a class="nav-link" id="nav-contact-tab" data-toggle="tab" href="#nav-confirmed" role="tab" aria-controls="nav-contact" aria-selected="false">Confirmed</a>
      <a class="nav-link" id="nav-contact-tab" data-toggle="tab" href="#nav-rejected" role="tab" aria-controls="nav-contact" aria-selected="false">Rejected</a>
      <a class="nav-link" id="nav-contact-tab" data-toggle="tab" href="#nav-canceled" role="tab" aria-controls="nav-contact" aria-selected="false">Canceled</a>
      <a class="nav-link" id="nav-contact-tab" data-toggle="tab" href="#nav-trashed" role="tab" aria-controls="nav-contact" aria-selected="false">Trashed</a>
    </div>
  </nav>
  <div class="tab-content" id="nav-tabContent">
    <div class="tab-pane fade show active" id="nav-all" role="tabpanel" aria-labelledby="nav-profile-tab">
      @if (count($requested_services) > 0)
      <table class="table table-bordered table-hover">
        <tr>
          <th>SN</th>
          <th>Requested to</th>
          <th>Profession</th>
          <th>Status</th>
          <th>Requested time</th>
            <th>Give Rating</th>
           
         
        </tr>
        @foreach ($requested_services as $key => $request)
        <tr class=" @if ($request->id == $hilight_id)
            bg-success p-2
        @endif ">
          <td>{{ ++$key }}</td>
          <td>{{ $request->provider->name }}
        
       
        
        </td>
          <td>{{$request->provider->profession->name}}</td>
        
          <td>
            @if($request->status == 'pending')
            <span class="badge badge-warning">Pending</span>
            @elseif($request->status == 'confirmed')
            <span class="badge badge-success">Confirmed</span>
            @elseif($request->status == 'rejected')
            <span class="badge badge-danger">Rejected</span>
            @endif

          </td>
            <td>
                <?php 
                    $time_diff =  strtotime(now()) - strtotime($request->created_at);
                    $days = floor($time_diff / (60 * 60 * 24)); 
                    $hours = floor(($time_diff - $days * 60 * 60 * 24) / (60 * 60));
                    $minutes = floor(($time_diff - $days * 60 * 60 * 24 - $hours * 60 * 60) / 60);
                    $seconds = floor(($time_diff - $days * 60 * 60 * 24 - $hours * 60 * 60 - $minutes * 60));
                    if($days > 0){
                        echo $days.' days ago';
                    }elseif($hours > 0){
                        echo $hours.' hours ago';
                    }elseif($minutes > 0){
                        echo $minutes.' minutes ago';
                    }elseif($seconds > 0){
                        echo $seconds.' seconds ago';
                    }

                    ?>
            </td>
            @if ($request->status == 'confirmed' && $request->rating >0)
                <td>
                        <?php $primary_stars = $request->rating;
                        $secondary_stars = 5 - $primary_stars;
                        ?>
                        @if ($primary_stars > 0)
                        @for ($i = 0; $i < $primary_stars; $i++)
                        <i class="fa fa-star text-warning"></i>
                        @endfor
                        @endif
                        @if ($secondary_stars > 0)
                        @for ($i = 0; $i < $secondary_stars; $i++)
                        <i class="fa fa-star text-secondary"></i>
                        @endfor
                        @endif
                        <span class=" badge badge-success" style="float: right;">Already rated</span>


                    
                    
                </td>
             
            @elseif ($request->status == 'confirmed')
             
            <td>
                <style>
                    .star{
                        visibility: hidden;
                    }
                </style>
            <form class="form-horizontal poststars" action="{{route('user.request.provider_rating', $request->id)}}" id="addStar" method="POST">
                {{ csrf_field() }}
                      <div class="form-group required">
                        <div class="col-sm-12">
                            <label class=" star-5" for="star-5"><i class="fa fa-star"></i></label>
                          <input class="star star-5" value="1" id="star-5" type="radio" name="rating"/>
                          <label class=" star-4" for="star-4"><i class="fa fa-star"></i></label>
                          <input class="star star-4" value="2" id="star-4" type="radio" name="rating"/>
                          <label class=" star-3" for="star-3"><i class="fa fa-star"></i></label>
                          <input class="star star-3" value="3" id="star-3" type="radio" name="rating"/>
                          <label class=" star-2" for="star-2"><i class="fa fa-star"></i></label>
                          <input class="star star-2" value="4" id="star-2" type="radio" name="rating"/>
                          <label class=" star-1" for="star-1"><i class="fa fa-star"></i></label>
                          <input class="star star-1" value="5" id="star-1" type="radio" name="rating"/>
                          <span class=" badge badge-warning" style="float: right;">Not rated</span>

                         </div>
                      </div>
              </form>
            </td>
            <script>
                $('#addStar').change('.star', function(e) {
                $(this).submit();
                });
            </script>
            @else
            <td>
                <span class="p-2 badge badge-danger">Your request is not confirmed yet.</span>
            </td>
             
            @endif
        
           
        </tr>
        @endforeach
      </table>
      @else
      <p class="mt-3" style="color: red">
        There are no request yet.
      </p>
      @endif
    </div>
    <div class="tab-pane fade  " id="nav-pending" role="tabpanel" aria-labelledby="nav-home-tab">
        @if (count($pending_services) > 0)
        <table class="table table-bordered table-hover">
            <tr>
            <th>SN</th>
            <th>Requested to</th>
            <th>Profession</th>
            <th>Status</th>
            <th>Requested time</th>
            <th>Actions</th>
            </tr>
            @foreach ($pending_services as $key => $request)
            <tr>
            <td>{{ ++$key }}</td>
            <td>{{ $request->provider->name }}</td>
            <td>{{$request->provider->profession->name}}</td>
            
            <td>
                @if($request->status == 'pending')
                <span class="badge badge-warning">Pending</span>
                @elseif($request->status == 'confirmed')
                <span class="badge badge-success">Confirmed</span>
                @elseif($request->status == 'rejected')
                <span class="badge badge-danger">Rejected</span>
                @endif
    
            </td>
                <td>
                <?php 
                    $time_diff = strtotime(now()) - strtotime($request->created_at);
                    $days = floor($time_diff / (60 * 60 * 24)); 
                    $hours = floor(($time_diff - $days * 60 * 60 * 24) / (60 * 60));
                    $minutes = floor(($time_diff - $days * 60 * 60 * 24 - $hours * 60 * 60) / 60);
                    $seconds = floor(($time_diff - $days * 60 * 60 * 24 - $hours * 60 * 60 - $minutes * 60));
                    if($days > 0){
                        echo $days.' days ago';
                    }elseif($hours > 0){
                        echo $hours.' hours ago';
                    }elseif($minutes > 0){
                        echo $minutes.' minutes ago';
                    }elseif($seconds > 0){
                        echo $seconds.' seconds ago';
                    }

                    ?>
            </td>
            <td>
                <a href="{{route('user.request.manage',$request->id)}}" class="btn btn-warning btn-sm">Cancel</a>
                <a onclick="return confirm('Are you sure?')" href="{{ route('user.request.soft_delete', $request->id) }}" class="btn btn-danger btn-sm">Delete</a>
            </td>
            </tr>
            @endforeach
        </table>
        @else
        <p class="mt-3" style="color: red">
            There are no request yet.
        </p>
        @endif
    </div>
   
  
    <div class="tab-pane fade" id="nav-confirmed" role="tabpanel" aria-labelledby="nav-contact-tab">
        @if (count($confirmed_services) > 0)
        <table class="table table-bordered table-hover">
            <tr>
            <th>SN</th>
            <th>Requested to</th>
            <th>Profession</th>
            <th>Status</th>
            <th>Requested time</th>
            <th>Give Rating</th>
            </tr>
            @foreach ($confirmed_services as $key => $request)
            <tr>
            <td>{{ ++$key }}</td>
            <td>{{ $request->provider->name }}</td>
            <td>{{$request->provider->profession->name}}</td>
            
            <td>
                @if($request->status == 'pending')
                <span class="badge badge-warning">Pending</span>
                @elseif($request->status == 'confirmed')
                <span class="badge badge-success">Confirmed</span>
                @elseif($request->status == 'rejected')
                <span class="badge badge-danger">Rejected</span>
                @endif
    
            </td>
                <td>
                <?php 
                    $time_diff = strtotime(now()) - strtotime($request->created_at);
                    $days = floor($time_diff / (60 * 60 * 24)); 
                    $hours = floor(($time_diff - $days * 60 * 60 * 24) / (60 * 60));
                    $minutes = floor(($time_diff - $days * 60 * 60 * 24 - $hours * 60 * 60) / 60);
                    $seconds = floor(($time_diff - $days * 60 * 60 * 24 - $hours * 60 * 60 - $minutes * 60));
                    if($days > 0){
                        echo $days.' days ago';
                    }elseif($hours > 0){
                        echo $hours.' hours ago';
                    }elseif($minutes > 0){
                        echo $minutes.' minutes ago';
                    }elseif($seconds > 0){
                        echo $seconds.' seconds ago';
                    }

                    ?>
            </td>
            @if ($request->status == 'confirmed' && $request->rating >0)
                <td>
                        <?php $primary_stars = $request->rating;
                        $secondary_stars = 5 - $primary_stars;
                        ?>
                        @if ($primary_stars > 0)
                        @for ($i = 0; $i < $primary_stars; $i++)
                        <i class="fa fa-star text-warning"></i>
                        @endfor
                        @endif
                        @if ($secondary_stars > 0)
                        @for ($i = 0; $i < $secondary_stars; $i++)
                        <i class="fa fa-star text-secondary"></i>
                        @endfor
                        @endif
                        <span class=" badge badge-success" style="float: right;">Already rated</span>


                    
                    
                </td>
             
            @elseif ($request->status == 'confirmed')
             
            <td>
                <style>
                    .star{
                        visibility: hidden;
                    }
                </style>
            <form class="form-horizontal poststars" action="{{route('user.request.provider_rating', $request->id)}}" id="addStar" method="POST">
                {{ csrf_field() }}
                      <div class="form-group required">
                        <div class="col-sm-12">
                            <label class=" star-5" for="star-5"><i class="fa fa-star"></i></label>
                          <input class="star star-5" value="1" id="star-5" type="radio" name="rating"/>
                          <label class=" star-4" for="star-4"><i class="fa fa-star"></i></label>
                          <input class="star star-4" value="2" id="star-4" type="radio" name="rating"/>
                          <label class=" star-3" for="star-3"><i class="fa fa-star"></i></label>
                          <input class="star star-3" value="3" id="star-3" type="radio" name="rating"/>
                          <label class=" star-2" for="star-2"><i class="fa fa-star"></i></label>
                          <input class="star star-2" value="4" id="star-2" type="radio" name="rating"/>
                          <label class=" star-1" for="star-1"><i class="fa fa-star"></i></label>
                          <input class="star star-1" value="5" id="star-1" type="radio" name="rating"/>
                          <span class=" badge badge-warning" style="float: right;">Not rated</span>

                         </div>
                      </div>
              </form>
            </td>
            <script>
                $('#addStar').change('.star', function(e) {
                $(this).submit();
                });
            </script>
            @else
            <td>
                <span class="p-2 badge badge-danger">Your request is not confirmed yet.</span>
            </td>
             
            @endif
            </tr>
            @endforeach
        </table>
        @else
        <p class="mt-3" style="color: red">
            There are no request yet.
        </p>
        @endif
    </div>

    <div class="tab-pane fade" id="nav-rejected" role="tabpanel" aria-labelledby="nav-contact-tab">
        @if (count($rejected_services) > 0)
        <table class="table table-bordered table-hover">
            <tr>
            <th>SN</th>
            <th>Requested to</th>
            <th>Profession</th>
            <th>Status</th>
            <th>Requested time</th>
            {{-- <th>Action</th> --}}
            </tr>
            @foreach ($rejected_services as $key => $request)
            <tr>
            <td>{{ ++$key }}</td>
            <td>{{ $request->provider->name }}</td>
            <td>{{$request->provider->profession->name}}</td>
            
            <td>
                @if($request->status == 'pending')
                <span class="badge badge-warning">Pending</span>
                @elseif($request->status == 'confirmed')
                <span class="badge badge-success">Confirmed</span>
                @elseif($request->status == 'rejected')
                <span class="badge badge-danger">Rejected</span>
                @endif
    
            </td>
                <td>
                <?php 
                    $time_diff = strtotime(now()) - strtotime($request->created_at);
                    $days = floor($time_diff / (60 * 60 * 24)); 
                    $hours = floor(($time_diff - $days * 60 * 60 * 24) / (60 * 60));
                    $minutes = floor(($time_diff - $days * 60 * 60 * 24 - $hours * 60 * 60) / 60);
                    $seconds = floor(($time_diff - $days * 60 * 60 * 24 - $hours * 60 * 60 - $minutes * 60));
                    if($days > 0){
                        echo $days.' days ago';
                    }elseif($hours > 0){
                        echo $hours.' hours ago';
                    }elseif($minutes > 0){
                        echo $minutes.' minutes ago';
                    }elseif($seconds > 0){
                        echo $seconds.' seconds ago';
                    }

                    ?>
            </td>
            {{-- <td>
                <a onclick="return confirm('Are you sure?')" href="{{ route('user.request.soft_delete', $request->id) }}" class="btn btn-danger btn-sm">Delete</a>
            </td> --}}
            </tr>
            @endforeach
        </table>
        @else
        <p class="mt-3" style="color: red">
            There are no request yet.
        </p>
        @endif
      </div>
  
      <div class="tab-pane fade" id="nav-canceled" role="tabpanel" aria-labelledby="nav-contact-tab">
        @if (count($canceled_services) > 0)
        <table class="table table-bordered table-hover">
            <tr>
            <th>SN</th>
            <th>Requested to</th>
            <th>Profession</th>
            <th>Status</th>
            <th>Requested time</th>
            {{-- <th>Actions</th> --}}
            </tr>
            @foreach ($canceled_services as $key => $request)
            <tr>
            <td>{{ ++$key }}</td>
            <td>{{ $request->provider->name }}</td>
            <td>{{$request->provider->profession->name}}</td>
            
            <td>
                @if($request->status == 'pending')
                <span class="badge badge-warning">Pending</span>
                @elseif($request->status == 'confirmed')
                <span class="badge badge-success">Confirmed</span>
                @elseif($request->status == 'rejected')
                <span class="badge badge-danger">Rejected</span>
                @endif
    
            </td>
                <td>
                <?php 
                    $time_diff = strtotime(now()) - strtotime($request->created_at);
                    $days = floor($time_diff / (60 * 60 * 24)); 
                    $hours = floor(($time_diff - $days * 60 * 60 * 24) / (60 * 60));
                    $minutes = floor(($time_diff - $days * 60 * 60 * 24 - $hours * 60 * 60) / 60);
                    $seconds = floor(($time_diff - $days * 60 * 60 * 24 - $hours * 60 * 60 - $minutes * 60));
                    if($days > 0){
                        echo $days.' days ago';
                    }elseif($hours > 0){
                        echo $hours.' hours ago';
                    }elseif($minutes > 0){
                        echo $minutes.' minutes ago';
                    }elseif($seconds > 0){
                        echo $seconds.' seconds ago';
                    }

                    ?>
            </td>
            {{-- <td>
                <a onclick="return confirm('Are you sure?')" href="{{ route('user.request.soft_delete', $request->id) }}" class="btn btn-danger btn-sm">Delete</a>
            </td> --}}
            </tr>
            @endforeach
        </table>
        @else
        <p class="mt-3" style="color: red">
            There are no request yet.
        </p>
        @endif
      </div>
    <div class="tab-pane fade" id="nav-trashed" role="tabpanel" aria-labelledby="nav-contact-tab">
        @if (count($trashed_services) > 0)
        <table class="table table-bordered table-hover">
            <tr>
            <th>SN</th>
            <th>Requested to</th>
            <th>Profession</th>
            <th>Status</th>
            <th>Requested time</th>
            <th>Actions</th>
            </tr>
            @foreach ($trashed_services as $key => $request)
            <tr>
            <td>{{ ++$key }}</td>
            <td>{{ $request->provider->name }}</td>
            <td>{{$request->provider->profession->name}}</td>
            
            <td>
                @if($request->status == 'pending')
                <span class="badge badge-warning">Pending</span>
                @elseif($request->status == 'confirmed')
                <span class="badge badge-success">Confirmed</span>
                @elseif($request->status == 'rejected')
                <span class="badge badge-danger">Rejected</span>
                @endif
    
            </td>
                <td>
                <?php 
                    $time_diff = strtotime(now()) - strtotime($request->created_at);
                    $days = floor($time_diff / (60 * 60 * 24)); 
                    $hours = floor(($time_diff - $days * 60 * 60 * 24) / (60 * 60));
                    $minutes = floor(($time_diff - $days * 60 * 60 * 24 - $hours * 60 * 60) / 60);
                    $seconds = floor(($time_diff - $days * 60 * 60 * 24 - $hours * 60 * 60 - $minutes * 60));
                    if($days > 0){
                        echo $days.' days ago';
                    }elseif($hours > 0){
                        echo $hours.' hours ago';
                    }elseif($minutes > 0){
                        echo $minutes.' minutes ago';
                    }elseif($seconds > 0){
                        echo $seconds.' seconds ago';
                    }

                    ?>
            </td>
            <td>
                <a onclick="return confirm('Are you sure?')" href="{{ route('user.request.restore', $request->id) }}" class="btn btn-danger btn-sm">Restore</a>

                <a onclick="return confirm('Are you sure?')" href="{{ route('user.request.delete', $request->id) }}" class="btn btn-danger btn-sm">Delete</a>
            </td>
            </tr>
            @endforeach
        </table>
        @else
        <p class="mt-3" style="color: red">
            There are no request yet.
        </p>
        @endif
    </div>
  </div>

  
@endsection