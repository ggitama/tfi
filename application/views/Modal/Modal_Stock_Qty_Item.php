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
                            <input type="hidden" id="store" value="<?= $store->store_code?>">
                            <input type="hidden" id="store_name" value="<?= $store->store_name?>">
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
                            <input type="hidden" id="fam" value="<?= $fam->famcode?>">
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label class="col-sm-5 col-form-label">Sub Family</label>
                        <div class="col-sm-7 form-group">
                            <input type="text" id="input-subfam" value="<?= $sub_fam->subfamcode.' - '.$sub_fam->subfam_name?>" name="subfam" class="form-control" disabled required>
                            <div id="error"></div>
                            <input type="hidden" id="sub_fam" value="<?= $sub_fam->subfamcode?>">
                        </div>
                    </div>
                    <div class="mb-3 row item">
                        <label class="col-sm-5 col-form-label">Item Code</label>
                        <div class="col-sm-7 form-group">
                            <select class="col-sm-7 form-select example-basic-single" id="item" name="item">
                                <option selected></option>
                                <?php foreach ($items as $item) : ?>
                                    <option value="<?= htmlentities($item['itemcode']) ?>"><?= htmlentities($item['itemcode']) ?> - <?= htmlentities($item['item_name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    
                    <div class="mb-3 row">
                        <label class="col-sm-5 col-form-label">Stock Qty</label>
                        <div class="col-sm-7 form-group">
                            <input type="number" id="input-qty" value="" name="qty" class="form-control " onkeypress="if ( isNaN(this.value + String.fromCharCode(event.keyCode) )) return false;" >
                            <div id="error"></div>
                        </div>
                    </div>


                    


                </div>
                <div class="modal-footer">
                    <button type="button" data="<?= htmlentities($id) ?>" onclick="close_modal(this)" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" data="<?= htmlentities($id) ?>" onclick="saveStockQtyItem(this)" class="btn btn-primary">SAVE CHANGES</button>
                </div>
            </form>
        </div>
    </div>
</div>


<script>
    $(document).ready(function() {
        val.<?= htmlentities($this->security->get_csrf_token_name()); ?> = "<?= htmlentities($token) ?>"
    });

    $(".example-basic-single").select2({
        dropdownParent: $("#<?= htmlentities($id) ?>"),
        placeholder: 'Select an option',
        theme: "classic",
        width: 'resolve',
        dropdownAutoWidth: true
    });
    $('.select2-container--classic').css('width', '100%')

    function saveStockQtyItem(data) {
        val.str_code = $('#store').val();
        val.str_name = $('#store_name').val();
        val.cat = $('#category').val();
        val.fam = $('#fam').val();
        val.sub_fam = $('#sub_fam').val();
        val.item = $('#item').val();
        val.qty = $('#input-qty').val();

        validated = validateStockQty_(val)

        if (validated.validate) {
            swal.fire('', msg, 'info')
        } else {
            $.ajax({
                url: '<?= htmlentities(base_url('Parameter/Stock_Qty/saveQtyItem/')) ?>',
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
                            $("#modal_add_item_stock_qty").modal('hide');
                            $("#modal_add_item_stock_qty").remove();
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

    function validateStockQty_(val) {
        msg = ''
        validate = false
        if (val.item == '') {
            validate = true
            msg += 'Harap isi field Item  </br>'
        }
        if (val.qty == '') {
            validate = true
            msg += 'Harap isi field Stock Qty </br>'
        }
        return {
            'validate': validate,
            'msg': msg
        }
    }

   
</script>