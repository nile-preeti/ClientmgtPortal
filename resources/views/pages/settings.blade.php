@extends('layouts.app')
@section('content')
    <!-- Page Content  -->
    <div id="content-page" class="content-page">
        <div class="container-fluid">


            <div class="iq-card">
                <div class="card-header">

                </div>
                <div class="card-body">
                    <div class="avot-card-table">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Name </th>
                                    <th>Value</th>

                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>





                                <tr>
                                    <td>Admin Fee</td>

                                    <td>{{ config('constant.ADMIN_FEE') }} %
                                    </td>

                                    <td> <button class="btn btn-primary" data-key="{{ 'ADMIN_FEE' }}" data-options="#admin_fee"
                                            data-value="{{ config('constant.ADMIN_FEE') }}"
                                            onclick="openRely(this)"><i class="ri-pencil-fill"></i> Edit</button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
            <div class="modal avot-modal fade" id="Sendreply" tabindex="-1" aria-labelledby="exampleModalLabel"
                aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-body">
                            <div class="Sendreply-modal-form">
                                <h4 class="mb-3 dark-signin font-weight-bold">Settings</h4>
                                <form action="{{ route('settings.store') }}" method="post" id="signin_form">
                                    @csrf

                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="">Key</label>
                                                <input type="text" name="key" class="form-control" id="key"
                                                    readonly>
                                            </div>
                                            <div class="form-group">
                                                <label for="">Value</label>
                                                <div id="date_format" class="date_format">
                                                    <select name="value"class="form-control">
                                                        <option value="m-d-Y">m-d-Y</option>
                                                        <option value="d-m-Y">d-m-Y</option>
                                                    </select>
                                                </div>
                                                <div id="currency" class="date_format">
                                                    <select name="value"class="form-control">
                                                        <option value="$">$</option>
                                                        <option value="₹">₹</option>
                                                    </select>
                                                </div>
                                                <div id="admin_fee" class="date_format">
                                                    <input type="text" value="{{ config('constant.ADMIN_FEE') }}"
                                                        name="value" class="form-control">
                                                </div>
                                            </div>
                                        </div>



                                        <div class="col-md-12">
                                            <div class="form-group float-right">
                                                <button class="btn btn-danger" data-bs-dismiss="modal" type="button" onclick="$('#Sendreply').modal('hide')"
                                                    aria-label="Close">Cancel</button>
                                                <button class="btn btn-success">Update</button>
                                            </div>
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
    <script>
        function openRely(ele) {
            $("#Sendreply").modal("show");
            $("#key").val(ele.getAttribute("data-key"));
            $("#value").val(ele.getAttribute("data-value"));
            $("#currency").hide()
            $("#date_format").hide()

            $(ele.getAttribute("data-options")).show();




        }
        $(document).ready(function() {
            $('#signin_form').validate({
                rules: {
                    reply: {
                        required: true,
                        maxlength: 191,

                    },

                },
                errorElement: "span",
                errorPlacement: function(error, element) {
                    error.addClass("text-danger ml-4");
                    element.closest(".form-group").append(error);
                },
                highlight: function(element, errorClass, validClass) {
                    $('.please-wait').click();
                    $(element).addClass("text-danger ml-4");
                },
                unhighlight: function(element, errorClass, validClass) {
                    $(element).removeClass("text-danger ml-4");
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
                                var form = $("#signin_form");
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
