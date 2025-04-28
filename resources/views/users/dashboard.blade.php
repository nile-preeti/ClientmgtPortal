@extends('layouts.user.app')
@section('content')

  <script src="https://api.mapbox.com/mapbox-gl-js/v2.15.0/mapbox-gl.js"></script>
  <link href="https://api.mapbox.com/mapbox-gl-js/v2.15.0/mapbox-gl.css" rel="stylesheet" />
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script src="{{ asset('jquery.js') }}"></script>
  <script src="{{ asset('plugins/bootstrap/js/bootstrap.min.js') }}"></script>
  <link rel="stylesheet" href="{{ asset('plugins/bootstrap/css/bootstrap.min.css') }}">
  <link rel="stylesheet" href="{{ asset('style.css') }}">

  <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/owl.carousel.min.css') }}">

  <link rel="stylesheet" href="{{ asset('users/attendance_records.css') }}">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

  <!--     Fonts and icons     -->
  <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700,200" rel="stylesheet" />
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet"/>
  <script script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script> <!-- Only if jQuery isn't already included -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>


  <style>
    body {
      margin: 0;
      display: flex;
      flex-direction: column;
      height: 100vh;
    }

    #map {
      flex: 1;
    }
    .owl-nav {
      display: none;
    }

    button:disabled {
      background-color: #cccccc;
      cursor: not-allowed;
    }

    .info {
      text-align: center;
      font-size: 13px;
      margin-top: 5px;
    }

    .nav .nav-item button.active {
      background-color: #8e8efe;
      width: 100%;
      text-align: left;
      border-radius: 0;
      padding: 18px;
      font-size: 16px;
      border-bottom: 1px solid #bbbbff;
    }

    .nav .nav-item button.active::after {
      content: "";
      border-right: 6px solid #bbbbff;
      height: 100%;
      position: absolute;
      right: -1px;
      top: 0;
    }

    .date-time-sec h2 {
      color: #000 !important;
    }

    .font-weight-bolder {
      font-weight: bold;
    }
    .selected-date {
    border: 2px solid #007bff;
    background-color: #e0f0ff;
    border-radius: 10px;
}
    .start-checkin-btn.disabled {
        color: #6c757d; /* Muted grey text */
        background-color: #f8f9fa; /* Light grey background */
        pointer-events: none; /* Disable interaction */
        border: 1px solid #ddd; /* Light grey border */
    }

    /* Optional: Add some styling for the hover state when disabled */
    .start-checkin-btn.disabled:hover {
        color: #6c757d; /* Ensure the color stays muted */
        background-color: #f8f9fa;
    }
    .ongoing-checkin-action button{
      color: var(--white);
      border-radius: 50px;
      background: #064086;
      box-shadow: 0px 8px 13px 0px rgba(35, 53, 111, 0.12);
      font-weight: 700;
      font-size: 12px;
      text-align: center;
      padding: 8px 15px;
      display: inline-block;
      border-bottom: none;
  }
  .ongoing-checkin-action button:disabled {
    color: #b0b0b0 !important;  /* Muted text color */
    background: #d3d3d3;  /* Light grey background */
    cursor: not-allowed;  /* Change the cursor to indicate the button is disabled */
    box-shadow: none;  /* Remove the shadow */
    border: 2px solid #b0b0b0 !important; /* Muted border color when disabled */
} 
  .container-wrapper-main{margin-left: 270px;}
  </style>
