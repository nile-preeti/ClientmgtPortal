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
                                <h2>{{ $customer->name }}</h2>
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
                                <p><a href="mailto:{{ $customer->email }}">{{ $customer->email }}</a></p>
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
                                <p>{{ $customer->phone }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="User-contact-info">
                            <div class="User-contact-info-icon">
                                <i class="ri-map-pin-line"></i>
                            </div>
                            <div class="User-contact-info-content">
                                <h2>Full Address</h2>
                                <p>{{ $customer->full_address }}</p>
                            </div>
                        </div>
                    </div>

                </div>
            </div>


            <div class="Scheduled-card mt-2">
                <h5 class="mt-2">Scheduled Jobs</h5>
                <div class="table-responsive">
                    <table id="user-list-table" class="table table-striped table-hover table-borderless mt-4" role="grid"
                        aria-describedby="user-list-page-info">
                        <thead>
                            <tr>
                                <th>Employee</th>
                                <th>Service</th>


                                <th>Start Date </th>
                                <th>End Date </th>
                                <th>From Time </th>
                                <th>To Time </th>
                                <th>Status</th>
                                <th>Action &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($job_schedules as $item)
                                <tr>
                                    <td>
                                        {{ $item->user->name }}
                                    </td>
                                    <td>
                                        {{ $item->service->name ?? 'N/A' }}
                                    </td>
                                    <td>{{ date('Y-m-d', strtotime($item->start_date)) }}
                                    </td>
                                    <td>{{ date('Y-m-d', strtotime($item->end_date)) }}
                                    </td>


                                    <td>{{ date('h:i A', strtotime($item->start_time)) }}
                                    </td>
                                    <td>{{ date('h:i A', strtotime($item->end_time)) }}
                                    </td>


                                    <td><span
                                            class="badge dark-icon-light iq-bg-primary">{{ $item->status ? 'Active' : 'Inactive' }}</span>
                                    </td>

                                    <td>
                                        <div class="flex align-items-center list-user-action">

                                            {{-- edit button --}}
                                            <a href="{{ route('job_schedules.edit', $item->id) }}" class="btnedit"><i
                                                    class="ri-pencil-fill"></i></a>
                                            {{-- delete  button --}}
                                            <a class="btndelete" data-id="{{ $item->id }}" style="cursor: pointer"
                                                data-url="{{ route('job_schedules.destroy', $item->id) }}"
                                                onclick="deletePublic(this)"><i class="ri-delete-bin-7-line"></i></a>
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
        </div>

    </div>
@endsection
