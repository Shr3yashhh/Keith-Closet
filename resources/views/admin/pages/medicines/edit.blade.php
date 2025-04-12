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
                     <h3 class="card-title"> <i class="fas fa-edit mr-2"></i><b>Edit Professions</b> </h3><a href="{{route('professions.index')}}" class="btn btn-sm btn-info " style="float: right !important;"> <i class="fas fa-eye mr-1"></i> View Profession</a> 
                 </div>
                 <div class="card-body">
                    <form action="{{route('professions.update',$profession->id)}}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input type="text" class="form-control" id="name" name="name" placeholder="Enter Name" value="{{$profession->name}}">
                            @error('name')
                                <span class="text-danger">{{$message}}</span>
                                
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="avatar">Avatar</label>
                            <input type="file" class="form-control"  name="avatar" onchange="previewImg(this,'#avatar_img');" > 
                        <div class="existing_avatar">
                            @if ($profession->avatar != null)
                                
                                    <img src="{{url('profession_avatar/'.$profession->avatar)}}" alt="{{$profession->name}}" class="img-fluid " style="width: 200px;border:2px solid yellow;">
                                    <span class="existing_avatar_label">Existing</span>
                                     <span class="new_avatar_label">New</span>
                                

                            @endif
                            <img style="width:200px;border:2px solid blue;" class="img-fluid prev_img mt-2" id="avatar_img" src="{{url('profession_avatar/'.$profession->avatar)}}" alt="your image" />                        </div>
                         </div>
                            @error('avatar')
                                <span class="text-danger">{{$message}}</span>
                                
                            @enderror 
                         <div class="form-group">
                            <label for="status">Meta Description</label>
                            <textarea class="form-control" id="ck_meta_description" name="meta_description" placeholder="Enter Meta Description">{{$profession->meta_description}}</textarea>
                            @error('meta_description')
                                <span class="text-danger">{{$message}}</span>

                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="status">Description</label>
                            <textarea class="form-control" id="ck_description" name="description" placeholder="Enter Meta Description">{{$profession->description}}</textarea>

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

 
                        

                   