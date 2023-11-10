<?= $this->extend('template/admin_template'); ?>

<?= $this->section('content'); ?>
<div class="content-header">
    <div class="container-fulid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">List of Offices</h1>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fulid">
        <div class="row mb-2">
            <div class="col-sm-12">
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalOffice">
                    Add Office
                </button>
            </div>
        </div>
        <table id="dataTableOffices" class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th hidden>ID</th>
                    <th>Name</th>
                    <th>Code</th>
                    <th>Description</th>
                    <th class="text-center">ACTION</th>
                </tr>
            </thead>
        </table>

        <div class="modal fade" id="modalOffice" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content" id="modalContent">
                    <div class="modal-header">
                        <h5 class="modal-title">Office Details</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close" id="closeModal">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body">
                        <form class="needs-validation" id="frmOffice" novalidate>
                            <div class="card-body">
                                <input type="hidden" name="o_id" id="o_id">
                                <div class="form-group">
                                    <label for="office_name">Office Name</label>
                                    <input type="text" class="form-control" name="office_name" id="office_name" placeholder="Enter Office Name" required>
                                    <div class="valid-feedback">Looks Good!</div>
                                    <div class="invalid-feedback">Please input Office Name.</div>
                                </div>
                                <div class="form-group">
                                    <label for="office_code">Office Code</label>
                                    <input type="text" class="form-control" name="office_code" id="office_code" placeholder="Enter Office Code" required>
                                    <div class="valid-feedback">Looks Good!</div>
                                    <div class="invalid-feedback">Please input Office Code.</div>
                                </div>
                                <div class="form-group">
                                    <label for="description">Description</label>
                                    <input type="text" class="form-control" name="description" id="description" placeholder="Enter Description" required>
                                    <div class="valid-feedback">Looks Good!</div>
                                    <div class="invalid-feedback">Please input Office Description.</div>
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
        $("#frmOffice").submit(function(event) {
            event.preventDefault();
            let formdata = $(this).serializeArray().reduce(function(obj, item) {
                obj[item.name] = item.value;
                return obj;
            }, {});

            let jsondata = JSON.stringify(formdata);

            if (this.checkValidity()) {
                if (!formdata.o_id) {
                    //save
                    $.ajax({
                        method: "POST",
                        url: "<?= base_url('office'); ?>",
                        data: jsondata,
                        success: function(result, textStatus, jqXHR) {
                            $(document).Toasts("create", {
                                class: "bg-success",
                                title: "Success",
                                body: "Office created successfuly.",
                                autohide: true,
                                delay: 3000
                            })
                            table.ajax.reload();
                            clearform();
                            $("#modalOffice").modal("hide");
                        },
                        error: function(result, textStatus, jqXHR) {
                            console.log(result);
                            $(document).Toasts("create", {
                                class: "bg-danger",
                                title: "Error",
                                body: "Failed to create the record.",
                                autohide: true,
                                delay: 3000
                            })
                        }

                    });
                } else {
                    //update
                    $.ajax({
                        method: "PUT",
                        url: "<?= base_url() ?>office/" + formdata.o_id,
                        data: jsondata,
                        success: function(result, textStatus, jqXHR) {
                            $(document).Toasts("create", {
                                class: "bg-success",
                                title: "Success",
                                body: "Office updated successfuly.",
                                autohide: true,
                                delay: 3000
                            })
                            table.ajax.reload();
                            clearform();
                            $("#modalOffice").modal("hide");
                        },
                        error: function(result, textStatus, jqXHR) {
                            $(document).Toasts("create", {
                                class: "bg-danger",
                                title: "Error",
                                body: "Failed to update the record.",
                                autohide: true,
                                delay: 3000
                            })
                        }
                    });

                }
            }


        });
    });

    var table = $("#dataTableOffices").DataTable({
        responsive: true,
        processing: true,
        serverSide: true,
        ajax: {
            url: "<?= base_url('office/list'); ?>",
            type: "GET"
        },
        columns: [{
                data: "office_id",
                visible: false,
                searchable: false,
            },
            {
                data: "office_name",
            },
            {
                data: "office_code",
            },
            {
                data: "description",
            },
            {
                data: null,
                "className": "text-center",
                defaultContent: '<td class="text-center"><button class="btn btn-primary" id="editRow"><i class="fas fa-edit"></i></button>&nbsp;<button class="btn btn-danger" id="deleteRow"><i class="fas fa-trash"></i></button></td>',
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
        let id = table.row(row).data().office_id;
        $.ajax({
            method: "GET",
            url: "<?= base_url() ?>office/" + id,
            success: function(result, textStatus, jqXHR) {
                //console.log(result);
                $("#modalOffice").modal("show");
                $("#o_id").val(result.office_id);
                $("#office_name").val(result.office_name);
                $("#office_code").val(result.office_code);
                $("#description").val(result.description);
            },
            error: function(result, textStatus, jqXHR) {
                $(document).Toasts("create", {
                    class: "bg-danger",
                    title: "Error",
                    //body: "Record was not found.",
                    body: result.responseJSON.messages,
                    autohide: true,
                    delay: 3000
                })
            }
        })
    });

    $(document).on("click", "#deleteRow", function() {
        let row = $(this).parents("tr")[0];
        let id = table.row(row).data().office_id;
        if (confirm("Are you sure you want to delete this record?")) {
            $.ajax({
                method: "DELETE",
                url: "<?= base_url() ?>office/" + id,
                success: function(result, textStatus, jqXHR) {
                    $(document).Toasts("create", {
                        class: "bg-success",
                        title: "Deleted",
                        body: "Record was deleted successfully.",
                        //body: result.responseJSON.messages,
                        autohide: true,
                        delay: 3000
                    })
                    clearform();
                    table.ajax.reload();
                },
                error: function(result, textStatus, jqXHR) {
                    $(document).Toasts("create", {
                        class: "bg-danger",
                        title: "Error",
                        body: result.responseJSON.messages,
                        autohide: true,
                        delay: 3000
                    })
                }
            });
        }

    });

    function clearform() {
        $("#o_id").val("");
        $("#office_name").val("");
        $("#office_code").val("");
        $("#description").val("");
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