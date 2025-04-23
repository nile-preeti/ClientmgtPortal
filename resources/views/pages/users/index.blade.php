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

                                        <div class="users-filter-search">
                                            <div id="user_list_datatable_info" class="dataTables_filter filter-search-info">
                                            <form class="position-relative d-flex" style="gap: 5px">
                                                    <div class="form-group mb-0" style="width: 100%">
                                                        <input type="search" class="form-control" name="search"
                                                            placeholder="Search..." aria-controls="user-list-table"
                                                            value="{{ $search }}">
                                                    </div>
                                                    <button type="submit" class="" style="border: none; background: none; cursor: pointer;">
                                                                <i class="fa fa-search" style="color:#3d3e3e;font-size:16px;border: 1px solid #3d3e3e;box-shadow:0px 8px 13px 0px rgba(35, 53, 111, 0.12);padding: 10px 0px;text-align: center;border-radius: 5px;width: 45px;height:44px;  justify-content: center; display: flex; align-items: center;"></i>
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
                                            <a class="addbtn" href="{{ route('userss.create') }}"><i class="ri-add-circle-line"></i> Add</a>
                                        </div>
                                    </div>
                                </div>
                                
                            </div>
                            
                        </div>
                        
                    </div>
                    @forelse ($users as $item)
                    <div class="iq-card">
                        <div class="iq-card-body">
                        
                            <div class="info-card">
                          
                                <div class="row align-items-center">
                                
                                    <div class="col-md-3">
                                        <div class="d-flex align-items-center">
                                            <div class="info-image">
                                                <img src="{{ $item->image ? asset("uploads/images/$item->image") : asset('avatar-1.png') }}" alt="image" class="img-fluid">
                                            </div>
                                            <div class="name-info ml-3">
                                                <p>{{ $item->name }}</p>
                                                <h6 class="mt-2"></h6>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3 overflow-hidden">
                                        <h6>Email Id</h6>
                                        <p class="mt-2">{{ $item->email }}</p>
                                    </div>
                                    <div class="col-md-2">
                                        <h6>Phone No.</h6>
                                        <p class="mt-2">{{ $item->phone ?? 'N/A' }}</p>
                                    </div>
                                    <div class="col-md-1 account-status">
                                        <h6>Status</h6>
                                        <p class="mt-2"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" class="bi bi-circle-fill me-2" viewBox="0 0 16 16"><circle cx="6" cy="6" r="6"></circle></svg>{{ $item->status ? 'Active' : 'Inactive' }}</p>
                                    </div>
                                    <div class="col-md-3 account-status">
                                        <h6>Action</h6>
                                        <div class="mt-2 flex align-items-center list-user-action">
                                            <a href="{{ route('userss.edit', $item->id) }}" class="btnedit"><i class="ri-pencil-fill"></i></a>

                                            <a class="btndelete" data-id="{{ $item->id }}" style="cursor: pointer" data-url="{{ route('userss.destroy', $item->id) }}" onclick="deletePublic(this)"><i class="ri-delete-bin-7-line"></i></a>

                                            <a class="btnview" data-id="{{ $item->id }}" style="cursor: pointer" href="{{ route('user.job_schedule', $item->id) }}"><i class="ri-briefcase-line"></i></a>
                                            <a class="btnview" data-id="{{ $item->id }}" style="cursor: pointer" href="{{ route('userAttendance', $item->id) }}"><i class="ri-calendar-event-fill"></i></a>
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
    </div> <!-- Large Approved modal -->

    <div class="modal fade CreateModel" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <form action="{{ route('userss.store') }}" method="post" id="create_form"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Create User</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="modal-form-item">
                            <div class="form-group">
                                <label for="name">Name*</label>
                                <input type="text" name="name" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="name">Email*</label>
                                <input type="email" name="email" class="form-control" required>
                            </div>

                            <div class="form-group">
                                <label for="name">Designation*</label>
                                <input type="text" name="designation" class="form-control" required>
                            </div>

                            <div class="form-group">
                                <label for="name">Phone Number*</label>
                                <input type="number" placeholder="(678) 878-9909"
                                    data-inputmask="'mask': '(999) 999-9999'" required name="phone"
                                    class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="name">Password*</label>
                                <input type="text" name="password" class="form-control" required>
                            </div>

                            {{-- <div class="form-group">
                                <label for="name">Image</label>
                            </div> --}}
                            <input type="hidden" name="image" id="create_image" class="form-control">
                            <div class="form-group">
                                <div class="dropzone" id="myDropzone"></div>
                            </div>
                            <div class="form-group">
                                <label for="name">Status</label>
                                <select class="form-control" name="status">
                                    <option value="1">Active </option>
                                    <option value="0">Inactive </option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Submit</button>

                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <!-- <button type="button" class="btn btn-success">Approve</button> -->
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade EditModel" tabindex="-1" role="dialog" aria-hidden="true" id="EditModel">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <form action="{{ route('userss.store') }}" method="post" id="edit_form" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                        <h5 class="modal-title">Edit User</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">

                        <div class="container-fluid">
                            <div class="form-group">
                                <label for="name">Name*</label>
                                <input type="text" name="name" id="name" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="name">Email*</label>
                                <input type="email" name="email" id="email" class="form-control" required>
                            </div>

                            <div class="form-group">
                                <label for="name">Designation*</label>
                                <input type="text" name="designation" id="designation" class="form-control" required>
                            </div>

                            <div class="form-group">
                                <label for="name">Phone No.*</label>
                                <input type="number" name="phone" placeholder="(678) 878-9909"
                                    data-inputmask="'mask': '(999) 999-9999'" id="phone" class="form-control"
                                    required>
                            </div>
                            <div class="form-group">
                                <div>
                                    <button type="button" class="btn btn-primary my-1"
                                        onclick="$('#password').toggle()">Reset
                                        Password</button>
                                </div>
                                <div>
                                    <input type="text" name="password" class="form-control" required id="password"
                                        style="display: none">

                                </div>
                            </div>
                            <input type="hidden" name="image" id="edit_image" class="form-control">

                            <div class="form-group">
                                <div class="dropzone" id="editDropzone"></div>
                            </div>
                            <div class="form-group">
                                <label for="name">Status</label>
                                <select class="form-control" name="status" id="status">

                                    <option value="1">Active </option>
                                    <option value="0">Inactive </option>

                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Submit</button>

                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <!-- <button type="button" class="btn btn-success">Approve</button> -->
                    </div>
                </form>
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
            var title = 'Are you sure, you want to delete this Employee ?';
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
@endpush
