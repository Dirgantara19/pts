<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>


<div class="login-box">
    <div class="card card-outline card-primary">
        <div class="card-header text-center">
            <div class="text-center my-4">
                <img class="img" src="{theme_url}img/logosmkn1bantul.png" style="width:150px; height: 150px;" alt="">

            </div>
            <a href="<?= base_url(); ?>" class="h2"><b>Raport </b>Siswa</a>
        </div>
        <div class="card-body">
            <p class="login-box-msg">You forgot your password? Here you can easily retrieve a new password.</p>
            <?php
            echo form_open('auth/forgot_password', 'class="form-horizontal"');
            ?> <div class="input-group mb-3">
                <?php
                echo form_input($identity, '', 'class="form-control" placeholder="' . $identity_label . '"');
                ?>
                <div class="input-group-append">
                    <div class="input-group-text">
                        <span class="fas fa-envelope"></span>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <?php echo form_submit('submit', 'Request new password', 'class="btn btn-primary btn-block"'); ?>

                </div>
                <!-- /.col -->
            </div>
            </form>


            <p class="mt-3 mb-1">
                <a href="<?= base_url(); ?>auth/login">Login</a>
            </p>
        </div>
        <!-- /.login-card-body -->
    </div>
</div>