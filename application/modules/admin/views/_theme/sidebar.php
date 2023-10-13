<!-- Sidebar user panel (optional) -->
<div class="user-panel mt-3 pb-3 mb-3 d-flex">
    <div class="image">
        <img src="" class="img-circle elevation-2 user-img" alt="User Image">
    </div>
    <div class="info">
        <a href="<?= base_url('admin/profile'); ?>" class="d-block user-fullname-title"></a>
    </div>
</div>


<!-- Sidebar Menu -->
<nav class="mt-2">
    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
        <li class="nav-item">
            <a class="nav-link" href="<?php echo base_url('admin/dashboard'); ?>">
                <i class="nav-icon fas fa-home"></i>
                <p>Dashboard</p>
            </a>
        </li>
        <!-- <li class="nav-item">
            <a class="nav-link" href="<?php echo base_url('admin/users'); ?>">
                <i class="nav-icon fas fa-users"></i>
                <p>{lang_users}</p>
            </a>
        </li> -->
        <!-- <li class="nav-item">
            <a class="nav-link" href="<?php echo base_url('admin/gurupengampu'); ?>">
                <i class="nav-icon fas fa-chalkboard-teacher"></i>
                <p>Guru</p>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="<?php echo base_url('admin/siswa'); ?>">
                <i class="nav-icon fas fa-user-graduate"></i>
                <p>

                    Siswa
                </p>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="<?php echo base_url('admin/kelas'); ?>">
                <i class="nav-icon fas fa-school"></i>
                <p>

                    Kelas
                </p>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="<?php echo base_url('admin/mapel'); ?>">
                <i class="nav-icon fas fa-book"></i>
                <p>
                    Mapel

                </p>
            </a>
        </li> -->
        <li class="nav-item menu-open">
            <a href="#" class="nav-link">
                <i class="nav-icon fas fa-book"></i>
                <p>
                    Data Master
                    <i class="fas fa-angle-left right"></i>
                </p>
            </a>
            <ul class="nav nav-treeview">
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo base_url('admin/gurupengampu'); ?>">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Guru</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo base_url('admin/siswa'); ?>">
                        <i class="far fa-circle nav-icon"></i>
                        <p>

                            Siswa
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo base_url('admin/kelas'); ?>">
                        <i class="far fa-circle nav-icon"></i>
                        <p>

                            Kelas
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo base_url('admin/mapel'); ?>">
                        <i class="far fa-circle nav-icon"></i>
                        <p>
                            Mapel

                        </p>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="<?php echo base_url('admin/users'); ?>">
                        <i class="far fa-circle nav-icon"></i>
                        <p>{lang_users}</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="<?php echo base_url('admin/tahunajaran'); ?>">
                        <i class="far fa-circle nav-icon"></i>
                        <p>

                            Tahun
                        </p>
                    </a>
                </li>
            </ul>
        </li>

        <div class="divider"></div>

        <li class="nav-item">
            <a class="nav-link" href="<?php echo base_url('admin/nilai'); ?>">
                <i class="nav-icon fas fa-sign"></i>
                <p>
                    Nilai
                </p>
            </a>
        </li>

    </ul>

</nav>


<!-- /.sidebar-menu -->