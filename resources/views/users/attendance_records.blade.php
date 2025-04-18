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


            .attendance-records-section {padding: 1rem 0; position: relative; }
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


    </style>
</head>

<body>
    <header class="header py-2">
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
    </header>
    <div class="attendance-records-section">
        <div class="container">
            <div class="attendance-records-head">
                <h2>
                    <a href="javascript:history.back()"><img src="https://nileprojects.in/hrmodule/public/assets/images/arrow-left.svg" class="ic-arrow-left"> </a>Attendance Record
                </h2>
                <div class="Search-filter">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <!-- <label for="month">Select Month:</label> -->
                                <select id="month" class="form-control">
                                    <option value="01">Select Month</option>
                                    <option value="01">January</option>
                                    <option value="02">February</option>
                                    <option value="03">March</option>
                                    <option value="04">April</option>
                                    <option value="05">May</option>
                                    <option value="06">June</option>
                                    <option value="07">July</option>
                                    <option value="08">August</option>
                                    <option value="09">September</option>
                                    <option value="10">October</option>
                                    <option value="11">November</option>
                                    <option value="12">December</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <!-- <label for="year">Select Year:</label> -->
                                <input type="number" id="year" class="form-control" min="2000" max="2050" placeholder="Select Year">
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <button class="btn-search" onclick="fetchAttendance(1)">Search</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="attendance-records-body">
                <div class="attendance-records-content">
                    <div id="recordsList" class="recordsList">
                    </div>

                    <div id="pagination-controls" class="d-flex justify-content-end mb-4">
                        <button id="prev-page" onclick="changePage('prev')" disabled>Previous</button>
                        <span id="page-info"></span>
                        <button id="next-page" onclick="changePage('next')">Next</button>
                    </div>
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
     let currentPage = 1;
let lastPage = 1;

// Function to display records
function displayRecords(records) {
    console.log(records);
    const recordsList = document.querySelector("#recordsList");
    recordsList.innerHTML = ""; // Clear previous records

    records.forEach((record) => {
        const listItem = document.createElement("li");
        listItem.classList.add("mt-2");

        const formatTime = (time) => {
            return time && time !== "N/A" ? new Date(`1970-01-01T${time}`).toLocaleTimeString([], {
                hour: '2-digit',
                minute: '2-digit'
            }) : "N/A";
        };

        const formatAddress = (address) => address || "N/A";

        // Determine Status for Display
        let statusLabel = "Present"; 
let bgColor = "badge-success"; 

if (record.status.key === "absent") {
    statusLabel = "Absent";
    bgColor = "badge-danger"; 
} else if (record.status.key === "holiday") {
    statusLabel = "Holiday";
    bgColor = "badge-warning"; 
} else if (record.status.key === "weekly_off") {
    statusLabel = "Weekly Off";
    bgColor = "badge-info"; 
} else if (record.status.key === "na") {  
    statusLabel = "N/A";
    bgColor = "badge-secondary"; 
} else if (record.status.key === "half_day") {  
    statusLabel = "Half Day";
    bgColor = "badge-warning"; 
} else if (record.status.key === "present") {  
    statusLabel = "Present";
    bgColor = "badge-success"; 
}



        listItem.innerHTML = `
        <div class="col-md-12">

            <div class="cp-card">
                <div class="cp-card-head">
                    <div class="cp-date">Date:<span> ${new Date(record.date).toLocaleDateString('en-GB')}</span></div>
                    <div class="cp-status"><span class="badge ${bgColor}">${statusLabel}</span></div>
                    <div class="cp-action-btn">
                       <a href="javascript:void(0);" class="view-break-details" data-id="${record.id}" data-bs-toggle="modal" data-bs-target="#exampleModal">
    <img src="https://nileprojects.in/client-portal/public/assets/images/eye.svg"> 
</a>
                    </div>
                </div> 
                <div class="cp-card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="cp-point-box">
                                <div class="cp-point-icon">
                                    <img src="https://nileprojects.in/client-portal/public/assets/images/time.svg">
                                </div>
                                <div class="cp-point-text">
                                    <h4>Check-in Time:</h4>
                                    <p>${formatTime(record.status.check_in_time)}</p>
                                </div>
                                
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="cp-point-box">
                                <div class="cp-point-icon">
                                    <img src="https://nileprojects.in/client-portal/public/assets/images/time.svg">
                                </div>
                                <div class="cp-point-text">
                                    <h4>Check-out Time:</h4>
                                    <p>${formatTime(record.status.check_out_time)}</p>
                                </div>
                            </div>
                        </div>

                        

                        <div class="col-md-4">
                            <div class="cp-point-box">
                                <div class="cp-point-icon">
                                    <img src="https://nileprojects.in/client-portal/public/assets/images/time.svg">
                                </div>
                                <div class="cp-point-text">
                                    <h4>Working Hours</h4>
                                     <p>${record.status.working_hours}</p>

                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="cp-point-box">
                                <div class="cp-point-icon">
                                    <img src="https://nileprojects.in/client-portal/public/assets/images/time.svg">
                                </div>
                                <div class="cp-point-text">
                                    <h4>Breaking  Hours:</h4>
                                    <p>${record.status.break_time}</p>

                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="cp-point-box">
                                <div class="cp-point-icon">
                                    <img src="https://nileprojects.in/client-portal/public/assets/images/location.svg">
                                </div>
                                <div class="cp-point-text">
                                    <h4>Check-in Address:</h4>
                                    <p>${formatAddress(record.status.check_in_address)}</p>
                                </div>
                            </div>
                        </div>
 
                        <div class="col-md-4">
                            <div class="cp-point-box">
                                <div class="cp-point-icon">
                                    <img src="https://nileprojects.in/client-portal/public/assets/images/location.svg">
                                </div>
                                <div class="cp-point-text">
                                    <h4>Check-out Address:</h4>
                                    <p>${formatAddress(record.status.check_out_address)}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>`;

        recordsList.appendChild(listItem);
    });
}


