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
                <div class="col-sm-12">
                    <div class="iq-card">
                   <!-- <div class="iq-card-header d-flex justify-content-between">
                          <div class="iq-header-title">
                             <h4 class="card-title">User List</h4>
                          </div>
                       </div> -->
                        <div class="iq-card-body">
                            <div class="search-filter-info">
                                <div class="row">
                                    <div class="col-sm-12 col-md-4">

                                        <div class="users-filter-search">
                                            <div id="user_list_datatable_info" class="dataTables_filter filter-search-info">
                                            <form class="position-relative d-flex" style="gap: 5px">
                                                    <div class="form-group mb-0" style="width: 100%">
                                                        <input type="search" class="form-control" name="search"
                                                            placeholder="Search..." aria-controls="user-list-table"
                                                            value="{{ $search }}">
                                                    </div>
                                                    <button type="submit" class="" style="border: none;  cursor: pointer;">
                                                        <i class="fa fa-search" style="color:#fff;background: #3d3e3e;font-size:16px;border: 1px solid #3d3e3e;box-shadow:0px 8px 13px 0px rgba(35, 53, 111, 0.12);border-radius: 5px;width: 45px;height:44px;  display: flex; justify-content: center; align-items: center;"></i>
                                                    </button>
                                                </form>
                                            </div>
                                            <div class="btn-reload"
                                                onclick="window.location.href = window.location.origin + window.location.pathname;">
                                                <img src="{{ asset('ic-reset.svg') }}" height="20" alt="">
                                            </div>
                                        </div>
                                    </div>
                                    @php
                                    $currentYear = request('year', date('Y')); // Default to current year if not passed
                                    $currentMonth = request('month', date('m')); // Default to current month if not passed
                                @endphp
                                
                                <div class="col-sm-12 col-md-2">
                                    <div class="form-group">
                                        {{-- <label for="selectStatus">Filter By Status</label> --}}
                                        <select class="form-control" id="selectStatus" onchange="updateFilters()">
                                            <option value="">--Filter By Status--</option>
                                            <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>Active</option>
                                            <option value="0" {{ request('status') == '0' ? 'selected' : '' }}>Inactive</option>
                                        </select>
                                    </div>
                                </div>
                                
                                <!-- Month Filter -->
                                <div class="col-sm-12 col-md-2">
                                    <div class="form-group">
                                        {{-- <label for="selectMonth">Filter By Month</label> --}}
                                        <select class="form-control" id="selectMonth" onchange="updateFilters()">
                                            <option value="">--Filter By Month--</option>
                                            @for ($m = 1; $m <= 12; $m++)
                                                <option value="{{ $m }}" {{ $currentMonth == $m ? 'selected' : '' }}>
                                                    {{ date('F', mktime(0, 0, 0, $m, 1)) }}
                                                </option>
                                            @endfor
                                        </select>
                                    </div>
                                </div>
                                
                                <!-- Year Filter -->
                                <div class="col-sm-12 col-md-2">
                                    <div class="form-group">
                                        {{-- <label for="selectYear">Filter By Year</label> --}}
                                        <select class="form-control" id="selectYear" onchange="updateFilters()">
                                            <option value="">--Filter By Year--</option>
                                            @for ($y = date('Y'); $y >= date('Y') - 5; $y--)
                                                <option value="{{ $y }}" {{ $currentYear == $y ? 'selected' : '' }}>
                                                    {{ $y }}
                                                </option>
                                            @endfor
                                        </select>
                                    </div>
                                </div>
                                
                                    <div class="col-sm-12 col-md-2">
                                        <div class="form-group">
                                            <a class="btnChangePassword"  href="{{ route('customer.reports') }}?export=true&month={{ request('month', '') }}&year={{ request('year', '') }}"><i
                                                    class="ri-download-line"></i>
                                                &nbsp; Report</a>
                                        </div>
                                    </div>

                                </div>
                                <div class="table-responsive">
                                <table id="user-list-table" class="table table-striped table-hover table-borderless mt-0"
                                        role="grid" aria-describedby="user-list-page-info">
                                        <thead>
                                            <tr>
                                                <th> Name</th>
                                                <th>Email</th>
                                                <th>Phone No.</th>
                                                <th>Full Adress</th>
                                                <th>City</th>
                                                <th>Status</th>
                                                <th>Total Services</th>
                                                <th>Total Working Hours</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($users as $item)
                                                <tr>
                                                    <td class="d-flex align-items-center"><img
                                                            class="avatar-30 rounded mr-2"
                                                            src="{{ $item->image ? asset("uploads/images/$item->image") : asset('avatar-1.png') }}"
                                                            alt="profile"> {{ $item->name }}</td>

                                                    <td>{{ $item->email }}</td>
                                                    {{-- <td>{{ $item->designation ?? 'N/A' }}</td> --}}
                                                    <td>{{ $item->phone ?? 'N/A' }}</td>
                                                    <td>{{ $item->full_address ?? 'N/A' }}</td>
                                                    <td>{{ $item->city ?? 'N/A' }}</td>
                                                    <td><span
                                                            class="badge dark-icon-light {{ $item->status ? 'iq-bg-primary' : 'bg-danger' }}">{{ $item->status ? 'Active' : 'Inactive' }}</span>
                                                    </td>
                                                    <td>{{ $item->services_count }}
                                                    @if($item->services_count > 0)
                                                        <a href="javascript:void(0);" 
                                                        data-user-id="{{ $item->id }}"
                                                        class="view-service-details" 
                                                        title="View Details">
                                                            <i class="fa fa-info-circle text-info ms-2"></i>
                                                        </a>
                                                    @endif
                                                    </td>
                                                    <td>
                                                        {{ $item->working_hours }} hrs
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
                                            @if ($users->onFirstPage())
                                                <li class="page-item disabled">
                                                    <a class="page-link" href="#" tabindex="-1"
                                                        aria-disabled="true">Previous</a>
                                                </li>
                                            @else
                                                <li class="page-item">
                                                    <a class="page-link"
                                                        href="{{ $users->previousPageUrl() }}">Previous</a>
                                                </li>
                                            @endif

                                            @foreach ($users->links()->elements as $element)
                                                @if (is_string($element))
                                                    <li class="page-item disabled"><a
                                                            class="page-link">{{ $element }}</a></li>
                                                @endif

                                                @if (is_array($element))
                                                    @foreach ($element as $page => $url)
                                                        @if ($page == $users->currentPage())
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

                                            @if ($users->hasMorePages())
                                                <li class="page-item">
                                                    <a class="page-link" href="{{ $users->nextPageUrl() }}">Next</a>
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
    <!-- Service Details Modal -->
