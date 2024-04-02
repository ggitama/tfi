<div class="body flex-grow-1 p-4 ms-5 me-5" style="background-color: #ffff;">

<h2 class="text-center">Stock Qty</h2>

    
    <div class="row mb-3">
        <label class="col-sm-2 col-form-label">Select Store</label>
        <div class="col-md-3">
        <?php if($data_user_store){?>
            <select class="form-select basic-single-store" id="select_store" name="select_store" aria-label="Default select Store" disabled>
                <?php foreach($store as $key => $vs):?>
                    <option value="<?= $vs->store_code?>" selected><?= $vs->store_code.' - '.$vs->store_name?></option>
                <?php endforeach;?>
            </select>
        <?php }else{?>
            <select class="form-select basic-single-store" id="select_store" name="select_store" aria-label="Default select Store" onchange="change_store_for_biu()">
                <option selected>SELECT STORE</option>
                    <?php foreach($store as $key => $vs):?>
                        <option value="<?= $vs->store_code?>"><?= $vs->store_code.' - '.$vs->store_name?></option>
                    <?php endforeach;?>
            </select>
        <?php }?>
        </div>
    </div>

    <div class="row mb-3">
        <label class="col-sm-2 col-form-label">Select Business Unit</label>
        <div class="col-md-3 biu_select_option">
            <select class="form-select basic-single-biu" id="select_biu" name="select_biu" aria-label="Default select Store" onchange="change_biu_for_dept()">
                <option selected>SELECT BUSINESS UNIT</option>
                    <?php if($data_user_store){?>
                        <?php foreach($business_unit as $key => $bu):?>
                            <option value="<?= $bu->biu_code?>"><?= $bu->biu_code.' - '.$bu->business_unit_name?></option>
                        <?php endforeach;?>
                    <?php }?>
            </select>
        </div>
    </div>

    <div class="row mb-3">
        <label class="col-sm-2 col-form-label">Select Department</label>
        <div class="col-md-3 dept_select_option">
            <select class="form-select basic-single-dept" id="select_dept" name="select_dept" aria-label="Default select Dept">
                <option selected>SELECT DEPARTMENT</option>
            </select>
        </div>
    </div>

    <div class="row mb-3">
        <label class="col-sm-2 col-form-label">Select Category</label>
        <div class="col-md-3 cat_select_option">
            <select class="form-select basic-single-store" id="select_cat" name="select_cat" aria-label="Default select Store">
                <option selected>SELECT CATEGORY</option>
                
            </select>
        </div>
    </div>

    <div class="row mb-3">
        <label class="col-sm-2 col-form-label">Select Family</label>
        <div class="col-md-3 fam_select_option">
            <select class="form-select basic-single-fam" id="select_fam" name="select_fam" aria-label="Default select Fam">
                <option selected>SELECT FAMILY</option>
               
            </select>
        </div>
    </div>

    <div class="row mb-3">
        <label class="col-sm-2 col-form-label">Select Sub Family</label>
        <div class="col-md-3 sub_fam_select_option">
            <select class="form-select basic-single-subfam" id="select_sub_fam" name="select_sub_fam" aria-label="Default select sub fam">
                <option selected>SELECT SUB FAMILY</option>
            </select>
        </div>
    </div>

    <div class="row mb-3">
        <label class="col-sm-2 col-form-label">Select Item</label>
        <div class="col-md-3 item_select_option">
            <select class="form-select example_select_item" id="select_item" name="select_item" aria-label="Default select item">
                <option selected>SELECT ITEM</option>
            </select>
        </div>
        <div class="col-md-2">
            <img width="20px" onclick="resetItem()" src="<?= base_url('Assets/Image/reload.png')?>" alt="icon_refresh">
        </div>
    </div>


    <div class="row mb-3">
    <label class="col-sm-2 col-form-label"><button class="btn btn-danger" onclick="select_item()">SELECT</button></label>
    </div>


    <div id="display_stock_item"></div>

</div>

<div class="modal_add_item_stock_qty"></div>
<div class="modal_edit_item_stock_qty"></div>
<div class="delete_modal_item_stock_qty"></div>
<div class="modal_upload_item_stock_qty"></div>


