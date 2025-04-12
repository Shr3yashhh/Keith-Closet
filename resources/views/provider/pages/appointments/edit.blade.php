@extends('provider.main')
@section('content')
<style>
    .existing_avatar{
        position: relative;
        width: 400px;
        display: flex;
        margin-top: 5px;
    }
    .existing_avatar_label{
        position: absolute;
        top: 0;
        right: 200px;
        color: #fff;
        background: #000;
        padding:2px;
    }
    .new_avatar_label{
        position: absolute;
        top: 0;
        right: 0;
        color: #fff;
        background: #000;
        padding:2px;
    }
</style>
     <div class="row">
         <div class="col-md-12">
             <div class="card">
                 <div class="card-header">
                     <h3 class="card-title"> <i class="fas fa-edit mr-2"></i><b>Edit appointment</b> </h3><a href="{{route('professions.index')}}" class="btn btn-sm btn-info " style="float: right !important;"> <i class="fas fa-eye mr-1"></i> View appointment</a>
                 </div>
                 <div class="card-body">
                    <form action="{{route('provider.appointment.update',$appointment->id)}}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label for="user">User</label>
                            <select class="form-control" name="user" placeholder="Enter Name">
                                <option value="{{ $appointment->patient->id }}" default> {{ $appointment->patient->name }}</option>
                            @foreach($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endforeach
                            </select>
                            @error('name')
                            <span class="text-danger">{{$message}}</span>

                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea class="form-control" value="{{ $appointment->description }}" name="description" placeholder="Enter Description">{{ $appointment->description }}</textarea>

                            @error('description')
                            <span class="text-danger">{{$message}}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="date">Date</label>
{{--                            @dd($appointment->date)--}}
                            {{--                            <textarea class="form-control" name="description" placeholder="Enter Meta Description"></textarea>--}}
                            <input type="date" value="{{ \Carbon\Carbon::parse($appointment->date)->format("Y-m-d") }}" class="form-control" name="date" placeholder="Enter Date" />

                            @error('description')
                            <span class="text-danger">{{$message}}</span>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary">Update</button>
                    </form>
                 </div>
                 <div class="cart-footer">

                 </div>
                    <!-- end content-->
                </div>
                <!--  end card  -->
            </div>
            <!-- end col-md-12 -->
        </div>
        <!-- end row -->

        <script type="text/javascript">
            $(document).ready(function(){
             $('#avatar_img').hide();
             $('.new_avatar_label').hide();
            })
            function previewImg(input,id) {
              if (input.files && input.files[0]) {
                // document.querySelectorAll('.prev_img').style.display="block";
                $(document).ready(function(){
                  $(id).show();
                  $('.new_avatar_label').show();
                })
                  var reader = new FileReader();

                  reader.onload = function (e) {
                      $(id).attr('src', e.target.result);
                  }

                  reader.readAsDataURL(input.files[0]);
              }
          }



        </script>
@endsection




