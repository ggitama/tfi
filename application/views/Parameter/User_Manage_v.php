<div class="body flex-grow-1 p-4 ms-5 me-5" style="background-color: #ffff;">


    <div class="row">
        <div class="col-md-4">
            <button class="btn btn-sm btn-primary" value="" data_modal="modal_add" onclick="modal_view()"> ADD USER</button>
        </div>
    </div>
    <h2 class="text-center">USER</h2>
    <table id="userManage" class="table table-bordered table-striped text-center align-middle" width="100%">

        <thead>
            <tr class="text-center">
                <th>No</th>
                <th>Username</th>
                <th>Nama</th>
                <th>Role</th>
                <th>LDAP</th>
                <th>Last Login</th>
                <th>Action</th>
            </tr>
        </thead>

    </table>

</div>

<div class="modal_add"></div>

<div class="modal_edit"></div>
<div class="modal_delete"></div>


<script>
    $(document).ready(function() {
        default_dt();
        var table;
    });

    function default_dt() {
        table = $('#userManage').DataTable({
            "language": {
                searchPlaceholder: "Username"
            },
            "processing": true,
            "responsive": true,
            "serverSide": true,
            "ordering": false, // Set true agar bisa di sorting
            "order": [
                [0, 'asc']
            ], // Default sortingnya berdasarkan kolom / field ke 0 (paling pertama)
            "ajax": {
                "url": "<?= htmlentities(base_url('Parameter/User_Manage_c/List_query/')) ?>", // URL file untuk proses select datanya
                "type": "POST",
                // "data": val,
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
                [10, 50, -1],
                [10, 50, 'All']
            ], // Combobox Limit
            "columns": [{
                    "data": 'username',
                    "sortable": false, // !!! id_sort
                    render: function(data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    }
                },
                {
                    "data": "username"
                },
                {
                    "data": "nama"
                },
                {
                    "data": "role_name"
                },
                {
                    "data": "ldap"
                },
                {
                    "data": "last_login"
                },
                {
                    data: null,
                    "sortable": false,
                    render: function(data, type, row, meta) {
                        
                        return `<button class="btn btn-success m-2" data_modal="modal_edit" onclick="modal_edit()" value="${data.username}">Edit</button><button class="btn btn-danger ms-3" onclick="delete_modal()" value="${data.username}">Delete</button>`;
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
        val.modal = 'MODAL ADD USER';
        val.id = 'modal_add';

        $.ajax({
            url: '<?= htmlentities(base_url('Parameter/User_Manage_c/modal_add')) ?>',
            type: "post",
            data: val,
            beforeSend: function() {
                $('#loader').show();
            },
            complete: function() {
                $('#loader').hide();
            },
            success: function(res) {
                
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
        val.username = event.target.value
        
        val.modal = 'MODAL EDIT USER';
        val.id = 'modal_edit';

        $.ajax({
            url: '<?= htmlentities(base_url('Parameter/User_Manage_c/modal_edit')) ?>',
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
        val.username = event.target.value
        val.modal = 'MODAL DELETE USER';
        val.id = 'modal_delete';
        $.ajax({
            url: '<?= htmlentities(base_url('Parameter/User_Manage_c/modal_delete')) ?>',
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