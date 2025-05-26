@extends('admin.main')
@section('content')
     <div class="row">
         <div class="col-md-12">
             <div class="card">
                 <div class="card-header">
                     <h3 class="card-title"> <i class="fas fa-plus mr-2"></i><b>Add Warehouse</b> </h3><a href="{{route('professions.index')}}" class="btn btn-sm btn-info " style="float: right !important;"> <i class="fas fa-eye mr-1"></i> View BEds</a>
                 </div>
{{--                 @dd($doctor, $user);--}}
                 <div class="card-body">
                    <form action="{{route('admin.beds.store')}}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input required type="text" class="form-control" name="name" placeholder="Enter Name">
                            @error('name')
                                <span class="text-danger">{{$message}}</span>

                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="code">Code</label>
                            <input required type="text" class="form-control" name="code" placeholder="Enter Code">
                            @error('code')
                                <span class="text-danger">{{$message}}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="address">Address</label>
                            <input required type="text" class="form-control" name="address" placeholder="Enter Address">
                            @error('name')
                                <span class="text-danger">{{$message}}</span>

                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="contact_number">Contact Number</label>
                            <input required type="text" class="form-control" name="contact_number" placeholder="Enter Contact Number">
                            @error('contact_number')
                                <span class="text-danger">{{$message}}</span>

                            @enderror
                        </div>
{{--                        <div class="form-group">--}}
{{--                            <label for="date">Date</label>--}}
{{--                            <textarea class="form-control" name="description" placeholder="Enter Meta Description"></textarea>--}}
{{--                            <input required type="date" class="form-control" name="date" placeholder="Enter Date">--}}

{{--                            @error('description')--}}
{{--                            <span class="text-danger">{{$message}}</span>--}}
{{--                            @enderror--}}
{{--                        </div>--}}

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




