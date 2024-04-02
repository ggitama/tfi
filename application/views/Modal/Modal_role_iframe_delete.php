<div class="modal fade" id="<?= htmlentities($id) ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <form id="form_<?= htmlentities($id) ?>">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel"><?= htmlentities($modal_title) ?></h5>
                    <button type="button" data="<?= htmlentities($id) ?>" onclick="close_modal(this)" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">


                    <input type="hidden" id="input-id_menu" value="<?= htmlentities($iframe_data->id_menu) ?>" name="id_menu" class="form-control" readonly>
                    <div class="mb-3 row">
                        <!-- <label class="col-sm-5 col-form-label">Role</label>
                        <div class="col-sm-7 form-group">
                            <select class="col-sm-7 form-select example-basic-single" id="role" name="role" aria-label="Default select example" disabled>
                                <option selected></option>
                                <?php foreach ($role_user as $role) : ?>
                                    <option value="<?= htmlentities($role['role_id']) ?>"><?= htmlentities($role['role_name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div> -->
                    </div>
                    <div class="mb-3 row">
                        <label class="col-sm-5 col-form-label">Menu</label>
                        <div class="col-sm-7 form-group">
                            <select class="col-sm-7 form-select example-basic-single" id="menu" name="menu" aria-label="Default select example" disabled>
                                <option selected></option>
                                <?php foreach ($menu_user as $menu) : ?>
                                    <option value="<?= htmlentities($menu['id_menu']) ?>"><?= htmlentities($menu['menu_name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label class="col-sm-5 col-form-label">Iframe</label>
                        <div class="col-sm-7 form-group">
                            <select class="col-sm-7 form-select example-basic-single" id="iframe" name="iframe" aria-label="Default select example" disabled>
                                <option selected></option>
                                <?php foreach ($iframe_role as $ifrmae) : ?>
                                    <option value="<?= htmlentities($ifrmae['id_iframe']) ?>"><?= htmlentities($ifrmae['iframe_name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>


                </div>
                <div class="modal-footer">
                    <button type="button" data="<?= htmlentities($id) ?>" onclick="close_modal(this)" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" data="<?= htmlentities($id) ?>" onclick="delete_role_iframe(this)" class="btn btn-primary">Delete</button>
                </div>
            </form>
        </div>
    </div>
</div>


<script>
    $(document).ready(function() {
        val.<?= htmlentities($this->security->get_csrf_token_name()); ?> = "<?= htmlentities($token) ?>"
        $("#role").val('<?= htmlentities($iframe_data->id_role) ?>').trigger('change');
        $("#menu").val('<?= htmlentities($iframe_data->id_menu) ?>').trigger('change');
        $("#iframe").val('<?= htmlentities($iframe_data->id_iframe) ?>').trigger('change');
    })
    $(".example-basic-single").select2({
        dropdownParent: $("#<?= htmlentities($id) ?>"),
        placeholder: 'Select an option',
        theme: "classic",
        width: 'resolve',
        dropdownAutoWidth: true
    });
    $('.select2-container--classic').css('width', '100%')



    function delete_role_iframe(data) {

        val.id_menu = $('input[name="id_menu"]').val();

        Swal.fire({
            title: 'Apakah anda yakin menghapus data ini ?',
            // showDenyButton: true,
            showCancelButton: true,
            confirmButtonText: 'Delete',
            // denyButtonText: Cancel,
        }).then((result) => {
            /* Read more about isConfirmed, isDenied below */
            if (result.isConfirmed) {
                $.ajax({
                    url: '<?= htmlentities(base_url('Parameter/Manage_Role_Iframe_c/delete_role_iframe/')) ?>',
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
                                text: 'deleted successfully',
                                icon: 'success'
                            }).then((result) => {
                                // Reload the Page
                                location.reload();
                            });
                        } else {
                            swal.fire("", response.res, "info")
                        }
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        alert('gagal');
                    }
                });

            } else if (result.isDenied) {
                Swal.fire('Changes are not saved', '', 'info')
            }
        })





    }
</script>