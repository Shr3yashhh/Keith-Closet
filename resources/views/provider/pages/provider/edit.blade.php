@extends('provider.main')
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
                        <h3 class="card-title"><b><i class="fa fa-edit mr-2"></i>Edit User Page</b><a class="ml-5" href="{{route('provider.changePassword',$provider->id)}}">Change Password</a></h3>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            @if ($provider->avatar != null)
                            <div class="modal fade" id="viewAvatar" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                  <div class="modal-content">
                                    <div class="modal-header">
                                      <h5 class="modal-title" id="exampleModalLabel">Avatar of {{$provider->name}}</h5>
                                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                      </button>
                                    </div>
                                        <div class="modal-body">
                                        
                                            <img src="{{ url('provider_avatar/'.$provider->avatar) }}" class="img-fluid"/>

                                        
                                        </div>
                                       
                                  </div>
                                </div>
                            </div>
                            @endif
                            @if ($provider->avatar != null)
                            <img data-toggle="modal" data-target="#viewAvatar" style="cursor: pointer;" src="{{ url('provider_avatar/'.$provider->avatar) }}" alt="{{ $provider->name }}" class="img-thumbnail provider-profile" width="100px" height="100px">
                            @else
                            <img data-toggle="modal" data-target="#viewAvatar" style="cursor: pointer;" src="{{ url('provider_avatar/default.jpg') }}" alt="{{ $provider->name }}" class="img-thumbnail provider-profile" width="100px" height="100px">
                                
                            @endif
                            <a style="cursor: pointer;" data-toggle="modal" data-target="#updateProviderProfile"><i class="fa fa-edit fa-2x ml-3" ></i> Edit Profile</a>
                        </div>
                        <form action="{{ route('providers.update', $provider->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                           
                            <div class="modal fade" id="updateProviderProfile" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
                                <input type="text" class="form-control" id="name" name="name" value="{{ $provider->name }}">
                            </div>
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email" class="form-control" id="email" name="email" value="{{ $provider->email }}">
                            </div>
                           <div class="form-group">
                            <div class="row">
                                <div class="col-lg-8 col-md-8 col-sm-8">
                                    <label for="citizenship">Citizenship</label>
                                    <input type="file" class="form-control" id="citizenship" name="citizenship">
                                   
                                </div>
                               @if ($provider->citizenship != null)
                               <div class="col-lg-4 col-md-4 col-sm-4">
                                <label for="">&nbsp;</label>
                                <p><a class="btn btn-outline-primary" data-toggle="modal" data-target="#viewCitizenship" >View Citizenship</a></p>
                                 </div>
                              
                                   
                               @endif
                            </div>
                            @if ($provider->citizenship != null)
                            <div class="modal fade" id="viewCitizenship" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                  <div class="modal-content">
                                    <div class="modal-header">
                                      <h5 class="modal-title" id="exampleModalLabel">Citizenship of {{$provider->name}}</h5>
                                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                      </button>
                                    </div>
                                        <div class="modal-body">
                                        
                                            <img src="{{ url('provider_citizenship/'.$provider->citizenship) }}" class="img-fluid"/>

                                        
                                        </div>
                                       
                                  </div>
                                </div>
                            </div>
                            @endif
                           </div>
                           
                            <div class="form-group">
                                <label for="role">Role</label>
                                <select name="profession_id" id="role" class="form-control">
                                    @foreach ($professions as $profession)
                                        
                                        <option value="{{ $profession->id }}" {{ $profession->id == $provider->profession_id ? 'selected' : '' }}>{{ $profession->name }}</option>

                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="phone">Phone</label>
                                <input type="text" class="form-control" id="phone" name="phone" value="{{ $provider->phone }}">
                            </div>
                            <div class="form-group">
                                <label for="address">Address</label>
                                <input type="text" class="form-control" id="address" name="address" value="{{ $provider->address }}">
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
