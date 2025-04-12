@section('footer')

</main><!-- End #main -->

<!-- ======= Footer ======= -->
<footer class="footer" role="contentinfo" id="contact">
  <div class="container">
    <div class="row">
      <div class="col-md-4 mb-4 mb-md-0">
        <h3 class="text-primary footer-heading">About keith's Closet</h3>
        <p class="text-dark">Keith's Closet Ltd (ABN 81 657 905 274) is registered as a Public Benevolent Institution with the Australian Charities and Not-for-profits Commission and endorsed as a Deductible Gift Recipient with the Australian Tax Office.  All donations of $2 or more are tax deductible.</p>
        <p class="social">
          <a href="#"><span class="bi bi-twitter"></span></a>
          <a href="#"><span class="bi bi-facebook"></span></a>
          <a href="#"><span class="bi bi-instagram"></span></a>
          <a href="#"><span class="bi bi-linkedin"></span></a>
        </p>
      </div>
      <div class="col-md-8 ms-auto">
        <div class="row site-section pt-0">
          <div class="col-md-4 mb-4 mb-md-0">
            <h3 class="text-primary footer-heading">Contact Us</h3>
            <ul class="list-unstyled footer-link">
              <li>
                <a href="#">
                  <span class="d-inline-block mr-2">
                    <i class="bi bi-map-fill text-primary"></i>
                  </span>
                  Sydney, Australia
                </a>
              </li>
              <li>
                <a href="#">
                  <span class="d-inline-block mr-2">
                    <i class="bi bi-envelope-fill text-primary"></i>
                  </span>
                  enquiries@keithscloset.org
                </a>
              </li>

            </ul>

          </div>
        </div>
      </div>
    </div>



  </div>
</footer><hr>
<!-- End Footer -->
<div class="container mt-2">
  <div class="row">
    <div class="col-md-12 text-center text-dark">
      <p>
        &copy; {{ date('Y') }} Keith's Closet. All rights reserved.</a>
      </p>

  </div>
</div>
<hr>

<a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

<!-- Vendor JS Files -->
<script src="{{url('frontend/assets/vendor/aos/aos.js')}}"></script>
<script src="{{url('frontend/assets/vendor/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
<script src="{{url('frontend/assets/vendor/swiper/swiper-bundle.min.js')}}"></script>
<script src="{{url('frontend/assets/vendor/php-email-form/validate.js')}}"></script>

<!-- Template Main JS File -->
<script src="{{url('frontend/assets/js/main.js')}}"></script>

</body>

</html>
@endsection
