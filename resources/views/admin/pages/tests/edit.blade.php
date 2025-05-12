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
                 @if (session('exist'))
                    <div class="alert alert-danger">
                        {{ session('exist') }}
                    </div>
                @endif
                 <div class="card-body">
                    <form action="{{route('admin.tests.update',$test->id)}}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <label for="product">Product</label>
                            <select class="form-control" name="product" placeholder="Enter Product">
                                <option value="{{ $test->product->id }}" default> {{ $test->product->name }}</option>
                            @foreach($products as $product)
                                    <option value="{{ $product->id }}">{{ $product->name }}</option>
                                @endforeach
                            </select>
                            @error('product')
                                <span class="text-danger">{{$message}}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="warehouse">Warehouse</label>
                            <select class="form-control" name="warehouse" placeholder="Enter Warehouse">
                                <option value="{{ $test->warehouse->id }}" default> {{ $test->warehouse->name }}</option>
                            @foreach($warehouses as $warehouse)
                                {{-- @dd($warehouse) --}}
                                    {{-- <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option> --}}
                                <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
                            @endforeach
                            </select>
                            @error('name')
                            <span class="text-danger">{{$message}}</span>

                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="bin_location">Bin Location</label>
                            <select class="form-control" name="bin_location" required>
                                <option value="{{ $test->bin_location }}" default> {{ $test->bin_location }}</option>
                                @foreach($binLocations["locations"] as $key => $binLocation)
                                    <option value="{{ $key }}">{{ $key }}</option>
                                @endforeach
                            </select>
                            @error('bin_location')
                                <span class="text-danger">{{$message}}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="quantity">Quantity</label>
                            <input
                                type="number"
                                class="form-control"
                                name="quantity"
                                placeholder="Enter Quantity"
                                value="{{ $test->quantity ?? old('quantity') }}"
                                min="1"
                            >

                            @error('quantity')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
{{--                        <div class="form-group">--}}
{{--                            <label for="date">Date</label>--}}
{{--                            @dd($test->date)--}}
{{--                            --}}{{--                            <textarea class="form-control" name="description" placeholder="Enter Meta Description"></textarea>--}}
{{--                            <input type="date" value="{{ \Carbon\Carbon::parse($test->date)->format("Y-m-d") }}" class="form-control" name="date" placeholder="Enter Date" />--}}

{{--                            @error('description')--}}
{{--                            <span class="text-danger">{{$message}}</span>--}}
{{--                            @enderror--}}
{{--                        </div>--}}

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