<script>

    function resetItem(){
        $("#select_item").val("").trigger("change");
    }

    function select_item(){
        val.store = $("#select_store option:selected").val()
        val.biu = $("#select_biu option:selected").val()
        val.dept = $("#select_dept option:selected").val()
        val.cat = $("#select_cat option:selected").val()
        val.fam = $("#select_fam option:selected").val()
        val.fam_name = $("#select_fam option:selected").text()
        val.sub_fam = $("#select_sub_fam option:selected").val()
        val.subfam_name = $("#select_sub_fam option:selected").text()
        val.item_code = $("#select_item option:selected").val()

        //validasi requirement dipilih
        // validates = validated(val)
        msg = ''
        validate = false
        if (val.store == "SELECT STORE") {
            validate = true
            msg += 'Harap isi field Store </br>'
        }
        if (val.biu == "SELECT BUSINESS UNIT") {
            validate = true
            msg += 'Harap isi field Business Unit </br>'
        }
        if (val.dept == "SELECT DEPARTMENT") {
            validate = true
            msg += 'Harap isi field Department </br>'
        }
        if (val.cat == "SELECT CATEGORY") {
            validate = true
            msg += 'Harap isi field Category </br>'
        }
        if (val.fam == "SELECT FAMILY") {
            validate = true
            msg += 'Harap isi field Family </br>'
        }
        if (val.sub_fam == "SELECT SUB FAMILY") {
            validate = true
            msg += 'Harap isi field Sub Family </br>'
        }
       

        if (validate) {
            swal.fire('', msg, 'info')
        }else{
            display_item(val)
            // if (val.sub_fam == "SELECT SUB FAMILY") {
            //     // pilihan function sub family 
            //     display_family(val)
            // }else{
            //     display_sub_family(val)
            //     // pilihan function family 
            // }
        }
    }

    function display_item(val){
        $(".displayitemqty").remove()
        html_display =`<div class="displayitemqty">
        <div class="row">
        <div class="col-md-12 text-center">
        </div>
        </div>
        <div class="row ">
            <div class="col-md-2 mb-2">
                <button class="btn btn-sm btn-primary" value="" data_modal="modal_add_item_stock_qty" onclick="modal_add_item_stock_qty()">ADD Stock Qty Item</button>
            </div>
            <div class="col-md-2 mb-2">
                <button class="btn btn-sm btn-success" value="" data_modal="modal_upload_item_stock_qty" onclick="modal_upload_item_stock_qty()">Upload Stock Qty Item</button>
            </div>
        </div>
        <table id="itemstockqty" class="table table-bordered table-responsive table-striped text-center align-middle" width="100%">

            <thead>
                <tr class="text-center">
                    <th>STORE CODE</th>
                    <th>STORE NAME</th>
                    <th>ITEM CODE</th>
                    <th>ITEM NAME</th>
                    <th>STOCK QTY</th>
                    <th>Action</th>
                </tr>
            </thead>
        </table>
        </div>`
            $("#display_stock_item").append(html_display)
            dataTable_item(val)
    }
    

    function dataTable_item(val) {
        $('#itemstockqty').DataTable({
            "searching": false,
            "processing": true,
            "responsive": true,
            "serverSide": true,
            "ordering": false, // Set true agar bisa di sorting

            "ajax": {
                "url": "<?= htmlentities(base_url('Parameter/Stock_Qty/List_query_item/')) ?>", // URL file untuk proses select datanya
                "type": "POST",
                "data": function(d) {
                    d.<?= htmlentities($this->security->get_csrf_token_name()); ?> = val.<?= htmlentities($this->security->get_csrf_token_name()); ?>;
                    d.store = val.store;
                    d.biu = val.biu;
                    d.dept = val.dept;
                    d.cat = val.cat;
                    d.fam = val.fam;
                    d.sub_fam = val.sub_fam;
                    d.item_code = val.item_code;

                },
                complete: function(response) {
                    token_csrf = JSON.parse(response.responseText).token;
                    val.<?= htmlentities($this->security->get_csrf_token_name()); ?> = token_csrf;
                    
                },
            },
            "deferRender": true,
            "aLengthMenu": [
                [25, 50, -1],
                [25, 50, 'All']
            ], // Combobox Limit
            "columns": [
                // {
                //     "data": 'str_code',
                //     "sortable": false, // !!! id_sort
                //     render: function(data, type, row, meta) {
                //         return meta.row + meta.settings._iDisplayStart + 1;
                //     }
                // },
                {
                    "data": "store_code"
                },
                {
                    "data": "store_name"
                },
                {
                    "data": "item_code"
                },
                {
                    "data": "item_name"
                },
                {
                    "data": "stock_qty"
                },
                {
                    data: null,
                    "ordering": false,
                    "sortable": false,
                    render: function(data, type, row, meta) {
                        return `<button class="btn btn-success m-2" data_modal="modal_edit_item_stock_qty" onclick="modal_edit_item_stock_qty()" value="${data.store_code}_${data.item_code}">Edit</button>
                        <button class="btn btn-danger ms-3" onclick="delete_modal_item_stock_qty()" value="${data.store_code}_${data.item_code}">Delete</button>`;
                    }
                }
            ],
        });
    }



    function change_store_for_biu(){
        val.store = $("#select_store option:selected").val()

        $.ajax({
            url: '<?= htmlentities(base_url('Parameter/Stock_Qty/data_biu_store')) ?>',
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
                
                html_biu = `<select class="form-select basic-single-biu" id="select_biu" name="select_biu" aria-label="Default select biu" onchange="change_biu_for_dept()">
                <option selected>SELECT BUSINESS UNIT</option>`

                $.each(JSON.parse(response.res),function(i,item){
                    html_biu +=  `<option value="${item.biu_code}">${item.biu_code} - ${item.business_unit_name}</option>`
                    
                })
                html_biu += `</select>`;
                $("#select_biu").remove()
                $(".biu_select_option").append(html_biu)
                
            
            },
            error: function(jqXHR, textStatus, errorThrown) {
                alert('gagal');
            }
        });

    }


    function change_biu_for_dept(){
        val.biu = $("#select_biu option:selected").val()
        val.store = $("#select_store option:selected").val()

        $.ajax({
            url: '<?= htmlentities(base_url('Parameter/Stock_Qty/data_dept')) ?>',
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
                
                html_dept = `<select class="form-select basic-single-dept" id="select_dept" name="select_dept" aria-label="Default select Dept" onchange="change_dept_for_cat()">
                <option selected>SELECT DEPARTMENT</option>`

                $.each(JSON.parse(response.res),function(i,item){
                    html_dept +=  `<option value="${item.dept_code}">${item.dept_code} - ${item.department_name}</option>`
                    
                })
                html_dept += `</select>`;
                $("#select_dept").remove()
                $(".dept_select_option").append(html_dept)
                
            
            },
            error: function(jqXHR, textStatus, errorThrown) {
                alert('gagal');
            }
        });

    }

    function change_dept_for_cat(){
        val.dept = $("#select_dept option:selected").val()
        val.store = $("#select_store option:selected").val()

        $.ajax({
            url: '<?= htmlentities(base_url('Parameter/Stock_Qty/data_cat')) ?>',
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
                
                html_cat = `<select class="form-select basic-single-cat" id="select_cat" name="select_cat" aria-label="Default select cat" onchange="change_cat_for_fam()">
                <option selected>SELECT CATEGORY</option>`

                $.each(JSON.parse(response.res),function(i,item){
                    html_cat +=  `<option value="${item.catcode}">${item.catcode} - ${item.cat_name}</option>`
                    
                })
                html_cat += `</select>`;
                $("#select_cat").remove()
                $(".cat_select_option").append(html_cat)
                
            
            },
            error: function(jqXHR, textStatus, errorThrown) {
                alert('gagal');
            }
        });
    }

    function change_cat_for_fam(){
        val.cat = $("#select_cat option:selected").val()
        val.store = $("#select_store option:selected").val()

        $.ajax({
            url: '<?= htmlentities(base_url('Parameter/Stock_Qty/data_fam')) ?>',
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
                
                html_fam = `<select class="form-select basic-single-fam" id="select_fam" name="select_fam" aria-label="Default select fam" onchange="change_fam_for_sub_fam()">
                <option selected>SELECT FAMILY</option>`

                $.each(JSON.parse(response.res),function(i,item){
                    html_fam +=  `<option value="${item.famcode}">${item.famcode} - ${item.fam_name}</option>`
                    
                })
                html_fam += `</select>`;
                $("#select_fam").remove()
                $(".fam_select_option").append(html_fam)
                
            
            },
            error: function(jqXHR, textStatus, errorThrown) {
                alert('gagal');
            }
        });
    }

    function change_fam_for_sub_fam(){
        val.fam = $("#select_fam option:selected").val()
        val.store = $("#select_store option:selected").val()

        $.ajax({
            url: '<?= htmlentities(base_url('Parameter/Stock_Qty/data_sub_fam')) ?>',
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
                
                html_sub_fam = `<select class="form-select basic-single-sub_fam" id="select_sub_fam" name="select_sub_fam" aria-label="Default select sub_fam" onchange="change_subfam_for_item()">
                <option selected>SELECT SUB FAMILY</option>`

                // alert(JSON.parse(response.res) == '')
                
                $.each(JSON.parse(response.res),function(i,item){
                    html_sub_fam +=  `<option value="${item.subfamcode}">${item.subfamcode} - ${item.subfam_name}</option>`
                    
                })
                html_sub_fam += `</select>`;
                $("#select_sub_fam").remove()
                $(".sub_fam_select_option").append(html_sub_fam)
                
            
            },
            error: function(jqXHR, textStatus, errorThrown) {
                alert('gagal');
            }
        });
    }

    function change_subfam_for_item(){
        val.sub_fam = $("#select_sub_fam option:selected").val()
        val.store = $("#select_store option:selected").val()

    }


    $(document).ready(function() {

        $('.example_select_item').select2({
            tags: false,
            ajax: {
                url: '<?= base_url('Parameter/Stock_Qty/data_items/') ?>',
                dataType: 'json',
                type: "GET",
                quietMillis: 50,
                data: function(term) {
                    return {
                        term: term,
                        store: val.store,
                        biu: val.biu,
                        dept:val.dept,
                        cat:val.cat,
                        fam:val.fam,
                        sub_fam:val.sub_fam,
                    };
                },
                processResults: function(data) {
                    return {
                        results: $.map(data, function(item) {
                            // alert(data.token)
                            console.log(item);
                            return {
                                text: item.itemcode + ' - ' + item.item_name,
                                id: item.itemcode
                            }
                            
                        })
                    };
                }
            },
            placeholder: 'Select an option',
            theme: "classic",
            width: 'resolve',
        });

    });

   

    function close_modal(data_) {
        action = $(data_).attr('data');
        $("#" + action).remove();
        $(".modal-backdrop").remove();
    }


    function modal_add_item_stock_qty() {
        val.modal = 'ADD STOCK QTY';
        val.id = 'modal_add_item_stock_qty';
        val.store = $("#select_store option:selected").val()
        val.biu = $("#select_biu option:selected").val()
        val.dept = $("#select_dept option:selected").val()
        val.cat = $("#select_cat option:selected").val()
        val.fam = $("#select_fam option:selected").val()
        val.sub_fam = $("#select_sub_fam option:selected").val()
        // validates = validated(val)
        msg = ''
        validate = false
        if (val.store == "SELECT STORE") {
            validate = true
            msg += 'Harap isi field Store </br>'
        }
        if (val.biu == "SELECT BUSINESS UNIT") {
            validate = true
            msg += 'Harap isi field Business Unit </br>'
        }
        if (val.dept == "SELECT DEPARTMENT") {
            validate = true
            msg += 'Harap isi field Department </br>'
        }
        if (val.cat == "SELECT CATEGORY") {
            validate = true
            msg += 'Harap isi field Category </br>'
        }
        if (val.fam == "SELECT FAMILY") {
            validate = true
            msg += 'Harap isi field Family </br>'
        }
        if (val.sub_fam == "SELECT SUB FAMILY") {
            validate = true
            msg += 'Harap isi field Sub Family </br>'
        }

        if(validate){
            swal.fire('', msg, 'info')
        }else{
            $.ajax({
                url: '<?= htmlentities(base_url('Parameter/Stock_Qty/modal_add_item_stock_qty')) ?>',
                type: "post",
                data: val,
                beforeSend: function() {
                    $('#loader').show();
                },
                complete: function() {
                    $('#loader').hide();
                },
                success: function(res) {
                    // alert(res);
                    $(".modal_add_item_stock_qty").html(res);
                    $('#modal_add_item_stock_qty').modal({
                        backdrop: 'static',
                        keyboard: false
                    });
                    $('#modal_add_item_stock_qty').modal('show');
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    alert('gagal');
                }
            });
        }

    };

    function modal_edit_item_stock_qty(){
        val.modal = 'EDIT STOCK QTY';
        val.id = 'modal_edit_item_stock_qty';
        idSqItem = event.target.value
        splitidSqItem = idSqItem.split("_")
        val.str_code = splitidSqItem[0]
        val.item_code = splitidSqItem[1]

        $.ajax({
            url: '<?= htmlentities(base_url('Parameter/Stock_Qty/modal_edit_item_stock_qty')) ?>',
            type: "post",
            data: val,
            beforeSend: function() {
                $('#loader').show();
            },
            complete: function() {
                $('#loader').hide();
            },
            success: function(res) {
                // alert(res);
                $(".modal_edit_item_stock_qty").html(res);
                $('#modal_edit_item_stock_qty').modal({
                    backdrop: 'static',
                    keyboard: false
                });
                $('#modal_edit_item_stock_qty').modal('show');
            },
            error: function(jqXHR, textStatus, errorThrown) {
                alert('gagal');
            }
        });

    }


    function delete_modal_item_stock_qty(){
        val.modal = 'DELETE STOCK QTY ITEM';
        val.id = 'delete_modal_item_stock_qty';
        idSqItem = event.target.value
        splitidSqItem = idSqItem.split("_")
        val.str_code = splitidSqItem[0]
        val.item_code = splitidSqItem[1]

        $.ajax({
            url: '<?= htmlentities(base_url('Parameter/Stock_Qty/delete_modal_item_stock_qty')) ?>',
            type: "post",
            data: val,
            beforeSend: function() {
                $('#loader').show();
            },
            complete: function() {
                $('#loader').hide();
            },
            success: function(res) {
                // alert(res);
                $(".delete_modal_item_stock_qty").html(res);
                $('#delete_modal_item_stock_qty').modal({
                    backdrop: 'static',
                    keyboard: false
                });
                $('#delete_modal_item_stock_qty').modal('show');
            },
            error: function(jqXHR, textStatus, errorThrown) {
                alert('gagal');
            }
        });
    }

    

    function modal_upload_item_stock_qty(){
        val.modal = 'UPLOAD STOCK QTY ITEM';
        val.id = 'modal_upload_item_stock_qty';
        val.store = $("#select_store option:selected").val()
        val.biu = $("#select_biu option:selected").val()
        val.dept = $("#select_dept option:selected").val()
        val.cat = $("#select_cat option:selected").val()
        val.fam = $("#select_fam option:selected").val()
        val.sub_fam = $("#select_sub_fam option:selected").val()

        msg = ''
        validate = false
        if (val.store == "SELECT STORE") {
            validate = true
            msg += 'Harap isi field Store </br>'
        }
        if (val.biu == "SELECT BUSINESS UNIT") {
            validate = true
            msg += 'Harap isi field Business Unit </br>'
        }
        if (val.dept == "SELECT DEPARTMENT") {
            validate = true
            msg += 'Harap isi field Department </br>'
        }
        if (val.cat == "SELECT CATEGORY") {
            validate = true
            msg += 'Harap isi field Category </br>'
        }
        if (val.fam == "SELECT FAMILY" ) {
            validate = true
            msg += 'Harap isi field Family </br>'
        }
        if (val.sub_fam == "SELECT SUB FAMILY" ) {
            validate = true
            msg += 'Harap isi field Sub Family </br>'
        }

        if(validate){
            swal.fire('', msg, 'info')
        }else{
            $.ajax({
                url: '<?= base_url('Parameter/Stock_Qty/modal_upload_item_stock_qty/') ?>',
                type: "post",
                data: val,
                beforeSend: function() {
                    $('#loader').show();
                },
                complete: function() {
                    $('#loader').hide();
                },
                success: function(res) {
                    $(".modal_upload_item_stock_qty").html(res);
                    $('#modal_upload_item_stock_qty').modal({
                        backdrop: 'static',
                        keyboard: false
                    });
                    $('#modal_upload_item_stock_qty').modal('show');
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    alert('gagal');
                }
            });
        }
    }

</script>