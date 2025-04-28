@extends('layouts.user.app')
@section('content')
    <script src="https://api.mapbox.com/mapbox-gl-js/v2.15.0/mapbox-gl.js"></script>
    <link href="https://api.mapbox.com/mapbox-gl-js/v2.15.0/mapbox-gl.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('jquery.js') }}"></script>
    <script src="{{ asset('plugins/bootstrap/js/bootstrap.min.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('plugins/bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('style.css') }}">
    <link rel="stylesheet" href="{{ asset('users/attendance_records.css') }}">

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

        button:disabled {
            background-color: #cccccc;
            cursor: not-allowed;
        }

        .info {
            text-align: center;
            font-size: 13px;
            margin-top: 5px;
        }

        #pagination-controls {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-top: 20px;
        }

        #pagination-controls button {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 10px 20px;
            font-size: 16px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.3s ease;
            margin: 0 10px;
        }

        #pagination-controls button:hover {
            background-color: #0056b3;
            transform: scale(1.05);
        }

        #pagination-controls button:disabled {
            background-color: #ccc;
            cursor: not-allowed;
        }

        #page-info {
            font-size: 16px;
            margin: 0 20px;
            color: #333;
        }

        .header {
            background: #064086;
        }

        .header img.logo {
            background: #fff;
            padding: 8px;
            border-radius: 6px;
        }

        .dropdown-toggle::after {
            color: #fff;
        }

        .profile-image {
            border: 2px solid #4183d1;
        }
        
        #recordsList li:first-child{margin-top: 0px !important;}
        .swal2-confirm{
                background-color: #ffffff !important;
                border: 1px solid #064086 !important;
                color: #064086 !important;
                padding: 9px 30px;
                border-radius: 50px;
            } 

            .swal2-confirm:hover{background: #fff !important;}

            .swal2-cancel {    padding: 10px 20px;
                font-size: 14px;
                border: none;
                border-radius: 50px;
                background-color: #064086 !important;
                color: white;
                font-weight: 500;
                display: inline-block;
            }
           
            div#swal2-html-container {
                color: #000;
                font-weight: 500;
            }

            .swal2-popup.swal2-modal.swal2-show{padding: 40px;}


            .attendance-records-head .form-control {position: relative; color: var(--gray); border-radius: 5px; font-weight: 400; font-size: 13px; box-sizing: border-box; padding:12px 15px 12px 15px; border: 1px solid var(--border); width: 100%; background: #FFF; box-shadow: 0px 8px 13px 0px rgba(0, 0, 0, 0.05); appearance: auto; }

            .attendance-records-head .btn-search {background: #064086; white-space: nowrap; width: 100%; padding: 10px 20px; display: inline-block; font-size: 13px; color: var(--white); border-radius: 5px; font-weight: 600; text-align: center; box-shadow: 0px 8px 13px 0px rgba(0, 0, 0, 0.05); border: none; }


            .services-page-section{padding: 1rem 0; position: relative; margin-left: 270px;}
            .recordsList{list-style: none; padding: 0; margin: 0;}

            .attendance-records-head {display: flex ; align-items: center; justify-content: space-between; margin-bottom: 1rem; }
            .attendance-records-head h2 {font-size: 20px; font-weight: 600; margin: 0; padding: 0; color: var(--black); }
            .cp-card {box-shadow: 0 0 #0000, 0 0 #0000, 0px 12px 28px 0px rgba(36, 7, 70, .06); background: var(--white); border-radius: 10px; position: relative; }

            .cp-card-head {display: flex ; align-items: center; padding: 10px; border-bottom:2px solid #f5f5fd; }
            .cp-date {font-size: 13px; font-weight: bold; margin: 0 0 0px 0; color: #064086; padding: 0; }
            .cp-card-body{padding: 10px;}
            .cp-point-box {display: flex ; gap: 10px; align-items: center; margin-bottom: 10px; }
            .cp-point-icon{background: #fafafa; padding: 10px; border-radius:5px; }

            .cp-point-text h4 {font-size: 13px; font-weight: bold; margin: 0 0 5px 0; color: #064086; padding: 0; } 
            .cp-point-text p {    font-size: 13px; font-weight: 400; margin: 0 0 0px 0; color:#000; padding: 0; line-height: 22px; }
            .cp-action-btn a {position: relative; color: var(--gray); border-radius: 5px; font-weight: 400; font-size: 13px; box-sizing: border-box; padding: 8px; border: 1px solid var(--border); background: #FFF; box-shadow: 0px 8px 13px 0px rgba(0, 0, 0, 0.05); margin-left: 10px; display: inline-block; }

            .badge {
                padding: .3em .6em;
                line-height: 1.3;
                text-transform: capitalize;
            }

            .iq-bg-primary {
                background: rgb(224 243 255);
                color: var(--iq-primary) !important;
                border: 1px solid #a6dcff;
            }

            .iq-bg-success {
                background-color: #28a745 !important; /* Green */
                color: white !important;
                padding: 5px 10px;
                border-radius: 5px;
            }

            .iq-bg-warning {
                background-color: #ffc107 !important; /* Yellow */
                color: black !important;
                padding: 5px 10px;
                border-radius: 5px;
            }

            button.mark-complete:disabled {
                border: 1px solid #dbd8d8 !important;
                color: grey !important;
                font-size: 12px;
                background-color: #f9f9f9 !important;
                cursor: not-allowed;
            }

            button.check_in:disabled {
                border: 1px solid #dbd8d8 !important;
                color: grey !important;
/*                margin-left: 10px;*/
                font-size: 12px;
                background-color: #f9f9f9 !important;
                cursor: not-allowed;
            }

            button.check_in {
                padding: 6px 20px;
                font-size: 12px;
                border: none;
                border-radius: 5px;
                background-color: #064086;
                color: white;
                font-weight: 500;
                display: inline-block;
                width: 100%;
                position: relative;
                box-shadow: 0 0 10px hwb(0deg 0% 100% / 5%);
            }

            .mark-complete{
                padding: 6px 20px;
                font-size: 14px;
                border: none;
                border-radius: 5px;
                background-color: #ffc107;
                color: white;
                font-weight: 500;
                display: inline-block;
                width: 100%;
                position: relative;
                box-shadow: 0 0 10px hwb(0deg 0% 100% / 5%);
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
    <!-- <header class="header py-2">
        <div class="container-fluid">
            <div class="d-flex flex-wrap align-items-center justify-content-between">
            @php
                $logo = \App\Models\Logo::first();
            @endphp
            <a href="#"> <img src="{{ $logo && file_exists(public_path('uploads/logo/' . $logo->name)) ? asset('uploads/logo/' . $logo->name) : asset('hrmodule.png') }}" class="logo card-img-absolute" alt="circle-image" height="50px"></a>




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
    <div class="services-page-section mt-5">
        <div class="container mt-5">
            <div class="attendance-records-head mt-5">
                <h2>
                    <a href="{{route('user.dashboard')}}"><img src="https://nileprojects.in/hrmodule/public/assets/images/arrow-left.svg" class="ic-arrow-left"> </a>Services
                </h2>
            </div>
            <div class="attendance-records-body">
                <div class="attendance-records-content">
                <div class="services-tabs">
                    <ul class="nav nav-tabs" role="tablist">

                    <li><a href="#AssignedServices" data-bs-toggle="tab" aria-selected="true" role="tab" class="active" tabindex="-1">Assigned</a>
                    </li>
                    <li><a href="#UnAssignedServices" data-bs-toggle="tab" aria-selected="false" role="tab" class="" tabindex="-1"> Completed</a>
                    </li>
                    
                    <button class="btn btn-md" style="border: 1px solid #064086; border-radius: 30px; background: #064086;" id="refreshFilters">
                        <img src="https://nileprojects.in/client-portal/public/ic-reset.svg" alt="">
                    </button>
                    </ul>
                </div>
                <div class="" style="width: 100%;">
                    <div class="row">
                        <div class="col-md-12">
                        <div class="Ongoing-calender-list">
                        @php
                            $today = \Carbon\Carbon::today()->toDateString();
                            $selected = request('selected_date') ?? $today;
                        @endphp
                        <form method="GET" id="calendarForm" action="{{ route('user.services') }}">
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

                            <div class="tab-pane" id="UnAssignedServices" role="tabpanel">
                            <div class="ongoing-services-list">
                            @forelse($completedSchedules as $job)
                                <div class="ongoing-services-item">
                                    <div class="ongoing-services-item-head">
                                        <div class="ongoing-services-item-title">
                                            <h2>Service: {{ $job->service->name }}</h2>
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
                                                <p>{{ \Carbon\Carbon::parse($job->start_time)->format('h:iA') }} - {{ \Carbon\Carbon::parse($job->end_time)->format('h:iA') }}</p>
                                                </div>
                                            </div>

                                            <div class="col-lg-6  service-shift-card">
                                                <div class="service-shift-card-image">
                                                <img src="{{ asset('assets/images/time.svg') }}">
                                                </div>
                                                <div class="service-shift-card-text">
                                                    <h2>Service Shift Date:</h2>
                                                    <p>{{ \Carbon\Carbon::parse($job->start_date)->format('m/d/Y') }} - {{ \Carbon\Carbon::parse($job->end_date)->format('m/d/Y') }}</p>
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
                                                        <img src="https://nileprojects.in/client-portal/public/assets/images/ic-sub-category.svg">
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
                                                        <img src="https://nileprojects.in/client-portal/public/assets/images/ic-dollar-circle.svg">
                                                    </div>
                                                    <div class="service-shift-card-text">
                                                        <h2>Price</h2>
                                                        <p>${{ $job->userService->price_per_hour ?? '0.00' }}</p>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-6 col-sm-6 col-lg-4">
                                                <div class="service-shift-card">
                                                    <div class="service-shift-card-image">
                                                        <img src="https://nileprojects.in/client-portal/public/assets/images/ic-dollar-circle.svg">
                                                    </div>
                                                    <div class="service-shift-card-text">
                                                        <h2>Total Earning</h2>
                                                        <p>${{ number_format($job->total_earning, 2) }}</p>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-6 col-sm-6 col-lg-4">
                                                <div class="service-shift-card">
                                                    <div class="service-shift-card-image">
                                                        <img src="https://nileprojects.in/client-portal/public/assets/images/ic-dollar-circle.svg">
                                                    </div>
                                                    <div class="service-shift-card-text">
                                                        <h2>Net Earning</h2>
                                                        <p>${{ number_format($job->net_earning, 2) }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="ongoing-services-item-foot">
                                        <div class="loaction-address"><img src="https://nileprojects.in/client-portal/public/assets/images/location.svg"> {{ $job->location ?? 'N/A' }}</div>
                                    </div>
                                </div>
                                @empty
                                <p class="text-center">No completed services available.</p>
                                @endforelse
                                </div>
                            </div>

                            <div class="tab-pane  active show" id="AssignedServices" role="tabpanel">
                            <div class="ongoing-services-list">
                            @forelse($assignedSchedules as $job)
                                <div class="ongoing-services-item">
                                    <div class="ongoing-services-item-head">
                                        <div class="ongoing-services-item-title">
                                            <h2>Service: {{ $job->service->name }}</h2>
                                        </div>
                                        <div class="ongoing-checkin-action">
                                        <a 
                                                class="px-4 start-checkin-btn {{ \Carbon\Carbon::parse($job->end_date)->isBefore(\Carbon\Carbon::today()) ? 'disabled' : '' }}" 
                                                href="javascript:void(0);" 
                                                data-id="{{ $job->id }}"
                                            >
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
                                                <p>{{ \Carbon\Carbon::parse($job->start_time)->format('h:iA') }} - {{ \Carbon\Carbon::parse($job->end_time)->format('h:iA') }}</p>
                                                </div>
                                            </div>

                                            <div class="col-lg-6  service-shift-card">
                                                <div class="service-shift-card-image">
                                                <img src="{{ asset('assets/images/time.svg') }}">
                                                </div>
                                                <div class="service-shift-card-text">
                                                    <h2>Service Shift Date:</h2>
                                                    <p>{{ \Carbon\Carbon::parse($job->start_date)->format('m/d/Y') }} - {{ \Carbon\Carbon::parse($job->end_date)->format('m/d/Y') }}</p>
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
                                                        <img src="https://nileprojects.in/client-portal/public/assets/images/ic-sub-category.svg">
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
                                                        <img src="https://nileprojects.in/client-portal/public/assets/images/ic-dollar-circle.svg">
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
                                        <div class="loaction-address"><img src="https://nileprojects.in/client-portal/public/assets/images/location.svg"> {{ $job->location ?? 'N/A' }}</div>
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
    </div>
    <script src="{{ asset('assets/js/owl.carousel.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/js/owl.carousel.js') }}" type="text/javascript"></script>
<script>
$(document).ready(function () {
    // Initialize Owl Carousel
    let owl = $('#Ongoingcalender');
    owl.owlCarousel({
        loop: false,
        margin: 10,
        nav: true,
        dots: false,
        responsive: {
            0: { items: 2 },
            600: { items: 4 },
            1000: { items: 6 }
        }
    });

    // Scroll to the selected date or today's date on load
    let selectedIndex = $('#selectedCalendarItem').closest('.owl-item').index();
    
    // If a date is selected, scroll to it
    if (selectedIndex >= 0) {
        owl.trigger('to.owl.carousel', [selectedIndex, 300]);
    } else {
        // Otherwise, scroll to today's date (if available)
        let today = new Date();
        let todayFormatted = today.getFullYear() + '-' +
            String(today.getMonth() + 1).padStart(2, '0') + '-' +
            String(today.getDate()).padStart(2, '0');
        
        let todayItem = $('.Ongoing-calender-item[data-date="' + todayFormatted + '"]').closest('.item');
        selectedIndex = todayItem.index();
        
        if (selectedIndex >= 0) {
            owl.trigger('to.owl.carousel', [selectedIndex, 300]);
        }
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
@endsection