<body>

  <!-- <header class="header py-2">
    <div class="container-fluid">
      <div class="d-flex flex-wrap align-items-center justify-content-between">

        <a href="#"> @php
          $logo = \App\Models\Logo::first();
          @endphp

          <img src="{{ $logo && file_exists(public_path('uploads/logo/' . $logo->name)) ? asset('uploads/logo/' . $logo->name) : asset('hrmodule.png') }}" class="logo card-img-absolute" alt="circle-image" height="50px"></a>


        <div class="dropdown text-end">
          <a href="#" class="d-flex align-items-center link-body-emphasis text-decoration-none dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
            <img src="https://nileprojects.in/hrmodule/public/assets/images/image.png" alt="mdo" width="40" height="40" class="rounded-circle profile-image">
            <h6 class="m-0 p-0 text-light profile-name"> &nbsp; Profile</h6>
          </a>
          <ul class="dropdown-menu text-small" style="">
            <li><a class="dropdown-item" href="{{route('user.profile')}}">Profile</a></li>
            <li>
              <hr class="dropdown-divider">
            </li>
            <li><a class="dropdown-item" href="#" onclick="logout()">Sign out</a></li>
          </ul>
        </div>
      </div>
    </div>
  </header> -->



  <div class="container-wrapper-main">
    <div class="container-fluid mt-5">
      <div class="row">
        
        <h4 class="text-dark mb-4 mt-5 pb-0 text-capitalize font-weight-bolder"> Welcome {{ auth()->user()->name }}</h4>
        <div class="services-tabs">
                <ul class="nav nav-tabs" role="tablist">
                  <li><a class="active" href="#OngoingServices" data-bs-toggle="tab" aria-selected="true" role="tab">Ongoing</a></li>
                  <li><a href="#AssignedServices" data-bs-toggle="tab" aria-selected="false" role="tab" class="" tabindex="-1"><i class="las la-eye-slash"></i> Assigned</a>
                  </li>
                  <!-- <li><a href="#UnAssignedServices" data-bs-toggle="tab" aria-selected="false" role="tab" class="" tabindex="-1"><i class="las la-eye-slash"></i> Completed</a>
                  </li> -->
                  
                  <button class="btn btn-md" style="border: 1px solid #064086; border-radius: 30px; background: #064086;" id="refreshFilters">
                    <img src="https://nileprojects.in/client-portal/public/ic-reset.svg" alt="">
                </button>
                </ul>
              </div>

        <div class="">
          <div class="row">
            <div class="col-md-12">
              
              <div class="Ongoing-calender-list">
              @php
                  $today = \Carbon\Carbon::today()->toDateString();
                  $selected = request('selected_date') ?? $today;
              @endphp
              <form method="GET" id="calendarForm" action="{{ route('user.dashboard') }}">
                  <input type="hidden" name="selected_date" id="selectedDateInput"  value="{{ $selected }}">
                  
                  <div id="Ongoingcalender" class="owl-carousel owl-theme">
                  @foreach($calendarDays as $day)
                    <div class="item">
                        <div
                            class="Ongoing-calender-item selectable-date {{ $selected == $day['full_date'] ? 'selected-date' : '' }}"
                            data-date="{{ $day['full_date'] }}"
                            @if($selected == $day['full_date']) id="selectedCalendarItem" @endif
                        >
                            <h3>{{ $day['day'] }}</h3>
                            <h2>{{ $day['date'] }}</h2>
                        </div>
                    </div>
                @endforeach
                  </div>
              </form>
            </div>
              <div class="tasks-content-info tab-content">
              <div class="tab-pane active show" id="OngoingServices" role="tabpanel">
                  <div class="ongoing-services-list">
                    @forelse($ongoingSchedules as $schedule)
                      @php $service = $schedule->service; @endphp

                      <div class="ongoing-services-item">
                        <div class="ongoing-services-item-head">
                          <div class="ongoing-services-item-title">
                            <h2>Service Name: {{ $service->name ?? 'No Title' }}</h2>
                          </div>
                          @php
                              $todayAttendance = $schedule->attendance
                                  ->where('date', '=', \Carbon\Carbon::today()->toDateString());

                              // Check if checkout time exists for today
                              $canMarkComplete = $todayAttendance->first(function ($att) {
                                  return !empty($att->check_out_time); // Check if check_out_time exists
                              });
                          @endphp
                          <div class="ongoing-checkin-action d-flex gap-2">
                                <!-- Start/Break/End button -->
                                <a 
                                    class="px-4 start-checkin-btn" 
                                    href="javascript:void(0);" 
                                    data-id="{{ $schedule->id }}"
                                >
                                 {{ $todayAttendance->isNotEmpty() ? 'Break/End' : 'Start' }}
                                </a>

                                <!-- Mark as Complete button -->
                                <button
                                  class="px-4 mark-complete-btn" 
                                  data-job-id="{{ $schedule->id }}" 
                                  {{ $canMarkComplete ? '' : 'disabled' }}
                              >
                                  Mark as Complete
                              </button>
                            </div>
                        </div>

                        <div class="ongoing-services-item-body">
                          <div class="row d-flex">
                          <div class="col-lg-6 service-shift-card">
                            <div class="service-shift-card-image">
                              <img src="{{ asset('assets/images/time.svg') }}">
                            </div>
                            <div class="service-shift-card-text">
                              <h2>Service Shift Timing:</h2>
                              <p>{{ \Carbon\Carbon::parse($schedule->start_time)->format('h:iA') }} - {{ \Carbon\Carbon::parse($schedule->end_time)->format('h:iA') }}</p>
                            </div>
                          </div>

                          <div class="col-lg-6  service-shift-card">
                            <div class="service-shift-card-image">
                              <img src="{{ asset('assets/images/time.svg') }}">
                            </div>
                            <div class="service-shift-card-text">
                                <h2>Service Shift Date:</h2>
                                <p>{{ \Carbon\Carbon::parse($schedule->start_date)->format('m/d/Y') }} - {{ \Carbon\Carbon::parse($schedule->end_date)->format('m/d/Y') }}</p>
                              </div>
                          </div>
                          </div>

                          <div class="instructions-text">
                            <h3>Primary Instructions: {{ $schedule->description ?? 'N/A' }}</h3>
                          </div>

                          <div class="row">
                            <div class="col-md-6 col-sm-6 col-lg-4">
                              <div class="service-shift-card">
                                <div class="service-shift-card-image">
                                  <img src="{{ asset('assets/images/customer.svg') }}">
                                </div>
                                <div class="service-shift-card-text">
                                  <h2>Customer Name:</h2>
                                  <p>{{ $schedule->customer->name ?? 'N/A' }}</p>
                                </div>
                              </div>
                            </div>

                            <div class="col-md-6 col-sm-6 col-lg-4">
                              <div class="service-shift-card">
                                <div class="service-shift-card-image">
                                  <img src="{{ asset('assets/images/ic-sub-category.svg') }}">
                                </div>
                                <div class="service-shift-card-text">
                                  <h2>Sub-Category</h2>
                                  <p>{{ $schedule->subCategory->sub_category ?? 'N/A' }}</p>
                                </div>
                              </div>
                            </div>

                            <div class="col-md-6 col-sm-6 col-lg-4">
                              <div class="service-shift-card">
                                <div class="service-shift-card-image">
                                  <img src="{{ asset('assets/images/ic-dollar-circle.svg') }}">
                                </div>
                                <div class="service-shift-card-text">
                                  <h2>Price</h2>
                                  <p>${{ $schedule->userService->price_per_hour ?? '0.00' }}</p>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>

                        <div class="ongoing-services-item-foot">
                          <div class="loaction-address">
                            <img src="{{ asset('assets/images/location.svg') }}">
                            {{ $schedule->location ?? 'N/A' }}
                          </div>
                        </div>
                      </div>

                    @empty
                      <p class="text-center">No ongoing services available.</p>
                    @endforelse
                  </div>
                </div>

                <div class="tab-pane" id="AssignedServices" role="tabpanel">
                    <div class="ongoing-services-list">
                        @forelse($assignedSchedules as $job)
                        <div class="ongoing-services-item">
                            <div class="ongoing-services-item-head">
                                <div class="ongoing-services-item-title">
                                    <h2>Service: {{ $job->service->name }}</h2>
                                </div>
                                <div class="ongoing-checkin-action">
                                    <a class="px-4 start-checkin-btn {{ \Carbon\Carbon::parse($job->end_date)->isBefore(\Carbon\Carbon::today()) ? 'disabled' : '' }}"
                                        href="javascript:void(0);" data-id="{{ $job->id }}">
                                        Start Job
                                    </a>
                                </div>
                            </div>
                            <div class="ongoing-services-item-body">
                                <div class="row d-flex">
                                    <div class="col-lg-6 service-shift-card">
                                        <div class="service-shift-card-image">
                                            <img src="{{ asset('assets/images/time.svg') }}">
                                        </div>
                                        <div class="service-shift-card-text">
                                            <h2>Service Shift Timing:</h2>
                                            <p>{{ \Carbon\Carbon::parse($job->start_time)->format('h:iA') }} - {{
                                                \Carbon\Carbon::parse($job->end_time)->format('h:iA') }}</p>
                                        </div>
                                    </div>

                                    <div class="col-lg-6  service-shift-card">
                                        <div class="service-shift-card-image">
                                            <img src="{{ asset('assets/images/time.svg') }}">
                                        </div>
                                        <div class="service-shift-card-text">
                                            <h2>Service Shift Date:</h2>
                                            <p>{{ \Carbon\Carbon::parse($job->start_date)->format('m/d/Y') }} - {{
                                                \Carbon\Carbon::parse($job->end_date)->format('m/d/Y') }}</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="instructions-text">
                                    <h3>Primary Instructions: {{ $job->description ?? 'N/A' }}</h3>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 col-sm-6 col-lg-4">
                                        <div class="service-shift-card">
                                            <div class="service-shift-card-image">
                                                <img src="https://nileprojects.in/client-portal/public/assets/images/customer.svg">
                                            </div>
                                            <div class="service-shift-card-text">
                                                <h2>Customer Name:</h2>
                                                <p>{{ $job->customer->name }}</p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6 col-sm-6 col-lg-4">
                                        <div class="service-shift-card">
                                            <div class="service-shift-card-image">
                                                <img
                                                    src="https://nileprojects.in/client-portal/public/assets/images/ic-sub-category.svg">
                                            </div>
                                            <div class="service-shift-card-text">
                                                <h2>Sub-Category</h2>
                                                <p>{{ $job->subCategory ? $job->subCategory->sub_category : 'N/A' }}</p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6 col-sm-6 col-lg-4">
                                        <div class="service-shift-card">
                                            <div class="service-shift-card-image">
                                                <img
                                                    src="https://nileprojects.in/client-portal/public/assets/images/ic-dollar-circle.svg">
                                            </div>
                                            <div class="service-shift-card-text">
                                                <h2>Price</h2>
                                                <p>${{ $job->userService->price_per_hour ?? '0.00' }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="ongoing-services-item-foot">
                                <div class="loaction-address"><img
                                        src="https://nileprojects.in/client-portal/public/assets/images/location.svg"> {{ $job->location
                                    ?? 'N/A' }}</div>
                            </div>
                        </div>
                        @empty
                        <p class="text-center">No Assigned services available.</p>
                        @endforelse
                    </div>
                </div>

              </div>
            </div>
          </div>
        </div>

      </div>

    </div>
  </div>
</body>

</html>

<script src="{{ asset('assets/js/owl.carousel.js') }}" type="text/javascript"></script>
<script>
$(document).ready(function () {
    // Initialize Owl Carousel
    $('#Ongoingcalender').owlCarousel({
        loop: false,
        margin: 10,
        nav: true,
        responsive: {
            0: { items: 2 },
            600: { items: 4 },
            1000: { items: 6 }
        }
    });

    let owl = $('#Ongoingcalender');
    owl.owlCarousel({
        items: 5,
        margin: 10,
        loop: false,
        nav: true,
        dots: false,
    });

    // Scroll to selected date on load
    let selectedIndex = $('#selectedCalendarItem').closest('.owl-item').index();
    if (selectedIndex >= 0) {
        owl.trigger('to.owl.carousel', [selectedIndex, 300]);
    }

    // Combined click handler for date selection
    $(document).on('click', '.selectable-date', function () {
        let selectedDate = $(this).data('date');

        // Set selected class
        $('.selectable-date').removeClass('selected-date');
        $(this).addClass('selected-date');

        // Set hidden input and submit form
        $('#selectedDateInput').val(selectedDate);
        $('#calendarForm').submit();
    });

    // Start check-in button
    $(document).on('click', '.start-checkin-btn', function () {
        var serviceId = $(this).data('id');
        var attendanceUrl = "{{ route('user.attendance') }}?service_id=" + serviceId;
        window.location.href = attendanceUrl;
    });
});
</script>

<script>
    document.getElementById('refreshFilters').addEventListener('click', function () {
        const url = new URL(window.location.href);

        // Clear specific filters
        url.searchParams.delete('selected_date');

        // Optional: clear other query params if needed
        // url.searchParams.delete('status');
        // url.searchParams.delete('search');

        window.location.href = url.toString();
    });
</script>

<script>
  $(document).on('click', '.mark-complete-btn', function () {
      const jobId = $(this).data('job-id');

      $.ajax({
          url: "{{ route('user.employee.markComplete') }}",
          type: "POST",
          data: {
              _token: "{{ csrf_token() }}",
              job_id: jobId,
              status: 2
          },
          success: function (response) {
              toastr.success('Job marked as complete!');
              window.location.reload();
              // Optionally, reload or update the UI
          },
          error: function (xhr) {
              toastr.error('Failed to mark job as complete.');
              console.error(xhr.responseText);
          }
      });
  });
</script>
@endsection