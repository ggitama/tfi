<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="<?= htmlentities(base_url('Assets/Login_template/'), ENT_QUOTES) ?>fonts/icomoon/style.css">

    <link rel="stylesheet" href="<?= htmlentities(base_url('Assets/Login_template/'), ENT_QUOTES) ?>css/owl.carousel.min.css">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="<?= htmlentities(base_url('Assets/Login_template/'), ENT_QUOTES) ?>css/bootstrap.min.css">

    <!-- Style -->
    <link rel="stylesheet" href="<?= htmlentities(base_url('Assets/Login_template/'), ENT_QUOTES) ?>css/style.css">

    <!-- link sweetalert2 -->
    <link rel="stylesheet" href="<?= htmlentities(base_url('Assets/sweetalert2/dist/sweetalert2.min.css'), ENT_QUOTES) ?>">
    <script src="<?= htmlentities(base_url('Assets/sweetalert2/dist/sweetalert2.min.js'), ENT_QUOTES) ?>"></script>

    <title>Login</title>
</head>

<body>
    <?php echo $this->session->flashdata('msg'); ?>
    <?php
    if (isset($_SESSION['msg'])) {
        unset($_SESSION['msg']);
    }
    ?>


    <div class="content">
        <div class="container">
            <div class="row">
                <div class="col-md-12 contents">
                    <div class="row justify-content-center">
                        <div class="col-md-8">
                            <div class="mb-4 text-center">
                                <h3>Change Password</h3>
                                <!-- <p class="mb-4">Lorem ipsum dolor sit amet elit. Sapiente sit aut eos consectetur adipisicing.</p> -->
                            </div>
                            <form method="post">
                                <input type="hidden" name="username" class="form-control" id="username" value="<?= htmlentities($username) ?>">
                                <div class="form-group first">
                                    <label for="password">Password</label>
                                    <input type="password" name="password" class="form-control" id="password">

                                </div>
                                <div class="form-group last mb-4">
                                    <label for="password_confirm">Password Confirm</label>
                                    <input type="password" name="password_confirm" class="form-control" id="password_confirm">
                                </div>
                                <input type="button" onclick="change_password()" name="enterin" value="Change Password" class="btn text-white btn-block btn-danger">

                            </form>
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </div>


    <script src="<?= htmlentities(base_url('Assets/Login_template/'), ENT_QUOTES) ?>js/jquery-3.3.1.min.js"></script>
    <script src="<?= htmlentities(base_url('Assets/Login_template/'), ENT_QUOTES) ?>js/popper.min.js"></script>
    <script src="<?= htmlentities(base_url('Assets/Login_template/'), ENT_QUOTES) ?>js/bootstrap.min.js"></script>
    <script src="<?= htmlentities(base_url('Assets/Login_template/'), ENT_QUOTES) ?>js/main.js"></script>


    <script>
        val = {}
        val.<?= $this->security->get_csrf_token_name(); ?> = "<?= $this->security->get_csrf_hash(); ?>"
        // setInterval(() => {
        //     var x = document.cookie;
        //     var posname = x.search('csrf_cookie_name');
        //     var hash = x.slice(posname + 17, posname + 49);
        //     val.<?= $this->security->get_csrf_token_name(); ?> = hash

        // }, (1000));

        function change_password() {
            var key = $(this).which;
            if (key == 13) // the enter key code
            {
                $('input[name = enterin]').click();
                return false;
            }
            val.password = $('#password').val()
            val.username = $('#username').val()
            val.password_confirm = $('#password_confirm').val()

            validations = validation_input(val)


            if (validations.validate) {
                Swal.fire('', validations.msg, 'info')
            } else {
                $.ajax({
                    url: '<?= htmlentities(base_url('Change_password_out_c/validations/')) ?>',
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
                        } else {
                            swal.fire('', response.res, 'info')
                        }
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        alert('gagal');
                    }
                });

            }

        }

        function validation_input(val) {
            msg = ''
            validate = false

            if (val.password_old == '') {
                msg += 'Please enter old password </br>'
                validate = true
            }
            if (val.password == '') {
                msg += 'Please enter new password </br>'
                validate = true
            }
            if (val.password_confirm == '') {
                msg += 'Please enter Confirm password </br>'
                validate = true
            }

            regex_password = valid_password(val.password_confirm)

            if (regex_password['validates']) {
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
                        url: '<?= htmlentities(base_url('Change_password_out_c/change_password/')) ?>',
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
                                    text: "Password successfully changed!",
                                    icon: 'success'
                                }).then((result) => {
                                    // Reload the Page
                                    window.location.assign('<?= htmlentities(base_url('Dashboard_performance_c')) ?>');
                                });

                            } else {
                                swal.fire('', response.res, 'info')
                            }
                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                            alert('gagal');
                        }
                    });
                }
            })
        }

        function valid_password(password) {
            validates = true
            msgs = 'Please match the required the username field ! </br>'
            // var pola= new RegExp(/^[a-z A-Z]+$/);
            // var pola = new RegExp(/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/);
            // var pola = new RegExp(/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]{8,}$/);
            var pola = new RegExp(/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*();:<>?.,{}|_+=-])[A-Za-z\d!@#$%^&*();:<>?.,{}|_+=-]{8,}$/);
            if (pola.test(password)) {
                msgs = ''
                validates = false
            }
            return {
                'validates': validates
            }

        }
    </script>
</body>

</html>