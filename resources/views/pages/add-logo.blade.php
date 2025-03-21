@extends('layouts.app')
@section('content')
    <!-- Page Content  -->
    <div id="content-page" class="content-page">
        <div class="container-fluid">
        <div class="">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-body">
                        <div class="Sendreply-modal-form">
                            <h4 class="mb-3 dark-signin font-weight-bold">Upload Logo</h4>
                            <form action="{{ route('logo.store') }}" method="POST" id="logoUploadForm" class="dropzone" enctype="multipart/form-data">
                                @csrf
                                <div class="dz-message">
                                    <i class="ri-upload-cloud-line" style="font-size: 2rem;"></i>
                                    <p>Drag & Drop or Click to Upload Logo</p>
                                </div>
                            </form>
                            
                            <div class="preview-container mt-3 text-center">
                                @php
                                    $logo = \App\Models\Logo::first();
                                    $logoPath = $logo && file_exists(public_path('uploads/logo/' . $logo->name)) ? asset('uploads/logo/' . $logo->name) : '';
                                @endphp

                                @if($logoPath)
                                    <div class="position-relative d-inline-block">
                                        <img id="logoPreview" src="{{ $logoPath }}" 
                                            class="img-thumbnail" 
                                            style="max-width: 150px;">
                                        
                                        <!-- Delete Icon (only for saved logo) -->
                                        <i class="ri-close-circle-fill text-danger position-absolute" 
                                            id="deleteLogo" 
                                            style="top: -10px; right: -10px; font-size: 20px; cursor: pointer;" 
                                            title="Delete Logo"></i>
                                    </div>
                                @else
                                    <img id="logoPreview" src="" class="img-thumbnail d-none" style="max-width: 150px;">
                                @endif
                            </div>

                            <div class="form-group mt-3 text-right">
                                <button class="btn btn-danger" data-bs-dismiss="modal" type="button" onclick="$('#Sendreply').modal('hide')" aria-label="Close">Cancel</button>
                                <button class="btn btn-success" id="submitLogo">Upload</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div>
    </div>
 <!-- Include Dropzone.js, SweetAlert2, and Toastr -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/min/dropzone.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

<script>
   Dropzone.autoDiscover = false;

var myDropzone = new Dropzone("#logoUploadForm", {
    paramName: "file",
    maxFiles: 1,
    acceptedFiles: "image/*",
    autoProcessQueue: false, // Prevent auto upload
    addRemoveLinks: true,
    dictDefaultMessage: "Drop an image here or click to upload",
    init: function () {
        var dropzoneInstance = this;

        // When upload button is clicked, submit the form
        document.getElementById("submitLogo").addEventListener("click", function (e) {
            e.preventDefault();
            if (dropzoneInstance.getQueuedFiles().length > 0) {
                dropzoneInstance.processQueue();
            } else {
                toastr.error("Please select a file before uploading.");
            }
        });

        this.on("success", function (file, response) {
            if (response.file_path) {
                $('#logoPreview').attr('src', response.file_path).removeClass('d-none');
                $("#deleteLogo").show(); // Show delete icon
                toastr.success(response.message || "Logo uploaded successfully!");
                window.location.reload();
            } else {
                toastr.error("Upload failed. Please try again.");
            }
        });

        this.on("error", function (file, errorMessage) {
            toastr.error(errorMessage);
        });

        this.on("maxfilesexceeded", function (file) {
            this.removeAllFiles();
            this.addFile(file);
        });
    }
});

// Handle Logo Deletion
$("#deleteLogo").click(function () {
    $.ajax({
        url: "{{ route('logo.delete') }}",
        type: "POST",
        data: {
            _token: "{{ csrf_token() }}"
        },
        success: function (response) {
            if (response.success) {
                $("#logoPreview").attr("src", "").addClass("d-none");
                $("#deleteLogo").hide();
                toastr.success("Logo deleted successfully!");
                window.location.reload();
            } else {
                toastr.error("Failed to delete logo.");
            }
        },
        error: function () {
            toastr.error("Something went wrong.");
        }
    });
});
</script>
@endsection
