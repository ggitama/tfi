<div class="body flex-grow-1 p-4 ms-5 me-5" style="background-color: #ffff;">

<h2 class="text-center">Stock Days3</h2>

    
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
            <select class="form-select basic-single-store" id="select_fam" name="select_fam" aria-label="Default select Store">
                <option selected>SELECT FAMILY</option>
               
            </select>
        </div>
    </div>

    <div class="row mb-3">
        <label class="col-sm-2 col-form-label">Select Sub Family</label>
        <div class="col-md-3 sub_fam_select_option">
            <select class="form-select basic-single-store" id="select_sub_fam" name="select_sub_fam" aria-label="Default select sub fam">
                <option selected>SELECT SUB FAMILY</option>
            </select>
        </div>
    </div>


    <div class="row mb-3">
    <label class="col-sm-2 col-form-label"><button class="btn btn-danger" onclick="select_fam_or_subfam()">SELECT</button></label>
    </div>


    <div id="display_stock_days"></div>

</div>

<div class="modal_add"></div>
<div class="modal_add_fam_stock_day"></div>
<div class="modal_edit_fam_stock_day"></div>
<div class="delete_modal_fam_stock_day"></div>

<div class="modal_add_subfam_stock_day"></div>
<div class="modal_edit_subfam_stock_day"></div>
<div class="delete_modal_subfam_stock_day"></div>

<div class="modal_upload_fam_stock_day"></div>
<div class="modal_upload_subfam_stock_day"></div>

<div class="modal_edit">

</div>
<div class="modal_delete">

</div>


