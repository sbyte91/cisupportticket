<?= $this->extend('template/admin_template'); ?>

<?= $this->section('content'); ?>
<div class="content-header">
    <div class="container-fulid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">List of Ticket Statuses</h1>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fulid">
        <div class="row mb-2">
            <div class="col-sm-12">
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalStatus">
                    Add Status
                </button>
            </div>
        </div>
        <table id="dataTableStatus" class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th hidden>ID</th>
                    <th>ticket_status</th>
                    <th class="text-center">ACTION</th>
                </tr>
            </thead>
        </table>

        <div class="modal fade" id="modalStatus" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content" id="modalContent">
                    <div class="modal-header">
                        <h5 class="modal-title">Ticket Status</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close" id="closeModal">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body">
                        <form class="needs-validation" id="frmStatus" novalidate>
                            <div class="card-body">
                                <input type="hidden" name="s_id" id="s_id">
                                <div class="form-group">
                                    <label for="office_name">Status</label>
                                    <input type="text" class="form-control" name="ticket_status" id="ticket_status" placeholder="Enter Status" required>
                                    <div class="valid-feedback">Looks Good!</div>
                                    <div class="invalid-feedback">Please input Ticket Status.</div>
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
        $("#frmStatus").submit(function(event) {
            event.preventDefault();
            let formdata = $(this).serializeArray().reduce(function(obj, item) {
                obj[item.name] = item.value;
                return obj;
            }, {});

            let jsondata = JSON.stringify(formdata);

            if (this.checkValidity()) {
                if (!formdata.s_id) {
                    //save
                    $.ajax({
                        method: "POST",
                        url: "<?= base_url('status'); ?>",
                        data: jsondata,
                        success: function(result, textStatus, jqXHR) {
                            $(document).Toasts("create", {
                                class: "bg-success",
                                title: "Success",
                                body: "Status created successfuly.",
                                autohide: true,
                                delay: 3000
                            })
                            table.ajax.reload();
                            clearform();
                            $("#modalStatus").modal("hide");
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
                        url: "<?= base_url() ?>status/" + formdata.s_id,
                        data: jsondata,
                        success: function(result, textStatus, jqXHR) {
                            $(document).Toasts("create", {
                                class: "bg-success",
                                title: "Success",
                                body: "Status updated successfuly.",
                                autohide: true,
                                delay: 3000
                            })
                            table.ajax.reload();
                            clearform();
                            $("#modalStatus").modal("hide");
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

    var table = $("#dataTableStatus").DataTable({
        responsive: true,
        processing: true,
        serverSide: true,
        ajax: {
            url: "<?= base_url('status/list'); ?>",
            type: "GET"
        },
        columns: [{
                data: "ticket_status_id",
                visible: false,
                searchable: false,
            },
            {
                data: "ticket_status",
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
        let id = table.row(row).data().ticket_status_id;
        $.ajax({
            method: "GET",
            url: "<?= base_url() ?>status/" + id,
            success: function(result, textStatus, jqXHR) {
                $("#modalStatus").modal("show");
                $("#s_id").val(result.ticket_status_id);
                $("#ticket_status").val(result.ticket_status);
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
        let id = table.row(row).data().ticket_status_id;
        if (confirm("Are you sure you want to delete this record?")) {
            $.ajax({
                method: "DELETE",
                url: "<?= base_url() ?>status/" + id,
                success: function(result, textStatus, jqXHR) {
                    $(document).Toasts("create", {
                        class: "bg-success",
                        title: "Deleted",
                        body: "Record was deleted successfully.",
                        //body: result.responseJSON.messages,
                        autohide: true,
                        delay: 3000
                    })
                    $('#dataTableStatus').DataTable().ajax.reload();
                    //table.ajax.reload();
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
        $("#s_id").val("");
        $("#ticket_status").val("");
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