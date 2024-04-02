<div class="modal fade" id="<?= htmlentities($id) ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <form id="form_<?= htmlentities($id) ?>">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel"><?= htmlentities($modal_title) ?></h5>
                    <button type="button" data="<?= htmlentities($id) ?>" onclick="close_modal(this)" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                    <div class="mb-3 row">
                        <label class="col-sm-5 col-form-label">User LDAP</label>
                        <div class="col-sm-7 form-group">
                            <!-- <input type="text" id="input-ldap" value="" name="ldap" class="form-control" required> -->
                            <input class="form-check-input" value="Yes" <?= (htmlentities($user_transmart->ldap) == 'Yes') ? 'checked' : ''; ?> type="radio" name="ldap" id="ldap1" disabled>
                            <label class="form-check-label" for="ldap1">
                                Yes
                            </label>
                            <input class="form-check-input ms-4" value="No" <?= (htmlentities($user_transmart->ldap) == 'No') ? 'checked' : ''; ?> type="radio" name="ldap" id="ldap2" disabled>
                            <label class="form-check-label" for="ldap2">
                                No
                            </label>
                            <div id="error"></div>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label class="col-sm-5 col-form-label">Username</label>
                        <div class="col-sm-7 form-group">
                            <input type="text" id="input-username" value="<?= htmlentities($user_transmart->username) ?>" name="username" class="form-control" readonly>
                            <div id="error"></div>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label class="col-sm-5 col-form-label">Nama</label>
                        <div class="col-sm-7 form-group">
                            <input type="text" id="input-nama" value="<?= htmlentities($user_transmart->nama) ?>" name="nama" class="form-control" readonly>
                            <div id="error"></div>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label class="col-sm-5 col-form-label">Role</label>
                        <div class="col-sm-7 form-group">
                            <select class="col-sm-7 form-select" id="role" name="role" aria-label="Default select example" disabled>

                                <?php foreach ($role_user as $role) : ?>
                                    <option value="<?= htmlentities($role['role_id']) ?>"><?= htmlentities($role['role_name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <!-- <div class="col-sm-7 form-group">
                            <input type="text" id="input-role" value="" name="role" class="form-control" required>
                            <div id="error"></div>
                        </div> -->
                    </div>
                    <!-- <div class="mb-3 row">
                        <label class="col-sm-5 col-form-label">Store</label>
                        <div class="col-sm-7 form-group">
                            <input type="text" id="input-store" value="" name="store" class="form-control" required>
                            <div id="error"></div>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label class="col-sm-5 col-form-label">Departement</label>
                        <div class="col-sm-7 form-group">
                            <input type="text" id="input-departement" value="" name="departement" class="form-control" required>
                            <div id="error"></div>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label class="col-sm-5 col-form-label">LDAP</label>
                        <div class="col-sm-7 form-group">
                            <input type="text" id="input-ldap" value="" name="ldap" class="form-control" required>
                            <div id="error"></div>
                        </div>
                    </div> -->


                </div>
                <div class="modal-footer">
                    <button type="button" data="<?= htmlentities($id) ?>" onclick="close_modal(this)" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" data="<?= htmlentities($id) ?>" onclick="delete_user(this)" class="btn btn-danger">DELETE USER</button>
                </div>
            </form>
        </div>
    </div>
</div>


<script>
    $(document).ready(function() {
        val.<?= htmlentities($this->security->get_csrf_token_name()); ?> = "<?= htmlentities($token) ?>"
        $('#role').val('<?= htmlentities($user_transmart->role_id) ?>');
    })

    function delete_user(data) {
        val.username = $('input[name="username"]').val();


        Swal.fire({
            title: 'Are you sure you delete user data? ?',
            text: "",
            icon: 'warning',
            showCancelButton: true,
            // showCancelButton: true,
            confirmButtonText: 'Delete',
            denyButtonText: `Cancel`,
        }).then((result) => {
            /* Read more about isConfirmed, isDenied below */
            if (result.isConfirmed) {
                $.ajax({
                    url: '<?= htmlentities(base_url('Parameter/User_Manage_c/delete_user/')) ?>',
                    type: "post",
                    data: val,
                    beforeSend: function() {
                        $('#loader').show();
                    },
                    complete: function() {
                        $('#loader').hide();
                    },
                    success: function(res) {
                        response = JSON.parse(res)
                        val.<?= htmlentities($this->security->get_csrf_token_name()); ?> = response.token;
                        if (response.res == '1') {
                            Swal.fire({
                                title: "",
                                text: "Your has been deleted!",
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