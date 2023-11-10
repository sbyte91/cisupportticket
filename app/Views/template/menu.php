        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand navbar-white navbar-light">
            <!-- Left navbar links -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
                </li>
            </ul>

            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="<?= base_url() ?>logout" role="button">
                        <i class="fas fa-sign-out-alt"></i>
                        Logout
                    </a>
                </li>
            </ul>
        </nav>
        <!-- /.navbar -->

        <!-- Main Sidebar Container -->
        <aside class="main-sidebar sidebar-dark-primary elevation-4">
            <!-- Brand Logo -->
            <a href="index3.html" class="brand-link">
                <!-- <img src="<?= base_url() ?>/public/dist/img/ticket.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8"> -->
                <img src="<?= base_url() ?>/public/dist/img/customer-service-support.svg" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
                <span class="brand-text font-weight-light">CI SUPPORT TICKET</span>
            </a>

            <!-- Sidebar -->
            <div class="sidebar">
                <!-- Sidebar user panel (optional) -->
                <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                    <div class="image">
                        <img src="<?= base_url() ?>/public/dist/img/male-user-icon-in-suit-white.svg" class="img-circle elevation-2" alt="User Image">
                    </div>
                    <div class="info">
                        <a href="#" class="d-block"><?= auth()->user()->username ?? "GUEST" ?></a>
                    </div>
                </div>

                <!-- Sidebar Menu -->
                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                        <!-- <li class="nav-header">TRANSACTIONS</li> -->
                        <li class="nav-item">
                            <a href="<?= base_url() ?>dashboard" class="nav-link">
                                <i class="nav-icon fas fa-chart-line"></i>
                                <p>
                                    Dashboard
                                </p>
                            </a>
                        </li>
                        <?php if (auth()->user()->inGroup('admin')) : ?>
                        <!-- <li class="nav-item">
                            <a href="<?= base_url() ?>authors" class="nav-link">
                                <i class="nav-icon fas fa-user"></i>
                                <p>
                                    Authors
                                </p>
                            </a>
                        </li> -->
                        <li class="nav-item">
                            <a href="<?= base_url() ?>register" class="nav-link">
                                <i class="nav-icon fas fa-user"></i>
                                <p>
                                    User
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url() ?>profiles" class="nav-link">
                                <i class="nav-icon fas fa-address-card"></i>
                                <p>
                                    Profiles
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url() ?>office" class="nav-link">
                                <i class="nav-icon fas fa-school"></i>
                                <p>
                                    Office
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url() ?>condition" class="nav-link">
                                <i class="nav-icon fas fa-tags"></i>
                                <p>
                                    Support Condition
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url() ?>status" class="nav-link">
                                <i class="nav-icon fas fa-clipboard-list"></i>
                                <p>
                                    Ticket Status
                                </p>
                            </a>
                        </li>
                        <?php endif ?>
                        <!-- <li class="nav-item">
                            <a href="<?= base_url() ?>posts" class="nav-link">
                                <i class="nav-icon fas fa-list"></i>
                                <p>
                                    Posts
                                </p>
                            </a>
                        </li> -->
                        <li class="nav-item">
                            <a href="<?= base_url() ?>tickets" class="nav-link">
                                <i class="nav-icon fas fa-chalkboard-teacher"></i>
                                <p>
                                    Tickets
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url() ?>responses" class="nav-link">
                                <i class="nav-icon fas fa-tasks"></i>
                                <p>
                                    Responses
                                </p>
                            </a>
                        </li>
                    </ul>
                </nav>
                <!-- /.sidebar-menu -->
            </div>
            <!-- /.sidebar -->
        </aside>