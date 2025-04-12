@section('footer')
</div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->

<!-- Main content -->

<!-- /.content -->
</div>
<!-- /.content-wrapper -->


<!-- Control Sidebar -->
<aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
</aside>
<!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->
</div>
  <!-- /.content-wrapper -->
  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->

 
<!-- jQuery -->
<script src="{{url('plugins/jquery/jquery.min.js')}}"></script>
<script src="{{url('plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
<script src="{{url('plugins/daterangepicker/daterangepicker.js')}}"></script>
<script src="{{url('plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js')}}"></script>
<script src="{{url('plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js')}}"></script>

<script src="{{url('dist/js/select2.min.js')}}"></script>
<script src="{{url('dist/js/adminlte.js')}}"></script>
<script src="{{url('dist/js/demo.js')}}"></script>
<script src="{{url('dist/js/pages/dashboard.js')}}"></script>
<script src="{{ asset('ckeditor/ckeditor.js') }}"></script>
{{-- <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> --}}

<script>
CKEDITOR.replace( 'ck_description', {
    filebrowserUploadUrl: "{{route('upload', ['_token' => csrf_token() ])}}",
    filebrowserUploadMethod: 'form'
});
CKEDITOR.replace( 'ck_meta_description', {
    filebrowserUploadUrl: "{{route('upload', ['_token' => csrf_token() ])}}",
    filebrowserUploadMethod: 'form'
});
</script>
<script>
  $(document).ready(function(){
    setTimeout(function(){
        $('.alert').hide('slow');
    },8000);
  })
  

  //multiple select JS
 
$(document).ready(function() {
    $('.select-multiple').select2();
});

  // end multiple JS

    
  //table search code
  $("#table_search").keyup(function () {
      var value = this.value.toLowerCase().trim();
    
      $("table tr").each(function (index) {
        if (!index) return;
        $(this).find("td").each(function () {
          var id = $(this).text().toLowerCase().trim();
          var not_found = (id.indexOf(value) == -1);
          $(this).closest('tr').toggle(!not_found);
          return not_found;
        });
      });
    });
      
  </script>
</body>
</html>

@endsection
