<!DOCTYPE html>
<!--
* CoreUI - Free Bootstrap Admin Template
* @version v4.2.0
* @link https://coreui.io
* Copyright (c) 2022 creativeLabs Łukasz Holeczek
* Licensed under MIT (https://coreui.io/license)
-->
<!-- Breadcrumb-->
<?php
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
?>


<html lang="en">

<head>



    <base href="<?= htmlentities(base_url('Assets/Dashboard/dist/')) ?>./">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <!-- <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests"> -->
    <title>Dashboard Transfashion</title>

    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="assets/favicon/ms-icon-144x144.png">
    <meta name="theme-color" content="#ffffff">



    <!-- Vendors styles-->
    <link rel="stylesheet" href="<?= htmlentities(base_url('Assets/Dashboard/dist/')) ?>vendors/simplebar/css/simplebar.css">
    <link rel="stylesheet" href="<?= htmlentities(base_url('Assets/Dashboard/dist/')) ?>css/vendors/simplebar.css">
    <!-- Main styles for this application-->
    <link href="<?= htmlentities(base_url('Assets/Dashboard/dist/')) ?>css/style.css" rel="stylesheet">



    <!-- select2 css -->
    <!-- select2 plugin -->
    <link rel="stylesheet" href="<?= htmlentities(base_url('Assets/select2-4.0.13/dist/css/select2.min.css')) ?>">


    <!-- link sweetalert2 -->
    <link rel="stylesheet" href="<?= htmlentities(base_url('Assets/sweetalert2/dist/sweetalert2.min.css')) ?>">


    <link rel="stylesheet" href="<?= htmlentities(base_url()) ?>Assets\bootstrap-5\css\bootstrap.min.css">


    <link rel="stylesheet" href="<?= htmlentities(base_url('Assets/DataTables/datatables.min.css')) ?>">
    <link rel="shortcut icon" href="<?= htmlentities(base_url('Assets/Image/')) ?>favicon.ico">


    <style>
        .white_content4 {
            display: none;
            width: 50%;
            height: auto;
            padding: 16px;
            z-index: 9999;
            max-height: calc(100% - 100px);
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }

        .dataTables_scrollHeadInner,
        .table {
            width: 100% !important
        }

        
    </style>

</head>

<body>

    <?php echo $this->session->flashdata('msg'); ?>
    <?php
    if (isset($_SESSION['msg'])) {
        unset($_SESSION['msg']);
    }
    ?>
    <div class="white_content4 text-center mx-auto" id="loader"><img src="<?= base_url('Assets/Image/ball_load.svg') ?>" width="100px"></div>
    <div class="sidebar sidebar-dark sidebar-fixed" id="sidebar">
        <div class="sidebar-brand d-none d-md-flex">
            <image class="sidebar-brand-full" width="118" height="46" alt="Transmart Logo" src="<?= htmlentities(base_url('Assets/Image/')) ?>logo-white.png" alt=""></image>

        </div>
        <ul class="sidebar-nav html_menu" style="font-size: 0.9em;" data-coreui="navigation" data-simplebar="">
            <?= $menus ?>

        </ul>

        <!-- <button class="sidebar-toggler" type="button" data-coreui-toggle="unfoldable"></button> -->
    </div>



    <div class="wrapper d-flex flex-column min-vh-100 bg-light">
        <header class="header header-sticky mb-4">
            <div class="container-fluid">
                <button class="header-toggler px-md-0 me-md-3" type="button" onclick="coreui.Sidebar.getInstance(document.querySelector('#sidebar')).toggle()">
                    <svg class="icon icon-lg">
                        <use xlink:href="<?= htmlentities(base_url('Assets/Dashboard/dist/')) ?>vendors/@coreui/icons/svg/free.svg#cil-menu"></use>
                    </svg>
                </button>

                <!-- <div class="row align-right"> -->
                <ul class="header-nav ms-auto">
                    <li class="nav-item ">
                        <?= htmlentities($nama) ?>
                    </li>
                </ul>
                <!-- </div> -->
                <div class="row">
                    <ul class="header-nav ms-3">
                        <li class="nav-item dropdown"><a class="nav-link py-0" data-coreui-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                                <div class="avatar avatar-md"><img class="avatar-img" src="<?= htmlentities(base_url()) ?>Assets/Image/user.png" alt="Icon User"></div>
                            </a>
                            <div class="dropdown-menu dropdown-menu-end pt-0">

                                <!-- <a class="dropdown-item" href="<?= htmlentities(base_url('Login_c/logout')) ?>"> -->
                                <a class="dropdown-item">
                                    <svg class="icon me-2">
                                        <use xlink:href="<?= htmlentities(base_url('Assets/Dashboard/dist/')) ?>vendors/@coreui/icons/svg/free.svg#cil-account-logout"></use>
                                    </svg>
                                    <button onclick="logouts_()" class="btn btn-primary-outline">Logout</button>
                                </a>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="header-divider"></div>
            <div class="container-fluid">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb my-0 ms-2">
                        <li class="breadcrumb-item">
                            <!-- if breadcrumb is single--><span><?= htmlentities($menu_header) ?></span>
                        </li>
                        <li class="breadcrumb-item active"><span><?= htmlentities($main_menu) ?></span></li>
                    </ol>
                </nav>
            </div>
        </header>

        <script src="vendors/@coreui/coreui/js/coreui.bundle.min.js"></script>
        <script src="vendors/simplebar/js/simplebar.min.js"></script>
        <script src="<?= htmlentities(base_url('Assets/sweetalert2/dist/sweetalert2.min.js')) ?>"></script>
        <script src="<?= htmlentities(base_url()) ?>Assets\bootstrap-5\js\bootstrap.min.js"></script>
        <script src="<?= htmlentities(base_url('Assets/jquery/dist/jquery.min.js')) ?>"></script>
        <script src="<?= htmlentities(base_url('Assets/DataTables/datatables.min.js')) ?>"></script>
        <!-- selectto pluggin -->
        <script src="<?= htmlentities(base_url('Assets/select2-4.0.13/dist/js/select2.min.js')) ?>"></script>

        <script>
            window.dataLayer = window.dataLayer || [];

            function gtag() {
                dataLayer.push(arguments);
            }
            // gtag('js', new Date());
            // Shared ID
            // gtag('config', 'UA-118965717-3');
            // Bootstrap ID
            // gtag('config', 'UA-118965717-5');

            function logouts_() {

                $.ajax({
                    url: '<?= htmlentities(base_url('Login_c/logout/')) ?>',
                    type: "post",
                    data: val,
                    beforeSend: function() {
                        $("#loader").show();
                    },
                    complete: function() {
                        $("#loader").hide();
                    },
                    success: function(res) {
                        location.reload();
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        alert('gagal');
                    }
                });
            }
        </script>