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
                                <h2>Personal  Details</h2>
                                <div class="row g-2">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="name">Name*</label>
                                            <input type="text" name="" class="form-control" placeholder="Name">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="name">Email*</label>
                                            <input type="email" name="email" value="ashmita.shrivastava@niletechnologies.com" class="form-control" required="" aria-invalid="false">
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="name">Phone Number*</label>
                                            <input type="text" placeholder="(678) 878-9909" value="" data-inputmask="'mask': '(999) 999-9999'" required="" name="phone" class="form-control">
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="name">Full Address*</label>
                                            <input type="text" name="full_address" id="full_address" class="form-control" required="" value="2030 Jennie Lee Drive, Idaho Falls, ID, USA">
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="name">City*</label>
                                            <input type="text" name="city" id="city" class="form-control" required="">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="name">State *</label>
                                            <select class="form-control" name="state_id" id="state_id">
                                                <option value="1456">Alabama </option>
                                                <option value="1400">Alaska </option>
                                                <option value="1424">American Samoa </option>
                                                <option value="1434">Arizona </option>
                                                <option value="1444">Arkansas </option>
                                                <option value="1402">Baker Island </option>
                                                <option value="1416">California </option>
                                                <option value="1450">Colorado </option>
                                                <option value="1435">Connecticut </option>
                                                <option value="1399">Delaware </option>
                                                <option value="1437">District of Columbia </option>
                                                <option value="1436">Florida </option>
                                                <option value="1455">Georgia </option>
                                                <option value="1412">Guam </option>
                                                <option value="1411">Hawaii </option>
                                                <option value="1398">Howland Island </option>
                                                <option value="1460">Idaho </option>
                                                <option value="1425">Illinois </option>
                                                <option value="1440">Indiana </option>
                                                <option value="1459">Iowa </option>
                                                <option value="1410">Jarvis Island </option>
                                                <option value="1428">Johnston Atoll </option>
                                                <option value="1406">Kansas </option>
                                                <option value="1419">Kentucky </option>
                                                <option value="1403">Kingman Reef </option>
                                                <option value="1457">Louisiana </option>
                                                <option value="1453">Maine </option>
                                                <option value="1401">Maryland </option>
                                                <option value="1433">Massachusetts </option>
                                                <option value="1426">Michigan </option>
                                                <option value="1438">Midway Atoll </option>
                                                <option value="1420">Minnesota </option>
                                                <option value="1430">Mississippi </option>
                                                <option value="1451">Missouri </option>
                                                <option value="1446">Montana </option>
                                                <option value="1439">Navassa Island </option>
                                                <option value="1408">Nebraska </option>
                                                <option value="1458">Nevada </option>
                                                <option value="1404">New Hampshire </option>
                                                <option value="1417">New Jersey </option>
                                                <option value="1423">New Mexico </option>
                                                <option value="1452">New York </option>
                                                <option value="1447">North Carolina </option>
                                                <option value="1418">North Dakota </option>
                                                <option value="1431">Northern Mariana Islands </option>
                                                <option value="4851">Ohio </option>
                                                <option value="1421">Oklahoma </option>
                                                <option value="1415">Oregon </option>
                                                <option value="1448">Palmyra Atoll </option>
                                                <option value="1422">Pennsylvania </option>
                                                <option value="1449">Puerto Rico </option>
                                                <option value="1461">Rhode Island </option>
                                                <option value="1443">South Carolina </option>
                                                <option value="1445">South Dakota </option>
                                                <option value="1454">Tennessee </option>
                                                <option value="1407">Texas </option>
                                                <option value="1432">United States Minor Outlying Islands </option>
                                                <option value="1413">United States Virgin Islands </option>
                                                <option value="1414">Utah </option>
                                                <option value="1409">Vermont </option>
                                                <option value="1427">Virginia </option>
                                                <option value="1405">Wake Island </option>
                                                <option value="1462">Washington </option>
                                                <option value="1429">West Virginia </option>
                                                <option value="1441">Wisconsin </option>
                                                <option value="1442">Wyoming </option>
                                            </select>
                                        </div>
                                    </div>
                                   
                               
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="name">Zip Code *</label>
                                            <input type="number" name="zipcode" id="zipcode" min="0" class="form-control" required="">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="client-form-card"> 
                                <h2>Customer Details</h2>
                                <div class="row g-2">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="name">Customer*</label>
                                            <select name="customer_id" class="form-control">
                                                @foreach ($customers as $item)
                                                    <option value="{{ $item->id }}" @selected(isset($job_schedule) && $job_schedule->customer_id == $item->id)>
                                                        {{ $item->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="name">Employee*</label>
                                            <select name="user_id" class="form-control" onchange="getServices(this.value)">
                                                @foreach ($users as $item)
                                                    <option value="{{ $item->id }}" @selected(isset($job_schedule) && $job_schedule->user_id == $item->id)>
                                                        {{ $item->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="name">Service*</label>
                                            <div id="service_container">
                                                <select name="service_id" class="form-control">

                                                    <option value="0">--Select Service</option>
                                                    @if (isset($job_schedule))
                                                        @foreach ($job_schedule->user->services as $item)
                                                            <option value="{{ $item->service->id }}"
                                                                @selected(isset($job_schedule) && $job_schedule->service_id == $item->service->id)>{{ $item->service->name }}
                                                            </option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                            </div>

                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="name">Start Date*</label>
                                            <input type="date" placeholder="(678) 878-9909"
                                                @if (isset($job_schedule)) value="{{ $job_schedule->start_date }}" @endif
                                                required name="start_date" class="form-control" required>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="name">End Date*</label>
                                            <input type="date" placeholder="(678) 878-9909"
                                                @if (isset($job_schedule)) value="{{ $job_schedule->end_date }}" @endif
                                                required name="end_date" class="form-control" required>
                                        </div>
                                    </div>
                                   
                               
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="name">Start Time*</label>
                                            <input type="time" placeholder="(678) 878-9909"
                                                @if (isset($job_schedule)) value="{{ $job_schedule->start_time }}" @endif
                                                required name="start_time" class="form-control" required>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="time">End Time*</label>
                                            <input type="time" placeholder="(678) 878-9909"
                                                @if (isset($job_schedule)) value="{{ $job_schedule->end_time }}" @endif
                                                required name="end_time" class="form-control" required>
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

            $('#create_form').validate({
                rules: {

                    name: {
                        required: true,
                        maxlength: 191,
                    },


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
                                        window.location = response.route;
                                    }
                                    var url = $('#redirect_url').val();
                                    if (url !== undefined || url != null) {
                                        window.location = url;
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
                                        window.location = response.route;
                                    }
                                    var url = $('#redirect_url').val();
                                    if (url !== undefined || url != null) {
                                        window.location = url;
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
@endsection
