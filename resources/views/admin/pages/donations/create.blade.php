@extends('admin.main')
@section('content')
@if (Session::has('error'))
<div class="alert alert-danger">
    {{-- @dd(Session::get("error")) --}}
    {{ Session::get('error') }}
</div>
@endif
     <div class="row">
         <div class="col-md-12">
             <div class="card">
                 <div class="card-header">
                     <h3 class="card-title"> <i class="fas fa-plus mr-2"></i><b>Add New Donation</b> </h3>
                 </div>
                 <div class="card-body">
                    <form action="{{route('admin.donations.store')}}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input type="text" class="form-control" id="name" name="name" placeholder="Enter Name">
                            @error('name')
                                <span class="text-danger">{{$message}}</span>
                            @enderror
                        </div>


                        <div class="form-group">
                            <label for="from_warehouse_id">From Warehouse</label>
                            <select name="from_warehouse_id" class="form-control" required>
                                <option value="">Select Warehouse</option>
                                @foreach($warehouses as $warehouse)
                                    <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- <div class="form-group mt-3">
                            <label for="to_warehouse_id">To Warehouse</label>
                            <select name="to_warehouse_id" class="form-control" required>
                                <option value="">Select Warehouse</option>
                                @foreach($warehouses as $warehouse)
                                    <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
                                @endforeach
                            </select>
                        </div> --}}



                        <h4 class="mt-4">Products</h4>
                        <div id="product-list">
                            <div class="row mb-2 product-item">
                                <div class="col-md-6">
                                    <select name="items[0][product_id]" class="form-control" required>
                                        <option value="">Select Product</option>
                                        @foreach($products as $product)
                                            <option value="{{ $product->id }}">{{ $product->name }} ({{ $product->sku }}) ({{$product->size ?? ""}})</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <input type="number" name="items[0][quantity]" class="form-control" placeholder="Quantity" min="1" required>
                                </div>
                                <div class="col-md-2">
                                    <button type="button" class="btn btn-danger remove-product">clear</button>
                                </div>
                            </div>
                        </div>
                        <button type="button" class="btn btn-secondary" id="add-product">Add Product</button>

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

    <script>
        let productIndex = 1;

        document.getElementById('add-product').addEventListener('click', () => {
            const template = `
            <div class="row mb-2 product-item">
                <div class="col-md-6">
                    <select name="items[${productIndex}][product_id]" class="form-control" required>
                        <option value="">Select Product</option>
                        @foreach($products as $product)
                            <option value="{{ $product->id }}">{{ $product->name }} ({{ $product->sku }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <input type="number" name="items[${productIndex}][quantity]" class="form-control" placeholder="Quantity" min="1" required>
                </div>
                <div class="col-md-2">
                    <button type="button" class="btn btn-danger remove-product">Clear</button>
                </div>
            </div>
            `;
            document.getElementById('product-list').insertAdjacentHTML('beforeend', template);
            productIndex++;
        });

        document.addEventListener('click', function (e) {
            if (e.target.classList.contains('remove-product')) {
                e.target.closest('.product-item').remove();
            }
        });


        const fromSelect = document.querySelector('select[name="from_warehouse_id"]');
        const toSelect = document.querySelector('select[name="to_warehouse_id"]');

        // function disableSameWarehouse() {
        //     const fromValue = fromSelect.value;

        //     [...toSelect.options].forEach(option => {
        //         option.disabled = option.value === fromValue && fromValue !== '';
        //     });
        // }

        // function disableSameWarehouseReverse() {
        //     const toValue = toSelect.value;

        //     [...fromSelect.options].forEach(option => {
        //         option.disabled = option.value === toValue && toValue !== '';
        //     });
        // }

        // fromSelect.addEventListener('change', () => {
        //     disableSameWarehouse();
        //     disableSameWarehouseReverse();
        // });

        // toSelect.addEventListener('change', () => {
        //     disableSameWarehouse();
        //     disableSameWarehouseReverse();
        // });
    </script>
@endsection
