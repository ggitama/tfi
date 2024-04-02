<div class="body flex-grow-1 p-4 ms-5 me-5" style="background-color: #ffff;">

    <div class="row">
        <div class="col-md-4">
            <button class="btn btn-sm btn-primary" value="" data_modal="modal_add" onclick="modal_view()">ADD ROLE IFRAME</button>
        </div>
    </div>
    <h2 class="text-center">Role Iframe</h2>
    <table id="roleManageIframe" class="table table-bordered table-striped text-center align-middle" width="100%">

        <thead>
            <tr class="text-center">
                <!-- <th>Role Name</th> -->
                <th>Menu Name</th>
                <th>Iframe Name</th>
                <th>Action</th>
            </tr>
        </thead>

    </table>

    <!-- </div> -->

</div>

<div class="modal_add"></div>
<div class="modal_edit"></div>
<div class="modal_delete"></div>

<script>
    $(document).ready(function() {
        default_dt();
    });

    function default_dt() {
        $('#roleManageIframe').DataTable({
            "language": {
                searchPlaceholder: "Iframe Name"
            },
            "processing": true,
            "responsive": true,
            "serverSide": true,
            "ordering": false, // Set true agar bisa di sorting
            "order": [
                [0, 'asc']
            ], // Default sortingnya berdasarkan kolom / field ke 0 (paling pertama)
            "ajax": {
                "url": "<?= htmlentities(base_url('Parameter/Manage_Role_Iframe_c/view_query/')) ?>", // URL file untuk proses select datanya
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
            "columns": [
                // {
                //     "data": "role_name"
                // },
                {
                    "data": "menu_name"
                },
                {
                    "data": "iframe_name"
                },
                {
                    data: null,
                    "sortable": false,
                    render: function(data, type, row, meta) {
                        return '<button class="btn btn-success m-2" data_modal="modal_edit" onclick="edit_modal()" value="' + data.id_menu + '">Edit</button><button class="btn btn-danger ms-3" onclick="delete_modal()" value="' + data.id_menu + '">Delete</button>';
                    }
                },
            ],
        });
    }

    function close_modal(data_) {
        action = $(data_).attr('data');
        $("#" + action).remove();
        $(".modal-backdrop").remove();
    }

    function modal_view() {
        val.modal = 'MODAL ADD ROLE USER';
        val.id = 'modal_add';
        $.ajax({
            url: '<?= htmlentities(base_url('Parameter/Manage_Role_Iframe_c/modal_add/')) ?>',
            type: "post",
            data: val,
            beforeSend: function() {
                $("#loader").show();
            },
            complete: function() {
                $("#loader").hide();
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
    }

    function edit_modal() {
        val.modal = 'MODAL EDIT ROLE IFRAME';
        val.id = 'modal_edit';
        val.id_menu = event.target.value;

        $.ajax({
            url: '<?= htmlentities(base_url('Parameter/Manage_Role_Iframe_c/modal_edit/')) ?>',
            type: "post",
            data: val,
            beforeSend: function() {
                $("#loader").show();
            },
            complete: function() {
                $("#loader").hide();
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
        val.modal = 'MODAL DELETE ROLE USER';
        val.id = 'modal_delete';
        val.id_menu = event.target.value;
        $.ajax({
            url: '<?= htmlentities(base_url('Parameter/Manage_Role_Iframe_c/modal_delete/')) ?>',
            type: "post",
            data: val,
            beforeSend: function() {
                $("#loader").show();
            },
            complete: function() {
                $("#loader").hide();
            },
            success: function(res) {
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
    };

    // function add_role_user(data_) {
    //     // action = $(data_).attr('data');
    //     action = event.target.attributes.data.value
    //     $('#error').html(" ");
    //     form = $('#form_' + action).serialize() + '&<?= $this->security->get_csrf_token_name(); ?>=' + val.<?= $this->security->get_csrf_token_name(); ?>;
    
    //     if (action == 'modal_delete') {
    //         delete_(form);
    //     } else {
    //         $.ajax({
    //             type: "POST",
    //             url: "<?php echo site_url('Parameter/Manage_Role_Menu_c/validate/'); ?>",
    //             data: form,
    //             dataType: "json",
    //             beforeSend: function() {
    //                 $("#loader").show();
    //             },
    //             complete: function() {
    //                 $("#loader").hide();
    //             },
    //             success: function(data) {
    //                 val.<?= htmlentities($this->security->get_csrf_token_name()); ?> = data.token;
    //                 form = $('#form_' + action).serialize() + '&<?= $this->security->get_csrf_token_name(); ?>=' + data.token;
    //                 if (data.action == 'ok') {
    //                     add_(form);
    //                 } else {
    //                     $.each(data, function(key, value) {
    //                         if (value == '') {
    //                             $('#input-' + key).removeClass('is-invalid');
    //                             $('#input-' + key).addClass('is-valid');
    //                             $('#input-' + key).parents('.form-group').find('#error').html(value);
    //                         } else {
    //                             $('#input-' + key).addClass('is-invalid');
    //                             $('#input-' + key).parents('.form-group').find('#error').html(value);
    //                         }
    //                     });
    //                 }
    //             }
    //         });
    //     }
    // }

    // function delete_(form) {
    //     Swal.fire({
    //         title: 'Are you sure?',
    //         text: "Menghapus data ini ?",
    //         icon: 'warning',
    //         showCancelButton: true,
    //         confirmButtonColor: '#3085d6',
    //         cancelButtonColor: '#d33',
    //         confirmButtonText: 'Yes, delete it!'
    //     }).then((result) => {
    //         if (result.isConfirmed) {
    //             $.ajax({
    //                 url: '<?= htmlentities(base_url('Parameter/Manage_Role_Menu_c/delete_/')) ?>',
    //                 type: "post",
    //                 data: form,
    //                 beforeSend: function() {
    //                     $("#loader").show();
    //                 },
    //                 complete: function() {
    //                     $("#loader").hide();
    //                 },
    //                 success: function(res) {
    //                     if (res == 'Berhasil di Hapus') {

    //                         Swal.fire({
    //                             title: "",
    //                             text: "Your role has been deleted!",
    //                             icon: 'success'
    //                         }).then((result) => {
    //                             // Reload the Page
    //                             location.reload();
    //                         });

    //                     } else {
    //                         alert(res);
    //                     }
    //                 },
    //                 error: function(jqXHR, textStatus, errorThrown) {
    //                     alert('gagal');
    //                 }
    //             });
    //         }
    //         // else{
    //         //     alert('gagal');
    //         // }
    //     })

    // }

    // function save() {
    //     form = $("#task").serialize();
    //     Swal.fire({
    //         title: 'Do you want to save the changes?',
    //         showDenyButton: true,
    //         confirmButtonText: 'Save',
    //         denyButtonText: Cancel,
    //     }).then((result) => {
    //         if (result.isConfirmed) {
    //             $.ajax({
    //                 url: '<?= htmlentities(base_url('Parameter/Manage_Role_Menu_c/update_role_user/')) ?>',
    //                 type: "post",
    //                 data: form,
    //                 beforeSend: function() {
    //                     $("#loader").show();
    //                 },
    //                 complete: function() {
    //                     $("#loader").hide();
    //                 },
    //                 success: function(res) {
    //                     if (res == 'Berhasil di Simpan') {
    //                         $('#modal_edit').modal('hide');
    //                         Swal.fire({
    //                             title: "",
    //                             text: "Your has been updated!",
    //                             icon: 'success'
    //                         }).then((result) => {
    //                             // Reload the Page
    //                             location.reload();
    //                         });

    //                     } else {
    //                         alert(res)
    //                     }
    //                 },
    //                 error: function(jqXHR, textStatus, errorThrown) {
    //                     alert('gagal');
    //                 }
    //             });
    //         }
    //     })
    // }

    // function add_(form) {
    //     Swal.fire({
    //         title: 'Do you want to save the changes?',
    //         showDenyButton: true,
    //         showCancelButton: false,
    //         confirmButtonText: 'Save',
    //         denyButtonText: Cancel,
    //     }).then((result) => {
    //         /* Read more about isConfirmed, isDenied below */
    //         if (result.isConfirmed) {
    //             $.ajax({
    //                 url: '<?= htmlentities(base_url('Parameter/Manage_Role_Menu_c/save_/')) ?>',
    //                 type: "post",
    //                 data: form,
    //                 beforeSend: function() {
    //                     $("#loader").show();
    //                 },
    //                 complete: function() {
    //                     $("#loader").hide();
    //                 },
    //                 success: function(res) {
    //                     if (res == 'Berhasil di Simpan') {
    //                         Swal.fire({
    //                             title: 'Your has been saved.',
    //                             icon: 'success',
    //                             timer: 2000
    //                         });
    //                         setTimeout(function() {
    //                             location.reload(true);
    //                         }, 1000);
    //                     } else {
    //                         alert(res);
    //                     }
    //                 },
    //                 error: function(jqXHR, textStatus, errorThrown) {
    //                     alert('gagal');
    //                 }
    //             });

    //         } else if (result.isDenied) {
    //             Swal.fire('Changes are not saved', '', 'info')
    //         }
    //     })

    // }

    // function key(tes) {
    //     names = $(tes).attr('name');
    //     term = $("input[type=text][name=" + names + "]").val();
    //     val = {};
    //     val[names] = term;

    //     $.ajax({
    //         type: "POST",
    //         url: "<?php echo site_url('Parameter/Manage_Role_Menu_c/validate_keyup/'); ?>",
    //         data: val,
    //         dataType: "json",
    //         success: function(data) {
    //             $.each(data, function(key, value) {
    //                 if (value == '') {
    //                     $('#input-' + key).removeClass('is-invalid');
    //                     $('#input-' + key).addClass('is-valid');
    //                     $('#input-' + key).parents('.form-group').find('#error').html(value);
    //                 } else {
    //                     $('#input-' + key).addClass('is-invalid');
    //                     $('#input-' + key).parents('.form-group').find('#error').html(value);
    //                 }

    //             });
    //         }
    //     });

    // }
</script>