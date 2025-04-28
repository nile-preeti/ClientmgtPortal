<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
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
            background-color: #064086;
            color: white;
            border: none;
            padding: 6px 20px;
            font-size: 14px;
            border-radius: 50px;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.3s ease;
            margin: 0px;
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
            font-size: 14px;
            margin: 0 20px;
            color: #000;
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

        span.badge.badge-info {
            background: #ffd12a;
            color: #000;
            font-weight: 500;
            padding: 8px 14px;
            margin-left: 20px;
        }

        span.badge.badge-warning {
            background: #ffd12a;
            color: #000;
            font-weight: 500;
            padding: 8px 14px;
            margin-left: 20px;
        }

        span.badge.badge-danger {
            background: #ff9448;
            color: #000;
            font-weight: 500;
            padding: 8px 14px;
            margin-left: 20px;
        }

        span.badge.badge-success {
            background: #5bd846;
            color: #000;
            font-weight: 500;
            padding: 8px 14px;
            margin-left: 20px;
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
            .btn.btn-search{background: #064086; padding: 12px 20px; font-size: 14px; color: #fff; border-radius: 50px;}

            .attendance-records-head .form-control {position: relative; color: var(--gray); border-radius: 5px; font-weight: 400; font-size: 13px; box-sizing: border-box; padding:12px 15px 12px 15px; border: 1px solid var(--border); width: 100%; background: #FFF; box-shadow: 0px 8px 13px 0px rgba(0, 0, 0, 0.05); appearance: auto; }

            .attendance-records-head .btn-search {background: #064086; white-space: nowrap; width: 100%; padding: 10px 20px; display: inline-block; font-size: 13px; color: var(--white); border-radius: 5px; font-weight: 600; text-align: center; box-shadow: 0px 8px 13px 0px rgba(0, 0, 0, 0.05); border: none; }


            .attendance-records-section {padding: 1rem 0; position: relative; margin-left:270px; }
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
            .cp-action-btn a {position: relative; color: var(--gray); border-radius: 5px; font-weight: 400; font-size: 13px; box-sizing: border-box; padding: 0px 4px; border: 1px solid var(--border); background: #FFF; box-shadow: 0px 8px 13px 0px rgba(0, 0, 0, 0.05); margin-left: 10px; display: inline-block; }
            .cp-action-btn img{height: 20px;}



            .info-card.member-info{padding: 1rem;}

            .info-card p {
                color: var(--gray, #505667);
                font-size: 14px;
                font-style: normal;
                font-weight: 700;
                line-height: 20px;
                letter-spacing: 0.25px;
                padding: 0;
                margin: 0;
            }

            .name-info p {
                font-size: 16px;
            }

            .info-card h6 {
                color: var(--gray1, #8F93A0);
                font-size: 12px;
                font-style: normal;
                font-weight: 400;
                line-height: 16px;
                letter-spacing: 0.4px;
                margin: 0;
                padding: 0;
            }
            .info-card .account-status svg {
                color: var(--green, #7BC043);
            }

            .week-number, .hours-worked {
                font-size: 13px;
                margin: 0;
                padding: 0;
                line-height: normal;
                color: var(--blue);
                font-weight: 700;
            }

            .day-details {
                border-radius: 10px;
                border: 1px solid #d9e2ee;
            }

            .date-info {
                color: var(--gray, #505667);
                font-size: 14px;
                font-style: normal;
                font-weight: 700;
                line-height: 20px;
                letter-spacing: 0.25px;
                margin: 0;
                padding: 0;
            }

            .info-card h3 {
                color: var(--gray, #505667);
                font-size: 12px;
                font-style: normal;
                line-height: 20px;
                letter-spacing: 0.25px;
                margin: 0;
                padding: 0;
            }

            

            .week-number, .hours-worked {
                font-size: 13px;
                margin: 0;
                padding: 0;
                line-height: normal;
                color: #23356f;
                font-weight: 700;
            }

    </style>
</head>
@extends('layouts.user.app')
<body>
    <!-- <header class="header py-2">
        <div class="container-fluid">
            <div class="d-flex flex-wrap align-items-center justify-content-between">

                <a href="#">  
                    @php
                    $logo = \App\Models\Logo::first();
                @endphp

                     <img src="{{ $logo && file_exists(public_path('uploads/logo/' . $logo->name)) ? asset('uploads/logo/' . $logo->name) : asset('hrmodule.png') }}" class="logo card-img-absolute"
                alt="circle-image" height="50px"></a>

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
    <div class="attendance-records-section mt-5">
        <div class="container mt-5">
            <div class="attendance-records-head mt-5">
                <h2>
                    <a href="javascript:history.back()"><img src="https://nileprojects.in/hrmodule/public/assets/images/arrow-left.svg" class="ic-arrow-left"> </a>Timesheet Details
                </h2>
                <div class="Search-filter">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <!-- <label for="month">Select Month:</label> -->
                                <select id="month" class="form-control">
                                    <option value="">Select Month</option>
                                    @foreach(['01' => 'January', '02' => 'February', '03' => 'March', '04' => 'April', '05' => 'May', '06' => 'June', '07' => 'July', '08' => 'August', '09' => 'September', '10' => 'October', '11' => 'November', '12' => 'December'] as $key => $monthName)
                                        <option value="{{ $key }}" {{ ($currentMonth == $key) ? 'selected' : '' }}>{{ $monthName }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <!-- <label for="year">Select Year:</label> -->
                                <input type="number" id="year" class="form-control" min="2000" max="2050" placeholder="Select Year" value="{{ $currentYear }}">
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <button class="btn-search" onclick="fetchAttendance()">Search</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="attendance-records-body">
                <div class="attendance-records-content">
                    <!-- <div id="recordsList" class="recordsList">
                    </div> -->

                    <div class="recordsList">
                        <div class="col-md-12">

                            <div class="cp-card">
                                <div class="info-card">
                                   
                                    <div class="info-card member-info">
                                       
                                        <div class="time-card-head d-flex align-items-center ">
                                            <img src="https://nileprojects.in/clearchoice-janitorial/public/assets/admin-images/working-hour.png" alt="image" class="img-fluid me-2" style="width: 25px; height: auto;border-radius: 0!important;">
                                            <h4 class="hours-worked">
                                            Total Hours worked: 
                                                {{ $totalHours }} hr{{ $totalHours != 1 ? 's' : '' }} 
                                                @if ($remainingMinutes > 0)
                                                    {{ $remainingMinutes }} min{{ $remainingMinutes != 1 ? 's' : '' }}
                                                @endif
                                            </h4>
                                        </div>
                                        <hr>
                                        <div class="week-details">
                                            <div class="row">
                                                @if($attendanceRecords->isNotEmpty())
                                                    @foreach($attendanceRecords as $record)
                                                        <div class="col-md-4">
                                                            <div class="day-details px-4 py-2 mt-4">
                                                                
                                                            <div class="d-flex justify-content-between">
                                                            <div>
                                                            <h5 class="date-info">Date: {{ $record->date }}</h5>
                                                            <h5 class="date-info">Service Name: {{ $record->jobSchedule->service->name ?? 'N/A' }}</h5>
                                                            </div>
                                                                <div class="cp-action-btn">
                                                                <a href="javascript:void(0);" class="view-break-details" data-bs-toggle="modal" data-bs-target="#breakModal{{ $record->id }}">
                                                                    <img src="https://nileprojects.in/client-portal/public/assets/images/eye.svg">
                                                                </a>
                                                                </div>
                                                            </div>
                                                                

                                                                <div class="time-details">
                                                                    <div class="d-flex align-items-center card-time-detail-info mt-3">
                                                                        <img src="https://nileprojects.in/clearchoice-janitorial/public/assets/admin-images/end-time-icon.png" alt="image" class="img-fluid me-2" style="width: 22px; height: auto;border-radius: 0!important;">
                                                                        <h3 class="time-sub-head">Start Time: {{ $record->check_in_time }}</h3>
                                                                    </div>
                                                                    <div class="d-flex align-items-center card-time-detail-info mt-2">
                                                                        <img src="https://nileprojects.in/clearchoice-janitorial/public/assets/admin-images/start-time-icon.png" alt="image" class="img-fluid me-2" style="width: 22px; height: auto;border-radius: 0!important;">
                                                                        <h3>End Time: {{ $record->check_out_time ?? 'Not checked out' }}</h3>
                                                                    </div>
                                                                </div>

                                                                <div class="d-flex card-time-detail-info mt-2">
                                                                    <img src="https://nileprojects.in/clearchoice-janitorial/public/assets/admin-images/total-work-hours.png" alt="image" class="img-fluid me-2" style="width: 22px; height: auto;border-radius: 0!important;">
                                                                    <h3>Total Hours Worked on Day:
                                                                        @if($record->check_out_time)
                                                                        @php
                                                                            $start = \Carbon\Carbon::parse($record->check_in_time);
                                                                            $end = \Carbon\Carbon::parse($record->check_out_time);
                                                                            $totalMinutes = $end->diffInMinutes($start);

                                                                            foreach($record->attendanceBreaks as $break) {
                                                                                if($break->start_break && $break->end_break) {
                                                                                    $totalMinutes -= \Carbon\Carbon::parse($break->end_break)->diffInMinutes(\Carbon\Carbon::parse($break->start_break));
                                                                                }
                                                                            }
                                                                            $totalHoursDecimal = number_format($totalMinutes / 60, 2);
                                                                        @endphp
                                                                        {{ $totalHoursDecimal }} hrs &nbsp; &nbsp; &nbsp;
                                                                        @else
                                                                            Absent
                                                                        @endif
                                                                    </h3>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="modal fade" id="breakModal{{ $record->id }}" tabindex="-1" aria-labelledby="modalLabel{{ $record->id }}" aria-hidden="true">
                                                            <div class="modal-dialog">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <h1 class="modal-title fs-5" id="modalLabel{{ $record->id }}">Break Details</h1>
                                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        @if($record->attendanceBreaks->isNotEmpty())
                                                                            @foreach($record->attendanceBreaks as $break)
                                                                                <p><strong>Break Start-End:</strong> {{ \Carbon\Carbon::parse($break->start_break)->format('h:i A') }} -
                                                                                {{ \Carbon\Carbon::parse($break->end_break)->format('h:i A') }}</p>
                                                                            @endforeach
                                                                        @else
                                                                            <p>No breaks recorded.</p>
                                                                        @endif
                                                                    </div>
                                                                    <div class="modal-footer d-none">
                                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                @else
                                                    <div class="col-12">
                                                        <div class="alert alert-warning text-center">
                                                            No attendance records found for the selected month and year.
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- <div id="pagination-controls" class="d-flex justify-content-end mb-4">
                        <button id="prev-page" onclick="changePage('prev')" disabled>Previous</button>
                        <span id="page-info"></span>
                        <button id="next-page" onclick="changePage('next')">Next</button>
                    </div> -->
                </div>
                <div class="col-md-12 attendance-record-data-tbl">
                    <!-- <div class=" table-responsive ">
                        <table class="table table-bordered table-hover attendance-record-data-tbl">
                          <thead class="">
                            <tr>
                              <th>#</th>
                              <th>Date</th>
                              <th>Time</th>
                              <th>Check-in Time & Address</th>
                              <th>Check-out Time & Address</th>
                            </tr>
                          </thead>
                          <tbody>
                            <tr>
                              <th scope="row">1</th>
                              <td>02 Feb 2025</td>
                              <td>10:00 AM </td>
                              <td>10:00 AM | 123/1, A block New Delhi</td>
                              <td>07:00 AM | 123/1, A block New Delhi</td>
                            </tr>
                            <tr>
                              <th scope="row">1</th>
                              <td>02 Feb 2025</td>
                              <td>10:00 AM </td>
                              <td>10:00 AM | 123/1, A block New Delhi</td>
                              <td>07:00 AM | 123/1, A block New Delhi</td>
                            </tr>
                            <tr>
                              <th scope="row">1</th>
                              <td>02 Feb 2025</td>
                              <td>10:00 AM </td>
                              <td>10:00 AM | 123/1, A block New Delhi</td>
                              <td>07:00 AM | 123/1, A block New Delhi</td>
                            </tr>
                            <tr>
                              <th scope="row">1</th>
                              <td>02 Feb 2025</td>
                              <td>10:00 AM </td>
                              <td>10:00 AM | 123/1, A block New Delhi</td>
                              <td>07:00 AM | 123/1, A block New Delhi</td>
                            </tr>
                          </tbody>
                        </table>
                    </div> -->
                    <!-- Table 1 - Bootstrap Brain Component -->

                    <!-- <div class="table-responsive" id="recordsTable">
                        <table class="table table-borderless bsb-table-xl text-nowrap align-middle m-0">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Date</th>

                                    <th>Check-in Time </th>
                                    <th>
                                        Check-in Address
                                    </th>
                                    <th>Check-out Time</th>
                                    <th>
                                        Check-out Address
                                    </th>
                                </tr>
                            </thead>
                            <tbody>


                            </tbody>
                        </table>
                    </div> -->

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
                Swal.fire("Success", "Logged out successfully", 'success').then((result) => {
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

    function fetchAttendance() {
        const month = document.getElementById('month').value;
        const year = document.getElementById('year').value;

        const url = "{{ url('user/attendance_records') }}?month=" + month + "&year=" + year;
        window.location.href = url;
    }
</script>

</body>

</html>