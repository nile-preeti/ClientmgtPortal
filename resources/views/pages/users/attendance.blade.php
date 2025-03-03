<style type="text/css">
    button:disabled {
        background-color: #ffffff !important;
        cursor: not-allowed;
        border: 1px solid #064086 !important;
        color: #064086 !important;
    }
</style>

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
                                <div class="col-md-3">
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

                                    <div class="col-sm-4 col-md-4">
                                        <div class="form-group">
                                            <select class="form-control" id="selectcountry"
                                                onchange="changeStatus(this.value)">
                                                <option value="">--Filter By Status--</option>
                                                <option value="Present" @if (request()->has('status') && request('status') == 'Present') selected @endif>
                                                    Present </option>

                                                <option value="Absent" @if (request()->has('status') && request('status') == 'Absent') selected @endif>
                                                    Absent </option>

                                                <option value="Half-day" @if (request()->has('status') && request('status') == 'Half-day') selected @endif>
                                                    Half-day </option>

                                            </select>


                                        </div>
                                    </div>


                                    <div class="col-sm-4 col-md-4">
                                        <div class="form-group">
                                            <select class="form-control" id="selectMonth"
                                                onchange="filterByMonth(this.value)">
                                                <option value="">--Filter By Month--</option>
                                                @for ($i = 1; $i <= 12; $i++)
                                                    @php
                                                        $currentMonth = date('m'); // Get current month as "02", "03", etc.
                                                        $selectedMonth = request('month', $currentMonth); // Use requested month or default to current
                                                    @endphp
                                                    <option value="{{ str_pad($i, 2, '0', STR_PAD_LEFT) }}"
                                                        {{ $selectedMonth == str_pad($i, 2, '0', STR_PAD_LEFT) ? 'selected' : '' }}>
                                                        {{ date('F', mktime(0, 0, 0, $i, 1)) }}
                                                    </option>
                                                @endfor
                                            </select>
                                        </div>
                                    </div>



                                    <div class="col-sm-3 col-md-3">
                                        <div class="form-group">
                                            <form id="filterForm">
                                                <input type="date" name="date" class="form-control" id="datePicker"
                                                    value="{{ request('date') }}">
                                            </form>
                                        </div>
                                    </div>

                                    <div class="col-md-1">
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
                                <div class="d-flex justify-content-between">
                                    <div class="d-flex justify-content-end mt-3">
                                        <p><b>Total Working Days: {{ $totalWorkingDays }}</b></p>
                                    </div>
                                    <div class="d-flex justify-content-end mt-3">
                                        <p><b>Total Present: {{ $totalPresent }}</b></p>
                                    </div>
                                    <div class="d-flex justify-content-end mt-3">
                                        <p><b>Total Absent: {{ $totalAbsent }}</b></p>
                                    </div>
                                </div>

                                <table id="user-list-table"
                                    class="table-responsive table table-striped table-hover table-borderless mt-4">
                                    <thead>
                                        <tr>
                                            <th>Date</th>
                                            <th>Check In Time</th>
                                            <th>Check In Location</th>
                                            <th>Check Out Time</th>
                                            <th>Check Out Location</th>
                                            <th>Working Hours</th>
                                            <th>Status</th>
                                            <th>breaks</th>

                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($allDaysPaginated as $item)
                                            <tr>
                                                <td>{{ date('Y-m-d', strtotime($item['date'])) }}</td>
                                                <td>{{ $item['check_in_time'] ?? 'N/A' }}</td>
                                                <td>{{ $item['check_in_full_address'] ? substr($item['check_in_full_address'], 0, 30) : 'N/A' }}
                                                </td>
                                                <td>{{ $item['check_out_time'] ?? 'N/A' }}</td>
                                                <td>{{ $item['check_out_full_address'] ? substr($item['check_out_full_address'], 0, 30) : 'N/A' }}
                                                </td>
                                                <td>{{ $item['working_hours'] }}</td>
                                                <td>
                                                    <span
                                                        class="badge 
                                                {{ $item['status'] == 'Absent' ? 'bg-danger' : 'iq-bg-primary' }}">
                                                        {{ is_array($item['status']) ? 'Present' : $item['status'] }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <button class="btn btn-primary"
                                                        onclick='showBreaks(@json($item['breaks']))'>View
                                                        </button>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="6" align="center">No records found</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            <div class="row justify-content-between mt-3">
                                <div id="user-list-page-info" class="col-md-6">
                                    {{-- <span>Showing 1 to 5 of 5 entries</span> --}}
                                </div>
                                <div class="col-md-6">
                                    <nav aria-label="Page navigation example">
                                        <ul class="pagination justify-content-end mb-0">
                                            {{-- Previous Button --}}
                                            @if ($allDaysPaginated->onFirstPage())
                                                <li class="page-item disabled">
                                                    <a class="page-link" href="#" tabindex="-1"
                                                        aria-disabled="true">Previous</a>
                                                </li>
                                            @else
                                                <li class="page-item">
                                                    <a class="page-link"
                                                        href="{{ $allDaysPaginated->previousPageUrl() }}">Previous</a>
                                                </li>
                                            @endif

                                            {{-- Page Numbers --}}
                                            @foreach ($allDaysPaginated->links()->elements[0] ?? [] as $page => $url)
                                                @if ($page == $allDaysPaginated->currentPage())
                                                    <li class="page-item active"><a class="page-link"
                                                            href="{{ $url }}">{{ $page }}</a></li>
                                                @else
                                                    <li class="page-item"><a class="page-link"
                                                            href="{{ $url }}">{{ $page }}</a></li>
                                                @endif
                                            @endforeach

                                            {{-- Next Button --}}
                                            @if ($allDaysPaginated->hasMorePages())
                                                <li class="page-item">
                                                    <a class="page-link"
                                                        href="{{ $allDaysPaginated->nextPageUrl() }}">Next</a>
                                                </li>
                                            @else
                                                <li class="page-item disabled">
                                                    <a class="page-link" href="#" tabindex="-1"
                                                        aria-disabled="true">Next</a>
                                                </li>
                                            @endif
                                        </ul>
                                    </nav>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> <!-- Large Approved modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Breaks table</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <table class="table">
                        <tbody id="breaks_table">

                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>

                </div>
            </div>
        </div>
    </div>
    <script>
        function showBreaks(breaks) {
            $("#exampleModal").modal("show");
            var html = '';
            console.log(breaks);
            if (breaks.length == 0) {
                $("#breaks_table").html(`<tr> <td>No Breaks</td> <td>${item.start_break}</td></tr>`);
            }
            breaks.map((item,i) => {
                html += `<tr> <td> ${i+1}.<td> Started - Finished</td> <td>${item.start_break} - ${item.end_break} </td></tr>`;



            });
            $("#breaks_table").html(html);
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
