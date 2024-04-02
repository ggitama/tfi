<!-- Modal Edit-->

<div class="modal fade" id="modal_delete" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <form id="task2">
                <input type="hidden" name="role_id" value="<?= htmlentities( $role_id )?>">
                <div class="modal-header bg-danger">
                    <h5 class="modal-title text-white" id="exampleModalLabel">Update Role Access Menu</h5>
                    <button type="button" data="<?= htmlentities( $id )?>" class="btn-close" onclick="close_modal(this)" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-2">
                            <h5>Level user :</h5>
                        </div>
                        <div class="col-md-5">
                            <h5><?= htmlentities( $role_name )?></h5>
                        </div>
                        <!-- <div class="col-md-2">
                            <h5>Parameter :</h5>
                        </div>
                        <div class="col-md-3">
                            <select class="col-sm-7 form-select example-basic-single" id="menu" name="menu" aria-label="Default select example">
                                <option selected></option>
                                <?php foreach ($level_detail as $rw) : ?>
                                    <option value="<?= htmlentities( $rw['menu_name'] )?>"><?= htmlentities( $rw['menu_name'] )?></option>
                                <?php endforeach; ?>
                            </select>
                            <h5><?= htmlentities( $role_name )?></h5>
                        </div> -->
                    </div>
                    <table class="table table-bordered table-striped align-middle">
                        <tbody>
                            <tr class="text-center">
                                <th scope="row">Menu Name</th>
                                <th> VIEW</th>
                                <!-- <th>ADD</th>
                                <th>EDIT</th>
                                <th>DELETE</th> -->
                            </tr>

                            <tr class="text-center">
                                <th class="text-start">Parent Menu</th>
                                <th colspan="4">
                                    <input class="form-check-input" type="checkbox" value="" id="selectParent" disabled> Select All
                                </th>
                            </tr>

                            <?php foreach ($level_detail2 as $row3) : ?>
                                <?php if ($row3['id_role'] == $role_id) : ?>
                                    <tr class="text-center">

                                        <td class="text-start"><?= htmlentities( $row3['menu_name'] )?></td>
                                        <td>
                                            <input class="form-check-input parentss" type="checkbox" id="<?= htmlentities( $row3['id_menu'])?>"
                                             name="view[]" value="<?= htmlentities($role_id) . '-' . htmlentities($row3['id_menu']) . '-' . '1' ?>" 
                                             id="flexCheckDefault" <?=  (htmlentities($row3['id_role'])) == htmlentities($role_id) && htmlentities($row3['view']) == 1 ? 'checked' : ''; ?> disabled>
                                             view
                                        </td>

                                    </tr>
                                <?php endif; ?>
                            <?php endforeach; ?>


                            <?php
                            foreach ($level_detail3 as $row4) {
                            ?>
                                <?php foreach ($row4 as $row5) { ?>
                                    <tr class="text-center">
                                        <th class="text-start"><?= htmlentities( $row5['menu_name'] )?></th>
                                        <th colspan="4">
                                            <input class="form-check-input" type="checkbox" value="" id="selectAll<?= htmlentities( $row5['id_menu'] )?>" disabled> Select All
                                        </th>
                                    </tr>


                                    <?php foreach ($level_detail4 as $row6) : ?>
                                        <?php foreach ($row6 as $row7) : ?>
                                            <?php if ($row7['parent'] ==  $row5['id_menu'] && $row7['id_role'] == $role_id) : ?>
                                                <tr class="text-center">
                                                    <td class="text-start"><?= htmlentities( $row7['menu_name'] )?></td>
                                                    <td>
                                                        <input class="form-check-input child<?= htmlentities( $row5['id_menu'] )?>"
                                                         type="checkbox" name="view[]" value="<?= htmlentities( $role_id) . '-' . htmlentities($row7['id_menu']) . '-' . '1' ?>" 
                                                         id="" <?= (htmlentities($row7['id_role']) == htmlentities($role_id) && htmlentities($row7['view']) == 1) ? 'checked' : ''; ?> disabled> view
                                                    </td>
                                                </tr>
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                    <?php endforeach; ?>
                                <?php } ?>
                            <?php } ?>


                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" data="<?= htmlentities( $id )?>" class="btn btn-secondary" onclick="close_modal(this)">Close</button>
                    <button onclick="delete_()" type="button" class="btn btn-primary">Delete</button>
                </div>
            </form>
        </div>

    </div>
</div>

<script>
    $(document).ready(function() {
        val.<?= htmlentities($this->security->get_csrf_token_name()); ?> = "<?= htmlentities($token) ?>"
    })
    $("#selectParent").click(function() {
        if ($("#selectParent").is(':checked')) {
            $(".parentss").each(function() {
                $(this).prop("checked", true);
            });
        } else {
            $(".parentss").each(function() {
                $(this).prop("checked", false);
            });
        }
    });

    $(".parentss").change(function() {
        var allSelected = true;

        $(".parentss").each(function() {
            if (!$(this).is(":checked")) {
                $("#selectParent").prop('checked', false);
                allSelected = false;
            }
        });

        if (allSelected)
            $("#selectParent").prop('checked', true);
    });


    <?php foreach ($level_detail3 as $row4) { ?>
        <?php foreach ($row4 as $row5) { ?>
            $("#selectAll<?= htmlentities( $row5['id_menu'] )?>").click(function() {
                if ($("#selectAll<?= htmlentities( $row5['id_menu'] )?>").is(':checked')) {
                    $(".child<?= htmlentities( $row5['id_menu'] )?>").each(function() {
                        $(this).prop("checked", true);
                    });
                } else {
                    $(".child<?= htmlentities( $row5['id_menu'] )?>").each(function() {
                        $(this).prop("checked", false);
                    });
                }
            });


            $(".child<?= htmlentities( $row5['id_menu'] )?>").change(function() {
                var allSelected = true;

                $(".child<?= htmlentities( $row5['id_menu'] )?>").each(function() {
                    if (!$(this).is(":checked")) {
                        $("#selectAll<?= htmlentities( $row5['id_menu'] )?>").prop('checked', false);
                        allSelected = false;
                    }
                });

                if (allSelected)
                    $("#selectAll<?= htmlentities( $row5['id_menu'] )?>").prop('checked', true);
            });

        <?php } ?>
    <?php } ?>
</script>