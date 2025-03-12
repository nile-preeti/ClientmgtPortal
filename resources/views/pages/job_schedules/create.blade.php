@extends('layouts.app')
@section('content')
    <!-- Page Content  -->
    <div id="content-page" class="content-page">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="client-form">
                        <form
                            action="{{ isset($job_schedule) ? route('job_schedules.update', $job_schedule) : route('job_schedules.store') }}"
                            method="post" id="create_form" enctype="multipart/form-data">
                            @csrf
                            @if (isset($job_schedule))
                                @method('PUT')
                            @endif

                            <div class="client-form-card">
                                <h2>Customer Details</h2>
                                <div class="row g-2">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="customer_select">Select Customer*</label>
                                            <select name="customer_id" id="customer_select" class="form-control"
                                                onchange="getCustomerDetails(this.value)">
                                                <option value="0">--Select Customer--</option>

                                                @foreach ($customers as $item)
                                                    <option value="{{ $item->id }}"
                                                        @if (isset($job_schedule) && $job_schedule->customer_id == $item->id) selected @endif>
                                                        {{ $item->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="customer_name">Name*</label>
                                            <input type="text" id="customer_name" readonly class="form-control"
                                                placeholder="Name"
                                                @if (isset($job_schedule)) value="{{ $job_schedule->customer->name }}" @endif>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="customer_email">Email*</label>
                                            <input type="email" id="customer_email" readonly class="form-control"
                                                @if (isset($job_schedule)) value="{{ $job_schedule->customer->email }}" @endif>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="customer_phone">Phone Number*</label>
                                            <input type="text" id="customer_phone"
                                                data-inputmask="'mask': '(999) 999-9999'" class="form-control"
                                                @if (isset($job_schedule)) value="{{ $job_schedule->customer->phone }}" @endif>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="full_address">Full Address*</label>
                                            <input type="text" id="full_address" class="form-control"
                                                @if (isset($job_schedule)) value="{{ $job_schedule->customer->full_address }}" @endif>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="city">City*</label>
                                            <input type="text" id="city" class="form-control"
                                                @if (isset($job_schedule)) value="{{ $job_schedule->customer->city }}" @endif>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="state_id">State *</label>
                                            <select class="form-control" id="state_id">
                                                <option value="">Select State</option>
                                                @php $selectedState = isset($job_schedule) ? $job_schedule->customer->state_id : ''; @endphp
                                                @foreach ($states as $item)
                                                    <option value="{{ $item->id }}"
                                                        @if ($selectedState == $item->id) selected @endif>
                                                        {{ $item->name }}</option>
                                                @endforeach

                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="zipcode">Zip Code *</label>
                                            <input type="number" id="zipcode" min="0" class="form-control"
                                                @if (isset($job_schedule)) value="{{ $job_schedule->customer->zipcode }}" @endif>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <div class="client-form-card">
                                <h2>Job Details</h2>

                                <div class="row g-2">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="name">Service*</label>
                                            <div id="service_container">
                                                <select name="service_id" class="form-control">

                                                    <option value="0">--Select Service</option>

                                                    @foreach ($services as $item)
                                                        <option value="{{ $item->id }}" @selected(isset($job_schedule) && $job_schedule->service_id == $item->id)>
                                                            {{ $item->name }}
                                                        </option>
                                                    @endforeach

                                                </select>
                                            </div>

                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="name">Employee*</label>
                                            <select name="user_id" class="form-control">
                                                @foreach ($users as $item)
                                                    <option value="{{ $item->id }}" @selected(isset($job_schedule) && $job_schedule->user_id == $item->id)>
                                                        {{ $item->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>


                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="name">Start Date*</label>
                                            <input type="date" placeholder="(678) 878-9909"
                                                @if (isset($job_schedule)) value="{{ $job_schedule->start_date }}" @endif
                                                required name="start_date" class="form-control" required    min="{{ isset($job_schedule) ? $job_schedule->start_date : date('Y-m-d') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="name">End Date*</label>
                                            <input type="date" placeholder="(678) 878-9909"
                                                @if (isset($job_schedule)) value="{{ $job_schedule->end_date }}" @endif
                                                required name="end_date" class="form-control" required    min="{{ isset($job_schedule) ? $job_schedule->end_date : date('Y-m-d') }}">
                                        </div>
                                    </div>


                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="name">Start Time*</label>
                                            <input type="time" placeholder="(678) 878-9909"
                                                @if (isset($job_schedule)) value="{{ $job_schedule->start_time }}" @endif
                                                required name="start_time" class="form-control" required  min="{{ isset($job_schedule) ? $job_schedule->start_time : date('H:i') }}" id="start_time">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="time">End Time*</label>
                                            <input type="time" placeholder="(678) 878-9909"
                                                @if (isset($job_schedule)) value="{{ $job_schedule->end_time }}" @endif
                                                required name="end_time" class="form-control" required  min="{{ isset($job_schedule) ? $job_schedule->end_time : date('H:i') }}"  id="end_time">
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="name">Status</label>
                                            <select class="form-control" name="status">
                                                <option value="1" @selected(isset($job_schedule) && $job_schedule->status == 1)>Active </option>
                                                <option value="0" @selected(isset($job_schedule) && $job_schedule->status == 0)>Inactive </option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-group"><label for="">Job Details</label>
                                            <textarea id="" cols="30" rows="3" name="description" class="form-control">
                                                @if (isset($job_schedule))
{{ $job_schedule->description }}
@endif
                                            </textarea>
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-md-12">
                                    <button type="submit" class="btnSubmit">Submit</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/3.3.4/jquery.inputmask.bundle.min.js"></script>
    <script></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/3.3.4/jquery.inputmask.bundle.min.js"></script>

    <script>
        function getServices(emp_id) {
            if (emp_id != 0) {
                $.get("{{ route('get_service') }}" + "?id=" + emp_id, function(data, status) {
                    if (data.success) {


                        var innerHTML = `<select name="service_id" class="form-control">
                                             <option value="0">--Select Service</option>`;
                        data.services.map(item => {
                            innerHTML += `<option value="${item.id}">${item.name}</option>`;
                        });
                        innerHTML += `</select>`
                        $("#service_container").html(innerHTML);
                    }
                })
            }
        }

        function getCustomerDetails(customerId) {
            if (!customerId) {
                return;
            }

            $.ajax({
                url: "{{ route('get_customer') }}",
                type: "GET",
                data: {
                    id: customerId
                },
                success: function(response) {
                    if (response) {
                        $('#customer_name').val(response.name || '');
                        $('#customer_email').val(response.email || '');
                        $('#customer_phone').val(response.phone || '');
                        $('#full_address').val(response.full_address || '');
                        $('#city').val(response.city || '');
                        $('#state_id').val(response.state_id || '');
                        $('#zipcode').val(response.zipcode || '');
                    }
                },
                error: function() {
                    alert('Failed to fetch customer details.');
                }
            });
        }
        $(":input").inputmask();
        $(document).ready(function() {
            // Add a custom validation rule
            $.validator.addMethod("imageFile", function(value, element) {
                // Check if the file extension is of an image type
                return this.optional(element) || /\.(jpg|jpeg|png|gif)$/i.test(value);
            }, "Please select a valid image file (JPG, JPEG, PNG, GIF).");
            $.validator.addMethod("phoneValid", function(value) {
                return /^[\+]?[(]?[0-9]{3}[)]?[-\s\.]?[0-9]{3}[-\s\.]?[0-9]{4,6}$/.test(value);
            }, 'Invalid phone number.');
            $.validator.addMethod("numericOrDecimal", function(value, element) {
                return this.optional(element) || /^[0-9]+(\.[0-9]+)?$/.test(value);
            }, "Please enter a valid numeric value .");
            $.validator.addMethod("integer", function(value, element) {
                return value != 0;
            }, "Please select valid option.");
            $('#create_form').validate({
                rules: {

                    name: {
                        required: true,
                        maxlength: 191,
                    },
                    customer_id: {
                        integer: true
                    },
                    user_id: {
                        integer: true
                    },
                    service_id: {
                        integer: true
                    }


                },
                errorElement: "span",
                errorPlacement: function(error, element) {
                    error.addClass("text-danger");
                    element.closest(".form-group").append(error);
                },
                highlight: function(element, errorClass, validClass) {
                    $('.please-wait').click();
                    $(element).addClass("text-danger ");
                },
                unhighlight: function(element, errorClass, validClass) {
                    $(element).removeClass("text-danger");
                },
                submitHandler: function(form, event) {
                    event.preventDefault();
                    let formData = new FormData(form);

                    $.ajax({
                        type: 'post',
                        url: form.action,
                        data: formData,
                        dataType: 'json',
                        contentType: false,
                        processData: false,

                        success: function(response) {
                            if (response.success) {

                                Swal.fire({
                                    title: 'Success',
                                    text: response.message,
                                    icon: 'success',

                                }).then((result) => {

                                    if (response.redirect == true) {
                                        window.location.href = response.route; 
                                    } else {
                                        location.reload(true);
                                    }
                                })

                                return false;
                            }

                            if (response.success == false) {
                                Swal.fire(
                                    'Error',
                                    response.message,
                                    'error'
                                );

                                return false;
                            }
                        },
                        error: function(data) {
                            if (data.status == 422) {
                                var form = $("#create_form");
                                let li_htm = '';
                                $.each(data.responseJSON.errors, function(k, v) {
                                    const $input = form.find(
                                        `input[name=${k}],select[name=${k}],textarea[name=${k}]`
                                    );
                                    if ($input.next('small').length) {
                                        $input.next('small').html(v);
                                        if (k == 'services' || k == 'membership') {
                                            $('#myselect').next('small').html(v);
                                        }
                                    } else {
                                        $input.after(
                                            `<small class='text-danger'>${v}</small>`
                                        );
                                        if (k == 'services' || k == 'membership') {
                                            $('#myselect').after(
                                                `<small class='text-danger'>${v[0]}</small>`
                                            );
                                        }
                                    }
                                    li_htm += `<li>${v}</li>`;
                                });

                                return false;
                            } else {
                                Swal.fire(
                                    'Error',
                                    data.statusText,
                                    'error'
                                );
                            }
                            return false;

                        }
                    });
                }
            })

            $('#edit_form').validate({
                rules: {
                    name: {
                        required: true,
                        maxlength: 191,
                    },
                    description: {
                        maxlength: 100
                    },
                },
                errorElement: "span",
                errorPlacement: function(error, element) {
                    error.addClass("text-danger");
                    element.closest(".form-group").append(error);
                },
                highlight: function(element, errorClass, validClass) {
                    $('.please-wait').click();
                    $(element).addClass("text-danger");
                },
                unhighlight: function(element, errorClass, validClass) {
                    $(element).removeClass("text-danger");
                },
                submitHandler: function(form, event) {
                    event.preventDefault();
                    let formData = new FormData(form);

                    $.ajax({
                        type: 'post',
                        url: form.action,
                        data: formData,
                        dataType: 'json',
                        contentType: false,
                        processData: false,

                        success: function(response) {
                            if (response.success) {

                                Swal.fire({
                                    title: 'Success',
                                    text: response.message,
                                    icon: 'success',

                                }).then((result) => {

                                    if (response.redirect == true) {
                                        window.location.href = response.route; 
                                    } else {
                                        location.reload(true);
                                    }
                                })

                                return false;
                            }

                            if (response.success == false) {
                                Swal.fire(
                                    'Error',
                                    response.message,
                                    'error'
                                );

                                return false;
                            }
                        },
                        error: function(data) {
                            if (data.status == 422) {
                                var form = $("#edit_form");
                                let li_htm = '';
                                $.each(data.responseJSON.errors, function(k, v) {
                                    const $input = form.find(
                                        `input[name=${k}],select[name=${k}],textarea[name=${k}]`
                                    );
                                    if ($input.next('small').length) {
                                        $input.next('small').html(v);
                                        if (k == 'services' || k == 'membership') {
                                            $('#myselect').next('small').html(v);
                                        }
                                    } else {
                                        $input.after(
                                            `<small class='text-danger'>${v}</small>`
                                        );
                                        if (k == 'services' || k == 'membership') {
                                            $('#myselect').after(
                                                `<small class='text-danger'>${v[0]}</small>`
                                            );
                                        }
                                    }
                                    li_htm += `<li>${v}</li>`;
                                });

                                return false;
                            } else {
                                Swal.fire(
                                    'Error',
                                    data.statusText,
                                    'error'
                                );
                            }
                            return false;

                        }
                    });
                }
            })
        });
    </script>
    <script>
    document.getElementById('start_time').addEventListener('change', function() {
        let startTime = this.value;
        document.getElementById('end_time').min = startTime;
    });
</script>
@endsection
