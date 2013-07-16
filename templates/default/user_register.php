<?php get_header(); ?>
<?php
if (sizeof($error)) :
?>
<div class="alert alert-error">
<?php
foreach ($error as $err) :
?>
<p><?php _e($err); ?></p>
<?php
endforeach;
?>
</div>
<?php
endif;
?>
<form class="form-horizontal" method="post" action="<?php get_register_uri(); ?>">
    <fieldset>
        <legend><?php _e('REGISTER'); ?></legend>
    <div class="control-group">
        <label class="control-label" for="inputUsername"><?php _e('USERNAME'); ?></label>
        <div class="controls">
            <input type="text" id="inputUsername" name="username" placeholder="<?php _e('USERNAME'); ?>" value="<?php echo $data['username']; ?>">
        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="inputPassword"><?php _e('PASSWORD'); ?></label>
        <div class="controls">
            <input type="password" id="inputPassword" name="password" placeholder="<?php _e('PASSWORD'); ?>">
        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="inputPasswordConfirm"><?php _e('PASSWORD_CONFIRM'); ?></label>
        <div class="controls">
            <input type="password" id="inputPasswordConfirm" name="password_confirm" placeholder="<?php _e('PASSWORD_CONFIRM'); ?>">
        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="inputEmail"><?php _e('EMAIL_ADDRESS'); ?></label>
        <div class="controls">
            <input type="email" id="inputEmail" name="email" placeholder="<?php _e('EMAIL_ADDRESS'); ?>" value="<?php echo $data['email']; ?>">
        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="inputEmailConfirm"><?php _e('EMAIL_ADDRESS_CONFIRM'); ?></label>
        <div class="controls">
            <input type="email" id="inputEmailConfirm" name="email_confirm" placeholder="<?php _e('EMAIL_ADDRESS_CONFIRM'); ?>" value="<?php echo $data['email_confirm']; ?>">
        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="inputTimezone"><?php _e('TIMEZONE'); ?></label>
        <div class="controls">
            <?php get_timezonelist($config['board_timezone'], false, array('id' => 'inputTimezone')); ?>
        </div>
    </div>
    <div class="control-group">
        <div class="controls">
            <button type="submit" class="btn" name="submit"><?php _e('REGISTER'); ?></button>
        </div>
    </div>
    </fieldset>
</form>
<?php get_footer(); ?>
