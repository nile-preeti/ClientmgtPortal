@extends('layouts.app')
@section('content')
    <!-- Page Content  -->
    <div id="content-page" class="content-page">
        <div class="container-fluid">

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
            @forelse ($job_schedules as $item)
            <div class="iq-card">
                        <div class="iq-card-body">
                       
                            <div class="info-card">
                           
                                <div class="row align-items-center">
                                    <!-- <div class="col-md-3">
                                        <div class="d-flex align-items-center">
                                            <div class="info-image">
                                                <img src="https://nileprojects.in/clearchoice-janitorial/public/assets/admin-images/user-default.png" alt="image" class="img-fluid">
                                            </div>
                                            <div class="name-info ml-3">
                                                <p>Roy3</p>
                                                <h6 class="mt-2"></h6>
                                            </div>
                                        </div>
                                    </div> -->
                                    <div class="col-md-2">
                                        <h6>Customer</h6>
                                        <p class="mt-2">{{ $item->customer->name }}</p>
                                    </div>
                                    <div class="col-md-2">
                                        <h6>Service</h6>
                                        <p class="mt-2"> {{ $item->service->name ?? 'N/A' }}</p>
                                    </div>
                                    <div class="col-md-2">
                                        <h6>Service Charge/hour </h6>
                                        <p class="mt-2"> ${{ $item->charge }}</p>
                                    </div>
                                    <div class="col-md-2">
                                        <h6>Start Date </h6>
                                        <p class="mt-2">{{ date('Y-m-d', strtotime($item->start_date)) }}</p>
                                    </div>
                                    <div class="col-md-2">
                                        <h6>End Date </h6>
                                        <p class="mt-2">{{ date('Y-m-d', strtotime($item->end_date)) }}</p>
                                    </div>
                                    <div class="col-md-2">
                                        <h6>From Time </h6>
                                        <p class="mt-2">{{ date('h:i A', strtotime($item->start_time)) }}</p>
                                    </div>
                                    <div class="col-md-2 mt-2">
                                        <h6>To Time </h6>
                                        <p class="mt-2">{{ date('h:i A', strtotime($item->end_time)) }}</p>
                                    </div>

                                    <div class="col-md-2 account-status mt-2">
                                        <h6 class="mb-2">Status</h6>
                                        <span class="badge dark-icon-light iq-bg-primary">{{ $item->status ? 'Active' : 'Inactive' }}</span>
                                    </div>
                                    <div class="col-md-3 account-status mt-2">
                                        <h6 class="mb-2">Action</h6>
                                        <div class=" flex align-items-center list-user-action">
                                            <a href="{{ route('job_schedules.edit', $item->id) }}"  class="btnedit"><i class="ri-pencil-fill"></i></a>
                                            <a class="btndelete" data-id="{{ $item->id }}" style="cursor: pointer"
                                                data-url="{{ route('job_schedules.destroy', $item->id) }}"
                                                onclick="deletePublic(this)"><i class="ri-delete-bin-7-line"></i></a>
                                        </div>
                                    </div>
                                    <!-- <div class="col-md-2">
                                        <div class="text-end">
                                            <a href="https://nileprojects.in/clearchoice-janitorial/timesheet-requests/13" class="view-btn"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye me-2" viewBox="0 0 16 16"><path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8zM1.173 8a13.133 13.133 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13.133 13.133 0 0 1 14.828 8c-.058.087-.122.183-.195.288-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5c-2.12 0-3.879-1.168-5.168-2.457A13.134 13.134 0 0 1 1.172 8z"></path><path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5zM4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0z"></path></svg>View</a>
                                        </div>
                                    </div> -->
                                </div>
                               
                            </div>
                             
                        </div>
                    </div>
                    @empty
                                <div class="row align-items-center"> <>
                                    <p>No records found</p>
                                </div>
                            @endforelse
        </div>

    </div>
    @endsection
