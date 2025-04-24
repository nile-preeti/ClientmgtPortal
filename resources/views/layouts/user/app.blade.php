<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>Check-in/Check-out with Map</title>
    <!-- Favicon -->
    <link rel="shortcut icon" href="{{ asset('assets/images/favicon.ico') }}" />
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}" />
    <!-- Chart list Js -->
    <link rel="stylesheet" href="{{ asset('assets/js/chartist/chartist.min.css') }}" />
    <!-- Typography CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/typography.css') }}" />
    <!-- Style CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}" />
    <!-- Responsive CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/responsive.css') }}" />
    <script src="{{ asset('assets/js/jquery.min.js') }}"></script>
    <script src="{{ asset('plugins/jquery-validation/jquery.validate.js') }}"></script>
    @stack('css')
    <script src="https://unpkg.com/dropzone@5/dist/min/dropzone.min.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/dropzone@5/dist/min/dropzone.min.css" type="text/css" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script src="https://api.mapbox.com/mapbox-gl-js/v2.15.0/mapbox-gl.js"></script>
    <link href="https://api.mapbox.com/mapbox-gl-js/v2.15.0/mapbox-gl.css" rel="stylesheet" />
    <script src="{{ asset('jquery.js') }}"></script>
    <script src="{{ asset('plugins/bootstrap/js/bootstrap.min.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('plugins/bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('style.css') }}">

    <link rel="shortcut icon" href="{{ asset('hrmodule.png') }}" type="image/png">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>
    <style>
        .search-filter-info .form-group select.form-control {
            padding: 10px 15px 14px 15px !important;
        }
    </style>
    
 
</head>