<div class="modal fade" id="serviceDetailsModal" tabindex="-1" aria-labelledby="serviceDetailsLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="serviceDetailsLabel">Service Details</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div id="service-details-content">
          <!-- Details will be populated here -->
          <p class="text-muted">Loading...</p>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Modal -->
<div class="modal fade" id="weeklyHoursModal" tabindex="-1" aria-labelledby="weeklyHoursModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="weeklyHoursModalLabel">Weekly Working Hours</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" id="weeklyHoursContent">
        Loading...
      </div>
    </div>
  </div>
</div>
@endsection
@push('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/3.3.4/jquery.inputmask.bundle.min.js"></script>

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

        function showData(ele) {
            $("#edit_form").attr("action", ele.getAttribute("data-url"));

            $("#email").val(ele.getAttribute("data-email"));

            $("#designation").val(ele.getAttribute("data-designation"));
            $("#phone").val(ele.getAttribute("data-phone"));

            $("#status").val(ele.getAttribute("data-status"));
            $("#name").val(ele.getAttribute("data-name"));
            $("#price_per_mile").val(ele.getAttribute("data-price_per_mile"));
            initializeDropzone("editDropzone", "{{ route('image-upload') }}", ele.getAttribute("data-image"), true)

        }

        function deletePublic(ele) {
            var title = ' you want to delete this category ?';
            Swal.fire({
                title: '',
                text: title,
                iconHtml: '<img src="{{ asset('assets/images/question.png') }}" height="25px">',
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

        // function changeStatus(val) {
        //     var currentUrl = new URL(window.location.href);
        //     // Add or update the 'run_id' parameter
        //     currentUrl.searchParams.set('status', val);
        //     if (val == "") {
        //         currentUrl.searchParams.delete('status');

        //     }
        //     // Reload the page with the new URL
        //     window.location.href = currentUrl.toString();

        // }

        function updateFilters() {
            let url = new URL(window.location.href);
            let params = new URLSearchParams(url.search);

            // Get selected filter values
            let status = document.getElementById("selectStatus").value;
            let month = document.getElementById("selectMonth").value;
            let year = document.getElementById("selectYear").value;

            // Update query parameters
            if (status) {
                params.set("status", status);
            } else {
                params.delete("status");
            }

            if (month) {
                params.set("month", month);
            } else {
                params.delete("month");
            }

            if (year) {
                params.set("year", year);
            } else {
                params.delete("year");
            }

            // Reload page with updated URL
            window.location.href = url.pathname + "?" + params.toString();
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
    $(document).on('click', '.view-service-details', function () {
        const userId = $(this).data('user-id');
        const month = $('#selectMonth').val();  // Get selected month
        const year = $('#selectYear').val();

        $('#service-details-content').html('<p class="text-muted">Loading...</p>');
        $('#serviceDetailsModal').modal('show');

        $.ajax({
            url: '{{ route("customer.service.details") }}',
            type: 'GET',
            data: { user_id: userId,
                month: month,
                year: year },
            success: function (response) {
                $('#service-details-content').html(response.html);
            },
            error: function () {
                $('#service-details-content').html('<p class="text-danger">Failed to load details.</p>');
            }
        });
    });
</script>

@endpush
