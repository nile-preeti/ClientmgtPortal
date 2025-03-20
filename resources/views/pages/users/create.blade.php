@extends('layouts.app')
@section('content')
    <!-- Page Content  -->
    <div id="content-page" class="content-page">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="client-form">
                        <form action="{{ isset($user) ? route('users.update', $user) : route('users.store') }}" method="post"
                            id="create_form" enctype="multipart/form-data">
                            @csrf
                            @if (isset($user))
                                @method('PUT')
                            @endif

                            <div class="client-form-card">
                                <h2>Create Employee</h2>
                                <div class="row">

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="name">Name*</label>
                                            <input type="text" name="name"
                                                @if (isset($user)) value="{{ $user->name }}" @endif
                                                class="form-control" required>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="name">Email*</label>
                                            <input type="email" name="email"
                                                @if (isset($user)) value="{{ $user->email }}" @endif
                                                class="form-control" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="name">Emp ID*</label>
                                            <input type="number" min="0" name="emp_id"
                                                @if (isset($user)) value="{{ $user->emp_id }}" @endif
                                                class="form-control" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="name">Phone Number*</label>
                                            <input type="text" placeholder="(678) 878-9909"
                                                @if (isset($user)) value="{{ $user->phone }}" @endif
                                                data-inputmask="'mask': '(999) 999-9999'" required name="phone"
                                                class="form-control">
                                        </div>
                                    </div>
                                    

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="name">Status</label>
                                            <select class="form-control" name="status">
                                                <option value="1" @selected(isset($user) && $user->status == 1)>Active </option>
                                                <option value="0" @selected(isset($user) && $user->status == 0)>Inactive </option>
                                            </select>
                                        </div>
                                    </div>

                                    @if (isset($user))
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                 <label for="name">&nbsp;</label>
                                                <div>
                                                    <button type="button" class="btnReset"
                                                        onclick="$('#password').toggle()">Reset
                                                        Password</button>
                                                </div>
                                                <div>
                                                    <input type="text" name="password" class="form-control" required
                                                        id="password" style="display: none">

                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="name">Password*</label>
                                                <input type="text" name="password" class="form-control" required>
                                            </div>
                                        </div>
                                    @endif

                                    <div class="col-md-4">
                                        <label for="">Profile Image</label>
                                        <input type="hidden" name="image" id="create_image" class="form-control">
                                        <div class="form-group">
                                            <div class="dropzone form-control" id="myDropzone"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="client-form-item">
    <div class="client-form-item-head">
        <h4>Services</h4>
        <button type="button" class="btnAdd" id="addService"><i class="ri-add-circle-line"></i> Add</button>
    </div>

    @if (isset($user))
        @foreach ($user->services as $item)
            <div class="client-form-item-body" id="old_service_{{ $item->id }}">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group service-box">
                            <input type="hidden" name="service_user_id[]" value="{{ $item->id }}">
                            <select name="old_services[]" class="form-control service-select" data-id="{{ $item->id }}">
                                <option value="">Select Service</option>
                                @foreach ($services as $subitem)
                                    <option value="{{ $subitem->id }}" 
                                        data-subcategory="{{ $subitem->sub_category }}" 
                                        @selected($item->service_id == $subitem->id)>
                                        {{ $subitem->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group service-box">
                            <input type="text" name="old_sub_category[]" 
                                class="form-control sub-category" 
                                value="{{ $item->sub_category ?? ($services->where('id', $item->service_id)->first()->sub_category ?? 'null') }}" 
                                readonly>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group service-box">
                            <input type="number" name="old_price_per_hour[]" 
                                value="{{ $item->price_per_hour }}" 
                                class="form-control" placeholder="Price per hour">
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="form-group">
                            <button type="button" class="btnremove" 
                                onclick="$('#old_service_{{ $item->id }}').remove()"> <i class="ri-delete-bin-line"></i> Remove</button>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    @endif

    <div class="client-form-serviceContainer">
        <div class="" id="serviceContainer"></div>
    </div>
</div>


                            <div class=" mb-2"> <button type="submit" class="btnSubmit">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/3.3.4/jquery.inputmask.bundle.min.js"></script>
    <script>
        $(document).ready(function () {
    $(".service-select").on("change", function () {
        let subCategory = $(this).find("option:selected").data("subcategory");
        $(this).closest(".row").find(".sub-category").val(subCategory);
    });
});
    </script>
    <script>
        $(":input").inputmask();
        document.getElementById("addService").addEventListener("click", function () {
    let container = document.getElementById("serviceContainer");
    let services = @json($services);
    let newRow = document.createElement("div");
    newRow.classList.add("row", "mt-2");

    var innerHTML = `
        <div class="col-md-4">
            <div class="form-group service-box">
                <select name="services[]" class="form-control service-select">
                    <option value="">Select Service</option>`;
    services.map(item => {
        innerHTML += `<option value="${item.id}" data-subcategory="${item.sub_category}">${item.name}</option>`;
    });

    innerHTML += `
                </select>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group service-box">
                <input type="text" name="sub_category[]" class="form-control sub-category" placeholder="Sub Category" readonly>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group service-box">
                <input type="number" name="price_per_hour[]" class="form-control" placeholder="Price per hour">
            </div>
        </div>
        <div class="col-md-2">
            <div class="form-group">
                <button type="button" class="btnremove removeService"> <i class="ri-delete-bin-line"></i> Remove</button>
            </div>
        </div>
    `;

    newRow.innerHTML = innerHTML;
    container.appendChild(newRow);

    // Add event listener to remove button
    newRow.querySelector(".removeService").addEventListener("click", function () {
        newRow.remove();
    });

    // Add event listener to service dropdown to auto-fill sub-category
    newRow.querySelector(".service-select").addEventListener("change", function () {
        let subCategory = this.options[this.selectedIndex].getAttribute("data-subcategory") || "";
        newRow.querySelector(".sub-category").value = subCategory;
    });
});

    </script>
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
    @if (isset($user) && $user->image)
        <script>
            initializeDropzone("myDropzone", "{{ route('image-upload') }}", "{{ asset('uploads/images/' . $user->image) }}", )
        </script>
    @else
        <script>
            initializeDropzone("myDropzone", "{{ route('image-upload') }}")
        </script>
    @endif
@endsection
