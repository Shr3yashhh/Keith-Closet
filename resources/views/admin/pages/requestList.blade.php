@extends('admin.main')
@section('content')
<h4 class="mt-2"><i class="fa fa-users mr-1 "></i> Your request lists</h4>
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
      {{-- <a class="nav-link" id="nav-contact-tab" data-toggle="tab" href="#nav-cancelled" role="tab" aria-controls="nav-contact" aria-selected="false">Cancelled</a> --}}
    </div>
  </nav>
  <div class="tab-content" id="nav-tabContent">
    <div class="tab-pane fade show active" id="nav-all" role="tabpanel" aria-labelledby="nav-profile-tab">
      @if (count($requested_services) > 0)
      <table class="table table-bordered table-hover">
        <tr>
          <th>SN</th>
          <th>Requested By</th>
          <th>Location</th>
          <th>Status</th>
          <th>Requested Date</th>
          <th>User Rating</th>
          {{-- <th>Actions</th> --}}
        </tr>
        @foreach ($requested_services as $key => $request)
        <tr  class=" @if ($request->id == $hilight_id)
          bg-success p-2
      @endif ">
          <td>{{ ++$key }}</td>
          <td>{{ $request->user->name }}</td>
          
         <td>
          @php
          $url = "#";
         @endphp
        @if ($request->requestedUser?->current_latitude != null && $request->requestedUser?->current_longitude != null)
        @php
            
        $url = "https://maps.google.com/?q=".$request->requestedUser?->current_latitude.",".$request->requestedUser?->current_longitude;
        @endphp
         @endif   
            <a href="{{ $url }}" target="_blank">Follow Link</a></td>
        
         
          <td>
            @if($request->status == 'pending')
           <button data-toggle="modal" data-target="#allModal{{$key}}" class=" btn btn-sm btn-warning">{{ $request->status }}</button>
            @elseif($request->status == 'confirmed')
           <button data-toggle="modal" data-target="#allModal{{$key}}" class="btn btn-sm btn-success">{{ $request->status }}</button>
           @elseif($request->status == 'rejected')
           <button data-toggle="modal" data-target="#allModal{{$key}}" class="btn btn-sm btn-danger">{{ $request->status }}</button> 
            {{-- @elseif($request->status == 'cancelled')
           <button data-toggle="modal" data-target="#allModal{{$key}}" class="btn btn-sm btn-danger">{{ $request->status }}</button> --}}
            @endif

            {{-- modal start --}}
            <div class="modal fade" id="allModal{{$key}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
              <div class="modal-dialog">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Choose status</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                  <form action="{{route('provider.request.manage',$request->id)}}" method="POST">
                  <div class="modal-body">
                      @csrf
                      @method('PATCH')
                      <div class="form-group">
                       
                        <select name="status" id="status" class="form-control">
                          <option value="pending" @if ($request->status == 'pending') selected @endif>Pending</option>
                          <option value="confirmed" @if ($request->status == 'confirmed') selected @endif>Confirm</option>
                          <option value="rejected" @if ($request->status == 'rejected') selected @endif>Reject</option>
                          {{-- <option value="cancelled" @if ($request->status == 'cancelled') selected @endif>Cancel</option> --}}
                        </select>
                   
                  </div>
                  <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Change Status</button>
                  </div>
                 </form>
                </div>
              </div>
            </div>
            
            {{-- modal end --}}
          </td>
            <td>
              <?php 
                $time_diff = strtotime(now()) - strtotime($request->created_at);
                $days = floor($time_diff / (60 * 60 * 24));
                $hours = floor(($time_diff - $days * 60 * 60 * 24) / (60 * 60));
                $minutes = floor(($time_diff - $days * 60 * 60 * 24 - $hours * 60 * 60) / 60);
                $seconds = floor(($time_diff - $days * 60 * 60 * 24 - $hours * 60 * 60 - $minutes * 60));
                if($days > 0){
                  echo $days." days ago";
                }elseif($hours > 0){
                  echo $hours." hours ago";
                }elseif($minutes > 0){
                  echo $minutes." minutes ago";
                }elseif($seconds > 0){
                  echo $seconds." seconds ago";
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


                
                
            </td>
         
      
        @else
        <td>
            <span class="p-2 badge badge-danger">No ratings yet.</span>
        </td>
         
        @endif
          {{-- <td>
            <a onclick="return confirm('Are you sure?')" href="{{ route('provider.request.soft_delete', $request->id) }}" class="btn btn-danger btn-sm">Delete</a>
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
    <div class="tab-pane fade  " id="nav-pending" role="tabpanel" aria-labelledby="nav-home-tab">
        @if (count($pending_services) > 0)
        <table class="table table-bordered table-hover">
            <tr>
            <th>SN</th>
            <th>Requested By</th>
            <th>Location</th>
            <th>Status</th>
            <th>Requested Date</th>
            {{-- <th>Actions</th> --}}
            </tr>
            @foreach ($pending_services as $key => $request)
            <tr>
            <td>{{ ++$key }}</td>
            <td>{{ $request->user->name }}</td>
           <td>
          @php
          $url = "#";
         @endphp
        @if ($request->requestedUser?->current_latitude != null && $request->requestedUser?->current_longitude != null)
        @php
            
        $url = "https://maps.google.com/?q=".$request->requestedUser?->current_latitude.",".$request->requestedUser?->current_longitude;
        @endphp
         @endif   
            <a href="{{ $url }}" target="_blank">Follow Link</a></td>
            
            <td>
              @if($request->status == 'pending')
             <button data-toggle="modal" data-target="#pendingModal{{$key}}" class=" btn btn-sm btn-warning">{{ $request->status }}</button>
              @elseif($request->status == 'confirmed')
             <button data-toggle="modal" data-target="#pendingModal{{$key}}" class="btn btn-sm btn-success">{{ $request->status }}</button>
             @elseif($request->status == 'rejected')
             <button data-toggle="modal" data-target="#pendingModal{{$key}}" class="btn btn-sm btn-danger">{{ $request->status }}</button> 
              {{-- @elseif($request->status == 'cancelled')
             <button data-toggle="modal" data-target="#pendingModal{{$key}}" class="btn btn-sm btn-danger">{{ $request->status }}</button> --}}
              @endif
  
              {{-- modal start --}}
              <div class="modal fade" id="pendingModal{{$key}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title" id="exampleModalLabel">Choose status</h5>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>
                    <form action="{{route('provider.request.manage',$request->id)}}" method="POST">
                    <div class="modal-body">
                        @csrf
                        @method('PATCH')
                        <div class="form-group">
                         
                          <select name="status" id="status" class="form-control">
                            <option value="pending" @if ($request->status == 'pending') selected @endif>Pending</option>
                            <option value="confirmed" @if ($request->status == 'confirmed') selected @endif>Confirm</option>
                            <option value="rejected" @if ($request->status == 'rejected') selected @endif>Reject</option>
                            {{-- <option value="cancelled" @if ($request->status == 'cancelled') selected @endif>Cancel</option> --}}
                          </select>
                     
                    </div>
                    <div class="modal-footer">
                      <button type="submit" class="btn btn-primary">Change Status</button>
                    </div>
                   </form>
                  </div>
                </div>
              </div>
              
              {{-- modal end --}}
            </td>
                 <td>
              <?php 
                $time_diff = strtotime(now()) - strtotime($request->created_at);
                $days = floor($time_diff / (60 * 60 * 24));
                $hours = floor(($time_diff - $days * 60 * 60 * 24) / (60 * 60));
                $minutes = floor(($time_diff - $days * 60 * 60 * 24 - $hours * 60 * 60) / 60);
                $seconds = floor(($time_diff - $days * 60 * 60 * 24 - $hours * 60 * 60 - $minutes * 60));
                if($days > 0){
                  echo $days." days ago";
                }elseif($hours > 0){
                  echo $hours." hours ago";
                }elseif($minutes > 0){
                  echo $minutes." minutes ago";
                }elseif($seconds > 0){
                  echo $seconds." seconds ago";
                }
                
                ?>
            </td>
            {{-- <td>
                <a onclick="return confirm('Are you sure?')" href="{{ route('provider.request.soft_delete', $request->id) }}" class="btn btn-danger btn-sm">Delete</a>
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
   
  
    <div class="tab-pane fade" id="nav-confirmed" role="tabpanel" aria-labelledby="nav-contact-tab">
        @if (count($confirmed_services) > 0)
        <table class="table table-bordered table-hover">
            <tr>
            <th>SN</th>
            <th>Requested By</th>
            <th>Location</th>
            <th>Status</th>
            <th>Requested Date</th>
            <th>User Rating</th>
            {{-- <th>Actions</th> --}}
            </tr>
            @foreach ($confirmed_services as $key => $request)
            <tr>
            <td>{{ ++$key }}</td>
            <td>{{ $request->user->name }}</td>
           <td>
          @php
          $url = "#";
         @endphp
        @if ($request->requestedUser?->current_latitude != null && $request->requestedUser?->current_longitude != null)
        @php
            
        $url = "https://maps.google.com/?q=".$request->requestedUser?->current_latitude.",".$request->requestedUser?->current_longitude;
        @endphp
         @endif   
            <a href="{{ $url }}" target="_blank">Follow Link</a></td>
            
            <td>
              @if($request->status == 'pending')
             <button data-toggle="modal" data-target="#confirmedModal{{$key}}" class=" btn btn-sm btn-warning">{{ $request->status }}</button>
              @elseif($request->status == 'confirmed')
             <button data-toggle="modal" data-target="#confirmedModal{{$key}}" class="btn btn-sm btn-success">{{ $request->status }}</button>
             @elseif($request->status == 'rejected')
             <button data-toggle="modal" data-target="#confirmedModal{{$key}}" class="btn btn-sm btn-danger">{{ $request->status }}</button> 
              {{-- @elseif($request->status == 'cancelled')
             <button data-toggle="modal" data-target="#confirmedModal{{$key}}" class="btn btn-sm btn-danger">{{ $request->status }}</button> --}}
              @endif
  
              {{-- modal start --}}
              <div class="modal fade" id="confirmedModal{{$key}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title" id="exampleModalLabel">Choose status</h5>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>
                    <form action="{{route('provider.request.manage',$request->id)}}" method="POST">
                    <div class="modal-body">
                        @csrf
                        @method('PATCH')
                        <div class="form-group">
                         
                          <select name="status" id="status" class="form-control">
                            <option value="pending" @if ($request->status == 'pending') selected @endif>Pending</option>
                            <option value="confirmed" @if ($request->status == 'confirmed') selected @endif>Confirm</option>
                            <option value="rejected" @if ($request->status == 'rejected') selected @endif>Reject</option>
                            {{-- <option value="cancelled" @if ($request->status == 'cancelled') selected @endif>Cancel</option> --}}
                          </select>
                     
                    </div>
                    <div class="modal-footer">
                      <button type="submit" class="btn btn-primary">Change Status</button>
                    </div>
                   </form>
                  </div>
                </div>
              </div>
              
              {{-- modal end --}}
            </td>
                 <td>
              <?php 
                $time_diff = strtotime(now()) - strtotime($request->created_at);
                $days = floor($time_diff / (60 * 60 * 24));
                $hours = floor(($time_diff - $days * 60 * 60 * 24) / (60 * 60));
                $minutes = floor(($time_diff - $days * 60 * 60 * 24 - $hours * 60 * 60) / 60);
                $seconds = floor(($time_diff - $days * 60 * 60 * 24 - $hours * 60 * 60 - $minutes * 60));
                if($days > 0){
                  echo $days." days ago";
                }elseif($hours > 0){
                  echo $hours." hours ago";
                }elseif($minutes > 0){
                  echo $minutes." minutes ago";
                }elseif($seconds > 0){
                  echo $seconds." seconds ago";
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


                
                
            </td>
         
      
        @else
        <td>
            <span class="p-2 badge badge-danger">No ratings yet.</span>
        </td>
         
        @endif
            {{-- <td>
                <a onclick="return confirm('Are you sure?')" href="{{ route('provider.request.soft_delete', $request->id) }}" class="btn btn-danger btn-sm">Delete</a>
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

    <div class="tab-pane fade" id="nav-rejected" role="tabpanel" aria-labelledby="nav-contact-tab">
        @if (count($rejected_services) > 0)
        <table class="table table-bordered table-hover">
            <tr>
            <th>SN</th>
            <th>Requested By</th>
            <th>Location</th>
            <th>Status</th>
            <th>Requested Date</th>
            {{-- <th>Actions</th> --}}
            </tr>
            @foreach ($rejected_services as $key => $request)
            <tr>
            <td>{{ ++$key }}</td>
            <td>{{ $request->user->name }}</td>
           <td>
          @php
          $url = "#";
         @endphp
        @if ($request->requestedUser?->current_latitude != null && $request->requestedUser?->current_longitude != null)
        @php
            
        $url = "https://maps.google.com/?q=".$request->requestedUser?->current_latitude.",".$request->requestedUser?->current_longitude;
        @endphp
         @endif   
            <a href="{{ $url }}" target="_blank">Follow Link</a></td>
            
            <td>
              @if($request->status == 'pending')
             <button data-toggle="modal" data-target="#rejectedModal{{$key}}" class=" btn btn-sm btn-warning">{{ $request->status }}</button>
              @elseif($request->status == 'confirmed')
             <button data-toggle="modal" data-target="#rejectedModal{{$key}}" class="btn btn-sm btn-success">{{ $request->status }}</button>
             @elseif($request->status == 'rejected')
             <button data-toggle="modal" data-target="#rejectedModal{{$key}}" class="btn btn-sm btn-danger">{{ $request->status }}</button> 
              {{-- @elseif($request->status == 'cancelled')
             <button data-toggle="modal" data-target="#rejectedModal{{$key}}" class="btn btn-sm btn-danger">{{ $request->status }}</button> --}}
              @endif
  
              {{-- modal start --}}
              <div class="modal fade" id="rejectedModal{{$key}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title" id="exampleModalLabel">Choose status</h5>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>
                    <form action="{{route('provider.request.manage',$request->id)}}" method="POST">
                    <div class="modal-body">
                        @csrf
                        @method('PATCH')
                        <div class="form-group">
                         
                          <select name="status" id="status" class="form-control">
                            <option value="pending" @if ($request->status == 'pending') selected @endif>Pending</option>
                            <option value="confirmed" @if ($request->status == 'confirmed') selected @endif>Confirm</option>
                            <option value="rejected" @if ($request->status == 'rejected') selected @endif>Reject</option>
                            {{-- <option value="cancelled" @if ($request->status == 'cancelled') selected @endif>Cancel</option> --}}
                          </select>
                     
                    </div>
                    <div class="modal-footer">
                      <button type="submit" class="btn btn-primary">Change Status</button>
                    </div>
                   </form>
                  </div>
                </div>
              </div>
              
              {{-- modal end --}}
            </td>
                 <td>
              <?php 
                $time_diff = strtotime(now()) - strtotime($request->created_at);
                $days = floor($time_diff / (60 * 60 * 24));
                $hours = floor(($time_diff - $days * 60 * 60 * 24) / (60 * 60));
                $minutes = floor(($time_diff - $days * 60 * 60 * 24 - $hours * 60 * 60) / 60);
                $seconds = floor(($time_diff - $days * 60 * 60 * 24 - $hours * 60 * 60 - $minutes * 60));
                if($days > 0){
                  echo $days." days ago";
                }elseif($hours > 0){
                  echo $hours." hours ago";
                }elseif($minutes > 0){
                  echo $minutes." minutes ago";
                }elseif($seconds > 0){
                  echo $seconds." seconds ago";
                }
                
                ?>
            </td>
            {{-- <td>
                <a onclick="return confirm('Are you sure?')" href="{{ route('provider.request.soft_delete', $request->id) }}" class="btn btn-danger btn-sm">Delete</a>
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
  
     
    <div class="tab-pane fade" id="nav-cancelled" role="tabpanel" aria-labelledby="nav-contact-tab">
        @if (count($cancelled_services) > 0)
        <table class="table table-bordered table-hover">
            <tr>
            <th>SN</th>
            <th>Requested By</th>
            <th>Location</th>
            <th>Status</th>
            <th>Requested Date</th>
            {{-- <th>Actions</th> --}}
            </tr>
            @foreach ($cancelled_services as $key => $request)
            <tr>
            <td>{{ ++$key }}</td>
            <td>{{ $request->user->name }}</td>
           <td>
          @php
          $url = "#";
         @endphp
        @if ($request->requestedUser?->current_latitude != null && $request->requestedUser?->current_longitude != null)
        @php
            
        $url = "https://maps.google.com/?q=".$request->requestedUser?->current_latitude.",".$request->requestedUser?->current_longitude;
        @endphp
         @endif   
            <a href="{{ $url }}" target="_blank">Follow Link</a></td>
            
            <td>
              @if($request->status == 'pending')
             <button data-toggle="modal" data-target="#cancelledModal{{$key}}" class=" btn btn-sm btn-warning">{{ $request->status }}</button>
              @elseif($request->status == 'confirmed')
             <button data-toggle="modal" data-target="#cancelledModal{{$key}}" class="btn btn-sm btn-success">{{ $request->status }}</button>
             @elseif($request->status == 'rejected')
             <button data-toggle="modal" data-target="#cancelledModal{{$key}}" class="btn btn-sm btn-danger">{{ $request->status }}</button> 
              {{-- @elseif($request->status == 'cancelled')
             <button data-toggle="modal" data-target="#cancelledModal{{$key}}" class="btn btn-sm btn-danger">{{ $request->status }}</button> --}}
              @endif
  
              {{-- modal start --}}
              {{-- <div class="modal fade" id="cancelledModal{{$key}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title" id="exampleModalLabel">Choose status</h5>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>
                    <form action="{{route('provider.request.manage',$request->id)}}" method="POST">
                    <div class="modal-body">
                        @csrf
                        @method('PATCH')
                        <div class="form-group">
                         
                          <select name="status" id="status" class="form-control">
                            <option value="pending" @if ($request->status == 'pending') selected @endif>Pending</option>
                            <option value="confirmed" @if ($request->status == 'confirmed') selected @endif>Confirm</option>
                            <option value="rejected" @if ($request->status == 'rejected') selected @endif>Reject</option>
                            <option value="cancelled" @if ($request->status == 'cancelled') selected @endif>Cancel</option>
                          </select>
                     
                    </div>
                    <div class="modal-footer">
                      <button type="submit" class="btn btn-primary">Change Status</button>
                    </div>
                   </form>
                  </div>
                </div>
              </div> --}}
              
              {{-- modal end --}}
            </td>
                 <td>
              <?php 
                $time_diff = strtotime(now()) - strtotime($request->created_at);
                $days = floor($time_diff / (60 * 60 * 24));
                $hours = floor(($time_diff - $days * 60 * 60 * 24) / (60 * 60));
                $minutes = floor(($time_diff - $days * 60 * 60 * 24 - $hours * 60 * 60) / 60);
                $seconds = floor(($time_diff - $days * 60 * 60 * 24 - $hours * 60 * 60 - $minutes * 60));
                if($days > 0){
                  echo $days." days ago";
                }elseif($hours > 0){
                  echo $hours." hours ago";
                }elseif($minutes > 0){
                  echo $minutes." minutes ago";
                }elseif($seconds > 0){
                  echo $seconds." seconds ago";
                }
                
                ?>
            </td>
            {{-- <td>
                <a onclick="return confirm('Are you sure?')" href="{{ route('provider.request.restore', $request->id) }}" class="btn btn-danger btn-sm">Restore</a>

                <a onclick="return confirm('Are you sure?')" href="{{ route('provider.request.delete', $request->id) }}" class="btn btn-danger btn-sm">Delete</a>
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
  </div>

  
@endsection