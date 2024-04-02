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
                            <input type="text" id="input-iframe_name" value="<?= htmlentities($iframe_data->iframe_name) ?>" name="iframe_name" class="form-control" required>
                            <div id="error"></div>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label class="col-sm-5 col-form-label">Iframe Type</label>
                        <div class="col-sm-7 form-group">
                            <select class="col-sm-7 form-select example-basic-single" id="input-iframe_type" name="iframe_type" aria-label="Default select example">
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
                            <!-- <input type="text" id="input-iframe_tag" value="<?= htmlentities($iframe_data->iframe_tag) ?>" name="iframe_tag" class="form-control" required>\ -->
                            <textarea name="iframe_tag" id="input-iframe_tag" cols="45" rows="10"><?= htmlentities($iframe_data->iframe_tag) ?></textarea>
                            <div id="error"></div>
                        </div>
                    </div>


                </div>
                <div class="modal-footer">
                    <button type="button" data="<?= htmlentities($id) ?>" onclick="close_modal(this)" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" data="<?= htmlentities($id) ?>" onclick="edit_iframe(this)" class="btn btn-primary">SAVE CHANGES</button>
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

    function edit_iframe(data) {

        val.id_iframe = $('input[name="id_iframe"]').val();
        val.iframe_name = $('input[name="iframe_name"]').val();
        val.iframe_type = $('#input-iframe_type').val();
        val.iframe_tag = $('#input-iframe_tag').val();
        validated = validate_(val)

        if (validated.validate) {
            swal.fire('', msg, 'info')
        } else {
            $.ajax({
                url: '<?= htmlentities(base_url('Parameter/Manage_Iframe_c/edit_iframe/')) ?>',
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
                            text: "Your has been saved",
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


    }

    function validate_() {
        msg = ''
        validate = false
        if (val.iframe_name == '') {
            validate = true
            msg += 'Harap isi field iframe name </br>'
        }
        if (val.iframe_tag == '') {
            validate = true
            msg += 'Harap isi field iframe tag </br>'
        }
        regex_iframe_name = valid_iframe_name(val.iframe_name)
        regex_iframe_tag = valid_iframe_tag(val.iframe_tag)
        if (regex_iframe_name['validates'] || regex_iframe_tag['validates']) {
            validate = true
            msg += 'Please match the required the field ! </br>'
        }

        return {
            'validate': validate,
            'msg': msg
        }
    }
</script>