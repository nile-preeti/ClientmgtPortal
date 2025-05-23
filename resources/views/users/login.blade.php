<!doctype html>
<html lang="en">
   <head>
      <!-- Required meta tags -->
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
      <title>HR module</title>
      <!-- Favicon -->
    
      <link rel="shortcut icon" href="{{asset("assets/images/favicon.ico")}}" />
        <!-- Bootstrap CSS -->
        <link rel="stylesheet" href="{{asset("assets/css/bootstrap.min.css")}}" />
        <!-- Chart list Js -->
        <link rel="stylesheet" href="{{asset("assets/js/chartist/chartist.min.css")}}" />
        <!-- Typography CSS -->
        <link rel="stylesheet" href="{{asset("assets/css/typography.css")}}" />
        <!-- Style CSS -->
        <link rel="stylesheet" href="{{asset("assets/css/style.css")}}" />
        <!-- Responsive CSS -->
        <link rel="stylesheet" href="{{asset("assets/css/responsive.css")}}" />
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script src="{{asset("assets/js/jquery.min.js")}}"></script>
        <script src="{{ asset('plugins/jquery-validation/jquery.validate.js') }}"></script>
        <style>

        
        </style>
<link rel="shortcut icon" href="{{asset("hrmodule.png")}}" type="image/png">

   </head>
   <body>

        <!-- Sign in Start -->
        <section class="sign-in-page">
            <div class="container-fluid bg-white p-0">
                <div class="row no-gutters sign-in-detail align-self-center justify-content-center d-flex">
                    
                    <!-- <div class="col-sm-6 col-lg-6 text-center">
                        <div class="sign-in-detail text-white">
                            <img src="{{ asset('login-graphic.png') }}" class="img-fluid" class="" alt="" />
                        </div>
                    </div>
                    <div class="col-sm-6 col-lg-6 align-self-center">
                        <div class="sign-in-from">
                            <div class="text-white mb-5">
                                <img src="{{ asset('login-logo.png') }}" height="70px" alt="" />
                            </div>
                           
                            <h3 class="mb-0 dark-signin font-weight-bold">Employee Login</h3>
                            <p>Please enter your details</p>
                            <form class="mt-4" id="signin_form" enctype="multipart/form-data" action="{{route('user.login_post')}}" method="POST">
                                @csrf
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Employee Id</label>
                                    <input type="text" name="emp_id" class="form-control mb-0" id="exampleInputEmail1" placeholder="Enter emp id">
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputPassword1">Password</label>
                                    <input type="password"  name="password" class="form-control mb-0" id="exampleInputPassword1" placeholder="Password">
                                </div>
                                <div class="d-inline-block w-100">
                                    <button type="submit" class="btn btn-primary float-left">Sign in</button>
                                    {{-- <a href="#" class="float-right">Forgot password?</a> --}}
                                </div>
                                {{-- <div class="sign-info">
                                    <span class="dark-color d-inline-block line-height-2">Don't have an account? <a href="sign-up.html">Sign up</a></span>
                                </div> --}}
                            </form>
                        </div>
                    </div> -->


                <div class="p-4 card overflow-hidden" style="box-shadow: 0px 8px 13px rgb(0 0 0 / 5%); border-radius: 15px; position: relative; background-color: rgb(255 255 255 / 80%); overflow: hidden; -webkit-backdrop-filter: saturate(200%) blur(5px); backdrop-filter: saturate(200%) blur(5px);">
                    <div class="row"> 
                        <div class="col-lg-6 col-sm-12">
                            <div class="auth-content">
                                <div class="auth-content-info text-center">
                                    <h4 class="text-black mb-2 font-weight-bold">Secure Access Portal</h4>
                                    <p class="mb-5 text-dark">Unlock The Power Of Seamless And Protected Interaction With Your Intuitive User Panel.</p>
                                    <div class="auth-illustration">
                                        <img class="mt-2" src="https://nileprojects.in/client-portal/public/admin-login-img.svg" alt="" width="100%">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-sm-12">
                            <div class="sign-in-from">
                                <div class="text-white text-center" style="margin: 20px 0;" >
                                    @php
                                        $logo = \App\Models\Logo::first();
                                    @endphp

                                    <img class="rounded-pill logo-res" src="{{ $logo && file_exists(public_path('uploads/logo/' . $logo->name)) ? asset('uploads/logo/' . $logo->name) : asset('hrmodule.png') }}" height="90px"  alt="" />
                                </div>
                               
                                <h4 class="mb-0 dark-signin font-weight-bold text-center text-white">Employee Login</h4>
                                <p class="text-center text-white">Please enter your details to login.</p>
                                <form class="mt-4" id="signin_form" enctype="multipart/form-data" action="{{route('user.login_post')}}" method="POST">
                                    @csrf
                                    <div class="form-group">
                                        <label class="text-white" for="exampleInputEmail1"><i class="ri-profile-line"></i> &nbsp;Employee Id</label>
                                        <input type="text" name="emp_id" class="form-control mb-0" id="exampleInputEmail1" placeholder="">
                                    </div>
                                    <div class="form-group">
                                        <label class="text-white" for="exampleInputPassword1"><i class="ri-lock-password-line"></i> &nbsp;Password</label>
                                        <input type="password"  name="password" class="form-control mb-0" id="exampleInputPassword1" placeholder="">
                                    </div>
                                    <div class="d-inline-block w-100">
                                        <button type="submit" class="  btn-signin-1"> <i class="ri-login-circle-line font-weight-normal"></i> &nbsp;Sign in</button>
                                        {{-- <a href="#" class="float-right">Forgot password?</a> --}}
                                    </div>
                                    {{-- <div class="sign-info">
                                        <span class="dark-color d-inline-block line-height-2">Don't have an account? <a href="sign-up.html">Sign up</a></span>
                                    </div> --}}
                                </form>
                            </div>
                        </div>

                </div>
            </div>
        </section>
        <!-- Sign in END -->
      <!-- Optional JavaScript -->
      <!-- jQuery first, then Popper.js, then Bootstrap JS -->
  
      <script src="{{asset("assets/js/popper.min.js")}}"></script>
      <script src="{{asset("assets/js/bootstrap.min.js")}}"></script>
      <!-- Appear JavaScript -->
      <script src="{{asset("assets/js/jquery.appear.js")}}"></script>
      <!-- Countdown JavaScript -->
      <script src="{{asset("assets/js/countdown.min.js")}}"></script>
      <!-- Counterup JavaScript -->
      <script src="{{asset("assets/js/waypoints.min.js")}}"></script>
      <script src="{{asset("assets/js/jquery.counterup.min.js")}}"></script>
      <!-- Wow JavaScript -->
      <script src="{{asset("assets/js/wow.min.js")}}"></script>
      <!-- Apexcharts JavaScript -->
      <script src="{{asset("assets/js/apexcharts.js")}}"></script>
      <!-- Slick JavaScript -->
      <script src="{{asset("assets/js/slick.min.js")}}"></script>
      <!-- Select2 JavaScript -->
      <script src="{{asset("assets/js/select2.min.js")}}"></script>
      <!-- Owl Carousel JavaScript -->
      <script src="{{asset("assets/js/owl.carousel.min.js")}}"></script>
      <!-- Magnific Popup JavaScript -->
      <script src="{{asset("assets/js/jquery.magnific-popup.min.js")}}"></script>
      <!-- Smooth Scrollbar JavaScript -->
      <script src="{{asset("assets/js/smooth-scrollbar.js")}}"></script>
      <!-- Chart Custom JavaScript -->
      {{-- <script src="{{asset("assets/js/chart-custom.js")}}"></script> --}}
      <!-- Custom JavaScript -->
      {{-- <script src="{{asset("assets/js/custom.js")}}"></script> --}}
      <script>
        $(document).ready(function() {
            $('#signin_form').validate({
                rules: {
                    emp_id: {
                        required: true,
                        maxlength: 4,
                        digits: true
                    },
                    password: {
                        required: true,
                        maxlength: 191,

                    },
                },
                messages: {
                    emp_id: {
                        required: "Required",
                        maxlength: "Max 4 digits allowed.",
                        digits: "Only numbers allowed."
                    },
                    password: {
                        required: "Required",
                        maxlength: "Max 191 characters allowed."
                    }
                },
                errorElement: "span",
                errorPlacement: function(error, element) {
                    error.addClass("text-danger");
                    element.closest(".form-group").append(error);
                },
                highlight: function(element, errorClass, validClass) {
                    $('.please-wait').click();
                    $(element).addClass("text-danger");
                },
                unhighlight: function(element, errorClass, validClass) {
                    $(element).removeClass("text-danger");
                },
                submitHandler: function(form, event) {
                    event.preventDefault();
                    let formData = new FormData(form);

                    $.ajax({
                        type: 'post',
                        url: form.action,
                        data: formData,
                        dataType: 'json',
                        contentType: false,
                        processData: false,

                        success: function(response) {
                            if (response.status == "success") {

                                Swal.fire({
                                    title: 'Success',
                                    text: response.message,
                                    icon: 'success',

                                }).then((result) => {

                                    if (response.redirect) {
                                        window.location.href = response.redirect;
                                    }
                                    // var url = $('#redirect_url').val();
                                    // if (url !== undefined || url != null) {
                                    //     window.location = url;
                                    // } else {
                                    //     location.reload(true);
                                    // }
                                })

                                return false;
                            }

                            if (response.status == "error") {
                                Swal.fire(
                                    'Error',
                                    response.message,
                                    'error'
                                );

                                return false;
                            }
                        },
                        error: function(data) {
                            if (data.status == 422) {
                                var form = $("#signin_form");
                                let li_htm = '';
                                $.each(data.responseJSON.errors, function(k, v) {
                                    const $input = form.find(
                                        `input[name=${k}],select[name=${k}],textarea[name=${k}]`
                                    );
                                    if ($input.next('small').length) {
                                        $input.next('small').html(v);
                                        if (k == 'services' || k == 'membership') {
                                            $('#myselect').next('small').html(v);
                                        }
                                    } else {
                                        $input.after(
                                            `<small class='text-danger'>${v}</small>`
                                        );
                                        if (k == 'services' || k == 'membership') {
                                            $('#myselect').after(
                                                `<small class='text-danger'>${v[0]}</small>`
                                            );
                                        }
                                    }
                                    li_htm += `<li>${v}</li>`;
                                });

                                return false;
                            } else {
                                Swal.fire(
                                    'Error',
                                    data.statusText,
                                    'error'
                                );
                            }
                            return false;

                        }
                    });
                }
            })
        });
    </script>
   </body>
</html>
