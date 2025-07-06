<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>KOS BUK EN | <?= $title ?> </title>
    <link rel="shortcut icon" href="<?= base_url('assets/') ?>dist/img/logo.jpg">

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="<?= base_url('assets/') ?>plugins/fontawesome-free/css/all.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="<?= base_url('assets/') ?>dist/css/adminlte.min.css">
    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="<?= base_url('assets/') ?>plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">

    <!-- DataTables -->
    <link rel="stylesheet" href="<?= base_url('assets/') ?>plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="<?= base_url('assets/') ?>plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="<?= base_url('assets/') ?>plugins/datatables-buttons/css/buttons.bootstrap4.min.css">

    <style>
        .room-card {
            border: none;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s;
            height: 100%;
            display: flex;
            flex-direction: column;
        }

        .room-card:hover {
            transform: translateY(-5px);
        }

        .room-img-container {
            position: relative;
            height: 200px;
            overflow: hidden;
            border-top-left-radius: 0.25rem;
            border-top-right-radius: 0.25rem;
            flex-shrink: 0;
        }

        .room-img-container img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .room-status-badge {
            position: absolute;
            top: 10px;
            right: 10px;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: bold;
            /* background-color: rgba(255, 255, 255, 0.9); */
            /* color: #000; */
        }

        .room-info {
            padding: 15px;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .room-title {
            font-size: 1.1rem;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .room-price {
            font-weight: bold;
            margin-top: 10px;
        }
    </style>

</head>

<?php
$session = session();
?>

<body class="hold-transition layout-top-nav">
    <div class="wrapper">

        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand-md navbar-dark navbar-maroon">
            <div class="container">
                <a href="<?= site_url('Front-Dashboard') ?>" class="navbar-brand">
                    <img src="<?= base_url('assets/') ?>dist/img/logobuken.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
                    <span class="brand-text font-weight-maroon">KOS BUK EN</span>
                </a>
                <button class="navbar-toggler order-1" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse order-3" id="navbarCollapse">
                    <!-- Left navbar links -->
                    <ul class="navbar-nav">

                        <?php if ($session->get('logged_in_penyewa')) { ?>
                            <li class="nav-item">
                                <a href="<?= site_url('Front-Dashboard') ?>" class="nav-link <?php if ($menu == "mn_dashboard") {
                                                                                                    echo "active";
                                                                                                } ?>"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
                            </li>
                            <li class="nav-item">
                                <a href="<?= site_url('sewa-kamar-penyewa') ?>" class="nav-link <?php if ($menu == "mn_sewa_kamar") {
                                                                                                    echo "active";
                                                                                                } ?>"><i class="fas fa-bed"></i> Sewa Kamar</a>
                            </li>
                        <?php } ?>
                    </ul>
                    <ul class="navbar-nav ml-auto">
                        <!-- Navbar Search -->
                        <!-- <li class="nav-item">
                            <a class="nav-link" href="#" role="button">
                                <i class="fas fa-sign-out-alt"></i> Logout
                            </a>
                        </li> -->
                        <?php if ($session->get('logged_in_penyewa')) { ?>
                            <li class="nav-item dropdown ">
                                <a class="nav-link" data-toggle="dropdown" href="#" aria-expanded="true">
                                    <i class="far fa-user"></i> <?= $session->get('nama_penyewa') ?> <i class="fas fa-caret-down"></i></strong>
                                </a>
                                <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right " style="left: inherit; right: 0px;">

                                    <div class="dropdown-divider"></div>
                                    <a href="<?= site_url('Logout-Penyewa') ?>" class="dropdown-item">
                                        <i class="fas fa-sign-out-alt"></i> Logout
                                    </a>
                                </div>
                            </li>
                        <?php } else { ?>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="modal" data-target="#modal_login" role="button">
                                    <i class="fas fa-sign-in-alt"></i> Login
                                </a>
                            </li>
                        <?php } ?>
                    </ul>
                </div>
            </div>
        </nav>
        <!-- /.navbar -->
        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <div class="content-header">
                <div class="container">
                    <h1 class="m-0"> <?= $title ?></h1>
                </div><!-- /.container-fluid -->
            </div>
            <!-- /.content-header -->

            <!-- Main content -->
            <div class="content">
                <div class="container">
                    <?= $this->renderSection('content') ?>

                    <?= $this->include('template/Front_footer') ?>