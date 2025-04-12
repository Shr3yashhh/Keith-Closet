@extends('user.main')
@section('content')


<div class="cart">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        @if (session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                            
                        @endif
                        @if (session('error'))
                            <div class="alert alert-danger">
                                {{ session('error') }}
                            </div>
                            
                        @endif
                        <h3 class="card-title"><b><i class="fa fa-edit mr-2"></i>Edit User Page</b><a class="ml-5" href="{{route('user.changePassword',$user->id)}}">Change Password</a></h3>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            @if ($user->avatar != null)
                            <div class="modal fade" id="viewAvatar" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                  <div class="modal-content">
                                    <div class="modal-header">
                                      <h5 class="modal-title" id="exampleModalLabel">Avatar of {{$user->name}}</h5>
                                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                      </button>
                                    </div>
                                        <div class="modal-body">
                                        
                                            <img src="{{ url('user_avatar/'.$user->avatar) }}" class="img-fluid"/>

                                        
                                        </div>
                                       
                                  </div>
                                </div>
                            </div>
                            @endif
                            @if ($user->avatar != null)
                            <img data-toggle="modal" data-target="#viewAvatar" style="cursor: pointer;" src="{{ url('user_avatar/'.$user->avatar) }}" alt="{{ $user->name }}" class="img-thumbnail user-profile" width="100px" height="100px">
                            @else
                            <img data-toggle="modal" data-target="#viewAvatar" style="cursor: pointer;" src="{{ url('user_avatar/default.jpg') }}" alt="{{ $user->name }}" class="img-thumbnail user-profile" width="100px" height="100px">
                                
                            @endif
                            <a style="cursor: pointer;" data-toggle="modal" data-target="#updateuserProfile"><i class="fa fa-edit fa-2x ml-3" ></i> Edit Profile</a>
                        </div>
                        <form action="{{ route('users.update', $user->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                           
                            <div class="modal fade" id="updateuserProfile" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                  <div class="modal-content">
                                    <div class="modal-header">
                                      <h5 class="modal-title" id="exampleModalLabel">Upload New Profile Picture</h5>
                                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                      </button>
                                    </div>
                                        <div class="modal-body">
                                        
                                          
                                            <div class="form-group">
                                                <input type="file" class="form-control" id="avatar" name="avatar">
                                                @error('avatar')
                                                    <span class="text-danger">{{ $message }}</span>
                                                    
                                                @enderror
                                            </div>
                                        
                                        
                                        </div>
                                        <div class="modal-footer">
                                        <button type="submit" class="btn btn-primary">Save changes</button>
                                        </div>
                                  </div>
                                </div>
                            </div>
                            

                            <div class="form-group">
                                
                                <label for="name">Name</label>
                                <input type="text" class="form-control" id="name" name="name" value="{{ $user->name }}">
                            </div>
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email" class="form-control" id="email" name="email" value="{{ $user->email }}">
                            </div>
                          
                           

                            <div class="form-group">
                                <label for="phone">Phone</label>
                                <input type="text" class="form-control" id="phone" name="phone" value="{{ $user->phone }}">
                            </div>
                            <div class="form-group">
                                <label for="address">Address</label>
                                <input type="text" class="form-control" id="address" name="address" value="{{ $user->address }}">
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary">Update</button>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


@endsection
