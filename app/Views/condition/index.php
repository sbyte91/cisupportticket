<?= $this->extend('template/admin_template'); ?>

<?= $this->section('content'); ?>
<div class="content-header">
    <div class="container-fulid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">List of Support Condition</h1>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fulid">
        <div class="row mb-2">
            <div class="col-sm-12">
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalCondition">
                    Add Condition
                </button>
            </div>
        </div>
        <table id="dataTableCondition" class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th hidden>ID</th>
                    <th>Support Condition</th>
                    <th class="text-center">ACTION</th>
                </tr>
            </thead>
        </table>

        <div class="modal fade" id="modalCondition" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content" id="modalContent">
                    <div class="modal-header">
                        <h5 class="modal-title">Support Condition</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close" id="closeModal">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body">
                        <form class="needs-validation" id="frmCondition" novalidate>
                            <div class="card-body">
                                <input type="hidden" name="c_id" id="c_id">
                                <div class="form-group">
                                    <label for="condition">Status</label>
                                    <input type="text" class="form-control" name="condition" id="condition" placeholder="Enter Support Condition" required>
                                    <div class="valid-feedback">Looks Good!</div>
                                    <div class="invalid-feedback">Please input Support Condition.</div>
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
        $("#frmCondition").submit(function(event) {
            event.preventDefault();
            let formdata = $(this).serializeArray().reduce(function(obj, item) {
                obj[item.name] = item.value;
                return obj;
            }, {});

            let jsondata = JSON.stringify(formdata);

            if (this.checkValidity()) {
                if (!formdata.c_id) {
                    //save
                    $.ajax({
                        method: "POST",
                        url: "<?= base_url('condition'); ?>",
                        data: jsondata,
                        success: function(result, textStatus, jqXHR) {
                            $(document).Toasts("create", {
                                class: "bg-success",
                                title: "Success",
                                body: "Condition created successfuly.",
                                autohide: true,
                                delay: 3000
                            })
                            table.ajax.reload();
                            clearform();
                            $("#modalCondition").modal("hide");
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
                        url: "<?= base_url() ?>condition/" + formdata.c_id,
                        data: jsondata,
                        success: function(result, textStatus, jqXHR) {
                            $(document).Toasts("create", {
                                class: "bg-success",
                                title: "Success",
                                body: "Condition updated successfuly.",
                                autohide: true,
                                delay: 3000
                            })
                            table.ajax.reload();
                            clearform();
                            $("#modalCondition").modal("hide");
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

    var table = $("#dataTableCondition").DataTable({
        responsive: true,
        processing: true,
        serverSide: true,
        ajax: {
            url: "<?= base_url('condition/list'); ?>",
            type: "GET"
        },
        columns: [{
                data: "support_condition_id",
                visible: false,
                searchable: false,
            },
            {
                data: "condition",
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
        let id = table.row(row).data().support_condition_id;
        $.ajax({
            method: "GET",
            url: "<?= base_url() ?>condition/" + id,
            success: function(result, textStatus, jqXHR) {
                $("#modalCondition").modal("show");
                $("#c_id").val(result.support_condition_id);
                $("#condition").val(result.condition);
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
        let id = table.row(row).data().support_condition_id;
        if (confirm("Are you sure you want to delete this record?")) {
            $.ajax({
                method: "DELETE",
                url: "<?= base_url() ?>condition/" + id,
                success: function(result, textStatus, jqXHR) {
                    console.log(result);
                    $(document).Toasts("create", {
                        class: "bg-success",
                        title: "Deleted",
                        body: "Record was deleted successfully.",
                        //body: result.responseJSON.messages,
                        autohide: true,
                        delay: 3000
                    })
                    //$('#dataTableCondition').DataTable().ajax.reload();
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
        $("#c_id").val("");
        $("#condition").val("");
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