<body class="sidebar-main-active right-column-fixed header-top-bgcolor">
    <!-- Wrapper Start -->
    <div class="wrapper">
        <!-- Sidebar  -->
        <div class="iq-sidebar">
            <div class="iq-sidebar-logo d-flex justify-content-center">
                <a href="#">
                @php
                    $logo = \App\Models\Logo::first();
                @endphp

                    <div class="iq-light-logo">
                        <div class="iq-light-logo">
                        <img src="{{ $logo && file_exists(public_path('uploads/logo/' . $logo->name)) ? asset('uploads/logo/' . $logo->name) : asset('hrmodule.png') }}" height="100" class="" alt="Logo" />
                        </div>
                    </div>
                </a>
                <div class="iq-menu-bt-sidebar">
                    <div class="iq-menu-bt align-self-center">
                        <div class="wrapper-menu">
                            <div class="main-circle">
                                <i class="ri-arrow-left-s-line"></i>
                            </div>
                            <div class="hover-circle">
                                <i class="ri-arrow-right-s-line"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div id="sidebar-scrollbar">
            <nav class="iq-sidebar-menu">
                    <ul id="iq-sidebar-toggle" class="iq-menu">
                        <li  class="active">
                            <a href="{{route('user.dashboard')}}" class="iq-waves-effect"><i class="ri-home-4-line"></i>
                                <span>Dashboard</span></a>
                        </li>

                        <li class="">
                            <a href="{{route('user.services')}}" class="iq-waves-effect">
                                <i class="ri-user-settings-line"></i><span>Services</span></a>
                        </li>
                        <li>
                            <a href="{{route('user.attendance_records')}}" class="iq-waves-effect">
                                <i class="ri-user-3-line"></i><span>Attendance</span></a>
                        </li>
                        <li>
                            <a href="{{route('user.payout')}}" class="iq-waves-effect">
                                <i class="ri-calendar-event-line"></i><span>Payout</span></a>
                        </li>
                        <li>
                            <a href="" class="iq-waves-effect">
                                <i class="ri-list-check-2"></i><span>Sign Out</span></a>
                        </li>
                    </ul>
                </nav>
                <div class="p-3"></div>
            </div>
        </div>
        <!-- TOP Nav Bar -->
        <!-- TOP Nav Bar -->
        <div class="iq-top-navbar">
            <div class="iq-navbar-custom">
                <nav class="navbar navbar-expand-lg navbar-light p-0">
                    <div class="navbar-left">
                        <div class="iq-search-bar d-none d-md-block">
                            <form action="#" class="searchbox">

                                <h4 style="display: inline"></h4>
                            </form>
                        </div>
                    </div>
                    <button class="navbar-toggler" type="button" data-toggle="collapse"
                        data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                        aria-label="Toggle navigation">
                        <i class="ri-menu-3-line"></i>
                    </button>
                    <div class="iq-menu-bt align-self-center">
                        <div class="wrapper-menu">
                            <div class="main-circle"><i class="ri-arrow-left-s-line"></i></div>
                            <div class="hover-circle"><i class="ri-arrow-right-s-line"></i></div>
                        </div>
                    </div>
                    <div class="collapse navbar-collapse" id="navbarSupportedContent">

                    </div>
                    <ul class="navbar-list">
                        <li>
                            <div class="dropdown text-end">
                                  <a href="#" class="d-flex align-items-center link-body-emphasis text-decoration-none dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                    <img src="https://nileprojects.in/hrmodule/public/assets/images/image.png" alt="mdo" width="40" height="40" class="rounded-circle profile-image">
                                    <h6 class="m-0 p-0 text-light profile-name"> &nbsp; Profile</h6>
                                  </a>
                                  <ul class="dropdown-menu text-small" style="">
                                    <li><a class="dropdown-item" href="https://nileprojects.in/client-portal/user/profile">Profile</a></li>
                                    <li>
                                      <hr class="dropdown-divider">
                                    </li>
                                    <li><a class="dropdown-item" href="#" onclick="logout()">Sign out</a></li>
                                  </ul>
                                </div>
                                <!-- <img src="{{ asset('avatar.png') }}" class="img-fluid rounded mr-2"
                                    alt="user">
                                <div class="caption">
                                    <h6 class="mb-0 line-height text-white"></h6>
                                    <span class="font-size-12 text-white">Available</span>
                                </div> -->


                        </li>
                    </ul>
                </nav>
            </div>
        </div>
        <!-- TOP Nav Bar END -->
        <!-- TOP Nav Bar END -->
        @yield('content')
    </div>


    <!-- Wrapper END -->
    <!-- Footer -->
    <!-- <footer class="iq-footer">
        <div class="container-fluid">
            <div class="row"> -->
                <!-- <div class="col-lg-6">
                  <ul class="list-inline mb-0">
                     <li class="list-inline-item"><a href="privacy-policy.html">Privacy Policy</a></li>
                     <li class="list-inline-item"><a href="terms-of-service.html">Terms of Use</a></li>
                  </ul>
               </div> -->
                <!-- <div class="col-lg-12 text-center">
                    Copyright 2025 <a href="#">{{ config('constant.siteTitle') }}</a> All Rights
                    Reserved.
                </div>
            </div>
        </div>
    </footer> -->

    <!-- Footer END -->
    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->

    <script src="{{ asset('assets/js/Chart.js') }}"></script>

    <script src="{{ asset('assets/js/popper.min.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap.min.js') }}"></script>
    <!-- Appear JavaScript -->
    <script src="{{ asset('assets/js/jquery.appear.js') }}"></script>
    <!-- Countdown JavaScript -->
    <script src="{{ asset('assets/js/countdown.min.js') }}"></script>
    <!-- Counterup JavaScript -->
    <script src="{{ asset('assets/js/waypoints.min.js') }}"></script>
    <script src="{{ asset('assets/js/jquery.counterup.min.js') }}"></script>
    <!-- Apexcharts JavaScript -->
    <script src="{{ asset('assets/js/apexcharts.js') }}"></script>
    <!-- Slick JavaScript -->
    <!-- <script src="{{ asset('assets/js/slick.min.js') }}"></script> -->
    <!-- Select2 JavaScript -->
    <script src="{{ asset('assets/js/select2.min.js') }}"></script>
    <!-- Magnific Popup JavaScript -->
    <script src="{{ asset('assets/js/jquery.magnific-popup.min.js') }}"></script>
    <!-- Smooth Scrollbar JavaScript -->
    <script src="{{ asset('assets/js/smooth-scrollbar.js') }}"></script>
    <!-- lottie JavaScript -->
    <script src="{{ asset('assets/js/lottie.js') }}"></script>
    <!-- am core JavaScript -->
    <script src="{{ asset('assets/js/core.js') }}"></script>

    <script async src="{{ asset('assets/js/chart-custom.js') }}"></script>

    <!-- am animated JavaScript -->
    <script src="{{ asset('assets/js/animated.js') }}"></script>
    {{-- for dashbaor use only --}}
    {{-- <!-- ChartList Js -->
    <script src="{{ asset('assets/js/chartist/chartist.min.js') }}"></script>
    <!-- Chart Custom JavaScript -->
    <script async src="{{ asset('assets/js/chart-custom.js') }}"></script> --}}
    <!-- Custom JavaScript -->
    <script src="{{ asset('assets/js/custom.js') }}"></script>
    <script>
        function askLogout() {
            var title = ' you want to logout ?';
            Swal.fire({
                title: '',
                text: title,
                customClass: {
                    icon: 'no-border'
                },
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes'
            }).then((result) => {
                if (result.value) {
                    window.location = "{{ route('logout') }}";

                }

            })

        }
    </script>
    @stack('js')
</body>

</html>
