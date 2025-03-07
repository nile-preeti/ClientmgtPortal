

@extends('layouts.app')
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

                                        <div class="users-filter-search d-none">
                                            <div id="user_list_datatable_info" class="dataTables_filter filter-search-info">
                                            <form class="position-relative d-flex" style="gap: 5px">
                                                    <div class="form-group mb-0" style="width: 100%">
                                                        <input type="search" class="form-control" name="search"
                                                            placeholder="Search..." aria-controls="user-list-table"
                                                            value="{{ request()->has("search")?request("search"):"" }}">
                                                    </div>
                                                    <button type="submit" class="" style="border: none; background: none; cursor: pointer;">
                                                                <i class="fa fa-search" style="color:#3d3e3e;font-size:20px;border: 1px solid #3d3e3e;box-shadow:0px 8px 13px 0px rgba(35, 53, 111, 0.12);padding: 10px 0px;text-align: center;border-radius: 5px;width: 45px;height:45px;"></i>
                                                            </button>
                                                </form>
                                            </div>
                                            <div class="btn-reload"
                                                onclick="window.location.href = window.location.origin + window.location.pathname;">
                                                <img src="{{ asset('reset.png') }}" height="20" alt="">
                                            </div>
                                        </div>
                                    </div>

                                    {{-- <div class="col-sm-12 col-md-5">
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
                                    </div> --}}

                                </div>
                                <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Week</th>
                                            <th>Total Earnings</th>
                                            <th>Admin Fee</th>
                                            <th>Employee Earnings</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if(isset($weeks) && count($weeks) > 0)
                                            @foreach($weeks as $weekData)
                                                <tr>
                                                    <td>{{ $weekData['week_label'] }}</td>
                                                    <td><strong>${{ number_format($weekData['total_earnings'], 2) }}</strong></td>
                                                    <td><strong>${{ number_format($weekData['admin_earnings'], 2) }}</strong></td>
                                                    <td><strong>${{ number_format($weekData['user_earnings'], 2) }}</strong></td>
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td colspan="4" class="text-center">No earnings data available</td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>

                                </div>
                            </div>

                            <div class="row justify-content-between mt-3">
                                <div id="week-list-page-info" class="col-md-6">
                                    <!-- <span>Showing {{ $weeks->firstItem() }} to {{ $weeks->lastItem() }} of {{ $weeks->total() }} weeks</span> -->
                                </div>
                                <div class="col-md-6">
                                    <nav aria-label="Page navigation example">
                                        <ul class="pagination justify-content-end mb-0">
                                            {{-- Previous Page Link --}}
                                            @if ($weeks->onFirstPage())
                                                <li class="page-item disabled">
                                                    <a class="page-link" href="#" tabindex="-1" aria-disabled="true">Previous</a>
                                                </li>
                                            @else
                                                <li class="page-item">
                                                    <a class="page-link" href="{{ $weeks->previousPageUrl() }}">Previous</a>
                                                </li>
                                            @endif

                                            {{-- Pagination Links --}}
                                            @foreach ($weeks->links()->elements as $element)
                                                @if (is_string($element))
                                                    <li class="page-item disabled"><a class="page-link">{{ $element }}</a></li>
                                                @endif

                                                @if (is_array($element))
                                                    @foreach ($element as $page => $url)
                                                        @if ($page == $weeks->currentPage())
                                                            <li class="page-item active"><a class="page-link" href="#">{{ $page }}</a></li>
                                                        @else
                                                            <li class="page-item"><a class="page-link" href="{{ $url }}">{{ $page }}</a></li>
                                                        @endif
                                                    @endforeach
                                                @endif
                                            @endforeach

                                            {{-- Next Page Link --}}
                                            @if ($weeks->hasMorePages())
                                                <li class="page-item">
                                                    <a class="page-link" href="{{ $weeks->nextPageUrl() }}">Next</a>
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
                </div>
            </div>
        </div>
    </div> <!-- Large Approved modal -->
    
@endsection
@push('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/3.3.4/jquery.inputmask.bundle.min.js"></script>
@endpush
