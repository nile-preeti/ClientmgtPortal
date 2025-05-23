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

    .date-time-sec h2 {
      color: #000 !important;
    }

    .bg-gradient-danger {
      background-image: linear-gradient(310deg, #ff315a, #f56036);
    }

    .bg-gradient-success {
      background-image: linear-gradient(310deg, #54f9b2, #2dcecc);
    }

    .bg-gradient-warning {
      background-image: linear-gradient(310deg, #fa613e, #ffb33e);
    }

    /*            .bg-gradient-warning{background-image: linear-gradient(310deg, #00a1ff, #60c4ff);}*/

    .ic-dash img {
      height: 80px;
      background: #fff;
      padding: 14px;
      border-radius: 12px;
    }

    .card.card-img-holder {
      position: relative;
    }

    .card.card-img-holder .card-img-absolute {
      position: absolute;
      top: -170px;
      right: -14px;
      height: 440px;
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

    .btn.btn-submit {
      padding: 10px 20px;
      font-size: 14px;
      border: none;
      border-radius: 50px;
      background-color: #064086;
      color: white;
      box-shadow: 0 0 10px hwb(0deg 0% 100% / 5%);
    }

    .profile-form .mt-4 {
      margin-top: 20px;
    }

    .profile-form .form-control {
      padding: 10px;
    }

    @media(max-width:767px) {
      .user-profile {
        margin: 20px 0px;
      }

      .res-fields {
        display: flex;
        justify-content: space-between;
      }

      .profile-records-body h6{margin-bottom: 6px !important;}

      .subtotal-section{ margin-left: 0px !important;}
      .subtotal-section table tr td{font-size: 14px; padding: 0px 3px;}
      .accordion-button{padding: 10px;}

    }

    .swal2-confirm {
      background-color: #0069ac !important;
      border: 1px solid #064086 !important;
      color: #fff !important;
      padding: 9px 30px;
      border-radius: 5px;
    }

    .swal2-confirm:hover {
      background: #fff !important;
    }

    .swal2-cancel {
      padding: 10px 20px;
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

    .swal2-popup.swal2-modal.swal2-show {
      padding: 40px;
    }

    .res-fields label {
      color: #595959;
    }

    .res-fields p {
      font-weight: 500;
      color: #000;
    }

    .res-fields-1 label {
      color: #595959;
    }

    .res-fields-1 p {
      font-weight: 500;
      color: #000;
    }

    .profile-page-section {
      padding: 1rem 0;
      position: relative;
    }

    .profile-head {
      display: flex;
      align-items: center;
      justify-content: space-between;
      margin-bottom: 1rem;
    }

    .profile-head h2 {
      font-size: 20px;
      font-weight: 600;
      margin: 0;
      padding: 0;
      color: var(--black);
    }

    .user-table-item {
      box-shadow: 0 0 #0000, 0 0 #0000, 0px 12px 28px 0px rgba(36, 7, 70, .06);
      margin-bottom: 10px;
      background: var(--white);
      padding: 10px;
      border-radius: 10px;
      position: relative;
    }

    .user-profile-item {
      padding: 0 0 0rem 0;
      display: flex;
      align-items: center;
    }

    .user-profile-media {
      width: 60px;
      height: 60px;
      position: relative;
      overflow: hidden;
      border-radius: 50%;
      margin: 0 10px 0 0;
      border: 1px solid #eaedf7;
    }

    .user-profile-media img {
      object-fit: cover;
      width: 100%;
      height: 100%;
    }

    .user-profile-text h2 {
      font-size: 16px;
      font-weight: 600;
      margin: 0 0 5px 0;
      padding: 0;
      color: var(--black);
    }

    .user-contact-info {
      position: relative;
      width: 100%;
      border-radius: 0;
      padding: 0;
      display: flex;
      gap: 5px;
      margin-bottom: 0rem;
    }

    .user-contact-info-icon {
      width: 32px;
      height: 32px;
      line-height: 32px;
    }

    .user-contact-info-icon img {
      height: 32px;
    }

    .user-contact-info-content h2 {
      font-size: 12px;
      font-weight: 700;
      margin: 0 0 5px 0;
      padding: 0;
      color: var(--black);
    }

    .user-contact-info-content p {
      font-size: 14px;
      font-weight: normal;
      margin: 0;
      text-align: justify;
      overflow: hidden;
      display: -webkit-box;
      -webkit-line-clamp: 2;
      line-clamp: 2;
      -webkit-box-orient: vertical;
      color: var(--gray);
    }

    .email-text {
      font-size: 13px;
      font-weight: 400;
      margin: 0 0 5px 0;
      padding: 0;
      color: var(--gray);
    }

    .User-contact-info {
      position: relative;
      width: 100%;
      border-radius: 0;
      padding: 0;
      display: flex;
      margin-bottom: 0rem;
    }

    .User-contact-info-icon {
      width: 40px;
      height: 40px;
      margin-right: 10px;
      line-height: 40px;
    }

    .User-contact-info-icon img {
      height: 32px;
    }

    .User-contact-info-content h2 {
      font-size: 12px;
      font-weight: 700;
      margin: 0 0 5px 0;
      padding: 0;
      color: #4f5168;
    }

    .User-contact-info-content p {
      font-size: 12px;
      margin: 0;
      color: #8C9AA1;
      line-height: normal;
      font-weight: normal;
    }

    .user-profile-action {
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .cat-text {
      background: var(--body);
      padding: 3px 20px;
      display: inline-block;
      font-size: 13px;
      width: 100%;
      color: var(--purple);
      border-radius: 5px;
      font-weight: bold;
      text-align: center;
    }

    .profile-records-body {
      box-shadow: 0 0 #0000, 0 0 #0000, 0px 12px 28px 0px rgba(36, 7, 70, .06);
      background: var(--white);
      border-radius: 10px;
      padding: 20px;
    }

    .profile-records-body h6 {
      margin: 0px;
      padding: 0px;
    }

    .accordion-button:not(.collapsed) {
      background: #064086 !important;
      color: #fff;
    }

    .table thead tr th {
      background: #dee2e6;
      color: #000000;
    }

    .subtotal-section {
      margin-left: 50px
    }

    .subtotal-section table {
      width: 100% !important;
    }

    .subtotal-section table tr td {
      border: 1px solid #dee2e6;
    }

    .accordion-button:not(.collapsed) {
      background: #fff;
    }

    .accordion {
      --bs-accordion-btn-focus-box-shadow: none !important;
    }

    .accordion-body {
      background: #f8fbfd;
    }

    .accordion-button::after {
      border-radius: 50px;
      padding: 10px !important;
      border: 5px solid #fff;
    }

    .accordion-button:not(.collapsed)::after {
      background-color: #fff;
      border: 6px solid #fff;
    }

    .accordion-header {
      margin-bottom: 0;
      padding: 0;
    }

    .btn-reload {
      width: 44px;
      height: 44px;
      color: #3d3e3e;
      white-space: nowrap;
      background: #fff;
      box-shadow: 0px 8px 13px 0px rgba(35, 53, 111, 0.12);
      display: inline-block;
      text-align: center;
      border-radius: 5px;
      font-size: 14px;
      font-weight: 600;
      border: 1px solid #3d3e3e;
      line-height: 40px;
    }

    .btn-filter{
      background: #064086;
      white-space: nowrap;
      width: 100%;
      padding: 10px 20px;
      display: inline-block;
      font-size: 13px;
      color: var(--white);
      border-radius: 5px;
      font-weight: 600;
      text-align: center;
      box-shadow: 0px 8px 13px 0px rgba(0, 0, 0, 0.05);
      border: none;
    }

    .active>.page-link, .page-link.active {
    z-index: 3;
    color: var(--bs-pagination-active-color);
    background-color: #064086;
    border-color: #064086;
    }
    .profile-page-section .form-control{
    position: relative;
    color: var(--gray);
    border-radius: 5px;
    font-weight: 400;
    font-size: 13px;
    box-sizing: border-box;
    padding: 12px 15px 12px 15px;
    border: 1px solid var(--border);
    width: 100%;
    background: #FFF;
    box-shadow: 0px 8px 13px 0px rgba(0, 0, 0, 0.05);
    appearance: auto;
    }
  </style>


</head>
@extends('layouts.user.app')
<body>
  <!-- <header class="header py-2">
    <div class="container-fluid">
      <div class="d-flex flex-wrap align-items-center justify-content-between">

      @php
          $logo = \App\Models\Logo::first();
      @endphp
        <a href="#"> <img src="{{ $logo && file_exists(public_path('uploads/logo/' . $logo->name)) ? asset('uploads/logo/' . $logo->name) : asset('hrmodule.png') }}" class="logo card-img-absolute" alt="circle-image" height="50px"></a>




        <div class="dropdown text-end">
          <a href="#" class="d-flex align-items-center link-body-emphasis text-decoration-none dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
            <img src="{{ auth()->user()->image ? asset('uploads/images/' . auth()->user()->image) : 'https://nileprojects.in/client-portal/public/assets/images/image.png' }}"
              alt="mdo" width="40" height="40" class="rounded-circle">
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
  <div class="profile-page-section">
    <div class="container">
      <div class="profile-head mt-3 mb-4">
        <h2>
          <a href="{{route('user.dashboard')}}"><img src="https://nileprojects.in/hrmodule/public/assets/images/arrow-left.svg" class="ic-arrow-left"> </a>Payouts
        </h2>
        <div class="cp-date d-none">Total Earning:<span> $0</span>
        </div>
      </div>
      <form method="GET" action="{{ route('user.payout') }}">
        <div class="row mb-3">

          <div class="col-md-3">
            <select name="month" class="form-control">
              @foreach(range(1, 12) as $month)
              <option value="{{ $month }}" {{ $month == $selectedMonth ? 'selected' : '' }}>
                {{ \Carbon\Carbon::create()->month($month)->format('F') }}
              </option>
              @endforeach
            </select>
          </div>
          <div class="col-md-3">
            <select name="year" class="form-control">
              @foreach(range(Carbon\Carbon::now()->year, Carbon\Carbon::parse(auth()->user()->created_at)->year) as $year)
              <option value="{{ $year }}" {{ $year == $selectedYear ? 'selected' : '' }}>
                {{ $year }}
              </option>
              @endforeach
            </select>
          </div>
          <div class="col-md-1">
            <button type="submit" class="btn-primary btn-filter">Filter</button>
          </div>
          <div class="col-md-1">
            <div class="btn-reload" onclick="window.location.href = window.location.origin + window.location.pathname;">
              <img src="{{ asset('reset.png') }}" height="20" alt="">
            </div>
          </div>
        </div>
      </form>
      <div class="profile-records-body">
        <div class="accordion" id="accordionExample">
          @foreach ($paginatedData as $index => $week)
          <div class="accordion-item">
            <h2 class="accordion-header" id="heading{{ $index }}">
              <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                data-bs-target="#collapse{{ $index }}" aria-expanded="false"
                aria-controls="collapse{{ $index }}">
                <div class="d-md-flex align-items-center">
                  <h6>
                    @if ($week['is_current_week'])
                    {{ $week['week_label'] }}
                    @else
                    {{ $week['week_label'] }}
                    @endif
                  </h6>
                  <div class="subtotal-section">
                    <table border="1" cellpadding="0" cellspacing="0" width="100%">
                      <tr height="50">
                        <td align="center" width="150" rowspan="2">Payout Total:</td>
                        <td align="center" width="200">Subtotal:</td>
                        <td align="center" width="200">Admin Fee:</td>
                        <td align="center" width="200">Employee Earnings:</td>
                        <td align="center" width="200">Payable Amount:</td>
                      </tr>
                      <tr height="50">
                        <td align="center" width="150">${{ $week['total_earnings'] }}</td>
                        <td align="center">${{ $week['admin_earnings'] }}</td>
                        <td align="center">${{ $week['user_earnings'] }}</td>
                        <td align="center">${{ $week['user_earnings'] }}</td>
                      </tr>
                    </table>
                  </div>
                </div>
              </button>
            </h2>
            <div id="collapse{{ $index }}" class="accordion-collapse collapse"
              aria-labelledby="heading{{ $index }}" data-bs-parent="#accordionExample">
              <div class="accordion-body table-responsive">
                <table class="table table-bordered">
                  <thead>
                    <tr>
                      <th scope="col">Date</th>
                      <th scope="col">Total Earnings</th>
                      <th scope="col">Admin Fee</th>
                      <th scope="col">Total Employee Earnings</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach ($week['daily_earnings'] as $day)
                    <tr>
                      <td>{{ \Carbon\Carbon::parse($day['date'])->format('M d, Y') }}</td>
                      <td>${{ number_format($day['earnings'], 2) }}</td>
                      <td>${{ number_format($day['admin_fee'], 2) }}</td>
                      <td>${{ number_format($day['user_earnings'], 2) }}</td>
                    </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>
            </div>
          </div>
          @endforeach
        </div>
        <div class="row d-flex justify-content-end mt-3">
          <div class="col-md-6">
            <nav aria-label="Page navigation example">
              <ul class="pagination justify-content-end mb-0">
                @if ($paginatedData->onFirstPage())
                <li class="page-item disabled">
                  <a class="page-link" href="#" tabindex="-1" aria-disabled="true">Prev</a>
                </li>
                @else
                <li class="page-item">
                  <a class="page-link" href="{{ $paginatedData->previousPageUrl() }}">Prev</a>
                </li>
                @endif

                @foreach ($paginatedData->links()->elements as $element)
                @if (is_string($element))
                <li class="page-item disabled"><a class="page-link">{{ $element }}</a></li>
                @endif

                @if (is_array($element))
                @foreach ($element as $page => $url)
                @if ($page == $paginatedData->currentPage())
                <li class="page-item active"><a class="page-link" href="{{ $url }}">{{ $page }}</a></li>
                @else
                <li class="page-item"><a class="page-link" href="{{ $url }}">{{ $page }}</a></li>
                @endif
                @endforeach
                @endif
                @endforeach

                @if ($paginatedData->hasMorePages())
                <li class="page-item">
                  <a class="page-link" href="{{ $paginatedData->nextPageUrl() }}">Next</a>
                </li>
                @else
                <li class="page-item disabled">
                  <a class="page-link" href="#" tabindex="-1" aria-disabled="true">Next</a>
                </li>
                @endif
              </ul>
            </nav>
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
</body>

</html>