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
            <label class="col-sm-5 col-form-label">Role Name</label>
            <div class="col-sm-7 form-group">
              <input type="text" onkeypress="return event.keyCode != 13;" id="input-role_name" onkeyup="key(this)" value="" name="role_name" class="form-control">
              <div id="error"></div>
            </div>
          </div>


        </div>
        <div class="modal-footer">
          <button type="button" data="<?= htmlentities($id) ?>" onclick="close_modal(this)" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="button" data="<?= htmlentities($id) ?>" onclick="add_role_user(this)" class="btn btn-primary">SAVE CHANGES</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
  $(document).ready(function() {
    val.<?= htmlentities($this->security->get_csrf_token_name()); ?> = "<?= htmlentities($token) ?>"
  })
</script>