@extends('frontend.main')
@section('content')
<!-- ======= Hero Section ======= -->
<section class="hero-section" id="hero">

  <style>
    .ani-btn {
        transition: all 0.3s ease;
    }

    .ani-btn:hover {
        background-color: white;
        color: #000;
        transform: scale(1.05);
        border-color: #fff;
    }
</style>
  <div class="wave">

    <svg width="100%" height="355px" viewBox="0 0 1920 355" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
      <g id="Page-1" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
        <g id="Apple-TV" transform="translate(0.000000, -402.000000)" fill="#FFFFFF">
          <path d="M0,439.134243 C175.04074,464.89273 327.944386,477.771974 458.710937,477.771974 C654.860765,477.771974 870.645295,442.632362 1205.9828,410.192501 C1429.54114,388.565926 1667.54687,411.092417 1920,477.771974 L1920,757 L1017.15166,757 L0,757 L0,439.134243 Z" id="Path"></path>
        </g>
      </g>
    </svg>

  </div>

  <div class="container">
    <div class="row align-items-center">

      <div class="col-12 hero-text-image">

        <div class="row">
          <div class="col-lg-8 text-center text-lg-start">
            <h1 data-aos="fade-right">Help make A difference</h1>
            <p class="mb-5" data-aos="fade-right" data-aos-delay="100">
              Keith’s Closet believes that people acquiring mental health services should have access to clothing, accessories, essential items and homewares in times of need whether in hospital or in the community.
            </p>



            <div class="d-flex" data-aos="fade-right" data-aos-delay="200">
                <p style="padding-right: 5px" class="" data-aos="fade-right" data-aos-delay="200" data-aos-offset="-500"><a href="#getstarted" class="btn ani-btn btn-outline-white">Donate Us</a></p>
                <p data-aos="fade-right" data-aos-delay="200" data-aos-offset="-500"><a href="#getstarted" class="btn ani-btn btn-outline-white">Get Support</a></p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

</section><!-- End Hero -->
<!-- ======= Home Section ======= -->


<section class="section profession-section" id="services">
  <div class="container">
    <div class="row justify-content-center mb-2">
      <div class="col-md-12" data-aos="fade-up">
        <h2 class="section-heading">How You Can Help? </h2>
        <hr>
      </div>
    </div>

    <div class="row d-flex">
      <!-- Donate Card -->
      <div style="cursor: pointer" class="col-md-4 shadow mt-4 pt-3" data-aos="fade-up">
          <div class="feature-1 text-center">
              <div class="wrap-icon icon-1">
                  <img src="{{url('frontend/assets/img/doantiongigf.webp')}}" alt="Donate Icon" style="border-radius: 50%;" class="img-thumbnail" width="100px" height="100px">
              </div>
              <h3 class="mb-3"><a href="">Make a Donation</a></h3>
              <p>Your contribution helps us provide essential support and services to those in need.</p>
          </div>
      </div>
  
      <!-- Clothing and Homewares Card -->
      <div style="cursor: pointer" class="col-md-4 shadow mt-4 pt-3" data-aos="fade-up">
          <div class="feature-1 text-center">
              <div class="wrap-icon icon-1">
                  <img src="{{url('frontend/assets/img/wear.webp')}}" alt="Clothing Icon" style="border-radius: 50%;" class="img-thumbnail" width="100px" height="100px">
              </div>
              <h3 class="mb-3"><a href="">Clothing & Homewares</a></h3>
              <p>Support our mission by donating quality clothes and home goods to help communities thrive.</p>
          </div>
      </div>

      <div style="cursor: pointer" class="col-md-4 shadow mt-4 pt-3" data-aos="fade-up">
        <div class="feature-1 text-center">
            <div class="wrap-icon icon-1">
                <img src="{{url('frontend/assets/img/testtube.gif')}}" alt="Clothing Icon" style="border-radius: 50%;" class="img-thumbnail" width="100px" height="100px">
            </div>
            <h3 class="mb-3"><a href="">Become a Volunteer</a></h3>
            <p>Join our mission to make a difference. Lend your time and skills to support those in need and create lasting impact in your community.</p>

        </div>
    </div>
  </div>

  </div>
</section>

    <hr>

<!-- ======= CTA Section ======= -->
<section class="section cta-section" id="getstarted">
  <div class="container">

    <div class="row align-items-center">
      <div class="col-md-6 me-auto text-center text-md-start mb-5 mb-md-0">
        <h2 class="get-started-text">Start Using our Platform</h2>
      </div>
      <div class="col-md-5 text-center text-md-end">
        <p>
          {{-- <a href="{{url('provider-panel/login')}}" class="btn get-started-btns btn-outline-info d-inline-flex align-items-center"><i class="bi bi-people-fill"></i><span>Doctor Login</span></a>
          <a href="{{url('user-panel/login')}}" class="btn get-started-btns btn-outline-info d-inline-flex align-items-center"><i class="bi bi-people"></i><span>Patient Login</span></a> --}}
        </p>
      </div>
    </div>
  </div>
</section><!-- End CTA Section -->

@endsection
