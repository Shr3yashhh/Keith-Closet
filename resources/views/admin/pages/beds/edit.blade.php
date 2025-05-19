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
                     <h3 class="card-title"> <i class="fas fa-edit mr-2"></i><b>Edit Warehouse</b> 
                    {{-- </h3><a href="{{route('professions.index')}}" class="btn btn-sm btn-info " style="float: right !important;"> <i class="fas fa-eye mr-1"></i> View Profession</a> --}}
                 </div>
                 <div class="card-body">
                    <form action="{{route('admin.beds.update',$bed->id)}}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input required type="text" class="form-control" name="name" value="{{ $bed->name }}" placeholder="Enter Name">
                            @error('name')
                            <span class="text-danger">{{$message}}</span>

                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="code">Code</label>
                            <input required type="text" class="form-control" name="code" value="{{ $bed->code }}" placeholder="Enter Code">
                            @error('code')
                            <span class="text-danger">{{$message}}</span>

                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="address">Address</label>
                            <input required type="text" class="form-control" name="address" value="{{ $bed->address }}" placeholder="Enter Address">
                            @error('address')
                            <span class="text-danger">{{$message}}</span>

                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="contact_number">Contact Number</label>
                            <input required type="text" class="form-control" name="contact_number" value="{{ $bed->contact_number }}" placeholder="Enter Contact Number">
                            @error('contact_number')
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




