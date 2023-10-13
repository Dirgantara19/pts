<?php
defined('BASEPATH') or exit('No direct script access allowed');

?>


<!-- Button to trigger the modal -->
<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#editUserModal">
    Open Edit User Modal
</button>

<!-- Modal -->
<div class="modal fade" id="editUserModal" tabindex="-1" role="dialog" aria-labelledby="editUserModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editUserModalLabel">Edit User</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
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
                <div class="form-group row">
                    <div class="col-12 col-md-12">
                        <?php echo form_label('{lang_email}', 'email'); ?>
                        <?php echo form_input($email); ?>
                        <?= $this->form_validation->error('email'); ?>

                    </div>
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
                <div class="form-group has-warning row">
                    <div class="col-12 col-md-6">
                        <?php echo form_label('{lang_password}', 'password'); ?>
                        <?php echo form_input($password); ?>
                        <?= $this->form_validation->error('password'); ?>

                        <div class="form-control-feedback">{lang_password_if_change}</div>
                    </div>
                    <div class="col-12 col-md-6">
                        <?php echo form_label('{lang_password_confirm}', 'password_confirm'); ?>
                        <?php echo form_input($password_confirm); ?>
                        <?= $this->form_validation->error('password_confirm'); ?>

                        <div class="form-control-feedback">{lang_password_if_change}</div>
                    </div>
                </div>

                <?php if ($this->ion_auth->is_admin()) : ?>
                    <div class="form-group">
                        <p><?php echo lang('edit_user_groups_heading'); ?></p>
                        <?php foreach ($groups as $group) : ?>
                            <?php if ($group['id'] != 1 && $group['id'] != 3) : ?>
                                <div class="form-check form-check-inline">
                                    <label class="form-control form-checkbox">
                                        <?php
                                                    $checked  = NULL;
                                                    $disabled = NULL;
                                                    $item     = NULL;

                                                    foreach ($currentGroups as $grp) {
                                                        if ($group['id'] == $grp->id) {
                                                            $checked = ' checked';
                                                        }

                                                        if ($user_id == 1) {
                                                            $disabled = ' disabled';
                                                        }
                                                    };


                                                    ?>
                                        <input type="checkbox" name="groups[]" value="<?php echo $group['id']; ?>" class="form-control-input" <?php echo $checked . $disabled; ?>>
                                        <span class="form-control-indicator"></span>
                                        <span class="form-control-description"><?php echo htmlspecialchars($group['name'], ENT_QUOTES, 'UTF-8'); ?></span>


                                    </label>
                                </div>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
                <div class="form-group">
                    <?php
                    echo form_hidden('id', $user_id);
                    echo form_hidden($csrf);
                    echo form_submit('submit', '{lang_save}', array('class' => 'btn btn-primary'));
                    echo anchor('backend/users', '{lang_cancel}', array('class' => 'btn btn-primary'));
                    ?>
                </div>
                <?php echo form_close(); ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Save changes</button>
            </div>
        </div>
    </div>
</div>


<!-- <div class="row">
    <div class="col-12">
        {message}
    </div>
</div> -->