// Function to update pagination controls
// Function to update pagination controls
function updatePaginationControls() {
    const paginationControls = document.getElementById("pagination-controls");
    const pageInfo = document.getElementById("page-info");
    const prevButton = document.getElementById("prev-page");
    const nextButton = document.getElementById("next-page");

    pageInfo.textContent = `Page ${currentPage} of ${lastPage}`;

    prevButton.disabled = currentPage <= 1;
    nextButton.disabled = currentPage >= lastPage;

    if (lastPage <= 1) {
        paginationControls.style.cssText = "display: none !important;"; // Force hide
    } else {
        paginationControls.style.cssText = "display: flex !important;"; // Force show
    }
}


// Function to change the page
function changePage(direction) {
    if (direction === 'prev' && currentPage > 1) {
        currentPage--;
    } else if (direction === 'next' && currentPage < lastPage) {
        currentPage++;
    }

    fetchRecords(currentPage);
}

// Function to fetch attendance records for a specific page
function fetchRecords(page = 1) {
    $.get("{{ route('user.attendance.fetch') }}", {
        id: user.id,
        page: page
    }, function(data) {
        if (data.success) {
            if (data.records) {
                displayRecords(data.records); // Display the records
                currentPage = data.current_page; // Update current page
                lastPage = data.last_page; // Update last page
                updatePaginationControls(); // Update pagination controls
            }
        }
    });
}

// Check if user is logged in and fetch records
var user = @json($user);
console.log(user);
if (user) {
    fetchRecords(); // Fetch records for the first page
    $("#name").text(user.name); // Display user name
} else {
    window.location = "{{ route('user.dashboard') }}";
}
    </script>
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
    document.addEventListener("DOMContentLoaded", function () {
        let today = new Date();
        let currentMonth = String(today.getMonth() + 1).padStart(2, '0'); // Get month (01-12)
        let currentYear = today.getFullYear();

        // Set current month
        document.getElementById("month").value = currentMonth;

        // Populate year dropdown (from 2020 to current year + 5)
        let yearSelect = document.getElementById("year");
        let startYear = 2020;
        let endYear = currentYear + 5;
        
        for (let year = startYear; year <= endYear; year++) {
            let option = document.createElement("option");
            option.value = year;
            option.textContent = year;
            if (year === currentYear) {
                option.selected = true; // Select current year
            }
            yearSelect.appendChild(option);
        }
    });
</script>
   <script>
    $(document).ready(function () {
        let currentYear = new Date().getFullYear();
        let yearDropdown = $("#year");

        // Populate year dropdown (5 years back to 2 years ahead)
        for (let i = currentYear - 5; i <= currentYear + 2; i++) {
            yearDropdown.append(`<option value="${i}" ${i === currentYear ? 'selected' : ''}>${i}</option>`);
        }

        // Auto-fetch attendance for current month & year
        fetchAttendance(1);
    });

    function fetchAttendance(page = 1) {
    let userId = 1; // Replace with actual user ID
    let selectedMonth = $("#month").val();
    let selectedYear = $("#year").val();

    $.ajax({
        url: "{{ route('user.attendance.fetch') }}",
        type: "GET",
        data: {
            id: userId,
            month: selectedMonth,
            year: selectedYear,
            page: page
        },
        success: function (response) {
            if (response.success) {
                displayRecords(response.records); // Use the displayRecords function

                $("#page-info").text(`Page ${response.current_page} of ${response.last_page}`);
                $("#prev-page").prop("disabled", response.current_page === 1);
                $("#next-page").prop("disabled", response.current_page === response.last_page);

                currentPage = response.current_page;
                lastPage = response.last_page; // Ensure lastPage is updated
                updatePaginationControls(); // Keep pagination controls updated
            } else {
                alert(response.message);
            }
        }
    });
}

    function changePage(direction) {
        if (direction === "next") {
            fetchAttendance(currentPage + 1);
        } else if (direction === "prev" && currentPage > 1) {
            fetchAttendance(currentPage - 1);
        }
    }
</script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        let currentYear = new Date().getFullYear();
        document.getElementById("year").value = currentYear;
    });

    $(document).on("click", ".view-break-details", function () {
        let attendanceId = $(this).data("id");

        $.ajax({
            url: "{{ route('user.fetch.break.details') }}", // Update the route accordingly
            type: "POST",
            data: {
                id: attendanceId,
                _token: $('meta[name="csrf-token"]').attr("content") // CSRF token
            },
            success: function (response) {
                if (response.success && response.breaks.length > 0) {
                    let breakDetails = response.breaks.map((breakItem, index) => `
                        <p><strong>${index + 1}. Break Start - End:</strong> ${breakItem.start_break} - ${breakItem.end_break}</p>
                    `).join("");

                    $("#exampleModal .modal-body").html(breakDetails);
                } else {
                    $("#exampleModal .modal-body").html("<p>No break records found.</p>");
                }
            },
            error: function () {
                $("#exampleModal .modal-body").html("<p>Error fetching data.</p>");
            }
        });
    });
</script>

<!-- Modal -->
   <!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Break Details</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
      </div>
      <div class="modal-footer d-none">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

</body>

</html>