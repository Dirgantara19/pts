<?php
defined('BASEPATH') or exit('No direct script access allowed');

?>

<div class="row">
    <div class="col-12">
        <?php echo form_open(current_url()); ?>
        <div class="form-group row">
            <div class="col-12 col-md-12">
                <?php echo form_label('{lang_full_name}', 'full_name'); ?>
                <?php echo form_input($full_name); ?>
            </div>
        </div>
        <div class="form-group row">
            <div class="col-12 col-md-12">
                <?php echo form_label('{lang_email}', 'email'); ?>
                <?php echo form_input($email); ?>
            </div>
        </div>
        <div class="form-group has-warning row">
            <div class="col-12 col-md-6">
                <?php echo form_label('{lang_password}', 'password'); ?>
                <?php echo form_input($password); ?>
                <div class="form-control-feedback">{lang_password_if_change}</div>
            </div>
            <div class="col-12 col-md-6">
                <?php echo form_label('{lang_password_confirm}', 'password_confirm'); ?>
                <?php echo form_input($password_confirm); ?>
                <div class="form-control-feedback">{lang_password_if_change}</div>
            </div>
        </div>

        <?php if ($this->ion_auth->is_programmer()) : ?>
        <div class="form-group">
            <p><?php echo lang('edit_user_groups_heading'); ?></p>
            <?php foreach ($groups as $group) : ?>
            <div class="form-check form-check-inline">
                <label class="custom-control custom-checkbox">
                    <?php
                            $checked  = NULL;
                            $disabled = NULL;
                            $item     = NULL;

                            foreach ($currentGroups as $grp) {
                                if ($group['id'] == $grp->id) {
                                    $checked = ' checked';
                                }
                            }
                            ?>
                    <input type="checkbox" name="groups[]" value="<?php echo $group['id']; ?>"
                        class="custom-control-input" <?php echo $checked; ?>>
                    <span class="custom-control-indicator"></span>
                    <span
                        class="custom-control-description"><?php echo htmlspecialchars($group['name'], ENT_QUOTES, 'UTF-8'); ?></span>
                </label>
            </div>
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
</div>

<div class="row">
    <div class="col-12">
        {message}
    </div>
</div>