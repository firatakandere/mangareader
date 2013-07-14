<?php get_header(); ?>
<form class="form-horizontal" method="post" action="<?php get_register_uri(); ?>">
    <fieldset>
        <legend>Register</legend>
    <div class="control-group">
        <label class="control-label" for="inputUsername">Username</label>
        <div class="controls">
            <input type="text" id="inputUsername" name="username" placeholder="Username">
        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="inputPassword">Password</label>
        <div class="controls">
            <input type="password" id="inputPassword" name="password" placeholder="Password">
        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="inputPasswordConfirm">Password (Confirm)</label>
        <div class="controls">
            <input type="password" id="inputPasswordConfirm" name="password_confirm" placeholder="Password (Confirm)">
        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="inputEmail">Email Address</label>
        <div class="controls">
            <input type="email" id="inputEmail" name="email" placeholder="Email Address">
        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="inputEmailConfirm">Email Address (Confirm)</label>
        <div class="controls">
            <input type="email" id="inputEmailConfirm" name="email_confirm" placeholder="Email Address (Confirm)">
        </div>
    </div>
    <div class="control-group">
        <div class="controls">
            <button type="submit" class="btn" name="submit">Register</button>
        </div>
    </div>
    </fieldset>
</form>
<?php get_footer(); ?>
