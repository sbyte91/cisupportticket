<?= $this->extend('template/admin_template'); ?>

<?= $this->section('content'); ?>
<div class="content-header">
    <div class="container-fulid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Post Management</h1>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fulid">
        <div class="row mb-2">
            <div class="col-sm-12">
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalID">
                    Add Post
                </button>
            </div>
        </div>
        <table id="dataTable" class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>AUTHOR NAME</th>
                    <th>TITLE</th>
                    <th>DECRIPTION</th>
                    <th>CREATED AT</th>
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
                                <div class="form-group">
                                    <label for="title">Title</label>
                                    <input type="text" class="form-control" name="title" id="title" placeholder="Enter Title" required>
                                    <div class="valid-feedback">Looks Good!</div>
                                    <div class="invalid-feedback">Please provide a valid Title.</div>
                                </div>
                                <div class="form-group">
                                    <label for="description">Description</label>
                                    <input type="text" class="form-control" name="description" id="description" placeholder="Enter Description" required>
                                    <div class="valid-feedback">Looks Good!</div>
                                    <div class="invalid-feedback">Please provide a valid Description.</div>
                                </div>
                                <div class="form-group">
                                    <label for="content">Content</label>
                                    <textarea type="textarea" class="form-control" name="content" id="content" rows="5" placeholder="Enter Content" required></textarea>
                                    <div class="valid-feedback">Looks Good!</div>
                                    <div class="invalid-feedback">Please provide a valid Content.</div>
                                </div>
                                <div class="form-group">
                                    <label for="author_id">Author</label>
                                    <select class="form-control custom-select" name="author_id" id="author_id" required>
                                        <option value="">Select Author</option>
                                        <?php foreach ($authors as $author) : ?>
                                            <option value="<?= $author['id']; ?>"><?= $author['first_name'] . ' ' . $author['last_name']; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    <div class="valid-feedback">Looks Good!</div>
                                    <div class="invalid-feedback">Please provide a valid Author.</div>
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
                        url: "<?= base_url('posts'); ?>",
                        data: jsondata,
                        success: function(result, textStatus, jqXHR) {
                            $(document).Toasts("create", {
                                class: "bg-success",
                                title: "Success",
                                body: "Record Created Successfuly.",
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
                        url: "<?= base_url() ?>posts/" + formdata.id,
                        data: jsondata,
                        success: function(result, textStatus, jqXHR) {
                            $(document).Toasts("create", {
                                class: "bg-success",
                                title: "Success",
                                body: "Record Updated Successfuly.",
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
                                body: "Record Was Not Updated.",
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
            url: "<?= base_url('posts/list'); ?>",
            type: "POST"
        },
        columns: [{
                data: "id",
            },
            {
                data: "author_name",
            },
            {
                data: "title",
            },
            {
                data: "description",
            },
            {
                data: "created_at",
            },
            {
                data: null,
                defaultContent: `<td>
                <button class="btn btn-primary" id="editRow"><i class="fas fa-edit"></i></button>
                <button class="btn btn-danger" id="deleteRow"><i class="fas fa-trash"></i></button>
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
        let id = table.row(row).data().id;
        $.ajax({
            method: "GET",
            url: "<?= base_url() ?>posts/" + id,
            success: function(result, textStatus, jqXHR) {
                $("#modalID").modal("show");
                $("#id").val(result.id);
                $("#author_id").val(result.author_id);
                $("#title").val(result.title);
                $("#description").val(result.description);
                $("#content").val(result.content);
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

    $(document).on("click", "#deleteRow", function() {
        let row = $(this).parents("tr")[0];
        let id = table.row(row).data().id;
        if (confirm("Are you sure you want to delete this record?")) {
            $.ajax({
                method: "DELETE",
                url: "<?= base_url() ?>posts/" + id,
                success: function(result, textStatus, jqXHR) {
                    $(document).Toasts("create", {
                        class: "bg-success",
                        title: "Deleted",
                        body: "Record Was Deleted.",
                        autohide: true,
                        delay: 3000
                    });
                    table.ajax.reload();
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
            });
        }

    });

    function clearform() {
        $("#id").val("");
        $("#author_id").val("");
        $("#title").val("");
        $("#description").val("");
        $("#content").val("");
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