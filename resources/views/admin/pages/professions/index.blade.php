@extends('admin.main')
@section('content')

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
                    <h3 class="card-title"> <i class="fas fa-list mr-2"></i><b>List of Users</b> </h3><a href="{{route('professions.create')}}" class="btn btn-sm btn-info " style="float: right !important;"> <i class="fas fa-plus mr-1"></i> Add Profession</a> 
                </div>
                <div class="card-body">
                 @if ($professions->count() > 0)
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>SN</th>
                                <th>Name</th>
                                <th>Avatar</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($professions as $profession)
                                <tr>
                                    <td>{{$profession->id}}</td>
                                    <td>{{$profession->name}}</td>
                                    <td>
                                        @if ($profession->avatar != null)
                                            <img src="{{url('profession_avatar/'.$profession->avatar)}}" alt="{{$profession->name}}" class="img-circle img-thumbnail " style="width: 70px;">
                                        @else
                                        <img src="{{url('profession_avatar/default.jpg')}}" alt="{{$profession->name}}" class="img-circle img-thumbnail" style="width: 70px;">
                                        @endif
                                    </td>
                                    <td>
                                        @if ($profession->status == 1)
                                            <a  onclick="return confirm('Are you sure?')" href="{{route('admin.professions.manage',$profession->id)}}" class="btn btn-sm btn-success">Active</a>
                                        @else
                                            <a onclick="return confirm('Are you sure?')" href="{{route('admin.professions.manage',$profession->id)}}" class="btn btn-sm btn-danger">Inactive</a>
                                       
                                            
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{route('professions.edit',$profession->id)}}" class="btn btn-sm btn-info"> <i class="fas fa-edit mr-1"></i> Edit</a>
                                        <form style="display: inline-block" action="{{route('professions.destroy',$profession->id)}}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" onclick="return confirm('Are you sure?')" class="btn btn-sm btn-danger"> <i class="fas fa-trash mr-1"></i> Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <p class="text-danger">No User Found</p>

                     
                 @endif
                </div>
                <!-- end content-->
            </div>
            <!--  end card  -->
        </div>
        <!-- end col-md-12 -->
    </div>
    <!-- end row -->
@endsection
