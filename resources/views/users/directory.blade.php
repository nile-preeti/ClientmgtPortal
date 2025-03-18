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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
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

        #recordsList li:first-child {
            margin-top: 0px !important;
        }

        .swal2-confirm {
            background-color: #ffffff !important;
            border: 1px solid #064086 !important;
            color: #064086 !important;
            padding: 9px 30px;
            border-radius: 50px;
        }

        .swal2-confirm:hover {
            background: #fff !important;
        }

        .swal2-cancel {
            padding: 10px 20px;
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

        .swal2-popup.swal2-modal.swal2-show {
            padding: 40px;
        }


        .attendance-records-head .form-control {
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

        .attendance-records-head .btn-search {
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


        .services-page-section {
            padding: 1rem 0;
            position: relative;
        }

        .recordsList {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .attendance-records-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1rem;
        }

        .attendance-records-head h2 {
            font-size: 20px;
            font-weight: 600;
            margin: 0;
            padding: 0;
            color: var(--black);
        }

        .cp-card {
            box-shadow: 0 0 #0000, 0 0 #0000, 0px 12px 28px 0px rgba(36, 7, 70, .06);
            background: var(--white);
            border-radius: 10px;
            position: relative;
        }

        .cp-card-head {
            display: flex;
            align-items: center;
            padding: 10px;
            border-bottom: 2px solid #f5f5fd;
        }

        .cp-date {
            font-size: 13px;
            font-weight: bold;
            margin: 0 0 0px 0;
            color: #064086;
            padding: 0;
        }

        .cp-card-body {
            padding: 10px;
        }

        .cp-point-box {
            display: flex;
            gap: 10px;
            align-items: center;
            margin-bottom: 10px;
        }

        .cp-point-icon {
            background: #fafafa;
            padding: 10px;
            border-radius: 5px;
        }

        .cp-point-text h4 {
            font-size: 13px;
            font-weight: bold;
            margin: 0 0 5px 0;
            color: #064086;
            padding: 0;
        }

        .cp-point-text p {
            font-size: 13px;
            font-weight: 400;
            margin: 0 0 0px 0;
            color: #000;
            padding: 0;
            line-height: 22px;
        }

        .cp-action-btn a {
            position: relative;
            color: var(--gray);
            border-radius: 5px;
            font-weight: 400;
            font-size: 13px;
            box-sizing: border-box;
            padding: 8px;
            border: 1px solid var(--border);
            background: #FFF;
            box-shadow: 0px 8px 13px 0px rgba(0, 0, 0, 0.05);
            margin-left: 10px;
            display: inline-block;
        }

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
    </style>
</head>

<body>
    <header class="header py-2">
        <div class="container-fluid">
            <div class="d-flex flex-wrap align-items-center justify-content-between">

                <a href="#"> <img src="{{asset('hrmodule.png')}}" class="logo card-img-absolute" alt="circle-image" height="50px"></a>




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
    <div class="services-page-section">
        <div class="container">
            <div class="attendance-records-head">
                <h2>
                    <a href="javascript:history.back()"><img src="https://nileprojects.in/hrmodule/public/assets/images/arrow-left.svg" class="ic-arrow-left"> </a>Jobs
                </h2>
                <div class="Search-filter">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <form class="">
                                    <input type="search" class="form-control" name="search"
                                        placeholder="Search" aria-controls="user-list-table" value="">
                                </form>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <form id="filterForm">
                                    <input type="date" name="date" class="form-control" id="datePicker">
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="attendance-records-body">
                <div class="attendance-records-content">
                    <div id="recordsList" class="recordsList">
                    </div>

                    <div id="pagination-controls" class="d-flex justify-content-end">
                        <button id="prev-page" onclick="changePage('prev')" disabled>Previous</button>
                        <span id="page-info"></span>
                        <button id="next-page" onclick="changePage('next')">Next</button>
                    </div>
                </div>
                <div class="attendance-record-data-tbl">
                </div>
            </div>
        </div>
    </div>
    <script>
        let currentPage = 1;
        let lastPage = 1;
        let searchQuery = '';
        let selectedDate = '';

        // Function to display employee directory
        function displayEmployees(services) {
    console.log(services);
    const recordsList = document.querySelector("#recordsList");
    recordsList.innerHTML = ""; // Clear previous records

    if (services.length === 0) {
        // Show "No Records Found" when there is no data
        recordsList.innerHTML = `
        <li class="text-center mt-2">
            <h5 class="text-danger">No Records Found</h5>
        </li>`;
        return; // Do nothing if no records exist
    }

    const currentDateTime = new Date(); // Get the current date and time

    services.forEach((service) => {
        const jobEndDateTime = new Date(`${service.end_date}T${service.end_time}`);

        let statusLabel, badgeClass;

        if (service.status == 2) {
            statusLabel = 'Completed';
            badgeClass = 'iq-bg-success';
        } else if (currentDateTime > jobEndDateTime && [0, 1].includes(parseInt(service.status))) {
            statusLabel = 'Pending';
            badgeClass = 'iq-bg-warning';
        } else {
            statusLabel = service.status == 1 ? 'Active' : 'Inactive';
            badgeClass = 'iq-bg-primary';
        }

        const listItem = document.createElement("li");
        listItem.classList.add("mt-2");

        listItem.innerHTML = `
        <div class="col-md-12">
            <div class="cp-card">
                <div class="cp-card-head">
                    <div class="cp-date">Service Name:<span> ${service.service ? service.service.name : 'N/A'}</span>
                        <span class="badge dark-icon-light ${badgeClass}" style="margin-left: 10px;">
                            ${statusLabel}
                        </span>

                        <!-- Mark as Complete Button -->
                        ${service.status == 1 ? `
                        <button class="btn btn-success btn-sm mark-complete" 
                                data-id="${service.id}" 
                                style="margin-left: 10px;font-size:11px;">
                            Mark as Complete
                        </button>` : ''}
                    </div>
                </div> 

                <div class="cp-card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="cp-point-box">
                                <div class="cp-point-icon">
                                    <img src="https://nileprojects.in/client-portal/public/assets/images/customer.svg">
                                </div>
                                <div class="cp-point-text">
                                    <h4>Customer Name:</h4>
                                    <p>${service.customer ? service.customer.name : 'N/A'}</p>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="cp-point-box">
                                <div class="cp-point-icon">
                                    <img src="https://nileprojects.in/client-portal/public/assets/images/date.svg">
                                </div>
                                <div class="cp-point-text">
                                    <h4>Start Date:</h4>
                                    <p>${service.start_date || 'N/A'}</p>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="cp-point-box">
                                <div class="cp-point-icon">
                                    <img src="https://nileprojects.in/client-portal/public/assets/images/date.svg">
                                </div>
                                <div class="cp-point-text">
                                    <h4>End Date:</h4>
                                    <p>${service.end_date || 'N/A'}</p>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="cp-point-box">
                                <div class="cp-point-icon">
                                    <img src="https://nileprojects.in/client-portal/public/assets/images/time.svg">
                                </div>
                                <div class="cp-point-text">
                                    <h4>Start Time:</h4>
                                    <p>${service.start_time || 'N/A'}</p>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="cp-point-box">
                                <div class="cp-point-icon">
                                    <img src="https://nileprojects.in/client-portal/public/assets/images/time.svg">
                                </div>
                                <div class="cp-point-text">
                                    <h4>End Time:</h4>
                                    <p>${service.end_time || 'N/A'}</p>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="cp-point-box">
                                <div class="cp-point-icon">
                                    <img src="https://nileprojects.in/client-portal/public/assets/images/descriptionicon.svg">
                                </div>
                                <div class="cp-point-text">
                                    <h4>Description:</h4>
                                    <p>${service.description || 'N/A'}</p>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="cp-point-box">
                                <div class="cp-point-icon">
                                    <img src="https://nileprojects.in/client-portal/public/assets/images/descriptionicon.svg">
                                </div>
                                <div class="cp-point-text">
                                    <h4>Sub Category:</h4>
                                    <p>${service.service && service.service.sub_category ? service.service.sub_category : 'N/A'}</p>
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

            fetchEmployees(currentPage, searchQuery, selectedDate);
        }

        // Function to fetch employees with search
        function fetchEmployees(page = 1, search = '', date = '') {
            $.get("{{ route('user.employee.services') }}", {
                page: page,
                search: search,
                date: date
            }, function(data) {
                if (data.success) {
                    displayEmployees(data.job_schedules);
                    currentPage = data.current_page;
                    lastPage = data.last_page;
                    updatePaginationControls();
                }
            });
        }

        // Search input event listener
        document.querySelector("input[name='search']").addEventListener("input", function() {
            searchQuery = this.value;
            fetchEmployees(1, searchQuery);
        });

        document.getElementById("datePicker").addEventListener("change", function() {
            selectedDate = this.value;
            fetchEmployees(1, searchQuery, selectedDate);
        });

        // Load employees when the page is ready
        document.addEventListener("DOMContentLoaded", function() {
            const searchInput = document.querySelector("input[name='search']");
            searchQuery = searchInput.value; // Get existing search query if any
            fetchEmployees(1, searchQuery);
        });


        $(document).on("click", ".mark-complete", function() {
        let jobId = $(this).data("id");

        $.ajax({
            url: "{{ route('user.employee.markComplete') }}", // Adjust this route
            type: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                job_id: jobId,
                status: 2
            },
            success: function(response) {
                if (response.success) {
                    toastr.success("Job marked as complete!");
                    fetchEmployees(); // Refresh list
                } else {
                    toastr.error("Failed to update status.");
                }
            },
            error: function() {
                toastr.error("Something went wrong.");
            }
        });
    });
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            populateMonthFilter();
            fetchAttendance(); // Load attendance data initially
        });

        function populateMonthFilter() {
            const monthFilter = document.getElementById("monthFilter");
            const currentDate = new Date();
            const currentMonth = currentDate.getMonth() + 1; // JavaScript months are 0-based
            const currentYear = currentDate.getFullYear();

            for (let i = 0; i < 12; i++) {
                const date = new Date(currentYear, currentMonth - 1 - i, 1);
                const monthValue = date.toISOString().slice(0, 7); // Format YYYY-MM
                const monthText = date.toLocaleString('default', {
                    month: 'long',
                    year: 'numeric'
                });

                const option = new Option(monthText, monthValue);
                if (monthValue === `${currentYear}-${String(currentMonth).padStart(2, '0')}`) {
                    option.selected = true;
                }
                monthFilter.appendChild(option);
            }
        }

        function fetchAttendance(page = 1) {
            const userId = 1; // Replace with dynamic user ID
            const selectedMonth = document.getElementById("monthFilter").value;

            fetch(`/fetch-attendance?id=${userId}&month=${selectedMonth}&page=${page}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        displayRecords(data.records);
                        updatePagination(data.current_page, data.last_page);
                    }
                })
                .catch(error => console.error("Error fetching attendance:", error));
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