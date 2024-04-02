<div class="modal fade" id="<?= $id ?>">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <form id="form_<?= $id ?>" method="post" enctype='multipart/form-data'>
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel"><?= $modal_title ?></h5>

          <a href="<?= base_url() ?>Parameter/Stock_Qty/export_to_excel_stock_qty?store=<?=$store?>&biu=<?=$biu?>&dept=<?=$dept?>&cat=<?=$cat?>&fam=<?=$fam?>&subfam=<?=$sub_fam?>" target="_blank">
          <button type="button" class="btn btn-success ms-5">Template Excel Stock Qty Item</button>
        </a>

          <button type="button" data="<?= $id ?>" onclick="close_modal(this)" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">


          <div class="mb-3 row">
            <div class="col-sm-7 form-group">
              <input type="file" id="input-media_filesitem" name="file" class="form-control">
            </div>
            <div class="col-sm-2 form-group">
              <button type="button" id="<?= $id ?>" onclick="upload_media(this)" data="<?= $id ?>" class="btn btn-success">Upload</button>
            </div>
          </div>


        </div>

      </form>
    </div>
  </div>


<script>
     $(document).ready(function() {
        val.<?= htmlentities($this->security->get_csrf_token_name()); ?> = "<?= htmlentities($token) ?>"
    })

  function upload_media(datas) {
    id_data = datas.id
    store = '<?= $store?>'
    biu = '<?= $biu?>'
    dept = '<?= $dept?>'
    cat = '<?= $cat?>'
    fam = '<?= $fam?>'
    subfam = '<?= $sub_fam?>'

    var file_data = $('#input-media_filesitem').prop('files')[0];
    var form_data = new FormData();
    form_data.append('file', file_data);
    form_data.append('id_media', id_data);
    form_data.append('store', store);
    form_data.append('biu', biu);
    form_data.append('dept', dept);
    form_data.append('cat', cat);
    form_data.append('fam', fam);
    form_data.append('subfam', subfam);
    form_data.append('<?= htmlentities($this->security->get_csrf_token_name()); ?>', "<?= htmlentities($token) ?>");


    if (typeof file_data == 'undefined') {
      swal.fire('', 'Harap Masukan File', 'warning')
    } else {
      Swal.fire({
        title: 'Are you sure?',
        text: "",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: "Yes",
        cancelButtonText: "Cancel",
        closeOnConfirm: false,
        closeOnCancel: false,
        reverseButtons: true
      }).then((result) => {
        if (result.isConfirmed) {
          $.ajax({
            url: '<?= base_url('Parameter/Stock_Qty/upload_item_stock_qty/') ?>', // <-- point to server-side PHP script 

            dataType: 'text', // <-- what to expect back from the PHP script, if anything
            cache: false,
            contentType: false,
            processData: false,
            data: form_data,
            type: 'post',
            beforeSend: function() {
              $('#loader').show();
            },
            complete: function() {
              $('#loader').hide();
            },
            success: function(res) {
                response = JSON.parse(res)
                val.<?= htmlentities($this->security->get_csrf_token_name()); ?> = response.token;
            //   $('#modal_upload_subfam_stock_day').modal('toggle');

              Swal.fire({
                title: "",
                text: response.msg,
                icon: 'info'
              }).then((result) => {

                $("#modal_upload_item_stock_qty").modal('hide');
                $("#modal_upload_item_stock_qty").remove();
                // location.reload();
                $('#itemstockqty').DataTable().ajax.reload();
                // dataTableFmcg(1)
                // $('#modal_upload_subfam_stock_day').modal('hide');
                // $("#modal_upload_subfam_stock_day").remove();
                // $(".modal_upload_subfam_stock_day").remove();
              });
              // swal.fire('', php_script_response, 'info')
            }
          });
        }
        // else{
        //     alert('gagal');
        // }
      })

    }

  };
</script>
</div>