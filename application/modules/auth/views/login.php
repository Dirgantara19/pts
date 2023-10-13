<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>




<div class="login-box">
    <!-- /.login-logo -->
    <div class="card card-outline card-info">
        <div class="card-header text-center">
            <div class="text-center my-4">
                <img class="img" src="{theme_url}img/logosmkn1bantul.png" style="width:150px; height: 150px;" alt="">

            </div>
            <a href="<?= base_url(); ?>" class="h2"><b>Raport </b>Siswa</a>
        </div>
        <div class="card-body">
            <p class="login-box-msg">Sign in to start your session</p>

            <?php echo form_open('auth/login'); ?>
            <div class="input-group mb-3">
                <?php
                echo form_input($identity, '', 'type="email" class="form-control" placeholder="Email"');
                ?>
                <div class="input-group-append">
                    <div class="input-group-text">
                        <span class="fas fa-envelope"></span>
                    </div>
                </div>
            </div>
            <div class="input-group mb-3">
                <?php
                echo form_password($password, '', 'class="form-control" placeholder="Password"');
                ?>
                <div class="input-group-append">
                    <div class="input-group-text">
                        <span class="fas fa-lock"></span>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-8">
                    <div class="icheck-primary">
                        <?php
                        echo form_checkbox($remember, '', '', 'id="remember"');

                        ?>
                        <label for="remember">
                            <?php echo lang('login_remember_label');; ?>
                        </label>

                    </div>
                </div>
                <!-- /.col -->
                <div class="col-4">
                    <?php echo form_submit('submit', lang('login_submit_btn'), 'class="btn btn-primary btn-block"'); ?>

                </div>
                <?php echo form_close(); ?>
                <!-- /.col -->
            </div>
            </form>

            <!-- /.social-auth-links -->

            <p class="mb-1">
                <a href="<?= base_url(); ?>auth/forgot_password">I forgot my password</a>
            </p>

        </div>
        <!-- /.card-body -->
    </div>
    <!-- /.card -->
</div>