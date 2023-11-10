<?= $this->extend('template/admin_template'); ?>

<?= $this->section('content'); ?>
<div class="content-header">
    <div class="container-fulid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Profile Management</h1>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fulid">
        <div class="row mb-2">
            <div class="col-sm-12">
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalID">
                    Add User Profile
                </button>
            </div>
        </div>
        <table id="dataTable" class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th hidden>ID</th>
                    <th>USER NAME</th>
                    <th>LAST NAME</th>
                    <th>FIRST NAME</th>
                    <th>MIDDLE NAME</th>
                    <th>EMAIL</th>
                    <th>BIRTH DATE</th>
                    <th>GENDER</th>
                    <th>ACTION</th>
                </tr>
            </thead>
        </table>

        <div class="modal fade" id="modalID" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content" id="modalContent">
                    <div class="modal-header">
                        <h5 class="modal-title">Author Details</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close" id="closeModal">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body">
                        <form class="needs-validation" novalidate>
                            <div class="card-body">
                                <input type="hidden" name="id" id="id">
                                <label class="badge-danger"> Make sure user is already registered before creating profile.</label>
                                <div class="form-group">
                                    <label>User</label>
                                    <select name="user_id" id="user_id" class="form-control select2" style="width: 100%;" required>
                                        <option value="">Select User</option>
                                        <?php foreach($users as $user) {
                                            echo "<option value='".$user['id']."'>".$user['username']."</option>";
                                        }
                                        ?>
                                    </select>
                                    <div class="valid-feedback">Looks Good!</div>
                                    <div class="invalid-feedback">Please select User.</div>
                                </div>
                                <div class="form-group">
                                    <label for="first_name">First Name</label>
                                    <input type="text" class="form-control" name="first_name" id="first_name" placeholder="Enter First Name" required>
                                    <div class="valid-feedback">Looks Good!</div>
                                    <div class="invalid-feedback">Please provide a valid First Name.</div>
                                </div>
                                <div class="form-group">
                                    <label for="last_name">Middle Name</label>
                                    <input type="text" class="form-control" name="middle_name" id="middle_name" placeholder="Enter Middle Name">
                                    <div class="valid-feedback">Looks Good!</div>
                                    <div class="invalid-feedback">Please provide a valid Middle Name.</div>
                                </div>
                                <div class="form-group">
                                    <label for="last_name">Last Name</label>
                                    <input type="text" class="form-control" name="last_name" id="last_name" placeholder="Enter Last Name" required>
                                    <div class="valid-feedback">Looks Good!</div>
                                    <div class="invalid-feedback">Please provide a valid Last Name.</div>
                                </div>
                                <div class="form-group">
                                    <label for="birthdate">Birth Date</label>
                                    <div class="input-group date" id="birthdatepicker" data-target-input="nearest">
                                        <input type="text" class="form-control datetimepicker-input" data-target="#birthdatepicker" name="birth_date" id="birth_date" required>
                                        <div class="input-group-append" data-target="#birthdatepicker" data-toggle="datetimepicker">
                                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Gender</label>
                                    <select name="gender_id" id="gender_id" class="form-control select2" style="width: 100%;" required>
                                        <option value="">Select Gender</option>
                                        <option value="1">Male</option>
                                        <option value="2">Female</option>
                                    </select>
                                    <div class="valid-feedback">Looks Good!</div>
                                    <div class="invalid-feedback">Please select Gender.</div>
                                </div>
                            </div>
                            <!-- /.card-body -->

                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>
</section>
<?= $this->endSection(); ?>

