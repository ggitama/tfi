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
                        <label class="col-sm-5 col-form-label">Store Code</label>
                        <div class="col-sm-7 form-group">
                            <input type="text" id="input-store" value="<?= $store->store_code.' - '.$store->store_name?>" name="store" class="form-control" disabled required>
                            <div id="error"></div>

                            <input type="hidden" id="storecode" value="<?= $store->store_code?>">
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label class="col-sm-5 col-form-label">Business Unit</label>
                        <div class="col-sm-7 form-group">
                            <input type="text" id="input-biu" value="<?= $biu->biu_code.' - '.$biu->business_unit_name?>" name="biu" class="form-control" disabled required>
                            <div id="error"></div>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label class="col-sm-5 col-form-label">Department</label>
                        <div class="col-sm-7 form-group">
                            <input type="text" id="input-dept" value="<?= $dept->dept_code.' - '.$dept->department_name?>" name="dept" class="form-control" disabled required>
                            <div id="error"></div>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label class="col-sm-5 col-form-label">Category</label>
                        <div class="col-sm-7 form-group">
                            <input type="text" id="input-cat" value="<?= $cat->catcode.' - '.$cat->cat_name?>" name="cat" class="form-control" disabled required>
                            <div id="error"></div>
                            <input type="hidden" id="category" value="<?= $cat->catcode?>">
                        </div>
                    </div>
                   
                    <div class="mb-3 row">
                        <label class="col-sm-5 col-form-label">Family</label>
                        <div class="col-sm-7 form-group">
                            <input type="text" id="input-fam" value="<?= $fam->famcode.' - '.$fam->fam_name?>" name="fam" class="form-control" disabled required>
                            <div id="error"></div>
                            <input type="hidden" id="famcode" value="<?= $fam->famcode?>">
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label class="col-sm-5 col-form-label">Sub Family</label>
                        <div class="col-sm-7 form-group">
                            <input type="text" id="input-fam" value="<?= $sub_fam->subfamcode.' - '.$sub_fam->subfam_name?>" name="fam" class="form-control" disabled required>
                            <div id="error"></div>
                            <input type="hidden" id="subfamcode" value="<?= $sub_fam->subfamcode?>">
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label class="col-sm-5 col-form-label">Item Code</label>
                        <div class="col-sm-7 form-group">
                            <input type="text" id="input-item" value="<?= $item->itemcode.' - '.$item->item_name?>" name="item" class="form-control" disabled required>
                            <div id="error"></div>
                            <input type="hidden" id="itemcode" value="<?= $item->itemcode?>">
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label class="col-sm-5 col-form-label">Stock Qty</label>
                        <div class="col-sm-7 form-group">
                            <input type="number" id="edit-stock_qty" value="<?= $stock_qty?>" name="stock_qty" class="form-control" onkeypress="if ( isNaN(this.value + String.fromCharCode(event.keyCode) )) return false;" >
                            <div id="error"></div>
                        </div>
                    </div>



                </div>
                <div class="modal-footer">
                    <button type="button" data="<?= htmlentities($id) ?>" onclick="close_modal(this)" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" data="<?= htmlentities($id) ?>" onclick="editStockQtyItem(this)" class="btn btn-primary">SAVE CHANGES</button>
                </div>
            </form>
        </div>
    </div>
</div>


<script>
    $(document).ready(function() {
        val.<?= htmlentities($this->security->get_csrf_token_name()); ?> = "<?= htmlentities($token) ?>"
    })

    function editStockQtyItem(data) {
        val.str_code = $('#storecode').val();
        val.itemcode = $("#itemcode").val()
        val.stock_qty = $("#edit-stock_qty").val()
        val.item_code = $('#select_item').val();

        validated = validate_editQty(val)

        if (validated.validate) {
            swal.fire('', msg, 'info')
        } else {
            $.ajax({
                url: '<?= htmlentities(base_url('Parameter/Stock_Qty/edit_sqItem/')) ?>',
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
                    // alert(response.value)
                    if (response.value == '0') {
                        swal.fire("", response.msg, "warning");
                    } else {
                        Swal.fire({
                            title: "",
                            text: response.msg,
                            icon: 'success'
                        }).then((result) => {
                            // Reload the Page
                            // location.reload();
                            $("#modal_edit_item_stock_qty").modal('hide');
                            $("#modal_edit_item_stock_qty").remove();
                            $('#itemstockqty').DataTable().ajax.reload();
                        });
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    alert('gagal');
                    alert(textStatus)
                }
            });
        }


    }

    function validate_editQty(val) {
        msg = ''
        validate = false
       
        if (val.stock_qty == '') {
            validate = true
            msg += 'Harap isi field Stock Qty </br>'
        }
        return {
            'validate': validate,
            'msg': msg
        }
    }

    function save_user_action(val) {
        $.ajax({
            url: '<?= htmlentities(base_url('Parameter/Manage_Iframe_c/save_iframe/')) ?>',
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