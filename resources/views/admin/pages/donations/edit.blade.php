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
<?php
    $statuses = config("order_status")["statuses"];
    // dd($statuses);
?>
@if (Session::has('error'))
<div class="alert alert-danger">
    {{ Session::get('error') }}
</div>
@endif
     <div class="row">
         <div class="col-md-12">
             <div class="card">
                 <div class="card-header">
                     <h3 class="card-title"> <i class="fas fa-edit mr-2"></i><b>View Donation</b> </h3>
                     {{-- <a href="{{route('professions.index')}}" class="btn btn-sm btn-info " style="float: right !important;"> <i class="fas fa-eye mr-1"></i> View Profession</a>  --}}
                 </div>
                 <div class="card-body">
                    <form action="{{route('admin.orders.update',$order->id)}}" method="POST" enctype="multipart/form-data">
                        @csrf
                        {{-- @method('PUT') --}}
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input disabled type="text" class="form-control" id="name" value="{{ $order->username}}" name="name" placeholder="Enter Name">
                            @error('name')
                                <span class="text-danger">{{$message}}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="from_warehouse_id">From Warehouse</label>
                            <select disabled name="from_warehouse_id" class="form-control" required>
                                <option value="">{{ $order->senderWarehouse?->name}}</option>
                                {{-- @foreach($warehouses as $warehouse)
                                    <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
                                @endforeach --}}
                            </select>
                        </div>



                        <h4 class="mt-4">Products</h4>
                        <div id="product-list">
                            @foreach ($order['orderItems'] as $index => $item)
                            {{-- @dd($item->product->name) --}}
                                <div class="row mb-2 product-item">
                                    <div class="col-md-6">
                                        <select disabled name="items[{{ $index }}][product_id]" class="form-control" required>
                                            <option value="">{{ $item->product->name }}</option>
                                            {{-- @foreach($products as $product)
                                                <option value="{{ $product->id }}"
                                                    {{ $product->id == $item['product_id'] ? 'selected' : '' }}>
                                                    {{ $product->name }} ({{ $product->sku }})
                                                </option>
                                            @endforeach --}}
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <input disabled type="number"
                                               name="items[{{ $index }}][quantity]"
                                               class="form-control"
                                               placeholder="Quantity"
                                               min="1"
                                               value="{{ $item['quantity'] }}"
                                               required>
                                    </div>
                                    {{-- <div class="col-md-2">
                                        <button type="button" class="btn btn-danger remove-product">clear</button>
                                    </div> --}}
                                </div>
                            @endforeach
                        </div>

                        {{-- <div class="form-group mt-3">
                            <label for="status">status</label>
                            <select name="status" class="form-control" required>
                                <option value="">Select Status</option>
                                @foreach($statuses as $key => $status)
                                    <option value="{{ $key }}" {{ $order->status == $key ? 'selected' : '' }}>{{ $status["label"] }}</option>
                                @endforeach
                            </select>
                        </div> --}}
                        {{-- <button type="button" class="btn btn-secondary" id="add-product">Add Product</button> --}}

                        {{-- <button type="submit" class="btn btn-primary">Submit</button> --}}

                        {{-- <button type="submit" class="btn btn-primary">Update</button> --}}
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

 
                        

                   