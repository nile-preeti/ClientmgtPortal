@extends('layouts.app')
@section('content')
<link href="https://api.mapbox.com/mapbox-gl-js/v2.15.0/mapbox-gl.css" rel="stylesheet">
<script src="https://api.mapbox.com/mapbox-gl-js/v2.15.0/mapbox-gl.js"></script>
<link href="https://api.mapbox.com/mapbox-gl-js/plugins/mapbox-gl-geocoder/v4.2.0/mapbox-gl-geocoder.css" rel="stylesheet">
<script src="https://api.mapbox.com/mapbox-gl-js/plugins/mapbox-gl-geocoder/v4.7.2/mapbox-gl-geocoder.min.js"></script>
@push('css')
<style>
        /* Adjust Geocoder input width */
        .mapboxgl-ctrl-geocoder {
            width: 100% !important;
            max-width: 400px !important; /* Adjust width as needed */
        }
        .mapboxgl-ctrl-geocoder{
            position: relative !important;
            color: var(--gray) !important;
            border-radius: 5px !important;
            font-weight: 400 !important;
            font-size: 13px !important;
            box-sizing: border-box !important;
            padding: 5px 0px !important;
            border: 1px solid var(--iq-dark-border) !important;
            width: 100% !important;
            background: #FFF !important;
            box-shadow: 0px 8px 13px 0px rgba(0, 0, 0, 0.05) !important;
            min-width: 100% !important;
        }
        .mapboxgl-ctrl-geocoder--input {
        height: 36px;
        padding: 0px 15px !important;
    }
    .mapboxgl-ctrl-geocoder .mapboxgl-ctrl-geocoder--icon-search {
        display: none !important;
    }
    .popup-heading .close{color: #fff !important; opacity: 1 !important;}
    .popup-heading h5{color: #fff;}
    .popup-heading{
        background: #0069ac;
        align-items: center;
        display: flex;
        margin-bottom: 20px;
        padding: 10px;
        justify-content: space-between;}

    </style>
@endpush


    <!-- Page Content  -->
    <div id="content-page" class="content-page">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="iq-card">
                        <!-- <div class="iq-card-header d-flex justify-content-between">
                                                                                                                              <div class="iq-header-title">
                                                                                                                                 <h4 class="card-title">Customer List</h4>
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
                                                            placeholder="Search" aria-controls="user-list-table"
                                                            value="{{ $search }}">
                                                    </div>
                                                    <button type="submit" class="" style="border: none; background: none; cursor: pointer;">
                                                                <i class="fa fa-search" style="color:#3d3e3e;font-size:16px;border: 1px solid #3d3e3e;box-shadow:0px 8px 13px 0px rgba(35, 53, 111, 0.12);border-radius: 5px;width: 45px;height:44px; justify-content: center; display: flex; align-items: center;"></i>
                                                            </button>
                                                </form>
                                            </div>
                                            <div class="btn-reload"
                                                onclick="window.location.href = window.location.origin + window.location.pathname;">
                                                <img src="{{ asset('reset.png') }}" height="15" alt="">
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
                                            <a class="addbtn" {{-- onclick='initializeDropzone("myDropzone", "{{ route('image-upload') }}", null)' --}} data-toggle="modal"
                                                data-target=".CreateModel" href="#"><i class="ri-add-circle-line"></i> Add</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="table-responsive">
                                    <table id="user-list-table"
                                        class="table table-striped table-hover table-borderless mt-0" role="grid"
                                        aria-describedby="user-list-page-info">
                                        <thead>
                                            <tr>
                                                <th> Name</th>
                                                <th>Email</th>
                                                <th>Phone No.</th>
                                                <th>Address</th>
                                                <th>Status</th>
                                                <th>Action &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($customers as $item)
                                                <tr>
                                                    <td class="d-flex align-items-center"><img
                                                            class="avatar-30 rounded mr-2"
                                                            src="{{ $item->image ? asset("uploads/images/$item->image") : asset('avatar-1.png') }}"
                                                            alt="profile"> {{ $item->name }}</td>

                                                    <td>{{ $item->email }}</td>

                                                    <td>{{ $item->phone ?? 'N/A' }}</td>
                                                    <td>{{ $item->full_address ?? 'N/A' }}</td>
                                                    <td><span
                                                            class="badge dark-icon-light {{ $item->status ? 'iq-bg-primary' : 'bg-danger' }}">{{ $item->status ? 'Active' : 'Inactive' }}</span>
                                                    </td>

                                                    <td>
                                                        <div class="flex align-items-center list-user-action">
                                                            <a class="btnedit" data-toggle="modal"
                                                                data-name="{{ $item->name ?? '' }}"
                                                                data-status="{{ $item->status ?? '' }}"
                                                                data-email="{{ $item->email ?? '' }}"
                                                                data-full_address="{{ $item->full_address }}"
                                                                data-city="{{ $item->city }}"
                                                                data-state_id="{{ $item->state_id }}"
                                                                data-zipcode="{{ $item->zipcode }}"
                                                                data-phone="{{ $item->phone }}"
                                                                data-url="{{ route('customers.update', $item->id) }}"
                                                                onclick="showData(this)" data-target="#EditModel"
                                                                style="cursor: pointer"><i class="ri-pencil-fill"></i></a>
                                                            {{-- delete  button --}}
                                                            <a class="btndelete" data-id="{{ $item->id }}"
                                                                style="cursor: pointer"
                                                                data-url="{{ route('customers.destroy', $item->id) }}"
                                                                onclick="deletePublic(this)"><i
                                                                    class="ri-delete-bin-7-line"></i></a>
                                                            <a class="btnview" data-id="{{ $item->id }}"
                                                                style="cursor: pointer"
                                                                href="{{ route('customers.show', encrypt($item->id)) }}"><i
                                                                    class="ri-eye-fill"></i></a>


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
                                            @if ($customers->onFirstPage())
                                                <li class="page-item disabled">
                                                    <a class="page-link" href="#" tabindex="-1"
                                                        aria-disabled="true">Previous</a>
                                                </li>
                                            @else
                                                <li class="page-item">
                                                    <a class="page-link"
                                                        href="{{ $customers->previousPageUrl() }}">Previous</a>
                                                </li>
                                            @endif

                                            @foreach ($customers->links()->elements as $element)
                                                @if (is_string($element))
                                                    <li class="page-item disabled"><a
                                                            class="page-link">{{ $element }}</a></li>
                                                @endif

                                                @if (is_array($element))
                                                    @foreach ($element as $page => $url)
                                                        @if ($page == $customers->currentPage())
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

                                            @if ($customers->hasMorePages())
                                                <li class="page-item">
                                                    <a class="page-link" href="{{ $customers->nextPageUrl() }}">Next</a>
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

    <div class="modal fade CreateModel" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content" style="overflow: hidden;">
                <div class="modal-body pt-0" style="padding-left: 15px; padding-right: 15px;">
                    <form action="{{ route('customers.store') }}" method="post" id="create_form" enctype="multipart/form-data">
                        @csrf

                        <div class="row modal-form-item">
                            <div class="col-lg-12 popup-heading">
                                <h5 class="modal-title">Add Customer</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>

                            <div class="col-lg-6 form-group">
                                <label for="name">Name*</label>
                                <input type="text" name="name" class="form-control" required>
                            </div>

                            <div class="col-lg-6 form-group">
                                <label for="email">Email*</label>
                                <input type="email" name="email" class="form-control" required>
                            </div>

                            <div class="col-lg-6 form-group">
                                <label for="phone">Phone Number*</label>
                                <input type="text" name="phone" placeholder="(678) 878-9909" data-inputmask="'mask': '(999) 999-9999'" class="form-control" required>
                            </div>

                            <div class="col-lg-6 form-group">
                                <label for="full_address">Full Address*</label>
                                <div id="geocoder-container"></div>
                                <input type="hidden" id="full_address" name="full_address" required>
                            </div>

                            <div class="col-lg-6 form-group">
                                <label for="city">City*</label>
                                <input type="text" id="city" name="city" class="form-control" required>
                            </div>

                            <div class="col-lg-6 form-group">
                                <label for="state_id">State</label>
                                <select class="form-control" name="state_id" id="state">
                                    @foreach ($states as $item)
                                        <option value="{{ $item->id }}">{{ $item->name }} </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-lg-6 form-group">
                                <label for="zipcode">Zip Code *</label>
                                <input type="number" name="zipcode" min="0" class="form-control" required id="zipcode">
                            </div>

                            <div class="col-lg-6 form-group">
                                <label for="status">Status</label>
                                <select class="form-control" name="status">
                                    <option value="1">Active </option>
                                    <option value="0">Inactive </option>
                                </select>
                            </div>
                        </div>

                        <div class="modal-action ">
                            <button type="submit" class="btnSubmit">Submit</button>
                            <button type="button" class="btnClose" data-dismiss="modal">Close</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade EditModel" tabindex="-1" role="dialog" aria-hidden="true" id="EditModel">
        <div class="modal-dialog modal-lg">
            <div class="modal-content overflow-hidden">
                <div class="modal-body pt-0" style="padding-left: 15px; padding-right: 15px;">
                    <form action="{{ route('customers.store') }}" method="post" id="edit_form"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        
                        <div class="row modal-form-item">
                            <div class="col-lg-12 popup-heading">
                                <h5 class="modal-title">Edit Customer</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="col-lg-6 form-group">
                                <label for="name">Name*</label>
                                <input type="text" name="name" id="name" class="form-control" required>
                            </div>
                            <div class="col-lg-6 form-group">
                                <label for="name">Email*</label>
                                <input type="email" name="email" id="email" class="form-control" required>
                            </div>

                            <div class="col-lg-6 form-group">
                                <label for="name">Phone Number*</label>
                                <input type="text" name="phone" id="phone" placeholder="(678) 878-9909"
                                    data-inputmask="'mask': '(999) 999-9999'" id="phone" class="form-control"
                                    required>
                            </div>
                            <div class="col-lg-6 form-group">
                                <label for="full_address_edit">Full Address*</label>
                                <div id="edit-geocoder-container"></div> <!-- Geocoder will be attached here -->
                                <input type="hidden" id="full_address_edit" name="full_address" required>
                            </div>
                            <div class="col-lg-6 form-group">
                                <label for="name">City*</label>
                                <input type="text" name="city"  class="form-control" required id="city_edit">
                            </div>
                            <div class="col-lg-6 form-group">
                                <label for="name">State</label>
                                <select class="form-control" name="state_id" id="state_id">
                                    @foreach ($states as $item)
                                        <option value="{{ $item->id }}">{{ $item->name }} </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-lg-6 form-group">
                                <label for="name">Zip Code *</label>
                                <input type="number" name="zipcode" id="zipcode_edit" min="0" class="form-control"
                                    required>
                            </div>
                            {{-- <input type="hidden" name="image" id="create_image" class="form-control">
                        <div class="form-group">
                            <div class="dropzone" id="myDropzone"></div>
                        </div> --}}
                            <div class="col-lg-6 form-group">
                                <label for="name">Status</label>
                                <select class="form-control" name="status" id="status">
                                    <option value="1">Active </option>
                                    <option value="0">Inactive </option>
                                </select>
                            </div>
                        </div>
                        <div class="modal-action justify-content-end d-flex">
                            <div>
                            <button type="submit" class="btnSubmit">Submit</button>

                            <button type="button" class="btnClose" data-dismiss="modal">Close</button>
                            <!-- <button type="button" class="btn btn-success">Approve</button> --></div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
@endsection
@push('js')

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/3.3.4/jquery.inputmask.bundle.min.js"></script>
<script>
    mapboxgl.accessToken = "pk.eyJ1IjoidXNlcnMxIiwiYSI6ImNsdGgxdnpsajAwYWcya25yamlvMHBkcGEifQ.qUy8qSuM_7LYMSgWQk215w";

    const geocoder = new MapboxGeocoder({
        accessToken: mapboxgl.accessToken,
        types: 'address',
        placeholder: 'Start typing address...',
        countries: 'us',
        mapboxgl: undefined,
        marker: false
    });

    document.getElementById('geocoder-container').appendChild(geocoder.onAdd());

    geocoder.on('result', function (e) {
        let place = e.result;
        let city = "";
        let state = "";
        let zip = "";

        // Store full address
        document.getElementById('full_address').value = place.place_name;

        place.context.forEach((component) => {
            if (component.id.includes("place")) {
                city = component.text;
            }
            if (component.id.includes("region")) {
                state = component.text;
            }
            if (component.id.includes("postcode")) {
                zip = component.text;
            }
        });

        document.getElementById('city').value = city;

        let stateDropdown = document.getElementById('state');
        for (let i = 0; i < stateDropdown.options.length; i++) {
            if (stateDropdown.options[i].text.trim().toLowerCase() === state.toLowerCase()) {
                stateDropdown.options[i].selected = true;
                break;
            }
        }

        document.getElementById('zipcode').value = zip;
    });
</script>

<script>
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

    function showData(ele) {
        $("#edit_form").attr("action", ele.getAttribute("data-url"));

        $("#email").val(ele.getAttribute("data-email"));
        $("#full_address_edit").val(ele.getAttribute("data-full_address"));
        $("#city_edit").val(ele.getAttribute("data-city"));
        $("#state_id").val(ele.getAttribute("data-state_id"));
        $("#zipcode_edit").val(ele.getAttribute("data-zipcode"));
        $("#phone").val(ele.getAttribute("data-phone"));
        $("#status").val(ele.getAttribute("data-status"));
        $("#name").val(ele.getAttribute("data-name"));
        initializeEditGeocoder();

        setTimeout(() => {
            let geocoderInput = document.querySelector("#edit-geocoder-container input");
            geocoderInput.value = ele.getAttribute("data-full_address"); // Set input field
        }, 500);
    }

    function deletePublic(ele) {
        var title = 'Are you sure, you want to delete this Customer ?';
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
<script type="text/javascript">
    Dropzone.autoDiscover = false;

    function initializeDropzone(dropzoneId, uploadUrl, existingImageUrl = null, edit = false) {
        const myDropzone = new Dropzone(`#${dropzoneId}`, {
            dictDefaultMessage: '<img src="{{ asset('upload.png') }}" style="height:40px" alt="Drop an image here">',
            maxFilesize: 1, // Maximum file size in MB
            maxFiles: 1, // Allow only one file
            renameFile: function(file) {
                const dt = new Date();
                return dt.getTime() + file.name;
            },
            acceptedFiles: ".jpeg,.jpg,.png,.mp3", // Allowed file types
            timeout: 5000,
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            url: "{{ route('image-upload') }}", // Set dynamic upload URL
            addRemoveLinks: true,
            removedfile: function(file) {
                const name = file.upload ? file.upload.filename : file.name; // Handle manually added files
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    type: 'POST',
                    url: "{{ route('image-delete') }}", // URL for file removal
                    data: {
                        filename: name
                    },
                    success: function() {
                        console.log("File removed successfully");
                    },
                    error: function(e) {
                        console.error(e);
                    }
                });

                if (file.previewElement) {
                    file.previewElement.parentNode.removeChild(file.previewElement);
                }
            },
            success: function(file, response) {
                if (edit) {
                    $("#edit_image").val(response);
                } else {
                    $("#create_image").val(response);

                }
                console.log("File uploaded successfully:", response);
            },
            error: function(file, response) {
                console.error("File upload error:", response);
            },
            init: function() {
                this.on("addedfile", function(file) {
                    if (this.files.length > 1) {
                        this.removeFile(file);
                        alert("Only one file can be uploaded at a time!");
                    }
                });

                // If there's an existing image, display it in Dropzone
                if (existingImageUrl) {
                    const mockFile = {
                        name: "Existing Image",
                        size: 12345,
                        type: "image/jpeg"
                    };
                    this.emit("addedfile", mockFile);
                    this.emit("thumbnail", mockFile, existingImageUrl);
                    this.emit("complete", mockFile);
                    this.files.push(mockFile); // Add the file to the Dropzone files array
                }
            }
        });

        return myDropzone;
    }
</script>
<script>
    mapboxgl.accessToken = "pk.eyJ1IjoidXNlcnMxIiwiYSI6ImNsdGgxdnpsajAwYWcya25yamlvMHBkcGEifQ.qUy8qSuM_7LYMSgWQk215w";

    let editGeocoder;

    function initializeEditGeocoder() {
        // Remove previous geocoder if it exists
        if (editGeocoder) {
            document.getElementById("edit-geocoder-container").innerHTML = "";
        }

        editGeocoder = new MapboxGeocoder({
            accessToken: mapboxgl.accessToken,
            types: "address",
            placeholder: "Start typing address...",
            countries: "us",
            mapboxgl: undefined,
            marker: false
        });

        document.getElementById("edit-geocoder-container").appendChild(editGeocoder.onAdd());

        // Get the input field inside the geocoder
        let geocoderInput = document.querySelector("#edit-geocoder-container input");

        // Clear input field when clicking on it to allow fresh typing
        geocoderInput.addEventListener("focus", function () {
            if (this.value === document.getElementById("full_address_edit").value) {
                this.value = ""; // Clear the input field when clicked
            }
        });

        // Handle selection event
        editGeocoder.on("result", function (e) {
            let place = e.result;
            let city = "", state = "", zip = "";

            // Store full address
            document.getElementById("full_address_edit").value = place.place_name;

            place.context.forEach((component) => {
                if (component.id.includes("place")) city = component.text;
                if (component.id.includes("region")) state = component.text;
                if (component.id.includes("postcode")) zip = component.text;
            });

            document.getElementById("city_edit").value = city;

            let stateDropdown = document.getElementById("state_id");
            for (let i = 0; i < stateDropdown.options.length; i++) {
                if (stateDropdown.options[i].text.trim().toLowerCase() === state.toLowerCase()) {
                    stateDropdown.options[i].selected = true;
                    break;
                }
            }

            document.getElementById("zipcode_edit").value = zip;
        });
    }
</script>
@endpush
