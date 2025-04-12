@extends('admin.main')
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
                     <h3 class="card-title"> <i class="fas fa-edit mr-2"></i><b>Edit Doctor</b> </h3><a href="{{route('admin.providers')}}" class="btn btn-sm btn-info " style="float: right !important;"> <i class="fas fa-eye mr-1"></i> View Doctor</a>
                 </div>
                 <div class="card-body">
                    <form action="{{route('admin.provider.update',$provider->id)}}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input type="text" class="form-control" id="name" name="name" placeholder="Enter Name" value="{{$provider->name}}">
                            @error('name')
                            <span class="text-danger">{{$message}}</span>

                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="name">Email</label>
                            <label for="email"></label><input type="email" class="form-control" id="email" name="email" placeholder="Enter Email" value="{{$provider->email}}">
                            @error('email')
                            <span class="text-danger">{{$message}}</span>

                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="name">Phone Number</label>
                            <label for="phone"></label><input type="text" class="form-control" id="phone" name="phone" placeholder="Enter phone nunmber" value="{{$provider->phone}}">
                            @error('phone')
                            <span class="text-danger">{{$message}}</span>

                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="name">Address</label>
                            <label for="address"></label><input type="text" class="form-control" id="address" name="address" placeholder="Enter Name" value="{{$provider->address}}">
                            @error('address')
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




