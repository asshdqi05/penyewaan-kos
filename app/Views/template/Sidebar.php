<?php $session = session(); ?>
<aside class="main-sidebar sidebar-light-maroon elevation-4">
    <!-- Brand Logo -->
    <a href="index3.html" class="brand-link">
        <img src="<?= base_url('assets/') ?>dist/img/logobuken.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-olive">KOS BUK EN</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="<?= base_url('assets/') ?>dist/img/avatar2.png" class="img-circle elevation-2" alt="User Image">
            </div>
            <div class="info">
                <a href="#" class="d-block"><?= $session->get('nama') ?></a>
            </div>
        </div>


        <!-- Sidebar Menu -->
        <?php if ($session->get('role') == 1) : ?>
            <nav class="mt-2">
                <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                    <li class="nav-item">
                        <a href="<?= site_url('Dashboard') ?>" class="nav-link <?php if ($menu == "mn_dashboard") {
                                                                                    echo "active";
                                                                                } ?>">
                            <i class="nav-icon fas fa-tachometer-alt"></i>
                            <p>
                                Dashboard
                            </p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="<?= site_url('Kamar') ?>" class="nav-link <?php if ($menu == "mn_kamar") {
                                                                                echo "active";
                                                                            } ?>">
                            <i class="nav-icon fas fa-bed"></i>
                            <p>
                                Data Kamar
                            </p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="<?= site_url('Penyewa') ?>" class="nav-link <?php if ($menu == "mn_penyewa") {
                                                                                    echo "active";
                                                                                } ?>">
                            <i class="nav-icon fas fa-address-book"></i>
                            <p>
                                Data Penyewa
                            </p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="<?= site_url('Sewa-Kamar') ?>" class="nav-link <?php if ($menu == "mn_sewa_kamar") {
                                                                                    echo "active";
                                                                                } ?>">
                            <i class="nav-icon fas fa-laptop-house"></i>
                            <p>
                                Penyewaan Kamar
                            </p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="<?= site_url('Laporan') ?>" class="nav-link <?php if ($menu == "mn_laporan") {
                                                                                    echo "active";
                                                                                } ?>">
                            <i class="nav-icon fas fa-file-alt"></i>
                            <p>
                                Laporan
                            </p>
                        </a>
                    </li>
                </ul>
            </nav>
        <?php endif; ?>

        <?php if ($session->get('role') == 2) : ?>
            <nav class="mt-2">
                <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                    <li class="nav-item">
                        <a href="<?= site_url('Dashboard') ?>" class="nav-link <?php if ($menu == "mn_dashboard") {
                                                                                    echo "active";
                                                                                } ?>">
                            <i class="nav-icon fas fa-tachometer-alt"></i>
                            <p>
                                Dashboard
                            </p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="<?= site_url('Laporan') ?>" class="nav-link <?php if ($menu == "mn_laporan") {
                                                                                    echo "active";
                                                                                } ?>">
                            <i class="nav-icon fas fa-file-medical"></i>
                            <p>
                                Laporan
                            </p>
                        </a>
                    </li>
                </ul>
            </nav>
        <?php endif; ?>

    </div>
</aside>