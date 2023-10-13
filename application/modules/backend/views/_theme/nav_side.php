<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<ul class="nav flex-column">
    <li class="nav-item">
        <a class="nav-link" href="<?php echo site_url('backend/dashboard'); ?>">{lang_dashboard}</a>

        <a class="nav-link" href="<?php echo site_url('backend/users'); ?>">{lang_users}</a>

        <a class="nav-link" href="<?php echo site_url('backend/groups'); ?>">{lang_security_groups}</a>
    </li>
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="true"
            aria-controls="collapseTwo">
            <i class="fas fa-fw fa-cog"></i>
            <span>Debbuging ></span>
        </a>
        <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">


                <a class="nav-link" href="<?php echo site_url('backend/maintenance'); ?>">{lang_maintenance} (beta)</a>
            </div>
        </div>
    </li>
</ul>