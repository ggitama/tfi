<div class="modal fade" id="<?= htmlentities($id) ?>">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header" id="hesas">
                <h5 class="modal-title" id="exampleModalToggleLabel">FORM DATA</h5>
                <button type="button" data="<?= htmlentities($id) ?>" onclick="close_modal(this)" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="form_<?= htmlentities($id) ?>" method="post">
                <div class="modal-body">
                    <input type="hidden" name="id_menu" value="<?= htmlentities(($data_menu) ? $data_menu->id_menu : ''); ?>">
                    <!-- content -->
                    <div class="mb-3 row">
                        <label class="col-sm-5 col-form-label">Menu Name</label>
                        <div class="col-sm-7">
                            <input onkeypress="return event.keyCode != 13;" onkeyup="key(this)" type="text" value="<?= htmlentities(($data_menu) ? $data_menu->menu_name : ''); ?>" id="input-menu_name" name="menu_name" class="form-control">
                            <div id="error"></div>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label class="col-sm-5 col-form-label">Position</label>
                        <div class="col-sm-7">
                            <select id="input-position" name="position" class="form-select type_menu">
                                <?php foreach ($posisi as $pos) : ?>
                                    <option value="<?= htmlentities($pos['position']) ?>"><?= htmlentities($pos['position']) ?></option>
                                <?php endforeach; ?>
                            </select>
                            <div id="error"></div>
                        </div>
                    </div>



                    <div class="mb-3 row">
                        <label class="col-sm-5 col-form-label">Is Menu</label>
                        <div class="col-sm-2">
                            <div class="form-check">
                                <input value="Yes" class="form-check-input" type="radio" name="is_menu" id="flexRadioDefault1" checked>
                                <label class="form-check-label" for="flexRadioDefault1">
                                    Yes
                                </label>
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="form-check">
                                <input value="No" class="form-check-input" type="radio" name="is_menu" id="flexRadioDefault2" <?= htmlentities(($data_menu) ? (($data_menu->is_menu == 'No') ? 'checked' : '') : '') ?>>
                                <label class="form-check-label" for="flexRadioDefault2">
                                    No
                                </label>
                            </div>
                        </div>
                        <div id="error"></div>
                    </div>

                    <div class="mb-3 row position" id="position">
                        <!-- <label class="col-sm-5 col-form-label">Position</label>
            <div class="col-sm-7">
              <select class="form-select" name="position" aria-label="Default select example">
                <option value="0" selected>-- Pilih Posisi --</option>
                <option value="1">One</option>
                <option value="2">Two</option>
                <option value="3">Three</option>
              </select>
            </div> -->
                    </div>

                    <!-- close content -->
                </div>
                <div class="modal-footer">
                    <button type="button" data="<?= htmlentities($id) ?>" onclick="close_modal(this)" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" data="<?= htmlentities($id) ?>" onclick="action_edit(this)" class="btn <?php echo ((htmlentities($id) == 'modal_delete') ? 'btn-danger' : 'btn-primary') ?>  action_add"><?php echo ((htmlentities($id) == 'modal_delete') ? 'DELETE DATA' : 'SAVE CHANGES') ?></button>
                </div>
            </form>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $("#input-position").val("<?= htmlentities($data_menu->position) ?>").change();
            val.<?= htmlentities($this->security->get_csrf_token_name()); ?> = "<?= htmlentities($token) ?>"

        })
        $('#input-type').change(function() {
            var value = $(this).val();

            val.type = value;
            val.modal_id = '<?= htmlentities($id); ?>';
            nama_menu = $('#input-menu_name').val()
            format_file_name = nama_menu.replaceAll(' ', '_')

            if (value == '1' || value == '2' || value == '3') {
                $.post('<?php echo htmlentities(base_url('Parameter/Menu_c/parent_menu')) ?>', val, function(data) {
                    $('.parent_menu').html(data);
                    $('.parent_menu').show();
                    $('#input-file').val(format_file_name)
                    change_parent(val.modal_id)
                });
            } else if (value == '0') {
                $('#parent_menu').hide();
                $('#input-file').val('')
                $('#input-file').val(nama_menu)
            } else {
                $('#parent_menu').hide();
                $('#input-file').val(nama_menu)
            }
        });

        function change_parent(modal_id) {
            $('#input-parent').change(function() {
                parent_name = $(this).find("option:selected").text().replaceAll(' ', '_').toLowerCase()
                nama_menu = $('#input-menu_name').val()
                format_file_name = nama_menu.replaceAll(' ', '_')
                data_val = {}
                data_val.value_parent = event.target.value
                $('#input-file').val(parent_name + '/' + format_file_name);
                if (modal_id == 'modal_edit') {
                    $.post('<?php echo htmlentities(base_url('Parameter/Menu_c/posisi_menu')) ?>', data_val, function(data) {
                        $('.position').html(data);
                    });
                }

            })
        }


        $('#input-menu_name').keyup(function() {
            nama_menu = $('#input-menu_name').val()
            format_file_name = nama_menu.replaceAll(' ', '_')
            type_parent = $('#input-type').find("option:selected").val()
            if (type_parent == '0' || type_parent == 0) {
                $('#input-file').val(format_file_name.toLowerCase())
            } else {
                parent_name = $('#input-parent').find("option:selected").text().replace(' ', '_').toLowerCase()
                $('#input-file').val(parent_name + '/' + format_file_name.toLowerCase())
            }
        })

        function action_edit(data_) {
            action = $(data_).attr('data');
            $('#error').html(" ");
            form = $("#form_" + action).serialize() + '&<?= $this->security->get_csrf_token_name(); ?>=' + val.<?= $this->security->get_csrf_token_name(); ?>;

            $.ajax({
                type: "POST",
                url: "<?php echo htmlentities(site_url('Parameter/Menu_c/validate/')); ?>",
                data: form,
                dataType: "json",
                success: function(data) {
                    val.<?= htmlentities($this->security->get_csrf_token_name()); ?> = data.token;
                    form = $('#form_' + action).serialize() + '&<?= $this->security->get_csrf_token_name(); ?>=' + data.token;
                    if (data.action == 'ok') {
                        if (action == 'modal_edit') {
                            edit(form);
                        } else if (action == 'modal_add') {
                            // alert('ok')
                            save(form);
                        }
                    } else {
                        $.each(data, function(key, value) {
                            if (value == '') {
                                $('#input-' + key).removeClass('is-invalid');
                                $('#input-' + key).addClass('is-valid');
                                $('#input-' + key).parents('.form-group').find('#error').html(value);
                            } else {
                                $('#input-' + key).addClass('is-invalid');
                                $('#input-' + key).parents('.form-group').find('#error').html(value);
                            }
                        });
                    }

                }
            });

        }

        function edit(form) {
            Swal.fire({
                title: 'Do you want to save the changes?',
                showDenyButton: true,
                // showCancelButton: true,
                confirmButtonText: 'Save',
                denyButtonText: `Cancel`,
            }).then((result) => {
                /* Read more about isConfirmed, isDenied below */
                if (result.isConfirmed) {
                    $.ajax({
                        url: '<?= htmlentities(base_url('Parameter/Menu_c/edit_/')) ?>',
                        type: "post",
                        data: form,
                        success: function(res) {
                            response = JSON.parse(res)
                            val.<?= htmlentities($this->security->get_csrf_token_name()); ?> = response.token;
                            if (response.res == '1') {
                                Swal.fire({
                                    title: "",
                                    text: "Edited Successfully!",
                                    icon: 'success'
                                }).then((result) => {
                                    // Reload the Page
                                    location.reload();
                                });

                            } else {
                                alert(response.res);
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

        function key(isi) {
            names = $(isi).attr('name');
            term = $("input[type=text][name=" + names + "]").val();
            val[names] = term;

            $.ajax({
                type: "POST",
                url: "<?php echo htmlentities(site_url('Parameter/Menu_c/validate_keyup/')) ?>",
                data: val,
                dataType: "json",
                success: function(data) {
                    val.<?= htmlentities($this->security->get_csrf_token_name()); ?> = data.token;
                    $.each(data, function(key, value) {
                        if (value == '') {
                            $('#input-' + key).removeClass('is-invalid');
                            $('#input-' + key).addClass('is-valid');
                            $('#input-' + key).parents('.form-group').find('#error').html(value);
                        } else {
                            $('#input-' + key).addClass('is-invalid');
                            $('#input-' + key).parents('.form-group').find('#error').html(value);
                        }

                    });
                }
            });
        }


        function check_v(isi) {
            names = $(isi).attr('name');
            val[names] = isi.value;
            $.ajax({
                type: "POST",
                url: "<?php echo htmlentities(site_url('Parameter/Menu_c/validate_keyup/')) ?>",
                data: val,
                dataType: "json",
                success: function(data) {
                    $.each(data, function(key, value) {
                        val.<?= htmlentities($this->security->get_csrf_token_name()); ?> = data.token;
                        if (value == '') {
                            $('#input-' + key).removeClass('is-invalid');
                            $('#input-' + key).addClass('is-valid');
                            $('#input-' + key).parents('.form-group').find('#error').html(value);
                        } else {
                            $('#input-' + key).addClass('is-invalid');
                            $('#input-' + key).parents('.form-group').find('#error').html(value);
                        }

                    });
                }
            });
        }
    </script>
</div>