<style type="text/css">
    button:disabled {
        background-color: #ffffff !important;
        cursor: not-allowed;
        border: 1px solid #064086 !important;
        color: #064086 !important;
    }
    .highcharts-credits{display: none;}


            .info-card.member-info{padding: 0rem;}

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
                color: #064086;
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

            .cp-action-btn a {
                position: relative;
                color: var(--gray);
                border-radius: 5px;
                font-weight: 400;
                font-size: 13px;
                box-sizing: border-box;
                padding: 0px 4px;
                border: 1px solid var(--border);
                background: #FFF;
                box-shadow: 0px 8px 13px 0px rgba(0, 0, 0, 0.05);
                margin-left: 10px;
                display: inline-block;
            }
            
</style>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
<script src="{{ asset('plugins/bootstrap/js/bootstrap.min.js') }}"></script>
<link rel="stylesheet" href="{{ asset('plugins/bootstrap/css/bootstrap.min.css') }}">

@extends('layouts.app')
@section('content')
    <!-- Page Content  -->
    <div id="content-page" class="content-page">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="iq-card">
                        <div class="user-table-item">
                            <div class="row g-1 align-items-center">
                                <div class="col-md-3">
                                    <div class="user-profile-item mb-2">
                                        <div class="user-profile-media">
                                            <img src="https://mkradlandscapeandlawncare.com/assets/images/avatar.png">
                                        </div>
                                        <div class="user-profile-text">
                                            <h2>{{ $user->name }}</h2>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="User-contact-info mb-2">
                                        <div class="User-contact-info-icon">
                                            <i class="ri-mail-line"></i>
                                        </div> 
                                        <div class="User-contact-info-content">
                                            <h2>Email Address</h2>
                                            <p><a href="mailto:{{ $user->email }}">{{ $user->email }}</a></p>
                                        </div>    
                                    </div>
                                </div>
            
                                <div class="col-md-3">
                                    <div class="User-contact-info">
                                        <div class="User-contact-info-icon">
                                            <i class="ri-phone-line"></i>
                                        </div> 
                                        <div class="User-contact-info-content">
                                            <h2>Phone</h2>
                                            <p>{{ $user->phone }}</p>
                                        </div>    
                                    </div>
                                </div>
            
                               
                               
                            </div>
                        </div>
                        <!-- <div class="iq-card-header d-flex justify-content-between">
                                      <div class="iq-header-title">
                                         <h4 class="card-title">User List</h4>
                                      </div>
                                   </div> -->
                        <div class="iq-card-body">
                            <div class="search-filter-info">
                                <div class="row justify-content-between">
                                    {{-- <div class="col-sm-12 col-md-6">

                                        <div class="users-filter-search">
                                            <div id="user_list_datatable_info" class="dataTables_filter filter-search-info">
                                                <form class="position-relative">
                                                    <div class="form-group mb-0">
                                                        <input type="search" class="form-control" name="search"
                                                        placeholder="Search by  name..." aria-controls="user-list-table">
                                                    </div>
                                                </form>
                                            </div>
                                            
                                        </div>
                                    </div> --}}


                                    <div class="col-sm-5 col-md-5">
                                        <div class="form-group">
                                            <select class="form-control" id="selectMonth" onchange="filterByMonthYear()">
                                                <option value="">--Filter By Month--</option>
                                                @for ($i = 1; $i <= 12; $i++)
                                                    @php
                                                        $selectedMonth = request('month', date('m'));
                                                    @endphp
                                                    <option value="{{ str_pad($i, 2, '0', STR_PAD_LEFT) }}"
                                                        {{ $selectedMonth == str_pad($i, 2, '0', STR_PAD_LEFT) ? 'selected' : '' }}>
                                                        {{ date('F', mktime(0, 0, 0, $i, 1)) }}
                                                    </option>
                                                @endfor
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-sm-5 col-md-5">
                                        <div class="form-group">
                                            <select class="form-control" id="selectYear" onchange="filterByMonthYear()">
                                                <option value="">--Filter By Year--</option>
                                                @php
                                                    $currentYear = date('Y');
                                                    $selectedYear = request('year', $currentYear);
                                                @endphp
                                                @for ($y = $currentYear; $y >= ($currentYear - 10); $y--)
                                                    <option value="{{ $y }}" {{ $selectedYear == $y ? 'selected' : '' }}>{{ $y }}</option>
                                                @endfor
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <div class="btn-reload"
                                                onclick="window.location.href = window.location.origin + window.location.pathname;"
                                                style="
    padding: 10px;
