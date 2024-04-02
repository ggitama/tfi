<div class="body flex-grow-1 p-4 ms-5 me-5" style="background-color: #ffff;">

    <div class="row">
        <div class="col-md-4">
            <button class="btn btn-sm btn-primary" value="" data_modal="modal_add" onclick="modal_view()"> ADD IFRAME</button>
        </div>
    </div>
    <h2 class="text-center">IFRAME</h2>
    <table id="iframeManage" class="table table-bordered table-striped text-center align-middle" width="100%">

        <thead>
            <tr class="text-center">
                <th>No</th>
                <th>IFRAME NAME</th>
                <th>IFRAME TYPE</th>
                <th>Action</th>
            </tr>
        </thead>

    </table>

</div>

<div class="modal_add">

</div>

<div class="modal_edit">

</div>
<div class="modal_delete">

</div>


<script>
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
                {
                    "data": "i_name"
                },
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
</script>