<script>

    function select_fam_or_subfam(){
        val.store = $("#select_store option:selected").val()
        val.biu = $("#select_biu option:selected").val()
        val.dept = $("#select_dept option:selected").val()
        val.cat = $("#select_cat option:selected").val()
        val.fam = $("#select_fam option:selected").val()
        val.fam_name = $("#select_fam option:selected").text()
        val.sub_fam = $("#select_sub_fam option:selected").val()
        val.subfam_name = $("#select_sub_fam option:selected").text()

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
       

        if (validate) {
            swal.fire('', msg, 'info')
        }else{
            if (val.sub_fam == "SELECT SUB FAMILY") {
                // pilihan function sub family 
                display_family(val)
            }else{
                display_sub_family(val)
                // pilihan function family 
            }
        }
    }
    
    function display_family(val){
        $(".displayfamstockday").remove()
        $(".displaysubfamstockday").remove()
        html_display =`<div class="displayfamstockday">
        <div class="row">
        <div class="col-md-12 text-center">
        <h4>FAMILY ${val.fam_name}</h4>
        </div>
        </div>
        <div class="row ">
            <div class="col-md-2 mb-2">
                <button class="btn btn-sm btn-primary" value="" data_modal="modal_add_fam_stock_day" onclick="modal_add_fam_stock_day()">ADD Stock Days FAM</button>
            </div>
            <div class="col-md-2 mb-2">
                <button class="btn btn-sm btn-success" value="" data_modal="modal_upload_fam_stock_day" onclick="modal_upload_fam_stock_day()">Upload Stock Days FAM</button>
            </div>
        </div>
        <table id="famstockday" class="table table-bordered table-responsive table-striped text-center align-middle" width="100%">

            <thead>
                <tr class="text-center">
                    <th>STORE CODE</th>
                    <th>STORE NAME</th>
                    <th>FAMILY CODE</th>
                    <th>FAMILY NAME</th>
                    <th>STOCK DAY MIN</th>
                    <th>STOCK DAY MAX</th>
                    <th>Action</th>
                </tr>
            </thead>
        </table>
        </div>`
            $("#display_stock_days").append(html_display)
            dataTable_fam(val)
    }

    function dataTable_fam(val) {
        $('#famstockday').DataTable({
            // "language": {
            //     searchPlaceholder: "Iframe Name"
            // },
            "searching": false,
            "processing": true,
            "responsive": true,
            "serverSide": true,
            "ordering": false, // Set true agar bisa di sorting

            "ajax": {
                "url": "<?= htmlentities(base_url('Parameter/Stock_Days_c0001/List_query_fam/')) ?>", // URL file untuk proses select datanya
                "type": "POST",
                "data": function(d) {
                    d.<?= htmlentities($this->security->get_csrf_token_name()); ?> = val.<?= htmlentities($this->security->get_csrf_token_name()); ?>;
                    d.store = val.store;
                    d.biu = val.biu;
                    d.dept = val.dept;
                    d.cat = val.cat;
                    d.fam = val.fam;
                    d.sub_fam = val.sub_fam;

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
                    "data": "str_code"
                },
                {
                    "data": "str_name"
                },
                {
                    "data": "famcode"
                },
                {
                    "data": "fam_name"
                },
                {
                    "data": "stock_day_min"
                },
                {
                    "data": "stock_day_max"
                },
                {
                    data: null,
                    "ordering": false,
                    "sortable": false,
                    render: function(data, type, row, meta) {
                        return `<button class="btn btn-success m-2" data_modal="modal_edit_fam_stock_day" onclick="modal_edit_fam_stock_day()" value="${data.str_code}_${data.famcode}">Edit</button>
                        <button class="btn btn-danger ms-3" onclick="delete_modal_fam_stock_day()" value="${data.str_code}_${data.famcode}">Delete</button>`;
                    }
                }
            ],
        });
    }

    function display_sub_family(val){
        $(".displaysubfamstockday").remove()
        $(".displayfamstockday").remove()
        html_display =`
        <div class="displaysubfamstockday">
        <div class="row">
        <div class="col-md-12 text-center">
        <h4>SUB FAMILY ${val.subfam_name}</h4>
        </div>
        </div>
        <div class="row ">
            <div class="col-md-3 mb-2">
                <button class="btn btn-sm btn-primary" value="" data_modal="modal_add_sub_fam_stock_day" onclick="modal_add_sub_fam_stock_day()">Add Stock Days Sub Fam</button>
            </div>
            <div class="col-md-2 mb-2">
                <button class="btn btn-sm btn-success" value="" data_modal="modal_upload_sub_fam_stock_day" onclick="modal_upload_subfam_stock_day()">Upload Stock Days Sub FAM</button>
            </div>
        </div>
        <table id="subfamstockday" class="table table-bordered table-responsive table-striped text-center align-middle" width="100%">

            <thead>
                <tr class="text-center">
                    <th>STORE CODE</th>
                    <th>STORE NAME</th>
                    <th>SUB FAMILY CODE</th>
                    <th>SUB FAMILY NAME</th>
                    <th>STOCK DAY MIN</th>
                    <th>STOCK DAY MAX</th>
                    <th>Action</th>
                </tr>
            </thead>
        </table>
        </div>`
        $("#display_stock_days").append(html_display)
        dataTable_subfam(val)
    }

    function dataTable_subfam(val){
    $('#subfamstockday').DataTable({
            // "language": {
            //     searchPlaceholder: "Iframe Name"
            // },
            "searching": false,
            "processing": true,
            "responsive": true,
            "serverSide": true,
            "ordering": false, // Set true agar bisa di sorting

            "ajax": {
                "url": "<?= htmlentities(base_url('Parameter/Stock_Days_c0001/List_query_sub_fam/')) ?>", // URL file untuk proses select datanya
                "type": "POST",
                "data": function(d) {
                    d.<?= htmlentities($this->security->get_csrf_token_name()); ?> = val.<?= htmlentities($this->security->get_csrf_token_name()); ?>;
                    d.store = val.store;
                    d.biu = val.biu;
                    d.dept = val.dept;
                    d.cat = val.cat;
                    d.fam = val.fam;
                    d.sub_fam = val.sub_fam;

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
                    "data": "str_code"
                },
                {
                    "data": "str_name"
                },
                {
                    "data": "subfamcode"
                },
                {
                    "data": "subfam_name"
                },
                {
                    "data": "stock_day_min"
                },
                {
                    "data": "stock_day_max"
                },
                {
                    data: null,
                    "ordering": false,
                    "sortable": false,
                    render: function(data, type, row, meta) {
                        return `<button class="btn btn-success m-2" data_modal="modal_edit_subfam_stock_day" onclick="modal_edit_subfam_stock_day()" value="${data.str_code}_${data.subfamcode}">Edit</button>
                        <button class="btn btn-danger ms-3" onclick="delete_modal_subfam_stock_day()" value="${data.str_code}_${data.subfamcode}">Delete</button>`;
                    }
                }
            ],
        });
    }

    function change_store_for_biu(){
        val.store = $("#select_store option:selected").val()

        $.ajax({
            url: '<?= htmlentities(base_url('Parameter/Stock_Days_c0001/data_biu_store')) ?>',
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
            url: '<?= htmlentities(base_url('Parameter/Stock_Days_c0001/data_dept')) ?>',
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
            url: '<?= htmlentities(base_url('Parameter/Stock_Days_c0001/data_cat')) ?>',
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
            url: '<?= htmlentities(base_url('Parameter/Stock_Days_c0001/data_fam')) ?>',
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
                <option selected>SELECT FAMILY</option>
                <option value="all_fam" >ALL FAMILY</option>`

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
            url: '<?= htmlentities(base_url('Parameter/Stock_Days_c0001/data_sub_fam')) ?>',
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
                
                html_sub_fam = `<select class="form-select basic-single-sub_fam" id="select_sub_fam" name="select_sub_fam" aria-label="Default select sub_fam">
                <option selected>SELECT SUB FAMILY</option>`

                if (JSON.parse(response.res) != '') {
                    html_sub_fam += `<option value="all_subfam">ALL SUB FAMILY</option>`;
                }
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


    $(document).ready(function() {
        default_dt();
    });

    function default_dt() {
        $('#iframeManage').DataTable({
            "language": {
                searchPlaceholder: "Iframe Name"
            },
            "processing": true,
            "responsive": true,
            "serverSide": true,
            "ordering": false, // Set true agar bisa di sorting

            "ajax": {
                "url": "<?= htmlentities(base_url('Parameter/Manage_Iframe_c/List_query/')) ?>", // URL file untuk proses select datanya
                "type": "POST",
                "data": function(d) {
                    d.<?= htmlentities($this->security->get_csrf_token_name()); ?> = val.<?= htmlentities($this->security->get_csrf_token_name()); ?>;
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
            "columns": [{
                    "data": 'id_iframe',
                    "sortable": false, // !!! id_sort
                    render: function(data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    }
                },
                {
                    "data": "iframe_name"
                },
                // {
                //     "data": "iframe_tag"
                // },
                {
                    data: null,
                    "ordering": false,
                    "sortable": false,
                    render: function(data, type, row, meta) {
                        return '<button class="btn btn-success m-2" data_modal="modal_edit" onclick="modal_edit()" value="' + data.id_iframe + '">Edit</button><button class="btn btn-danger ms-3" onclick="delete_modal()" value="' + data.id_iframe + '">Delete</button>';
                    }
                }
            ],
        });
    }

    function close_modal(data_) {
        action = $(data_).attr('data');
        $("#" + action).remove();
        $(".modal-backdrop").remove();
    }

    function modal_view() {
        val.modal = 'MODAL ADD IFRAME';
        val.id = 'modal_add';

        $.ajax({
            url: '<?= htmlentities(base_url('Parameter/Manage_Iframe_c/modal_add')) ?>',
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
                $(".modal_add").html(res);
                $('#modal_add').modal({
                    backdrop: 'static',
                    keyboard: false
                });
                $('#modal_add').modal('show');
            },
            error: function(jqXHR, textStatus, errorThrown) {
                alert('gagal');
            }
        });
    };

    function modal_add_fam_stock_day() {
        val.modal = 'ADD STOCK DAYS FAMILY';
        val.id = 'modal_add_fam_stock_day';
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

        if(validate){
            swal.fire('', msg, 'info')
        }else{
            $.ajax({
                url: '<?= htmlentities(base_url('Parameter/Stock_Days_c0001/modal_add_fam_stock_day')) ?>',
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
                    $(".modal_add_fam_stock_day").html(res);
                    $('#modal_add_fam_stock_day').modal({
                        backdrop: 'static',
                        keyboard: false
                    });
                    $('#modal_add_fam_stock_day').modal('show');
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    alert('gagal');
                }
            });
        }

    };

    function modal_add_sub_fam_stock_day(){
        val.modal = 'ADD STOCK DAYS SUB FAMILY';
        val.id = 'modal_add_subfam_stock_day';
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
        if (val.fam == "SELECT FAMILY" || val.fam == "all_subfam") {
            validate = true
            msg += 'Harap isi field Family </br>'
        }
        if (val.sub_fam == "SELECT SUB FAMILY") {
            validate = true
            msg += 'Harap isi field sub Family </br>'
        }

        if(validate){
            swal.fire('', msg, 'info')
        }else{
            $.ajax({
                url: '<?= htmlentities(base_url('Parameter/Stock_Days_c0001/modal_add_subfam_stock_day')) ?>',
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
                    $(".modal_add_subfam_stock_day").html(res);
                    $('#modal_add_subfam_stock_day').modal({
                        backdrop: 'static',
                        keyboard: false
                    });
                    $('#modal_add_subfam_stock_day').modal('show');
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    alert('gagal');
                }
            });
        }
    }

    function modal_edit_fam_stock_day(){
        val.modal = 'EDIT STOCK DAYS FAMILY';
        val.id = 'modal_edit_fam_stock_day';
        idSdFam = event.target.value
        splitidSdFam = idSdFam.split("_")
        val.str_code = splitidSdFam[0]
        val.famcode = splitidSdFam[1]

        $.ajax({
            url: '<?= htmlentities(base_url('Parameter/Stock_Days_c0001/modal_edit_fam_stock_day')) ?>',
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
                $(".modal_edit_fam_stock_day").html(res);
                $('#modal_edit_fam_stock_day').modal({
                    backdrop: 'static',
                    keyboard: false
                });
                $('#modal_edit_fam_stock_day').modal('show');
            },
            error: function(jqXHR, textStatus, errorThrown) {
                alert('gagal');
            }
        });

    }

    function modal_edit_subfam_stock_day(){
        val.modal = 'EDIT STOCK DAYS SUB FAMILY';
        val.id = 'modal_edit_subfam_stock_day';
        idSdFam = event.target.value
        splitidSdFam = idSdFam.split("_")
        val.str_code = splitidSdFam[0]
        val.subfamcode = splitidSdFam[1]

        $.ajax({
            url: '<?= htmlentities(base_url('Parameter/Stock_Days_c0001/modal_edit_subfam_stock_day')) ?>',
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
                $(".modal_edit_subfam_stock_day").html(res);
                $('#modal_edit_subfam_stock_day').modal({
                    backdrop: 'static',
                    keyboard: false
                });
                $('#modal_edit_subfam_stock_day').modal('show');
            },
            error: function(jqXHR, textStatus, errorThrown) {
                alert('gagal');
            }
        });
    }

    function modal_edit() {
        val.id_iframe = event.target.value
        val.modal = 'MODAL EDIT USER';
        val.id = 'modal_edit';

        $.ajax({
            url: '<?= htmlentities(base_url('Parameter/Manage_Iframe_c/modal_edit')) ?>',
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
                $(".modal_edit").html(res);
                $('#modal_edit').modal({
                    backdrop: 'static',
                    keyboard: false
                });
                $('#modal_edit').modal('show');
            },
            error: function(jqXHR, textStatus, errorThrown) {
                alert('gagal');
            }
        });
    };

    function delete_modal() {
        val.id_iframe = event.target.value
        val.modal = 'MODAL DELETE USER';
        val.id = 'modal_delete';
        $.ajax({
            url: '<?= htmlentities(base_url('Parameter/Manage_Iframe_c/modal_delete')) ?>',
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
                $(".modal_delete").html(res);
                $('#modal_delete').modal({
                    backdrop: 'static',
                    keyboard: false
                });
                $('#modal_delete').modal('show');
            },
            error: function(jqXHR, textStatus, errorThrown) {
                alert('gagal');
            }
        });
    }

    function delete_modal_fam_stock_day(){
        val.modal = 'EDIT STOCK DAYS FAMILY';
        val.id = 'delete_modal_fam_stock_day';
        idSdFam = event.target.value
        splitidSdFam = idSdFam.split("_")
        val.str_code = splitidSdFam[0]
        val.famcode = splitidSdFam[1]

        $.ajax({
            url: '<?= htmlentities(base_url('Parameter/Stock_Days_c0001/delete_modal_fam_stock_day')) ?>',
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
                $(".delete_modal_fam_stock_day").html(res);
                $('#delete_modal_fam_stock_day').modal({
                    backdrop: 'static',
                    keyboard: false
                });
                $('#delete_modal_fam_stock_day').modal('show');
            },
            error: function(jqXHR, textStatus, errorThrown) {
                alert('gagal');
            }
        });
    }

    function delete_modal_subfam_stock_day(){
        val.modal = 'EDIT STOCK DAYS SUB FAMILY';
        val.id = 'delete_modal_subfam_stock_day';
        idSdFam = event.target.value
        splitidSdFam = idSdFam.split("_")
        val.str_code = splitidSdFam[0]
        val.subfamcode = splitidSdFam[1]

        $.ajax({
            url: '<?= htmlentities(base_url('Parameter/Stock_Days_c0001/delete_modal_subfam_stock_day')) ?>',
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
                $(".delete_modal_subfam_stock_day").html(res);
                $('#delete_modal_subfam_stock_day').modal({
                    backdrop: 'static',
                    keyboard: false
                });
                $('#delete_modal_subfam_stock_day').modal('show');
            },
            error: function(jqXHR, textStatus, errorThrown) {
                alert('gagal');
            }
        });
    }

    function modal_upload_fam_stock_day(){
        val.modal = 'UPLOAD STOCK DAYS FAMILY';
        val.id = 'modal_upload_fam_stock_day';
        val.store = $("#select_store option:selected").val()
        val.biu = $("#select_biu option:selected").val()
        val.dept = $("#select_dept option:selected").val()
        val.cat = $("#select_cat option:selected").val()
        val.fam = $("#select_fam option:selected").val()

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

        if(validate){
            swal.fire('', msg, 'info')
        }else{
            $.ajax({
                url: '<?= base_url('Parameter/Stock_Days_c0001/modal_upload_fam_stock_day/') ?>',
                type: "post",
                data: val,
                beforeSend: function() {
                    $('#loader').show();
                },
                complete: function() {
                    $('#loader').hide();
                },
                success: function(res) {
                    $(".modal_upload_fam_stock_day").html(res);
                    $('#modal_upload_fam_stock_day').modal({
                        backdrop: 'static',
                        keyboard: false
                    });
                    $('#modal_upload_fam_stock_day').modal('show');
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    alert('gagal');
                }
            });
        }
    }

    function modal_upload_subfam_stock_day(){
        val.modal = 'UPLOAD STOCK DAYS SUB FAMILY';
        val.id = 'modal_upload_subfam_stock_day';
        val.store = $("#select_store option:selected").val()
        val.biu = $("#select_biu option:selected").val()
        val.dept = $("#select_dept option:selected").val()
        val.cat = $("#select_cat option:selected").val()
        val.fam = $("#select_fam option:selected").val()
        val.subfam = $("#select_sub_fam option:selected").val()
        
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

        if(validate){
            swal.fire('', msg, 'info')
        }else{
            $.ajax({
                url: '<?= base_url('Parameter/Stock_Days_c0001/modal_upload_subfam_stock_day/') ?>',
                type: "post",
                data: val,
                beforeSend: function() {
                    $('#loader').show();
                },
                complete: function() {
                    $('#loader').hide();
                },
                success: function(res) {
                    $(".modal_upload_subfam_stock_day").html(res);
                    $('#modal_upload_subfam_stock_day').modal({
                        backdrop: 'static',
                        keyboard: false
                    });
                    $('#modal_upload_subfam_stock_day').modal('show');
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    alert('gagal');
                }
            });
        }
    }

    // function validated(val){
    //     msg = ''
    //     validate = false
    //     if (val.store == "SELECT STORE") {
    //         validate = true
    //         msg += 'Harap isi field Store </br>'
    //     }
    //     if (val.biu == "SELECT BUSINESS UNIT") {
    //         validate = true
    //         msg += 'Harap isi field Business Unit </br>'
    //     }
    //     if (val.dept == "SELECT DEPARTMENT") {
    //         validate = true
    //         msg += 'Harap isi field Department </br>'
    //     }
    //     if (val.cat == "SELECT CATEGORY") {
    //         validate = true
    //         msg += 'Harap isi field Category </br>'
    //     }
    //     if (val.fam == "SELECT FAMILY") {
    //         validate = true
    //         msg += 'Harap isi field Family </br>'
    //     }
    //     return {
    //         'validate' : validate,
    //         'msg':msg
    //     }
    // }
</script>