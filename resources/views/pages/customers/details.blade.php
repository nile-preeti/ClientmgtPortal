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
                    <table id="user-list-table" class="table table-striped table-borderless mt-4" role="grid"
                        aria-describedby="user-list-page-info">
                        <thead>
                            <tr>
                                <th>Employee</th>
                                <th>Service</th>
                                <th>Sub Category</th>
                                <th> Date </th>
                                <th>From Time </th>
                                <th>To Time </th>
                                <th>Status</th>
                                <th>Total Hours Worked</th>
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
                                    <td>
                                        {{ $item->subCategory->sub_category ?? 'N/A' }}
                                    </td>
                                    <td>{{ date('Y-m-d', strtotime($item->start_date)) }}
                                    </td>


                                    <td>{{ date('h:i A', strtotime($item->start_time)) }}
                                    </td>
                                    <td>{{ date('h:i A', strtotime($item->end_time)) }}
                                    </td>


                                    <td>
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
                                    <span
                                            class="badge dark-icon-light {{ $badgeClass }}">{{ $statusLabel }}</span>
                                    </td>
                                        <td> {{ $jobTotalHours[$item->id] ?? '0.00' }} hrs</td>
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