<?= $this->section('pagescripts'); ?>
<script>
    $(function() {
        $('#birthdatepicker').datetimepicker({
            format: 'YYYY-MM-DD'
        });

        $("form").submit(function(event) {
            event.preventDefault();
            let formdata = $(this).serializeArray().reduce(function(obj, item) {
                obj[item.name] = item.value;
                return obj;
            }, {});

            let jsondata = JSON.stringify(formdata);

            if (this.checkValidity()) {
                if (!formdata.id) {
                    //save
                    $.ajax({
                        method: "POST",
                        url: "<?= base_url('profiles'); ?>",
                        data: jsondata,
                        success: function(result, textStatus, jqXHR) {
                            $(document).Toasts("create", {
                                class: "bg-success",
                                title: "Success",
                                body: "Profile created successfuly.",
                                autohide: true,
                                delay: 3000
                            })
                            table.ajax.reload();
                            clearform();
                            $("#modalID").modal("hide");
                        },
                        error: function(result, textStatus, jqXHR) {
                            $(document).Toasts("create", {
                                class: "bg-danger",
                                title: "Error",
                                body: "Record Was Not Created.",
                                autohide: true,
                                delay: 3000
                            })
                        }

                    });
                } else {
                    //update
                    $.ajax({
                        method: "PUT",
                        url: "<?= base_url() ?>profiles/" + formdata.id,
                        data: jsondata,
                        success: function(result, textStatus, jqXHR) {
                            $(document).Toasts("create", {
                                class: "bg-success",
                                title: "Success",
                                body: "Record updated successfuly.",
                                autohide: true,
                                delay: 3000
                            })
                            table.ajax.reload();
                            clearform();
                            $("#modalID").modal("hide");
                        },
                        error: function(result, textStatus, jqXHR) {
                            $(document).Toasts("create", {
                                class: "bg-danger",
                                title: "Error",
                                body: "Record was not updated.",
                                autohide: true,
                                delay: 3000
                            })
                        }
                    });

                }
            }


        });
    });

    var table = $("#dataTable").DataTable({
        responsive: true,
        processing: true,
        serverSide: true,
        ajax: {
            url: "<?= base_url('profiles/list'); ?>",
            type: "GET"
        },
        columns: [{
                data: "profile_id",
                visible: false,
                searchable: false,
            },
            {
                data: "user_name",
            },
            {
                data: "last_name",
            },
            {
                data: "first_name",
            },
            {
                data: "middle_name",
            },
            {
                data: "email",
            },
            {
                data: "birth_date",
            },
            {
                data: "gender",
            },
            {
                data: null,
                defaultContent: `<td>
                <button class="btn btn-primary" id="editRow"><i class="fas fa-edit"></i></button>
                </td>`,
            }
        ],
        paging: true,
        lengthChange: true,
        searching: true,
        ordering: true,
        info: true,
        autoWidth: true,
        lengthMenu: [5, 10, 25, 50]
    });

    $(document).on("click", "#editRow", function() {
        let row = $(this).parents("tr")[0];
        let id = table.row(row).data().profile_id;
        $.ajax({
            method: "GET",
            url: "<?= base_url() ?>profiles/" + id,
            success: function(result, textStatus, jqXHR) {
                $("#modalID").modal("show");
                $("#id").val(result.profile_id);
                $("#user_id").val(result.user_id).trigger("change").prop('readonly',true);
                $("#last_name").val(result.last_name);
                $("#middle_name").val(result.middle_name);
                $("#first_name").val(result.first_name);
                $("#birth_date").val(result.birth_date);
                $("#gender_id").val(result.gender_id).trigger("change");
            },
            error: function(result, textStatus, jqXHR) {
                $(document).Toasts("create", {
                    class: "bg-danger",
                    title: "Error",
                    body: "Record Was Not Found.",
                    autohide: true,
                    delay: 3000
                });
            }
        })
    });

    // $(document).on("click", "#deleteRow", function() {
    //     let row = $(this).parents("tr")[0];
    //     let id = table.row(row).data().profile_id;
    //     if (confirm("Are you sure you want to delete this record?")) {
    //         $.ajax({
    //             method: "DELETE",
    //             url: "<?= base_url() ?>profiles/" + id,
    //             success: function(result, textStatus, jqXHR) {
    //                 $(document).Toasts("create", {
    //                     class: "bg-success",
    //                     title: "Deleted",
    //                     body: "Record Was Deleted.",
    //                     autohide: true,
    //                     delay: 3000
    //                 });
    //                 table.ajax.reload();
    //             },
    //             error: function(result, textStatus, jqXHR) {
    //                 $(document).Toasts("create", {
    //                     class: "bg-danger",
    //                     title: "Error",
    //                     body: "Record Was Not Found.",
    //                     autohide: true,
    //                     delay: 3000
    //                 });
    //             }
    //         });
    //     }

    // });

    function clearform() {
        $("#id").val("");
        $("#user_id").val("").trigger("change");
        $("#last_name").val("");
        $("#middle_name").val("");
        $("#first_name").val("");
        $("#gender_id").val("").trigger("change");
        $("#birthdate").val("");
    }

    $(document).ready(function() {
        'use strict';

        let form = $(".needs-validation");

        form.each(function() {
            $(this).on('submit', function(event) {
                if (this.checkValidity() === false) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                $(this).addClass('was-validated');
            });
        });
    });
</script>
<?= $this->endSection(); ?>