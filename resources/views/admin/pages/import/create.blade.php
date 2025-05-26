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
                     <h3 class="card-title"> <i class="fas fa-plus mr-2"></i><b>Add New Import</b> </h3>
                 </div>
                 <div class="card-body">
                    <form action="{{ route("admin.import.store") }}" method="POST" enctype="multipart/form-data">
                      @csrf

                      <div class="mb-3">
                        <label for="file" class="form-label">Upload CSV File</label>
                        <input class="form-control" type="file" id="file" name="file" accept=".csv" required>
                        <div class="form-text">Only CSV files are allowed.</div>
                      </div>

                      <div class="mb-3">
                        <label for="Type" class="form-label">Type</label>
                        <select class="form-select" id="Type" name="type" required>
                          <option value="" disabled selected>Select type</option>
                          <option value="stock">Stock</option>
                          {{-- <option value="users">Users</option> --}}
                          {{-- <option value="orders">Orders</option> --}}
                        </select>
                      </div>

                      <button type="submit" class="btn btn-success w-100">Start Import</button>
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
