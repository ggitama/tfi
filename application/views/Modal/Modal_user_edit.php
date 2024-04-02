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
                            <input onclick="ldap_check()" class="form-check-input" value="Yes" <?= (htmlentities($user_transmart->ldap) == 'Yes') ? 'checked' : ''; ?> type="radio" name="ldap" id="ldap1">
                            <label class="form-check-label" for="ldap1">
                                Yes
                            </label>
                            <input onclick="ldap_check()" class="form-check-input ms-4" value="No" <?= (htmlentities($user_transmart->ldap) == 'No') ? 'checked' : ''; ?> type="radio" name="ldap" id="ldap2">
                            <label class="form-check-label" for="ldap2">
                                No
                            </label>
                            <div id="error"></div>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label class="col-sm-5 col-form-label">Is Active</label>
                        <div class="col-sm-7 form-group">
                            <!-- <input type="text" id="input-ldap" value="" name="ldap" class="form-control" required> -->
                            <input class="form-check-input" value="1" <?= (htmlentities($user_transmart->is_active) == '1') ? 'checked' : ''; ?> type="radio" name="isActive" id="isActive1">
                            <label class="form-check-label" for="isActive1">
                                Acitve
                            </label>
                            <input class="form-check-input ms-4" value="2" <?= (htmlentities($user_transmart->is_active) == '2') ? 'checked' : ''; ?> type="radio" name="isActive" id="isActive2">
                            <label class="form-check-label" for="isActive2">
                                Non Active
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
                            <input type="text" id="input-nama" value="<?= htmlentities($user_transmart->nama) ?>" name="nama" class="form-control" required>
                            <div id="error"></div>
                        </div>
                    </div>
                    <div class="mb-3 row roless">
                        <label class="col-sm-5 col-form-label">Role</label>
                        <div class="col-sm-7 form-group">
                            <select class="col-sm-7 form-select example-basic-single" id="role" name="role" aria-label="Default select example">

                                <?php foreach ($role_user as $role) : ?>
                                    <option value="<?= htmlentities($role['role_id']) ?>"><?= htmlentities($role['role_name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
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
                    <button type="button" data="<?= htmlentities($id) ?>" onclick="edit_user(this)" class="btn btn-primary">SAVE CHANGES</button>
                </div>
            </form>
        </div>
    </div>
</div>



<script>
    $(document).ready(function() {
        val.<?= htmlentities($this->security->get_csrf_token_name()); ?> = "<?= htmlentities($token) ?>"
        $("#role").val('<?= htmlentities($user_transmart->role_id) ?>').trigger('change');
        ldap = '<?= htmlentities($user_transmart->ldap) ?>'
        if (ldap == 'No') {
            $(".pw").remove()
            html = `<div class="mb-3 row pw">
                        <label class="col-sm-5 col-form-label"> </label>
                        <div class="col-sm-7 form-group">
                            <button class="btn btn-primary" onclick="change_password()">Ganti Password</button>
                        </div>
                    </div>`
            $(".roless").after(html);
        } else {
            $(".pw").remove()
        }
    })

    function ldap_check() {
        ldap = '<?= htmlentities($user_transmart->ldap) ?>'
        value_ = $('input[name="ldap"]:checked').val();
        if (ldap == 'Yes') {
            if (value_ == 'No') {
                $(".pws").remove()
                html = `<div class="mb-3 row pws">
                            <label class="col-sm-5 col-form-label">Password</label>
                            <div class="col-sm-7 form-group">
                                <input type="password" id="input-password" value="" name="password" class="form-control" required>
                                <div id="error"></div>
                            </div>
                        </div>`
                $(".roless").after(html);
            } else {
                $(".pws").remove()
            }
        } else {
            // alert('yes')
            if (value_ == 'No') {
                $(".pw").remove()
                html = `<div class="mb-3 row pw">
                        <label class="col-sm-5 col-form-label"> </label>
                        <div class="col-sm-7 form-group">
                            <button class="btn btn-primary" onclick="change_password()">Ganti Password</button>
                        </div>
                    </div>`
                $(".roless").after(html);
            } else {
                $(".pw").remove()
            }
        }
    }

    function change_password() {
        $(".pw").remove()
        ldap = '<?= htmlentities($user_transmart->ldap) ?>'
        if (ldap == 'No') {
            $(".pw2").remove()
            html = `<div class="mb-3 row pw2">
                        <label class="col-sm-5 col-form-label">Password Baru</label>
                        <div class="col-sm-5 form-group">
                            <input type="password" id="input-password" value="" name="password" class="form-control" required>
                        </div>
                        <div class="col-sm-1">
                                <button class="btn btn-danger" onclick="cancel_pw()">Cancel</button>
                        </div>
                    </div>`
            $(".roless").after(html);
        } else {
            $(".pw2").remove()
        }
    }

    function cancel_pw() {
        $(".pw2").remove()
        ldap = '<?= htmlentities($user_transmart->ldap) ?>'
        if (ldap == 'No') {
            $(".pw").remove()
            html = `<div class="mb-3 row pw">
                        <label class="col-sm-5 col-form-label"></label>
                        <div class="col-sm-7 form-group">
                            <button class="btn btn-primary" onclick="change_password()">Ganti Password</button>
                        </div>
                    </div>`
            $(".roless").after(html);
        } else {
            $(".pw").remove()
        }
    }


    $(".example-basic-single").select2({
        dropdownParent: $("#<?= htmlentities($id) ?>"),
        placeholder: 'Select an option',
        theme: "classic",
        width: 'resolve',
        dropdownAutoWidth: true
    });
    $('.select2-container--classic').css('width', '100%')

    function edit_user(data) {
        val.ldap = $('input[name="ldap"]:checked').val();
        val.isActive = $('input[name="isActive"]:checked').val();
        val.username = $('input[name="username"]').val();
        val.password = $('input[name="password"]').val();
        // alert(val.password)
        val.nama = $('input[name="nama"]').val();
        val.role = $('#role').find(":selected").val();
        validated = validate_(val)

        if (validated.validate) {
            swal.fire('', msg, 'info')
        } else {

            $.ajax({
                url: '<?= htmlentities(base_url('Parameter/User_Manage_c/edit_user/')) ?>',
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
                            text: "User saved successfully",
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
        }


    }

    function validate_() {
        msg = ''
        validate = false
        if (val.ldap === undefined) {
            validate = true
            msg += 'Please fill in the LDAP option, yes or no !</br>'
        }
        if (val.username == '') {
            validate = true
            msg += 'Please fill in the username field ! </br>'
        }
        if (val.nama == '') {
            validate = true
            msg += 'Please fill in the name field !  </br>'
        }
        if (val.role == '') {
            validate = true
            msg += 'Please fill in the choice of role !</br>'
        }

        regex_username = valid_username(val.username)
        regex_name = valid_nama(val.nama)
        regex_ldap = valid_nama(val.ldap)
        regex_role = valid_username(val.role)
        if (regex_username['validates'] || regex_name['validates'] || regex_ldap['validates'] || regex_role['validates']) {
            validate = true
            msg += 'Please match the required the field ! </br>'
        }

        return {
            'validate': validate,
            'msg': msg
        }
    }
</script>