@extends('frontend.main')
@section('content')

   <!-- ======= Single Blog Section ======= -->
   <section class="hero-section inner-page">
    {{-- <div class="wave">

      <svg width="1920px" height="265px" viewBox="0 0 1920 245" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
        <g id="Page-1" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
          <g id="Apple-TV" transform="translate(0.000000, -402.000000)" fill="#FFFFFF">
            <path d="M0,439.134243 C175.04074,464.89273 327.944386,477.771974 458.710937,477.771974 C654.860765,477.771974 870.645295,442.632362 1205.9828,410.192501 C1429.54114,388.565926 1667.54687,411.092417 1920,477.771974 L1920,667 L1017.15166,667 L0,667 L0,439.134243 Z" id="Path"></path>
          </g>
        </g>
      </svg>

    </div> --}}

    {{-- <div class="container">
      <div class="row align-items-center">
        <div class="col-12">
          <div class="row d-flex justify-content-center">
            <div class="col-md-12 text-center hero-text">
              
              <h1 data-aos="fade-up" data-aos-delay="">Here is the result of your search</h1>
            </div>
          </div>
        </div>
      </div>
    </div> --}}

  </section>
  <div class="container">
    <div class="row">
       <div class="col-sm-12">
          <div class="row mt-4">
            
             <div class="col-md-12">
           
               <form action="{{route('home.search')}}" class="form-group mb-2 rounded d-flex home-search-form" method="POST">
                  @csrf
                  <select name="search" id="home_search" class="form-control">
                     <option selected disabled>Choose any service</option>
                     @foreach ($search_options as $option)
                     <option value="{{$option->name}}">{{$option->name}}</option>
                     
                   @endforeach
                   </select>                  <input type="submit" class="form-control" value="Search" id="home_search_submit_btn">
                </form>
             
                <div class="card shadow-none">
                   <div class="card-header">
                      <h4>Available Service Providers for <span class="fw-bold">{{$professions[0]->profession_name??'Given Service'}} </span></h4>
                   </div>
                @if ($professions->count() > 0)
                   <div class="row card-body">
                     @foreach ($professions->sortBy('distance') as $key => $profession )
                       
                      <div class="col-sm-12 mb-4">
                         <div class="shadow-sm card cardHover ">
                            <div class="row p-2">
                               <div class="col-md-8 col-sm-12 col-xs-12 ">
                                  <div class="profile-img d-inline-flex">
                                     <div class="profile-avatar">
                                       @if ($profession->user_avatar != null)
                                       <img src="{{url('provider_avatar/'.$profession->user_avatar)}}" alt="{{$profession->user_name}}" style="border-radius: 50%;" class=" img-thumbnail" width="100px" height="100px">
                                          @if ($profession->is_active == 1)
                                          <i class="bi bi-circle-fill text-success rounded-circle"></i>
                                          @else
                                             <i class="bi bi-circle-fill text-danger rounded-circle"></i>
                                          @endif
                                       @else
                                          <img src="{{url('provider_avatar/default.jpg')}}" alt="{{$profession->user_name}}" style="border-radius: 50%;" class=" img-thumbnail" width="100px" height="100px">
                                          @if ($profession->is_active == 1)
                                          <i class="bi bi-circle-fill text-success rounded-circle"></i>
                                          @else
                                             <i class="bi bi-circle-fill text-danger rounded-circle"></i>
                                          @endif
                                       @endif
                                       @if ($profession->current_provider_rating == 0)
                                          <div class="text-danger">Not Rated yet</div>
                                          @else
                                          <div>
                                             <?php
                                              $primary_stars = $profession->current_provider_rating;
                                             $secondary_stars = 5 - $primary_stars;
                                             
                                             ?>

                                             @if ($primary_stars > 0)
                                             @for ($i = 0; $i < $primary_stars; $i++)
                                             <span class="bi bi-star-fill text-warning"></span>
                                             @endfor
                                             @endif
                                             @if ($secondary_stars > 0)
                                             @for ($i = 0; $i < $secondary_stars; $i++)
                                             <span class="bi bi-star-fill text-secondary"></span>
                                             @endfor
                                             @endif
                         
                                          </div>
                                          
                                       @endif
                                      
                                         <div>
                                          <small>
                                             <span class="bi bi-check-circle-fill text-success"> Verified Profile</span> 
                                             </small>
                                         </div>
                                      
                                      
                                     </div>
                                     <div class="profilesummary px-4">
                                        <ul class="list-unstyled">
                                           <li class="h4" style="color:#100000">{{$profession->user_name}}</li>
                                           <li class="text-dark">
                                             <span class="bi bi-circle-square text-primary"></span> : @if ($profession->is_active == 1)
                                             <span class="text-success">Online</span>
                                             @else
                                             <span class="text-danger">Offline</span>
                                                
                                             @endif
                                            </li>
                                           <li class="text-dark">
                                            <span class="bi bi-person-badge text-primary"></span> : <b> {{$profession->profession->name}}</b>
                                           </li>
                                           
                                           @php
                                               $url = "#";
                                           @endphp
                                           @if ($profession->current_latitude != null && $profession->current_longitude != null)
                                            @php
                                               
                                            $url = "https://maps.google.com/?q=".$profession->current_latitude.",".$profession->current_longitude;
                                          @endphp  
                                           @endif
                                           @if (isset($profession->distance) || $profession->distance != null || $profession->distance != 0 || $profession->distance != '')
                                              <li style="font-weight:100">
                                             <span class="bi bi-map-fill text-primary"></span> :
                                              <a  @if ($profession->current_latitude != null && $profession->current_longitude != null) target="_blank" @endif href="{{$url}}" style="color:blue;"> 
                                                <b><em>Locate Now</em></b></a><strong class="text-dark"> ({{$profession->distance .' KM'??'0 KM'}}) </strong>
                                          </li>
                                           @endif
                                           
                                           <li><span class="bi bi-telephone-fill text-primary"> : <a href="tel:{{$profession->phone??''}}" class="text-decoration-none text-dark">{{$profession->phone??'N/A'}}</a></li>
                                        </ul>
                                     </div>
                                  </div>
                                  <div class="pt-3 text-center d-none">
                                     <br>
                                     *****
                                  </div>
                               </div>
                               <div class="col-md-4 col-sm-12 col-xs-12   align-items-center">
                                    @if (Session::has('session_user') && $profession->is_active == 1)

                                    <form action="{{route('request.service')}}" method="POST" class="request-form">
                                       @csrf
                                       <input type="hidden" name="user_latitude" value="{{$profession->current_user_lattitude??''}}">
                                       <input type="hidden" name="user_longitude" value="{{$profession->current_user_longitude??''}}">
                                       <input type="hidden" name="user_id" value="{{Session::get('session_user')->id}}">
                                       <input type="hidden" name="provider_id" value="{{$profession->provider_id}}">
                                       <button onclick="return confirm('Are you sure?')" type="submit" class="btn btn-primary rounded-pill btn-block request-btn">Request Provider</button>
                                    </form>
                                    @elseif (Session::has('session_user') && $profession->is_active == 0)
                                    <p  class="text-danger text-center"><i class="bi bi-exclamation-circle"></i> 
                                       <strong>Sorry!</strong> This provider is currently offline.
                                    </p>
                                    @else
                                    <p  class="text-danger text-center"><i class="bi bi-exclamation-circle"></i> You must login to use this feature 
                                       <a class="text-primary text-center" href="{{url('user-panel/login')}}"> Login Now</a>
                                    </p>
                                       
                                    @endif
                                     
                                     
                                  
                               </div>
                            </div>
                         </div>
                      </div>
                      @endforeach
                      <!-- <p class="text-center">End of results</p> -->
                   </div>
                @else
            
                   
                   <div class="card-body">
                     <p class="text-danger">
                        Couldn't found any results for your search.
                     </p>
                                  
                </div>
            @endif
                </div>
               
               

               
          </div>
       </div>
       <!-- map integration -->
         <div id="map"></div>
         <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBHsIIqvhLvtMwXHh9WJzGz2KqHvvg1q1U"></script>

         <script>
            // Initialize the map
            function initMap() {
            // Create a map object and center it on a specific location
            const map = new google.maps.Map(document.getElementById("map"), {
               center: { lat: 37.7749, lng: -122.4194 },
               zoom: 13,
            });

            console.log(map); 

            // Request the user's location
            if (navigator.geolocation) {
               navigator.geolocation.getCurrentPosition(
                  (position) => {
                     console.log(position);
                  const userLocation = {
                     lat: position.coords.latitude,
                     lng: position.coords.longitude,
                  };

                  // Create a marker at the user's location
                  const marker = new google.maps.Marker({
                     position: userLocation,
                     map: map,
                     title: "Your Location",
                  });

                  // Center the map on the user's location
                  map.setCenter(userLocation);
                  },
                  () => {
                  // Handle location error
                  alert("Error: The Geolocation service failed.");
                  }
               );
            } else {
               // Browser doesn't support geolocation
               alert("Error: Your browser doesn't support geolocation.");
            }
            }

         </script>

       <!-- end map integration  -->
    </div>
 </div>
@endsection