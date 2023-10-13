<!-- Sidebar user panel (optional) -->
<div class="user-panel mt-3 pb-3 mb-3 d-flex">
    <div class="image">
        <img src="" class="img-circle elevation-2 user-img" alt="User Image">
    </div>
    <div class="info">
        <a href="<?= base_url('guru/profile'); ?>" class="d-block user-fullname-title"></a>
    </div>
</div>


<!-- Sidebar Menu -->
<nav class="mt-2">
    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
        <!-- Divider -->
        <!-- Nav Item - Dashboard -->
        <li class="nav-item">
            <a class="nav-link" href="<?php echo base_url('guru/dashboard'); ?>">
                <i class="nav-icon fas fa-home"></i>
                <p>Dashboard</p>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="<?php echo base_url('guru/siswa'); ?>">
                <i class="nav-icon fas fa-user-graduate"></i>
                <p>

                    Siswa
                </p>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="<?php echo base_url('guru/nilai'); ?>">
                <i class="nav-icon fas fa-sign"></i>
                <p>
                    Nilai
                </p>
            </a>
        </li>

    </ul>
</nav>
<!-- /.sidebar-menu -->