">
                                                <img src="{{ asset('reset.png') }}" height="20" alt="">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                               
                                <div class="recordsList">
                                    <div class="col-md-12">

                                        <div class="cp-card">
                                            <div class="info-card">
                                            
                                                <div class="info-card member-info">
                                                
                                                    <div class="time-card-head d-flex align-items-center ">
                                                        <img src="https://nileprojects.in/clearchoice-janitorial/public/assets/admin-images/working-hour.png" alt="image" class="img-fluid me-2" style="width: 25px; height: auto;border-radius: 0!important;">
                                                        <h4 class="hours-worked">
                                                        Total Hours worked: 
                                                            {{ $totalHours }} hour{{ $totalHours != 1 ? 's' : '' }} 
                                                            @if ($remainingMinutes > 0)
                                                                {{ $remainingMinutes }} minute{{ $remainingMinutes != 1 ? 's' : '' }}
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
                                                                                    {{ $totalHoursDecimal }} hours &nbsp; &nbsp; &nbsp;
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
                            </div>

                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> <!-- Large Approved modal -->


   <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/modules/xrange.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"></script>
    <script>
        function showBreaks(item) {
        console.log("Raw Data:", item);

        // Ensure times are properly formatted in UTC
        let checkInTime = moment.utc(item.date + " " + item.check_in_time, "YYYY-MM-DD HH:mm:ss").valueOf();
        let checkOutTime = item.check_out_time ?
            moment.utc(item.date + " " + item.check_out_time, "YYYY-MM-DD HH:mm:ss").valueOf() :
            null;

        console.log("Parsed Check-in Time:", moment.utc(checkInTime).format("YYYY-MM-DD HH:mm:ss"));
        console.log("Parsed Check-out Time:", checkOutTime ? moment.utc(checkOutTime).format("YYYY-MM-DD HH:mm:ss") : "NULL");

        let segments = [];
        let lastTime = checkInTime; // Start from check-in time

        // Loop through breaks and generate segments
        item.breaks.forEach((breakItem, index) => {
            let startBreak = moment.utc(item.date + " " + breakItem.start_break, "YYYY-MM-DD HH:mm:ss").valueOf();
            let endBreak = moment.utc(item.date + " " + breakItem.end_break, "YYYY-MM-DD HH:mm:ss").valueOf();

            // Work segment before break (green)
            segments.push({
                x: lastTime,
                x2: startBreak,
                y: 0,
                color: 'green'
            });

            // Break segment (red)
            segments.push({
                x: startBreak,
                x2: endBreak,
                y: 0,
                color: 'red'
            });

            lastTime = endBreak; // Move to end of break
        });

        // Add remaining work time if checkout exists
        if (checkOutTime) {
            segments.push({
                x: lastTime,
                x2: checkOutTime,
                y: 0,
                color: 'green'
            });
        } else {
            // No checkout → Extend work after last break
            segments.push({
                x: lastTime,
                x2: lastTime + 60 * 60 * 1000, // Add 1 hour for visualization
                y: 0,
                color: 'green'
            });
        }

        console.log("Final Segments:", segments);

        // Ensure X-Axis starts at check-in and ends at check-out
        let xMin = checkInTime;
        let xMax = checkOutTime ? checkOutTime : lastTime + 60 * 60 * 1000;

        // Show modal
        $("#exampleModal").modal("show");

        // Render Highcharts
        Highcharts.chart('breaks-chart-container', {
            chart: {
                type: 'xrange'
            },
            title: {
                text: 'Work & Break Timeline'
            },
            xAxis: {
                type: 'datetime',
                min: xMin, // Set min time to check-in
                max: xMax, // Set max time to check-out
                title: {
                    text: 'Time'
                },
                labels: {
                    format: '{value:%H:%M}'
                }
            },
            yAxis: {
                title: {
                    text: 'Timeline'
                },
                categories: ['Work'], // One row only
                reversed: true
            },
            series: [{
                name: 'Work & Breaks',
                data: segments
            }]
        });
    }

        function changeStatus(val) {
            var currentUrl = new URL(window.location.href);
            // Add or update the 'run_id' parameter
            currentUrl.searchParams.set('status', val);
            if (val == "") {
                currentUrl.searchParams.delete('status');

            }
            // Reload the page with the new URL
            window.location.href = currentUrl.toString();

        }

        function filterByMonth(month) {
            var url = new URL(window.location.href);
            url.searchParams.set('month', month);
            window.location.href = url.href;
        }
        function filterByMonthYear() {
            let month = document.getElementById('selectMonth').value;
            let year = document.getElementById('selectYear').value;
            let url = new URL(window.location.href);
            url.searchParams.set('month', month);
            url.searchParams.set('year', year);
            window.location.href = url.toString();
        }

    </script>
    <script>
        document.getElementById('datePicker').addEventListener('keydown', function(e) {
            e.preventDefault(); // Prevent manual typing
        });
    </script>
    <script>
        $(document).ready(function() {
            $("#datePicker").change(function() {
                $("#filterForm").submit(); // Submit form automatically on date selection
            });
        });
    </script>
@endsection
