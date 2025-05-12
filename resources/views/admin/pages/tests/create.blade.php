@extends('admin.main')
@section('content')
     <div class="row">
         <div class="col-md-12">
             <div class="card">
                 <div class="card-header">
                     <h3 class="card-title"> <i class="fas fa-plus mr-2"></i><b>Add Product Stock on warehouse</b> </h3>
                 </div>
                {{-- @dd($errors); --}}
                @if (session('exist'))
                    <div class="alert alert-danger">
                        {{ session('exist') }}
                    </div>
                @endif
                 <div class="card-body">
                    <form action="{{route('admin.stock.store')}}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label for="product">Product</label>
                            <select class="form-control" name="product" required>
                                <option value="" disabled selected>Select a product</option>
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
                            <select class="form-control" name="warehouse" placeholder="Select Warehouse">
                                <option value="" disabled selected>Select a warehouse</option>
                                @foreach($warehouses as $warehouse)
                                    <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
                                @endforeach
                            </select>
                            @error('warehouse')
                                <span class="text-danger">{{$message}}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="bin_location">Bin Location</label>
                            <select class="form-control" name="bin_location" required>
                                <option value="" disabled selected>Select a bin location</option>
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
                                value="{{ old('quantity') }}"
                                min="1"
                            >

                            @error('quantity')
                                <span class="text-danger">{{ $message }}</span>
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




