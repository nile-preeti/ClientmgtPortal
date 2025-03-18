@extends('layouts.app')
@push('css')
<style>
    .table-borderless td {
        font-weight:normal;
    }
</style>
@endpush
@section('content')
    <!-- Page Content  -->
    <div id="content-page" class="content-page">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="iq-card">
                        <!-- <div class="iq-card-header d-flex justify-content-between">
                                                                                                                              <div class="iq-header-title">
                                                                                                                                 <h4 class="card-title">User List</h4>
                                                                                                                              </div>
                                                                                                                           </div> -->
                        <div class="iq-card-body">
                            <div class="search-filter-info">
                                <div class="row justify-content-between">
                                    <div class="col-sm-12 col-md-5">

                                        <div class="users-filter-search">
                                            <div id="user_list_datatable_info" class="dataTables_filter filter-search-info">
                                            <form class="position-relative d-flex" style="gap: 5px">
                                                    <div class="form-group mb-0" style="width: 100%">
                                                        <input type="search" class="form-control" name="search"
                                                        placeholder="Search..." aria-controls="user-list-table"
                                                        value="{{ $search }}">
                                                    </div>
                                                    <button type="submit" class="" style="border: none; background: none; cursor: pointer;">
                                                                <i class="fa fa-search" style="color:#3d3e3e;font-size:20px;border: 1px solid #3d3e3e;box-shadow:0px 8px 13px 0px rgba(35, 53, 111, 0.12);padding: 10px 0px;text-align: center;border-radius: 5px;width: 45px;height:45px;"></i>
                                                            </button>
                                                </form>
                                            </div>
                                            <div class="btn-reload" onclick="window.location.href = window.location.origin + window.location.pathname;">
                                                <img src="{{ asset('reset.png') }}" height="20" alt="">
                                            </div>
                                        </div>
                                    </div>
                                  
                                    <div class="col-sm-12 col-md-5">
                                        <div class="form-group">
                                            <select class="form-control" id="selectcountry"
                                                onchange="changeStatus(this.value)">
                                                <option value="">--Filter By Status--</option>
                                                <option value="1" @if (request()->has('status') && request('status') == 1) selected @endif>
                                                    Active </option>

                                                <option value="0" @if (request()->has('status') && request('status') == 0) selected @endif>
                                                    Inactive </option>

                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-sm-12 col-md-2">
                                        <div class="form-group">
                                            <a class="addbtn" href="{{ route('job_schedules.create') }}">Create</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="table-responsive">
                                    <table id="user-list-table" class="table table-striped table-hover table-borderless mt-0" role="grid"
                                        aria-describedby="user-list-page-info" >
                                        <thead>
                                            <tr>
                                                <th>Employee</th>
                                                <th>Service</th>
                                                <th>Customer</th>

                                                <th>From</th>
                                                <th>To</th>
                                                <th>Status</th>
                                                <th>Action &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($job_schedules as $item)
                                                <tr>
                                                    <td>
                                                        {{$item->user->name}}
                                                    </td>
                                                    <td>
                                                        {{$item->service->name??"N/A"}}
                                                    </td>
                                                    <td>
                                                        {{$item->customer->name}}
                                                    </td>

                                                    <td>{{ date("Y-m-d", strtotime($item->start_date)) . " / " . date("h:i A", strtotime($item->start_time)) }}</td>
                                                    <td>{{ date("Y-m-d", strtotime($item->end_date)) . " / " . date("h:i A", strtotime($item->end_time)) }}</td>

                                                    @php
                                                        $currentDateTime = now();
                                                        $jobEndDateTime = \Carbon\Carbon::parse($item->end_date . ' ' . $item->end_time);

                                                        if ($item->status == 2) {
                                                            $statusLabel = 'Completed';
                                                            $badgeClass = 'iq-bg-success';
                                                        } elseif ($currentDateTime->greaterThan($jobEndDateTime) && in_array($item->status, [1])) {
                                                            $statusLabel = 'Pending';
                                                            $badgeClass = 'iq-bg-warning';
                                                        } else {
                                                            $statusLabel = $item->status ? 'Active' : 'Inactive';
                                                            $badgeClass = 'iq-bg-primary';
                                                        }
                                                    @endphp
                                                    <td><span class="badge dark-icon-light {{ $badgeClass }}">{{ $statusLabel }}</span></td>

                                                    <td>
                                                        <div class="flex align-items-center list-user-action">
                                                            
                                                                {{-- edit button --}}
                                                            <a href="{{ route('job_schedules.edit', $item->id) }}"
                                                                class="btnedit"><i class="ri-pencil-fill"></i></a>
                                                            {{-- delete  button --}}
                                                            <a class="btndelete" data-id="{{ $item->id }}"
                                                                style="cursor: pointer"
                                                                data-url="{{ route('job_schedules.destroy', $item->id) }}"
                                                                onclick="deletePublic(this)"><i
                                                                    class="ri-delete-bin-7-line"></i></a>
                                                            {{-- <a class="btnview" data-id="{{ $item->id }}" --}}
                                                                {{-- style="cursor: pointer"
                                                                href="{{ route('userAttendance', $item->id) }}"><i
                                                                    class="ri-eye-fill"></i></a> --}}


                                                        </div>

                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="5" align="center">No records found</td>
                                                </tr>
                                            @endforelse



                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="row justify-content-between mt-3">
                                <div id="user-list-page-info" class="col-md-6">
                                    {{-- <span>Showing 1 to 5 of 5 entries</span> --}}
                                </div>
                                <div class="col-md-6">
                                    <nav aria-label="Page navigation example">
                                        <ul class="pagination justify-content-end mb-0">
                                            @if ($job_schedules->onFirstPage())
                                                <li class="page-item disabled">
                                                    <a class="page-link" href="#" tabindex="-1"
                                                        aria-disabled="true">Previous</a>
                                                </li>
                                            @else
                                                <li class="page-item">
                                                    <a class="page-link"
                                                        href="{{ $job_schedules->previousPageUrl() }}">Previous</a>
                                                </li>
                                            @endif

                                            @foreach ($job_schedules->links()->elements as $element)
                                                @if (is_string($element))
                                                    <li class="page-item disabled"><a
                                                            class="page-link">{{ $element }}</a></li>
                                                @endif

                                                @if (is_array($element))
                                                    @foreach ($element as $page => $url)
                                                        @if ($page == $job_schedules->currentPage())
                                                            <li class="page-item active"><a class="page-link"
                                                                    href="{{ $url }}">{{ $page }}</a>
                                                            </li>
                                                        @else
                                                            <li class="page-item"><a class="page-link"
                                                                    href="{{ $url }}">{{ $page }}</a>
                                                            </li>
                                                        @endif
                                                    @endforeach
                                                @endif
                                            @endforeach

                                            @if ($job_schedules->hasMorePages())
                                                <li class="page-item">
                                                    <a class="page-link" href="{{ $job_schedules->nextPageUrl() }}">Next</a>
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

    
@endsection
@push('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/3.3.4/jquery.inputmask.bundle.min.js"></script>

    <script>
        $(":input").inputmask();
       

        function deletePublic(ele) {
            var title = 'Are you sure, you want to delete this Job ?';
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
                    var id = ele.getAttribute("data-id");
                    var url = ele.getAttribute("data-url");

                    var _token = '{{ csrf_token() }}';

                    var obj = {

                        _token
                    };
                    $.ajax({
                        url: url,
                        type: 'DELETE',
                        data: obj,
                        success: function(data) {
                            // console.log(data);
                            if (data.success) {
                                Swal.fire("Success", data.message, 'success').then((result) => {
                                    if (result.value) {
                                        var url = $('#redirect_url').val();
                                        if (url !== undefined || url != null) {
                                            window.location = url;
                                        } else {
                                            location.reload(true);
                                        }

                                    }
                                });
                            } else {
                                Swal.fire("Error", data.message, 'error');
                            }
                        }
                    });
                }

            })

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
    </script>
   
@endpush
