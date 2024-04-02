<!-- <div class="body flex-grow-1 px-3">
    <div class="container-lg">
        <div class="row">
            <h1>Manage Menu Appliaction</h1>
        </div>
        <div class="row">
            <div class="col-md-4">
                <button class="btn btn-sm btn-primary" value="" data_modal="modal_add" onclick="modal_view()"> ADD MENU</button>
            </div>
            <table id="manage_menu" class="table table-bordered table-striped text-center align-middle">
                <thead>
                    <tr class="text-center">
                        <th>Menu Name</th>
                        <th>Type</th>
                        <th>Action</th>
                    </tr>
                </thead>
            </table>
        </div>

    </div>
</div> -->

<div class="body flex-grow-1 p-4 ms-5 me-5" style="background-color: #ffff;">

    <div class="row">
        <div class="col-md-4">
            <button class="btn btn-sm btn-primary" value="" data_modal="modal_add" onclick="modal_view()"> ADD MENU</button>
        </div>
    </div>
    <!-- <div class="container-lg"> -->



    <h2 class="text-center">MENU</h2>
    <table id="manage_menu" class="table table-bordered table-striped text-center align-middle" width="100%">

        <thead>
            <tr class="text-center">
                <!-- <th scope="row">No</th> -->
                <th>Menu Name</th>
                <!-- <th>Type</th> -->
                <th>Action</th>
            </tr>
        </thead>

    </table>

    <!-- </div> -->

</div>

<div class="modal_add"></div>
<div class="modal_edit"></div>


<script>
    $(document).ready(function() {
        default_dt();
    });

    function default_dt() {
        $('#manage_menu').DataTable({
            "language": {
                searchPlaceholder: "Menu Name"
            },
            "processing": true,
            "responsive": true,
            "serverSide": true,
            "ordering": false, // Set true agar bisa di sorting
            "order": [
                [0, 'asc']
            ], // Default sortingnya berdasarkan kolom / field ke 0 (paling pertama)
            "ajax": {
                "url": "<?= htmlentities(base_url('Parameter/Menu_c/list_menu/')) ?>", // URL file untuk proses select datanya
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
                [25, 50, 75, 100, -1],
                [25, 50, 75, 100, 'All']
            ], // Combobox Limit
            "columns": [{
                    "data": "menu_name"
                },
                {
                    data: null,
                    "ordering": false,
                    "sortable": false,
                    render: function(data, type, row, meta) {
                        return '<button class="btn btn-success" data_modal="modal_edit" onclick="modal_edit()" value="' + data.id_menu + '">Edit</button>';
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
        val.data_modal = event.target.attributes.data_modal.value
        val.id_menu = event.target.value
        $.ajax({
            url: '<?= htmlentities(base_url('Parameter/Menu_c/Modal_view')) ?>',
            type: 'post',
            data: val,
            success: function(res) {
                $('.' + val.data_modal).html(res);
                $('#' + val.data_modal).modal({
                    backdrop: 'static',
                    keyboard: false
                });
                $('#' + val.data_modal).modal('show');
            },
            error: function(jqXHR, textStatus, errorThrown) {
                alert('gagal');
            }
        })
    }

    function action_edit(data_) {
        action = $(data_).attr('data');
        $('#error').html(" ");
        form = $("#form_" + action).serialize() + '&<?= $this->security->get_csrf_token_name(); ?>=' + val.<?= $this->security->get_csrf_token_name(); ?>;

        if (action == 'modal_edit') {
            edit(form);
        } else if (action == 'modal_add') {
            // alert('ok')
            save(form);
        };
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
                            swal.fire('',response.res,'info')
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



    function modal_edit() {
        val.data_modal = event.target.attributes.data_modal.value
        val.id_menu = event.target.value
        $.ajax({
            url: '<?= htmlentities(base_url('Parameter/Menu_c/Modal_edit')) ?>',
            type: 'post',
            data: val,
            success: function(res) {
                $('.' + val.data_modal).html(res);
                $('#' + val.data_modal).modal({
                    backdrop: 'static',
                    keyboard: false
                });
                $('#' + val.data_modal).modal('show');
            },
            error: function(jqXHR, textStatus, errorThrown) {
                alert('gagal');
            }
        })
    }


    // $('#input-parent').on('change', function() {

    // })
</script>