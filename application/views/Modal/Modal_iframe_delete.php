<div class="modal fade" id="<?= htmlentities($id) ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <form id="form_<?= htmlentities($id) ?>">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel"><?= htmlentities($modal_title) ?></h5>
                    <button type="button" data="<?= htmlentities($id) ?>" onclick="close_modal(this)" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">


                    <input type="hidden" id="input-id_iframe" value="<?= htmlentities($iframe_data->id_iframe) ?>" name="id_iframe" class="form-control" readonly>
                    <div class="mb-3 row">
                        <label class="col-sm-5 col-form-label">Iframe Name</label>
                        <div class="col-sm-7 form-group">
                            <input type="text" id="input-iframe_name" value="<?= htmlentities($iframe_data->iframe_name) ?>" name="iframe_name" class="form-control" disabled>
                            <div id="error"></div>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label class="col-sm-5 col-form-label">Iframe Type</label>
                        <div class="col-sm-7 form-group">
                            <select class="col-sm-7 form-select example-basic-single" id="input-iframe_type" name="iframe_type" aria-label="Default select example" readonly>
                                <option selected></option>
                                <?php foreach ($template as $t) : ?>
                                    <option value="<?= htmlentities($t['id_template_iframe']) ?>"><?= htmlentities($t['iframe_name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label class="col-sm-5 col-form-label">Iframe Tag</label>
                        <div class="col-sm-7 form-group">
                            <!-- <input type="text" id="input-iframe_tag" value="<?= htmlentities($iframe_data->iframe_tag) ?>" name="iframe_tag" class="form-control" disabled> -->
                            <textarea name="iframe_tag" id="input-iframe_tag" cols="50" rows="10" disabled><?= htmlentities($iframe_data->iframe_tag) ?></textarea>
                            <div id="error"></div>
                        </div>
                    </div>


                </div>
                <div class="modal-footer">
                    <button type="button" data="<?= htmlentities($id) ?>" onclick="close_modal(this)" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" data="<?= htmlentities($id) ?>" onclick="delete_iframe(this)" class="btn btn-danger">Delete</button>
                </div>
            </form>
        </div>
    </div>
</div>


<script>
    $(document).ready(function() {
        val.<?= htmlentities($this->security->get_csrf_token_name()); ?> = "<?= htmlentities($token) ?>"
        $("#input-iframe_type").val('<?= htmlentities($iframe_data->iframe_type) ?>').trigger('change');

    })

    function delete_iframe(data) {
        val.id_iframe = $('input[name="id_iframe"]').val();

        Swal.fire({
            title: 'Are you sure?',
            text: "Menghapus data ini ?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '<?= htmlentities(base_url('Parameter/Manage_Iframe_c/delete_iframe/')) ?>',
                    type: "post",
                    data: val,
                    beforeSend: function() {
                        $("#loader").show();
                    },
                    complete: function() {
                        $("#loader").hide();
                    },
                    success: function(res) {
                        response = JSON.parse(res)
                        val.<?= htmlentities($this->security->get_csrf_token_name()); ?> = response.token;
                        if (response.res == '1') {
                            Swal.fire({
                                title: "",
                                text: "has been deleted",
                                icon: 'success'
                            }).then((result) => {
                                // Reload the Page
                                location.reload();
                            });
                        } else {
                            swal.fire("", response.res, "info")
                            // alert(res);
                        }
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        alert('gagal');
                    }
                });

            }
        })




    }
</script>