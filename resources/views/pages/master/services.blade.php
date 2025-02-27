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
                                                                                                                 <h4 class="card-title">Service List</h4>
                                                                                                              </div>
                                                                                                           </div> -->
                        <div class="iq-card-body">
                            <div class="search-filter-info">
                                <div class="row justify-content-between">
                                    <div class="col-sm-12 col-md-5">
                                        <div class="users-filter-search">
                                            <div id="user_list_datatable_info" class="dataTables_filter filter-search-info">
                                                <form class="position-relative">
                                                    <div class="form-group mb-0">
                                                        <input type="search" class="form-control" name="search"
                                                        placeholder="Search by  name..." aria-controls="user-list-table" value="{{$search}}">
                                                    </div>
                                                </form>
                                            </div>
                                            <div class="btn-reload" onclick="window.location.href = window.location.origin + window.location.pathname;">
                                                <img src="{{ asset('reset.png') }}" height="20" alt="">
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
                                            <a class="addbtn"
                                                data-toggle="modal" data-target=".CreateModel" href="#">Create</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="table-responsive">
                                    <table id="user-list-table" class="table table-striped table-borderless mt-0" role="grid"
                                        aria-describedby="user-list-page-info">
                                        <thead>
                                            <tr>
                                                <th> Name</th>
                                              
                                                <th>Status</th>
                                                <th>Action &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($services as $item)
                                                <tr>
                                                    <td class="d-flex align-items-center"> {{ $item->name }}</td>

                                              
                                                    <td><span
                                                            class="badge dark-icon-light iq-bg-primary">{{ $item->status ? 'Active' : 'Inactive' }}</span>
                                                    </td>

                                                    <td>
                                                        <div class="flex align-items-center list-user-action">
                                                            <a class="btnedit" data-toggle="modal"
                                                                data-name="{{ $item->name ?? '' }}"
                                                                data-status="{{ $item->status ?? '' }}"
                                                               
                                                                data-url="{{ route('master.services.update', $item->id) }}"
                                                                onclick="showData(this)" data-target="#EditModel"
                                                                style="cursor: pointer"><i class="ri-pencil-fill"></i></a>
                                                            {{-- delete  button --}}
                                                            <a class="btndelete" data-id="{{ $item->id }}"
                                                                style="cursor: pointer"
                                                                data-url="{{ route('master.services.destroy', $item->id) }}"
                                                                onclick="deletePublic(this)"><i
                                                                    class="ri-delete-bin-7-line"></i></a>
                                                          


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
                                            @if ($services->onFirstPage())
                                                <li class="page-item disabled">
                                                    <a class="page-link" href="#" tabindex="-1"
                                                        aria-disabled="true">Previous</a>
                                                </li>
                                            @else
                                                <li class="page-item">
                                                    <a class="page-link"
                                                        href="{{ $services->previousPageUrl() }}">Previous</a>
                                                </li>
                                            @endif

                                            @foreach ($services->links()->elements as $element)
                                                @if (is_string($element))
                                                    <li class="page-item disabled"><a
                                                            class="page-link">{{ $element }}</a></li>
                                                @endif

                                                @if (is_array($element))
                                                    @foreach ($element as $page => $url)
                                                        @if ($page == $services->currentPage())
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

                                            @if ($services->hasMorePages())
                                                <li class="page-item">
                                                    <a class="page-link" href="{{ $services->nextPageUrl() }}">Next</a>
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
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <form action="{{ route('master.services.store') }}" method="post" id="create_form"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <h5 class="modal-title">Create Service</h5>
                        
                    

                        <div class="modal-form-item">
                            <div class="form-group">
                                <label for="name">Name*</label>
                                <input type="text" name="name" class="form-control" required>
                            </div>
                           
                            <div class="form-group">
                                <label for="name">Status</label>
                                <select class="form-control" name="status">
                                    <option value="1">Active </option>
                                    <option value="0">Inactive </option>
                                </select>
                            </div>
                        </div>
                        <div class="modal-action">
                            <button type="submit" class="btnSubmit">Submit</button>

                            <button type="button" class="btnClose" data-dismiss="modal">Close</button>
                            <!-- <button type="button" class="btn btn-success">Approve</button> -->
                        </div>
                    </div>
                    
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade EditModel" tabindex="-1" role="dialog" aria-hidden="true" id="EditModel">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <form action="{{ route('master.services.store') }}" method="post" id="edit_form" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <h5 class="modal-title">Edit Service</h5>
                        
                    

                        <div class="modal-form-item">
                            <div class="form-group">
                                <label for="name">Name*</label>
                                <input type="text" name="name" id="name" class="form-control" required>
                            </div>
                          
                            <div class="form-group">
                                <label for="name">Status</label>
                                <select class="form-control" name="status" id="status">

                                    <option value="1">Active </option>
                                    <option value="0">Inactive </option>

                                </select>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btnSubmit">Submit</button>

                            <button type="button" class="btnClose" data-dismiss="modal">Close</button>
                            <!-- <button type="button" class="btn btn-success">Approve</button> -->
                        </div>
                    </div>
                    
                </form>
            </div>
        </div>
    </div>
@endsection
@push('js')
    <script>
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

          
            $("#status").val(ele.getAttribute("data-status"));
            $("#name").val(ele.getAttribute("data-name"));
       
           

        }

        function deletePublic(ele) {
            var title = ' you want to delete this service ?';
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
