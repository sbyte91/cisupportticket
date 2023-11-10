<?= $this->extend('template/admin_template'); ?>

<?= $this->section('content'); ?>
<div class="content-header">
    <div class="container-fulid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">List of Tickets</h1>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fulid">
        <div class="row mb-2">
            <div class="col-sm-12">
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalTicket">
                   Create Response
                </button>
            </div>
        </div>
        <table id="dataTableTicket" class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th hidden>ID</th>
                    <th hidden>Ticket ID</th>
                    <th>Ticket #</th>
                    <!-- <th>Requested By</th> -->
                    <!-- <th>Email</th> -->
                    <!-- <th hidden>Severity ID</th>
                    <th>Severity</th> -->
                    <th>Acted By</th>
                    <th hidden>Status ID</th>
                    <th>Status</th>
                    <th>Remarks</th>
                    <th class="text-center">ACTION</th>
                </tr>
            </thead>
        </table>

        <div class="modal fade" id="modalTicket" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content" id="modalContent">
                    <div class="modal-header">
                        <h5 class="modal-title">Request Ticket Details</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close" id="closeModal">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body">
                        <form class="needs-validation" id="frmTicket" novalidate>
                            <div class="card-body">
                                <input type="hidden" name="t_id" id="t_id">
                                <div class="form-group">
                                    <label>Office</label>
                                    <select name="support_ticket_id" id="support_ticket_id" class="form-control select2" style="width: 100%;" required>
                                        <option value="">Select Ticket</option>
                                        <?php foreach($tickets as $t) {
                                            echo "<option value='".$t['support_ticket_id']."'>".$t['ticket_num']."</option>";
                                        }
                                        ?>
                                    </select>
                                    <div class="valid-feedback">Looks Good!</div>
                                    <div class="invalid-feedback">Please select Ticket.</div>
                                </div>
                                <div class="form-group">
                                    <label>Severity</label>
                                    <select name="ticket_status_id" id="ticket_status_id" class="form-control select2" style="width: 100%;" required>
                                        <option value="">Select Status</option>
                                        <?php foreach($statuses as $st) {
                                            echo "<option value='".$st['ticket_status_id']."'>".$st['ticket_status']."</option>";
                                        }
                                        ?>
                                    </select>
                                    <div class="valid-feedback">Looks Good!</div>
                                    <div class="invalid-feedback">Please select Status.</div>
                                </div>
                                <div class="form-group">
                                    <label for="description">Remarks</label>
                                    <textarea class="form-control" rows="3" name="remarks" id="remarks" placeholder="Enter Remarks" required></textarea>
                                    <div class="valid-feedback">Looks Good!</div>
                                    <div class="invalid-feedback">Please input Remarks.</div>
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
        $("#frmTicket").submit(function(event) {
            event.preventDefault();
            let formdata = $(this).serializeArray().reduce(function(obj, item) {
                obj[item.name] = item.value;
                return obj;
            }, {});

            let jsondata = JSON.stringify(formdata);

            if (this.checkValidity()) {
                if (!formdata.t_id) {
                    //save
                    $.ajax({
                        method: "POST",
                        url: "<?= base_url('response'); ?>",
                        data: jsondata,
                        success: function(result, textStatus, jqXHR) {
                            console.log(result);
                            $(document).Toasts("create", {
                                class: "bg-success",
                                title: "Success",
                                body: "Response created successfuly.",
                                autohide: true,
                                delay: 3000
                            })
                            table.ajax.reload();
                            clearform();
                            $("#modalTicket").modal("hide");
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
                        url: "<?= base_url() ?>response/" + formdata.t_id,
                        data: jsondata,
                        success: function(result, textStatus, jqXHR) {
                            $(document).Toasts("create", {
                                class: "bg-success",
                                title: "Success",
                                body: "Response updated successfuly.",
                                autohide: true,
                                delay: 3000
                            })
                            table.ajax.reload();
                            clearform();
                            $("#modalTicket").modal("hide");
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

    var table = $("#dataTableTicket").DataTable({
        responsive: true,
        processing: true,
        serverSide: true,
        ajax: {
            url: "<?= base_url('response/list'); ?>",
            type: "GET"
        },
        columns: [{
                data: "support_ticket_response_id",
                visible: false,
                searchable: false,
            },
            {
                data: "support_ticket_id",
                visible: false,
                searchable: false,
            },
            {
                data: "ticket_num",
            },
            {
                data: "acted_by",
            },
            {
                data: "ticket_status_id",
                visible: false,
                searchable: false,
            },
            {
                data: "status",
            },
            {
                data: "remarks",
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
        let id = table.row(row).data().support_ticket_response_id;
        $.ajax({
            method: "GET",
            url: "<?= base_url() ?>response/" + id,
            success: function(result, textStatus, jqXHR) {
                //console.log(result);
                $("#modalTicket").modal("show");
                $("#t_id").val(result.support_ticket_response_id);
                //$("#office_id").val(result.office_id);
                //$('#office_id option:first').prop('selected',true);
                //$("#support_condition_id").val(result.support_condition_id);
                //$("#support_condition_id option:first").prop('selected',true);
                $("#support_ticket_id").val(result.support_ticket_id).trigger("change");
                $("#ticket_status_id").val(result.ticket_status_id).trigger("change");
                $("#remarks").text(result.remarks);
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
        let id = table.row(row).data().support_ticket_response_id;
        if (confirm("Are you sure you want to delete this record?")) {
            $.ajax({
                method: "DELETE",
                url: "<?= base_url() ?>response/" + id,
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
        $("#t_id").val("");
        $("#support_ticket_id").val("").trigger("change");
        $("#ticket_status_id").val("").trigger("change");
        $("#remarks").text("");
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