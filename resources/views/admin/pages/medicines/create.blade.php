@extends('admin.main')
@section('content')
     <div class="row">
         <div class="col-md-12">
             <div class="card">
                 <div class="card-header">
                     <h3 class="card-title"> <i class="fas fa-plus mr-2"></i><b>Add New Professions</b> </h3><a href="{{route('professions.index')}}" class="btn btn-sm btn-info " style="float: right !important;"> <i class="fas fa-eye mr-1"></i> View Profession</a> 
                 </div>
                 <div class="card-body">
                    <form action="{{route('professions.store')}}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input type="text" class="form-control" id="name" name="name" placeholder="Enter Name">
                            @error('name')
                                <span class="text-danger">{{$message}}</span>
                                
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="avatar">Avatar</label>
                            <input type="file" class="form-control"  name="avatar" onchange="previewImg(this,'#avatar_img');" > 
                            @error('avatar')
                                <span class="text-danger">{{$message}}</span>
                                
                            @enderror 
                            <img style="width:200px;" class="img-fluid prev_img mt-2" id="avatar_img" src="#" alt="your image" />                        </div>
                        <div class="form-group">
                            <label for="status">Meta Description</label>
                            <textarea class="form-control" id="ck_meta_description" name="meta_description" placeholder="Enter Meta Description"></textarea>
                            @error('meta_description')
                                <span class="text-danger">{{$message}}</span>

                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="status">Description</label>
                            <textarea class="form-control" id="ck_description" name="description" placeholder="Enter Meta Description"></textarea>

                            @error('description')
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

 
                        

                   