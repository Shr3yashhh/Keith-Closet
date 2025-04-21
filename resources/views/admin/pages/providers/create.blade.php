@extends('admin.main')
@section('content')
     <div class="row">
         <div class="col-md-12">
             <div class="card">
                 <div class="card-header">
                     <h3 class="card-title"> <i class="fas fa-plus mr-2"></i><b>Add New Admin</b> </h3><a href="{{route('professions.index')}}" class="btn btn-sm btn-info " style="float: right !important;"> <i class="fas fa-eye mr-1"></i> View Admin</a>
                 </div>
                 <div class="card-body">
                    <form action="{{route('admin.provider.store')}}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input type="text" class="form-control" id="name" name="name" placeholder="Enter Name">
                            @error('name')
                                <span class="text-danger">{{$message}}</span>

                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="name">Email</label>
                            <label for="email"></label><input type="email" class="form-control" id="email" name="email" placeholder="Enter Email" value="">
                            @error('email')
                            <span class="text-danger">{{$message}}</span>

                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="name">Phone Number</label>
                            <label for="phone"></label><input type="text" class="form-control" id="phone" name="phone" placeholder="Enter phone number" value="">
                            @error('phone')
                            <span class="text-danger">{{$message}}</span>

                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="name">Address</label>
                            <label for="address"></label><input type="text" class="form-control" id="address" name="address" placeholder="Enter Name" value="">
                            @error('address')
                            <span class="text-danger">{{$message}}</span>

                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="name">Password</label>
                            <label for="password"></label><input type="password" class="form-control" id="password" name="password" placeholder="Enter Password" value="">
                            @error('address')
                            <span class="text-danger">{{$message}}</span>

                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary">Submit</button>
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
            })
            function previewImg(input,id) {
              if (input.files && input.files[0]) {
                // document.querySelectorAll('.prev_img').style.display="block";
                $(document).ready(function(){
                  $(id).show();
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




