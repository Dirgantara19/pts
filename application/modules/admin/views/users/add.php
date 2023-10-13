<?php
defined('BASEPATH') OR exit('No direct script access allowed');

?>

<div class="row">
    <div class="col-12">
        <?php echo form_open(current_url()); ?>
        <div class="form-group row">
            <div class="col-12 col-md-6">
                <?php echo form_label('{lang_full_name}', 'full_name'); ?>
                <?php echo form_input($full_name); ?>
                <?= $this->form_validation->error('full_name'); ?>
            </div>
            <div class="col-12 col-md-6">
                <?php echo form_label('{lang_title}', 'title'); ?>
                <?php echo form_input($title); ?>
                <?= $this->form_validation->error('title'); ?>
            </div>
        </div>
        <?php
if ($identity_column !== 'email')
{
	echo '<p>';
	echo lang('create_user_identity_label', 'identity');
	echo '<br />';
	echo form_error('identity');
	echo form_input($identity);
	echo '</p>';
}
?>
        <div class="form-group">
            <?php echo form_label('{lang_email}', 'email'); ?>
            <?php echo form_input($email); ?>
            <?= $this->form_validation->error('email'); ?>

        </div>
        <div class="form-group row">
            <div class="col-12 col-md-6">
                <?php echo form_label('{lang_nip}', 'nip'); ?>
                <?php echo form_input($nip); ?>
                <?= $this->form_validation->error('nip'); ?>

            </div>
            <div class="col-12 col-md-6">
                <?php echo form_label('{lang_nik}', 'nik'); ?>
                <?php echo form_input($nik); ?>
                <?= $this->form_validation->error('nik'); ?>

            </div>
        </div>
        <div class="form-group row">
            <div class="col-12 col-md-6">
                <?php echo form_label('{lang_password}', 'password'); ?>
                <?php echo form_input($password); ?>
                <?= $this->form_validation->error('password'); ?>

            </div>
            <div class="col-12 col-md-6">
                <?php echo form_label('{lang_password_confirm}', 'password_confirm'); ?>
                <?php echo form_input($password_confirm); ?>
                <?= $this->form_validation->error('password_confirm'); ?>

            </div>
        </div>
        <div class="form-group">
            <?php echo form_submit('submit', '{lang_create}', array('class' => 'btn btn-primary')); ?>
            <?php echo anchor('admin/users', '{lang_cancel}', array('class' => 'btn btn-primary')); ?>
        </div>
        <?php echo form_close(); ?>
    </div>
</div>