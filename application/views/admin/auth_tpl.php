<!-- AUTH FORM -->
<div id="auth_container" style="background-image: url(/img/admin/auth<?php if ($error) echo '_error'; ?>.gif);">
	<form method="post" action="/admin/auth/enter">
    	<p><?php echo $lang->line('FIELD_USER_LOGIN'); ?> <input type="text" name="user_login" maxlength="25" /></p>
        <p><?php echo $lang->line('FIELD_USER_PASSWORD'); ?> <input type="password" name="user_password" maxlength="25" /></p>
        <input type="submit" value="<?php echo $lang->line('ACT_ENTER'); ?>" id="auth_submit" />
    </form>
</div><!-- END OF HEADER -->
