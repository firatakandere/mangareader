<?php
/**
* @ignore
*/
if (!defined('IN_MANGAREADER') || !defined('IN_ADMIN'))
{
    exit;
}
?>
<form class="form-horizontal" method="post" action="<?php get_new_category_uri(); ?>">
    <input type="hidden" name="is_adult" value="0">
    <div class="control-group">
        <label class="control-label" for="inputCategoryName"><?php _e('CATEGORY_NAME'); ?></label>
        <div class="controls">
            <input type="text" name="category_name" id="inputCategoryName" placeholder="<?php _e('CATEGORY_NAME'); ?>">
        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="inputIsAdult"><?php _e('IS_ADULT'); ?></label>
        <div class="controls">
            <div class="btn-group" data-toggle="buttons-radio">
                <button type="button" class="btn btn-primary btn-is-adult" value="1">Yes</button>
                <button type="button" class="btn btn-primary btn-is-adult" value="0">No</button>
            </div>
        </div>
    </div>
    <div class="control-group">
        <div class="controls">
            <button type="submit" name="submit" class="btn"><?php _e('SUBMIT'); ?></button>
        </div>
    </div>
</form>
