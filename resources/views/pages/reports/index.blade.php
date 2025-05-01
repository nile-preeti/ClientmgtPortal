@extends('layouts.app')
@section('content')
    <style>
        .search-filter-info .form-group select.form-control {
            padding: 10px 15px 14px 15px !important;
        }
    </style>
    <!-- Page Content  -->
    <div id="content-page" class="content-page">
        <div class="container-fluid">
            <div class="row">
                
                <div class="col-sm-6 col-md-6 col-lg-3">
                <a href="{{ route('employee.reports') }}" style="text-decoration: none; color: inherit;">
                    <div class="iq-card iq-card-block iq-card-stretch " style="cursor: pointer">
                        <div class="iq-card-body">
                            <div class="d-flex d-flex align-items-center justify-content-between">
                                <div>
                                    <h6 class="over-title-text">Reports by Employees</h6>
                                    <h2 class="over-title-value">{{$users}}</h2>
                                </div>
                                <div class="iq-card-icon"><i class="ri-group-line"></i></div>
                            </div>
                        </div>
                    </div>
                     </a>
                </div>
                <div class="col-sm-6 col-md-6 col-lg-3">
                <a href="{{ route('customer.reports') }}" style="text-decoration: none; color: inherit;">
                    <div class="iq-card iq-card-block iq-card-stretch " style="cursor: pointer">
                        <div class="iq-card-body">
                            <div class="d-flex d-flex align-items-center justify-content-between">
                                <div>
                                    <h6 class="over-title-text">Reports by Customer</h6>
                                    <h2 class="over-title-value">{{$customer}}</h2>
                                </div>
                                <div class="iq-card-icon"><i class="ri-user-3-line"></i></div>
                            </div>
                        </div>
                    </div>
                    </a>
                </div>
                <div class="col-sm-6 col-md-6 col-lg-3">
                    <div class="iq-card iq-card-block iq-card-stretch " onclick="location.replace('https://nileprojects.in/client-portal/users')" style="cursor: pointer">
                        <div class="iq-card-body">
                            <div class="d-flex d-flex align-items-center justify-content-between">
                                <div>
                                    <h6 class="over-title-text">Reports by Month</h6>
                                    <h2 class="over-title-value">12</h2>
                                </div>
                                <div class="iq-card-icon"><i class="ri-calendar-event-line"></i></div>
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
