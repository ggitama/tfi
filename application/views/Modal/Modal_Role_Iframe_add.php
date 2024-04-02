<div class="modal fade" id="<?= htmlentities($id) ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <form id="form_<?= htmlentities($id) ?>">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel"><?= htmlentities($modal_title) ?></h5>
                    <button type="button" data="<?= htmlentities($id) ?>" onclick="close_modal(this)" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- <div class="mb-3 row">
                        <label class="col-sm-5 col-form-label">Role</label>
                        <div class="col-sm-7 form-group">
                            <select class="col-sm-7 form-select example-basic-single" id="role" name="role" aria-label="Default select example">
                                <option selected></option>
                                <?php foreach ($role_user as $role) : ?>
                                    <option value="<?= htmlentities($role['role_id']) ?>"><?= htmlentities($role['role_name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div> -->
                    <div class="mb-3 row">
                        <label class="col-sm-5 col-form-label">Menu</label>
                        <div class="col-sm-7 form-group">
                            <select class="col-sm-7 form-select example-basic-single" id="menu" name="menu" aria-label="Default select example">
                                <option selected></option>
                                <?php foreach ($menu_user as $menu) : ?>
                                    <option value="<?= htmlentities($menu['id_menu']) ?>"><?= htmlentities($menu['menu_name']).'-'.$menu['parent_name'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label class="col-sm-5 col-form-label">Iframe</label>
                        <div class="col-sm-7 form-group">
                            <select class="col-sm-7 form-select example-basic-single" id="iframe" name="iframe" aria-label="Default select example">
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
                    <button type="button" data="<?= htmlentities($id) ?>" onclick="saveRoleIframe(this)" class="btn btn-primary">SAVE CHANGES</button>
                </div>
            </form>
        </div>
    </div>
</div>


<script>
    $(document).ready(function() {
        val.<?= htmlentities($this->security->get_csrf_token_name()); ?> = "<?= htmlentities($token) ?>"
    })
    $(".example-basic-single").select2({
        dropdownParent: $("#<?= htmlentities($id) ?>"),
        placeholder: 'Select an option',
        theme: "classic",
        width: 'resolve',
        dropdownAutoWidth: true
    });
    $('.select2-container--classic').css('width', '100%')
    // function view_username() {
    //     tes = event.target.value
    //     alert(tes)
    //     $("#input-nama").val('dio')
    //     $("#input-nama").val('dio')
    // }

    // function ldap_check() {
    //     tes = $('input[name="ldap"]:checked').val();
    // }

    function saveRoleIframe(data) {
        // val.role = $('#role').find(":selected").val();
        val.menu = $('#menu').find(":selected").val();
        val.iframe = $('#iframe').find(":selected").val();
        validated = validate_(val)

        if (validated.validate) {
            swal.fire('', msg, 'info')
        } else {
            $.ajax({
                url: '<?= htmlentities(base_url('Parameter/Manage_Role_Iframe_c/check_role_iframe/')) ?>',
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
                        swal.fire("", "Harap Periksa kembali, terdapat Role Iframe yang sama", "warning");
                    } else {
                        save_user_action(val)
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
        // if (val.role == '') {
        //     validate = true
        //     msg += 'Harap isi Pilihan role </br>'
        // }
        if (val.menu == '') {
            validate = true
            msg += 'Harap isi Pilihan menu </br>'
        }
        if (val.iframe == '') {
            validate = true
            msg += 'Harap isi Pilihan iframe </br>'
        }

        return {
            'validate': validate,
            'msg': msg
        }
    }

    function save_user_action(val) {
        $.ajax({
            url: '<?= htmlentities(base_url('Parameter/Manage_Role_Iframe_c/save_role_iframe/')) ?>',
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
                        text: "saved successfully",
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
</script>