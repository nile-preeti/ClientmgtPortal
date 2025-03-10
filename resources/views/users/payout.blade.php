
<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>Check-in/Check-out with Map</title>
        <script src="https://api.mapbox.com/mapbox-gl-js/v2.15.0/mapbox-gl.js"></script>
        <link href="https://api.mapbox.com/mapbox-gl-js/v2.15.0/mapbox-gl.css" rel="stylesheet" />
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script src="{{ asset('jquery.js') }}"></script>
        <script src="{{ asset('plugins/bootstrap/js/bootstrap.min.js') }}"></script>
        <link rel="stylesheet" href="{{ asset('plugins/bootstrap/css/bootstrap.min.css') }}">
        <link rel="stylesheet" href="{{ asset('style.css') }}">
        <link rel="stylesheet" href="{{ asset('users/attendance_records.css') }}">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>


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

            .date-time-sec h2 {color: #000 !important;}

            .bg-gradient-danger{background-image: linear-gradient(310deg, #ff315a, #f56036);}
            .bg-gradient-success{background-image: linear-gradient(310deg, #54f9b2, #2dcecc);}
            .bg-gradient-warning{background-image: linear-gradient(310deg, #fa613e, #ffb33e);}
/*            .bg-gradient-warning{background-image: linear-gradient(310deg, #00a1ff, #60c4ff);}*/
            
            .ic-dash img{height: 80px; background: #fff; padding: 14px; border-radius: 12px;}
            .card.card-img-holder {position: relative;}
            .card.card-img-holder .card-img-absolute {position: absolute; top: -170px; right: -14px; height: 440px;}
            .header {background:#064086; }
            .header img.logo {background: #fff; padding: 8px; border-radius: 6px;}
            .dropdown-toggle::after{color: #fff;}
            .profile-image{border: 2px solid #4183d1;}
            .btn.btn-submit{padding: 10px 20px; font-size: 14px; border: none; border-radius: 50px; background-color: #064086; color: white;  box-shadow: 0 0 10px hwb(0deg 0% 100% / 5%);}
            .profile-form .mt-4{margin-top: 20px;}
            .profile-form .form-control{padding: 10px;}

            @media(max-width:767px) {
            .user-profile{margin: 20px 0px; }
            .res-fields {display: flex; justify-content: space-between;}

            }
            .swal2-confirm{
                background-color: #0069ac !important;
                border: 1px solid #064086 !important;
                color: #fff !important;
                padding: 9px 30px;
                border-radius: 5px;
            } 

            .swal2-confirm:hover{background: #fff !important;}

            .swal2-cancel {    padding: 10px 20px;
                font-size: 14px;
                border: none;
                border-radius: 5px;
                background-color: #c93126 !important;
                color: white;
                font-weight: 500;
                display: inline-block;
            }
           
            div#swal2-html-container {
                color: #000;
                font-weight: 500;
            }

            .swal2-popup.swal2-modal.swal2-show{padding: 40px;}

            .res-fields label{color: #595959;}
            .res-fields p{font-weight: 500; color: #000;}

            .res-fields-1 label{color: #595959;}
            .res-fields-1 p{font-weight: 500; color: #000;}
.profile-page-section{padding: 1rem 0; position: relative;}

.profile-head{display: flex;align-items: center; justify-content: space-between; margin-bottom: 1rem;}
.profile-head h2 {
    font-size: 20px;
    font-weight: 600;
    margin: 0;
    padding: 0;
    color: var(--black);
}

.user-table-item{box-shadow: 0 0 #0000,0 0 #0000, 0px 12px 28px 0px rgba(36,7,70,.06); margin-bottom: 10px; background: var(--white); padding: 10px; border-radius: 10px; position: relative;}

.user-profile-item {padding: 0 0 0rem 0; display: flex; align-items: center; }
.user-profile-media {width: 60px; height: 60px; position: relative; overflow: hidden; border-radius: 50%; margin: 0 10px 0 0; border: 1px solid #eaedf7; }
.user-profile-media img {object-fit: cover; width: 100%; height: 100%; }
.user-profile-text h2 {font-size: 16px; font-weight: 600; margin: 0 0 5px 0; padding: 0; color: var(--black); }
.user-contact-info {position: relative; width: 100%; border-radius: 0; padding:  0; display: flex; gap: 5px; margin-bottom: 0rem; }
.user-contact-info-icon {width: 32px; height: 32px; line-height: 32px; }
.user-contact-info-icon img {height: 32px; }
.user-contact-info-content h2 {    font-size: 12px; font-weight: 700; margin: 0 0 5px 0; padding: 0; color:var(--black); }
.user-contact-info-content p {font-size: 14px; font-weight: normal; margin: 0; text-align: justify; overflow: hidden; display: -webkit-box; -webkit-line-clamp: 2; line-clamp: 2; -webkit-box-orient: vertical; color: var(--gray); }

.email-text{font-size: 13px; font-weight: 400; margin: 0 0 5px 0; padding: 0; color: var(--gray); }

.User-contact-info {position: relative; width: 100%; border-radius: 0; padding:  0; display: flex;  margin-bottom: 0rem; }
.User-contact-info-icon {width: 40px; height: 40px; margin-right: 10px; line-height: 40px; }
.User-contact-info-icon img {height: 32px; }
.User-contact-info-content h2 {    font-size: 12px; font-weight: 700; margin: 0 0 5px 0; padding: 0; color: #4f5168; }

.User-contact-info-content p {font-size: 12px; margin: 0; color: #8C9AA1; line-height: normal; font-weight: normal; }

.user-profile-action{display: flex; align-items: center; gap: 10px;}

.cat-text {background: var(--body); padding: 3px 20px; display: inline-block; font-size: 13px; width: 100%; color: var(--purple); border-radius: 5px; font-weight: bold; text-align: center; }




        </style>


    </head>

    <body>
    <header class="header py-2">
        <div class="container-fluid">
          <div class="d-flex flex-wrap align-items-center justify-content-between">
            
            <a href="#"> <img src="{{asset('hrmodule.png')}}" class="logo card-img-absolute" alt="circle-image" height="50px"></a>

           


            <div class="dropdown text-end">
              <a href="#" class="d-flex align-items-center link-body-emphasis text-decoration-none dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
              <img src="{{ auth()->user()->image ? asset('uploads/images/' . auth()->user()->image) : 'https://nileprojects.in/client-portal/public/assets/images/image.png' }}" 
     alt="mdo" width="40" height="40" class="rounded-circle">
                <h6 class="m-0 p-0 text-light profile-name"> &nbsp; Profile</h6>
              </a>
              <ul class="dropdown-menu text-small" style="">
                <li><a class="dropdown-item" href="{{route('user.profile')}}">Profile</a></li>
                <li><hr class="dropdown-divider"></li>
                <li><a class="dropdown-item" href="#" onclick="logout()">Sign out</a></li>
              </ul>
            </div>
          </div>
        </div>
    </header>
    <div class="profile-page-section">
        <div class="container">
            <div class="profile-head">
                    <h2>
                        <a href="{{route('user.dashboard')}}"><img src="https://nileprojects.in/hrmodule/public/assets/images/arrow-left.svg" class="ic-arrow-left"> </a>Payouts
                    </h2>
                    <div class="cp-date">Total Earning:<span> $0</span>
                    </div>
            </div>
            <div class="profile-records-body">
                <table class="table table-bordered">
                  <thead>
                    <tr>
                      <th scope="col">#</th>
                      <th scope="col">First</th>
                      <th scope="col">Last</th>
                      <th scope="col">Handle</th>
                      <th scope="col">Handle</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <th scope="row">1</th>
                      <td>Mark</td>
                      <td>Otto</td>
                      <td>@mdo</td>
                      <td>@mdo</td>
                    </tr>
                    <tr>
                      <th scope="row">2</th>
                      <td>Jacob</td>
                      <td>Thornton</td>
                      <td>@fat</td>
                      <td>@fat</td>
                    </tr>
                    <tr>
                      <th scope="row">3</th>
                      <td>Jacob</td>
                      <td>Thornton</td>
                      <td>@fat</td>
                      <td>@fat</td>
                    </tr>
                    
                  </tbody>
                </table>
            </div>
    </div>
    <!-- Change Password Modal -->
  <div class="modal fade" id="changePasswordModal" tabindex="-1" role="dialog" aria-labelledby="changePasswordModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">

        <div class="modal-body">
            <h6>Change Password</h6>
        <form id="changePasswordForm">
            @csrf
            <div class="form-group">
                <label for="new_password">New Password</label>
                <input type="password" class="form-control" id="new_password" name="new_password" required>
            </div>
            <div class="form-group">
                <label for="confirm_password">Confirm Password</label>
                <input type="password" class="form-control" id="confirm_password" name="new_password_confirmation" required>
            </div>
            <div id="passwordError" class="text-danger d-none">Passwords do not match!</div>
            <div class="modal-footer1 mt-2">
                <button type="button" class="btnCancel" data-dismiss="modal">Cancel</button>
                <button type="button" class="btnUpdate" id="submitPasswordChange">Update</button>
            </div>
        </form> 

        </div>
        
      </div>
    </div>
  </div>
        <script>
          function logout() {

            var title = 'Are you sure, you want to logout ?';
            Swal.fire({
              title: '',
              text: title,
              // iconHtml: '<img src="{{ asset('assets/images/question.png') }}" height="25px">',
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

                // localStorage.removeItem('user')
                $.get("{{ route('user.logout') }}", function(data) {
                  if (data.success) {
                    Swal.fire({
                        title: "",
                        text: "Logged out successfully", // Show only the text
                        iconHtml: "", // Removes the default success icon
                        showConfirmButton: true,
                        confirmButtonText: "OK"
                    }).then((result) => {
                        if (result.value) {
                            location.replace("{{ route('user.login') }}");
                        }
                    });
                }
                })


              }

            })

            }
        </script>
        <script>
    $(document).ready(function() {
        $("#submitPasswordChange").click(function() {
            let newPassword = $("#new_password").val();
            let confirmPassword = $("#confirm_password").val();

            if (newPassword !== confirmPassword) {
                $("#passwordError").removeClass("d-none");
                return;
            } else {
                $("#passwordError").addClass("d-none");
            }

            $.ajax({
                url: "{{ route('user.change.password') }}", // Your backend route
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    new_password: newPassword,
                    new_password_confirmation: confirmPassword
                },
                success: function(response) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: 'Password updated successfully!',
                        confirmButtonColor: '#3085d6',
                        confirmButtonText: 'OK'
                    }).then(() => {
                      location.reload();
                        $("#changePasswordModal").modal("hide");  // Hide the modal
                        $("#changePasswordForm")[0].reset();  // Reset the form
                        $("body").removeClass("modal-open");  // Fix body overflow issue
                        $(".modal-backdrop").remove();  // Remove modal overlay
                    });
                },
                error: function(xhr) {
                    let errorMessage = "Something went wrong! Try again.";
                    if (xhr.responseJSON && xhr.responseJSON.errors) {
                        errorMessage = Object.values(xhr.responseJSON.errors).flat().join("\n");
                    }
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: errorMessage,
                        confirmButtonColor: '#d33',
                        confirmButtonText: 'OK'
                    });
                }
            });
        });
    });
</script>
    </body>

    </html>

   

