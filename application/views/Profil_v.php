<div class="body flex-grow-1 p-4 ms-5 me-5" style="background-color: #ffff;">

    <div class="row">
        <!-- <form action="<?= htmlentities(base_url('Profil_c/')) ?>" class="my-form" method="post"></form> -->
        <form action="" class="my-form" method="post">
            <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
            <div class="col-md-12">
                <div class="mb-3">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" class="form-control" id="username" value="<?= htmlentities($user->username) ?>" disabled>
                </div>
                <div class="mb-3">
                    <label for="nama" class="form-label">Nama</label>
                    <input type="text" class="form-control" id="nama" value="<?= htmlentities($user->nama) ?>" disabled>
                </div>
                <div class="mb-3">
                    <label for="role" class="form-label">Role</label>
                    <input type="text" class="form-control" id="role" value="<?= htmlentities($user->role_name) ?>" disabled>
                </div>
                <div class="mb-3 ldaps">
                    <label for="ldap" class="form-label">LDAP</label>
                    <input type="text" class="form-control" id="ldap" value="<?= htmlentities($user->ldap) ?>" disabled>
                </div>
                <?php if ($user->ldap == 'No') { ?>
                    <div class="mb-3">
                        <label for="password_old" class="form-label">Password Old</label>
                        <input type="password" class="form-control" name="password_old" id="password_old" value="">
                    </div>
                    <div class="mb-3">
                        <label for="password_new" class="form-label">Password New</label>
                        <input type="password" class="form-control" name="password_new" id="password_new" value="">
                    </div>
                    <div class="mb-3">
                        <label for="password_confirm" class="form-label">Password Confirm</label>
                        <input type="password" class="form-control" name="password_confirm" id="password_confirm" value="">
                    </div>
                    <!-- <input type="submit" data="<?= htmlentities($user->username) ?>" value="Change Password" id="my-input" class="btn btn-primary"> -->
                    <input type="button" value="Change Password" onclick="validate_()" id="my-input" class="btn text-white btn-block btn-danger">
                <?php } ?>
            </div>
        </form>

    </div>

</div>

<script>
    // $(".my-form").submit(function(event) {
    function validate_() {
        // alert('tes')
        // val.username = event.target.attributes.data.value
        // val.username = $('#username').val()
        val.password_old = $('#password_old').val()
        val.password_new = $('#password_new').val()
        val.password_confirm = $('#password_confirm').val()
        // alert(val.password_old);
        // form_serialize = $( this ).serialize()
        validations = validation_input(val)
        event.preventDefault();



        if (validations.validate) {
            Swal.fire('', validations.msg, 'info')
        } else {
           
            $.ajax({
                url: '<?= htmlentities(base_url('Profil_c/validations/')) ?>',
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
                    if (response.res == 'oke') {
                        action_change_password(val)
                        // Swal.fire({
                        //     title: "",
                        //     text: 'User saved successfully',
                        //     icon: 'success'
                        // }).then((result) => {
                        //     // Reload the Page
                        //     location.reload();
                        // });
                    } else {
                        swal.fire("", response.res, "info")
                    }

                    // if (res == 'oke') {
                    //     action_change_password(val)
                    // } else {
                    //     swal.fire('', res, 'info')
                    // }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    alert('gagal');
                }
            });

        }
    };
    // });

    // function change_password() {
    //   
    //     val.username = event.target.attributes.data.value
    //     val.password_old = $('#password_old').val()
    //     val.password_new = $('#password_new').val()
    //     val.password_confirm = $('#password_confirm').val()

    //     validations = validation_input(val)



    //     if (validations.validate) {
    //         Swal.fire('', validations.msg, 'info')
    //     } else {
    //         $.ajax({
    //             url: '<?= htmlentities(base_url('Profil_c/validations/')) ?>',
    //             type: "post",
    //             data: val,
    //             beforeSend: function() {
    //                 $("#loader").show();
    //             },
    //             complete: function() {
    //                 $("#loader").hide();
    //             },
    //             success: function(res) {
    //                 if (res == 'oke') {
    //                     action_change_password(val)
    //                 } else {
    //                     swal.fire('', res, 'info')
    //                 }
    //             },
    //             error: function(jqXHR, textStatus, errorThrown) {
    //                 alert('gagal');
    //             }
    //         });

    //     }

    // }

    function validation_input(val) {
        msg = ''
        validate = false

        if (val.password_old == '') {
            msg += 'Please enter old password </br>'
            validate = true
        }
        if (val.password_new == '') {
            msg += 'Please enter new password </br>'
            validate = true
        }
        if (val.password_confirm == '') {
            msg += 'Please enter Confirm password </br>'
            validate = true
        }
        // regex_password = valid_password(val.password_old)
        regex_password_confirm = valid_password(val.password_new)
        if (regex_password_confirm['validates']) {
            validate = true
            msg += 'Password Minimum eight characters, at least one uppercase letter, one lowercase letter, one number and one special character ! </br>'
        }

        return {
            'msg': msg,
            'validate': validate
        }
    }

    function action_change_password(val) {
        Swal.fire({
            title: 'Are you sure change password?',
            text: "",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, Change it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '<?= htmlentities(base_url('Profil_c/change_password/')) ?>',
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
                        if (response.res == 'oke') {
                            Swal.fire({
                                title: "",
                                text: 'User saved successfully',
                                icon: 'success'
                            }).then((result) => {
                                // Reload the Page
                                location.reload();
                            });
                        } else {
                            swal.fire("", response.res, "info")
                        }
                        // if (res == 'oke') {
                        //     Swal.fire({
                        //         title: "",
                        //         text: "Password successfully changed!",
                        //         icon: 'success'
                        //     }).then((result) => {
                        //         // Reload the Page
                        //         location.reload();
                        //     });

                        // } else {
                        //     alert('failed');
                        // }
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        alert('gagal');
                    }
                });
            }
        })
    }
</script>