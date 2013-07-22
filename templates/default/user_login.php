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
<form class="form-horizontal" method="post" action="<?php get_login_uri(); ?>">
    <fieldset>
        <legend><?php _e('LOGIN'); ?></legend>
        <div class="control-group">
            <label class="control-label" for="inputUsername"><?php _e('USERNAME'); ?></label>
            <div class="controls">
                <input type="text" id="inputUsername" name="username" placeholder="<?php _e('USERNAME'); ?>" value="<?php echo $data['username']; ?>">
            </div>
        </div>
        <div class="control-group">
            <label class="control-label" for="inputPassword"><?php _e('PASSWORD'); ?></label>
            <div class="controls">
                <input type="password" id="inputPassowrd" name="password" placeholder="<?php _e('PASSWORD'); ?>">
            </div>
        </div>
        <div class="control-group">
            <div class="controls">
                <button class="btn" type="submit" name="submit"><?php _e('LOGIN'); ?></button>
            </div>
        </div>
    </fieldset>
</form>
<?php get_footer(); ?>
