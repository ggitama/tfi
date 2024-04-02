<!doctype html> <html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="<?= htmlentities( base_url('Assets/Login_template/fonts/icomoon/style.css'),ENT_QUOTES) ?>">

    <link rel="stylesheet" href="<?= htmlentities( base_url('Assets/Login_template/css/owl.carousel.min.css'),ENT_QUOTES) ?>">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="<?= htmlentities( $url_bs5,ENT_QUOTES) ?>">

    <!-- Style -->
    <link rel="stylesheet" href="<?= htmlentities( base_url('Assets/Login_template/css/style.css'),ENT_QUOTES) ?>">

    <!-- link sweetalert2 -->
    <link rel="stylesheet" href="<?= htmlentities( base_url('Assets/sweetalert2/dist/sweetalert2.min.css'),ENT_QUOTES )?>">
    <script src="<?= htmlentities( base_url('Assets/sweetalert2/dist/sweetalert2.min.js'),ENT_QUOTES) ?>"></script>

    <link rel="shortcut icon" href="<?= htmlentities(base_url('Assets/Image/')) ?>favicon.ico">

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
                <div class="col-md-6 order-md-2">
                    <img src="<?= htmlentities( base_url('Assets/Image/Login_image.png'),ENT_QUOTES) ?>" alt="Image" class="img-fluid">
                </div>
                <div class="col-md-6 contents">
                    <div class="row justify-content-center">
                        <div class="col-md-8">
                            <div class="mb-4">
                                <h3>Sign In to <strong>Transfashion</strong></h3>
                              
                            </div>
                            <form action="<?= htmlentities( base_url('Login_c/do_login')) ?>" method="post">
                                <div class="form-group first">
                                    <label for="username">Username</label>
                                    <input type="text" name="username" class="form-control" id="username">
                                    <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
                                </div>
                                <div class="form-group last mb-4">
                                    <label for="password">Password</label>
                                    <input type="password" name="password" class="form-control" id="password">
                                </div>

                                <!-- <div class="d-flex mb-5 align-items-center">
                                    <label class="control control--checkbox mb-0"><span class="caption">Remember me</span>
                                        <input type="checkbox" checked="checked" />
                                        <div class="control__indicator"></div>
                                    </label>
                                    <span class="ml-auto"><a href="#" class="forgot-pass">Forgot Password</a></span>
                                </div> -->
                                <!-- <div class="d-flex mb-5 align-items-center">
                                    <label class="control control--checkbox mb-0">
                                        <span class="caption">Not Have Account ?</span>
                                    </label>
                                    <span class="ml-auto"><a href="#" class="forgot-pass">Sign Up</a></span>
                                </div> -->
                                <input type="submit" value="Log In" class="btn text-white btn-block btn-dark">


                            </form>
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </div>


    <script src="<?= htmlentities( base_url('Assets/Login_template/js/jquery-3.3.1.min.js'),ENT_QUOTES )?>"></script>
    <script src="<?= htmlentities( base_url('Assets/Login_template/js/popper.min.js'),ENT_QUOTES )?>"></script>
    <script src="<?= htmlentities( base_url('Assets/Login_template/js/bootstrap.min.js'),ENT_QUOTES )?>"></script>
    <script src="<?= htmlentities( base_url('Assets/Login_template/js/main.js'),ENT_QUOTES )?>"></script>
</body>

</html>