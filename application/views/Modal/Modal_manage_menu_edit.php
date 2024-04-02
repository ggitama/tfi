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
                        <label class="col-sm-5 col-form-label">Type</label>
                        <div class="col-sm-7">
                            <select id="input-type" name="type" class="form-select type_menu" aria-label="Default select example">
                                <!-- <option value="<?= htmlentities(($data_menu) ? $data_menu->type : '') ?>" selected><?= htmlentities(($data_menu) ? $data_menu->type : ' -- Pilih Type --') ?></option> -->
                                <option value="0">Parent</option>
                                <option value="1">Child</option>
                                <option value="2">Child 2</option>
                                <option value="3">Child 3</option>
                            </select>
                            <div id="error"></div>
                        </div>
                    </div>
                    <div class="mb-3 row parent_menu" id="parent_menu">
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

                    <div class="mb-3 row">
                        <label class="col-sm-5 col-form-label">Position</label>
                        <div class="col-sm-7 posisition_change1">
                            <select id="input-position" name="position" class="form-select posisition_change">
                                <?php foreach ($posisi as $pos) : ?>
                                    <option value="<?= htmlentities($pos['position']) ?>"><?= htmlentities($pos['position']) ?></option>
                                <?php endforeach; ?>
                            </select>
                            <div id="error"></div>
                        </div>
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

            type = '<?= htmlentities(($data_menu) ? $data_menu->type : '') ?>'
            // $("#input-type").select2("val", type);
            // $("#input-type").val(type).trigger('change');
            $("#input-type").val(type);
            val.type = type
            if (type != 0) {
                parent = '<?= htmlentities(($data_menu) ? $data_menu->parent : '') ?>'
                $.post('<?php echo htmlentities(base_url('Parameter/Menu_c/parent_menu')) ?>', val, function(data) {
                    val.<?= htmlentities($this->security->get_csrf_token_name()); ?> = data.token;
                    $('.parent_menu').html(data.html);
                    $('.parent_menu').show();
                    // change_parent(val.modal_id)
                    $("#input-parent").val(parent);
                });
            } else {
                $('#parent_menu').hide();
            }
            // console.log(type);

        })

        $('#input-type').change(function() {
            var value = $(this).val();

            val.type = value;
            val.modal_id = '<?= htmlentities($id); ?>';
            nama_menu = $('#input-menu_name').val()
            format_file_name = nama_menu.replaceAll(' ', '_')


            if (value == '1' || value == '2' || value == '3') {
                $.post('<?php echo htmlentities(base_url('Parameter/Menu_c/parent_menu')) ?>', val, function(data) {
                    val.<?= htmlentities($this->security->get_csrf_token_name()); ?> = data.token;
                    $('.parent_menu').html(data.html);
                    $('.parent_menu').show();
                });

            } else if (value == '0') {
                $.post('<?php echo htmlentities(base_url('Parameter/Menu_c/posisi_menu')) ?>', val, function(data) {
                    val.<?= htmlentities($this->security->get_csrf_token_name()); ?> = data.token;
                    $("#input-position").val(data.posisi)
                    $(".posisition_change").remove()
                    html_ps = ` <select id="input-position" name="position" class="form-select posisition_change">`;
                    last_posisition = 0
                    $.each(JSON.parse(data.get_parent_posisi), function(i, item) {
                        console.log(item);
                        html_ps += `<option value="${item.position}">${item.position}</option>`
                        if (item.position < 99) {
                            last_posisition++
                        }
                    });
                    html_ps += `<option value="${last_posisition+1}" selected>${last_posisition+1}</option>`

                    html_ps += `</select>`
                    $(".posisition_change1").append(html_ps)

                });
                $('#parent_menu').hide();
            } else {
                $('#parent_menu').hide();
            }
        });

        function parentss() {
            // alert('tes')
            // data_val = {}
            val.id_menu = '<?= $data_menu->id_menu ?>'
            val.value_parent = event.target.value
            console.log(val);
            // $('#input-file').val(parent_name + '/' + format_file_name);

            $.post('<?php echo htmlentities(base_url('Parameter/Menu_c/posisi_menu')) ?>', val, function(data) {
                val.<?= htmlentities($this->security->get_csrf_token_name()); ?> = data.token;
                $("#input-position").val(data.posisi)
                $(".posisition_change").remove()
                html_ps = ` <select id="input-position" name="position" class="form-select posisition_change">`;
                last_posisition = 0
                $.each(JSON.parse(data.get_parent_posisi), function(i, item) {
                    console.log(item);
                    html_ps += `<option value="${item.position}">${item.position}</option>`
                    if (item.position < 99) {
                        last_posisition++
                    }
                });
                html_ps += `<option value="${last_posisition+1}" selected>${last_posisition+1}</option>`

                html_ps += `</select>`
                $(".posisition_change1").append(html_ps)

            });
        }

        // function change_parent(modal_id) {
        // $('#input-parent').change(function() {
        // $('#input-parent').on('change', function() {
        //     alert('tes')
        //     data_val = {}
        //     data_val.value_parent = event.target.value
        //     // $('#input-file').val(parent_name + '/' + format_file_name);
        //     if (modal_id == 'modal_edit') {
        //         $.post('<?php echo htmlentities(base_url('Parameter/Menu_c/posisi_menu')) ?>', data_val, function(data) {
        //             val.<?= htmlentities($this->security->get_csrf_token_name()); ?> = data.token;
        //             $('.position').html(data);
        //         });
        //     }

        // })
        // }

        // $('#input-parent').change(function() {
        //     data_val = {}
        //     data_val.value_parent = event.target.value
        //     // console.log(data_val);
        //     if (modal_id == 'modal_edit') {
        //         $.post('<?php echo htmlentities(base_url('Parameter/Menu_c/posisi_menu')) ?>', data_val, function(data) {
        //             val.<?= htmlentities($this->security->get_csrf_token_name()); ?> = data.token;
        //             $('.position').html(data);
        //         });
        //     }

        // })


        $('#input-menu_name').keyup(function() {
            nama_menu = $('#input-menu_name').val()
            format_file_name = nama_menu.replaceAll(' ', '_')
            type_parent = $('#input-type').find("option:selected").val()
            // if (type_parent == '0' || type_parent == 0) {
            //     $('#input-file').val(format_file_name.toLowerCase())
            // } else {
            //     parent_name = $('#input-parent').find("option:selected").text().replace(' ', '_').toLowerCase()
            //     $('#input-file').val(parent_name + '/' + format_file_name.toLowerCase())
            // }
        })







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