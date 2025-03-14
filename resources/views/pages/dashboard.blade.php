@extends('layouts.app')
@push('css')
    <style>
        .list-dot {
            padding-left: 15px !important;
            line-height: 50px !important;
            width: 50px !important;
            height: 50px !important;
            background: #805b33;
            border-radius: 30px;
            color: white;
            font-size: 30px;
        }
    </style>
@endpush
@section('content')
    <div id="content-page" class="content-page">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6 col-md-6 col-lg-3">
                    <div class="iq-card iq-card-block iq-card-stretch "
                        onclick="location.replace('{{ route('userss.index') }}')" style="cursor: pointer">
                        <div class="iq-card-body">
                            <div class="d-flex d-flex align-items-center justify-content-between">
                                <div>
                                    <h6 class="over-title-text">Total Employees</h6>
                                    <h2 class="over-title-value">{{ count($users) }}</h2>
                                </div>
                                <div class="iq-card-icon"><i
                                        class="ri-group-line"></i></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-md-6 col-lg-3">
                    <div class="iq-card iq-card-block iq-card-stretch "
                        onclick="location.replace('{{ route('customers.index') }}')" style="cursor: pointer">
                        <div class="iq-card-body">
                            <div class="d-flex d-flex align-items-center justify-content-between">
                                <div>
                                    <h6 class="over-title-text">Total Customer</h6>
                                    <h2 class="over-title-value">{{ ($customers) }}</h2>
                                </div>
                                <div class="iq-card-icon">
                                    <i class="ri-user-3-line"></i>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-md-6 col-lg-3">
                    <div class="iq-card iq-card-block iq-card-stretch "
                        onclick="location.replace('{{ route('job_schedules.index') }}')" style="cursor: pointer">
                        <div class="iq-card-body">
                            <div class="d-flex d-flex align-items-center justify-content-between">
                                <div>
                                    <h6 class="over-title-text">Total jobs</h6>
                                    <h2 class="over-title-value">{{ ($jobs) }}</h2>
                                </div>
                                <div class="iq-card-icon">
                                    <i class="ri-briefcase-line"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-md-6 col-lg-3">
                    <div class="iq-card iq-card-block iq-card-stretch "
                        onclick="location.replace('{{ route('job_schedules.index') }}')" style="cursor: pointer">
                        <div class="iq-card-body">
                            <div class="d-flex d-flex align-items-center justify-content-between">
                                <div>
                                    <h6 class="over-title-text">Total services</h6>
                                    <h2 class="over-title-value">{{ ($services) }}</h2>
                                </div>
                                <div class="iq-card-icon">
                                    <i class="ri-settings-3-line"></i>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <div class="row">
                <div class="col-md-6 col-lg-6">
                    <div class="iq-card iq-card-block iq-card-stretch iq-card-height overflow-hidden">
                        <div class="iq-card-header d-flex justify-content-between">
                           <div class="iq-header-title">
                              <h4 class="card-title">Column Chart</h4>
                           </div>
                           <div class="iq-card-header-toolbar d-flex align-items-center">
                              <div class="dropdown">
                                 <span class="dropdown-toggle" id="dropdownMenuButton" data-toggle="dropdown">
                                 <i class="ri-more-fill"></i>
                                 </span>
                                 <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton" >
                                    <a class="dropdown-item" href="#"><i class="ri-eye-fill mr-2"></i>View</a>
                                    <a class="dropdown-item" href="#"><i class="ri-delete-bin-6-fill mr-2"></i>Delete</a>
                                    <a class="dropdown-item" href="#"><i class="ri-pencil-fill mr-2"></i>Edit</a>
                                    <a class="dropdown-item" href="#"><i class="ri-printer-fill mr-2"></i>Print</a>
                                    <a class="dropdown-item" href="#"><i class="ri-file-download-fill mr-2"></i>Download</a>
                                 </div>
                              </div>
                           </div>
                        </div>
                        <div class="iq-card-body">
                        <div id="home-chart-02"></div>
                    </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-6">
                    <div class="iq-card">
                        <div class="iq-card-header d-flex justify-content-between">
                           <div class="iq-header-title">
                              <h4 class="card-title">Radial Bar Charts</h4>
                           </div>
                        </div>
                        <div class="iq-card-body">
                           <div id="apex-radialbar-chart"></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-6">
                    <div class="iq-card">
                        <div class="iq-card-header d-flex justify-content-between">
                           <div class="iq-header-title">
                              <h4 class="card-title"> Pie Charts</h4>
                           </div>
                        </div>
                        <div class="iq-card-body">
                            <div id="services-pie-chart" style="height: 400px;"></div>
                        </div>
                    </div>
                </div>
                


                <!-- <div class="col-lg-12">
                    <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                        <div class="iq-card-header d-flex justify-content-between">
                            <div class="iq-header-title">
                                <h4 class="card-title">Employees </h4>
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <form class="position-relative mr-1">
                                    <div class="form-group mb-0">
                                        <a class="btnChangePassword" href="{{route("reports")}}?export=true"><i
                                                class="ri-download-line"></i>
                                            &nbsp; Download Logs</a>
                                    </div>
                                </form>

                                <div class="form-group mb-0 mr-2">
                                        <a class="btnUpdate" href="{{ route('userss.index') }}">
                                            &nbsp; View All</a>
                                    </div>
                                <div class="todo-date d-flex mr-3">
                                    <i class="ri-calendar-2-line text-primary mr-2"></i>
                                    <span>{{ date('l, d M, Y') }}</span>
                                </div>
                            </div>

                                <div class="iq-card-header-toolbar d-flex align-items-center">
                                      <div class="dropdown">
                                         <span class="dropdown-toggle text-primary" id="dropdownMenuButton5" data-toggle="dropdown">
                                         <i class="ri-more-fill"></i>
                                         </span>
                                         <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton5">
                                            <a class="dropdown-item" href="#"><i class="ri-eye-fill mr-2"></i>View</a>
                                            <a class="dropdown-item" href="#"><i class="ri-delete-bin-6-fill mr-2"></i>Delete</a>
                                            <a class="dropdown-item" href="#"><i class="ri-pencil-fill mr-2"></i>Edit</a>
                                            <a class="dropdown-item" href="#"><i class="ri-printer-fill mr-2"></i>Print</a>
                                            <a class="dropdown-item" href="#"><i class="ri-file-download-fill mr-2"></i>Download</a>
                                         </div>
                                      </div>
                                </div> 
                        </div>
                        <div class="iq-card-body">
                            <div class="table-responsive">
                                <table class="table mb-0 table-borderless">
                                    <thead>
                                        <tr>
                                            <th> Name</th>
                                            <th> Email</th>
                                      
                                            <th>Phone No.</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($users as $item)
                                            <tr>
                                                <td>
                                                    {{ $item->name }}
                                                </td>
                                                <td>
                                                    {{ $item->email }}
                                                </td>

                                          

                                                <td>{{$item->phone ?? 'N/A'}}</td>
                                                <td><span
                                                        class="badge dark-icon-light iq-bg-primary">{{ $item->status ? 'Active' : 'Inactive' }}</span>
                                                </td>

                                                {{-- <td>
                                                    <div class="flex align-items-center list-user-action">
                                                        <a class="iq-bg-danger" data-id="{{ $item->id }}"
                                                            style="cursor: pointer"
                                                            href="{{ route('userAttendance', $item->id) }}"><i
                                                                class="ri-eye-fill"></i></a>


                                                    </div>

                                                </td> --}}

                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan=" 3">No records found</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div> -->
                {{-- <div class="col-lg-4">
                    <div class="iq-card iq-card-block iq-card-stretch ">
                        <div class="iq-card mb-0" style="box-shadow: none;">
                            <div class="iq-card-header d-flex justify-content-between">
                                <div class="iq-header-title">
                                    <h4 class="card-title">New Messages</h4>
                                </div>
                                <div class="iq-card-header-toolbar d-flex align-items-center">
                                    <a href="#">See All</a>
                                </div>
                            </div>
                        </div>
                        <div class="iq-card mb-0" style="box-shadow: none;">

                            <div class="iq-card-body">
                                <ul class="suggestions-lists m-0 p-0">



                                </ul>
                            </div>
                        </div>
                    </div>
                </div> --}}
            </div>
        </div>

    </div>
    
@endsection
@push('js')
<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/highcharts.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        Highcharts.chart('services-pie-chart', {
            chart: {
                type: 'pie'
            },
            title: {
                text: 'Top 5 Assigned Services'
            },
            tooltip: {
                pointFormat: '{series.name}: <b>{point.y}</b>'
            },
            series: [{
                name: 'Assignments',
                colorByPoint: true,
                data: @json($chartData)
            }]
        });
    });
</script>
@endpush
