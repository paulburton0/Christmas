{* Smarty *}

{include file='html_header.tpl' title='Welcome'}

<body>

{if $error}
<div id="error" style="display: block;">{$error}
<p id="error_toggler"><a href="javascript:hideError('error');">Hide</a></p></div>
{/if}


<div id="c_error" style="display: none;"></div>

<div id="main">

<p>Welcome to christmas.mountaintopweather.com!</p>
<p>If this is your first time here, please <span class="emphasis"><a href="{$PHP_SELF}?page=register" >register a new account</a></span>.</p>
<p>If you've forgotten your password, click the "Forgot your password?" link below the login form to reset it.</p>

<form name="login_form" id="main_login_form" method="post" action="{$PHP_SELF}?page=main" onSubmit="return validateLogin(getElementsByTagName('input'));">
	<div class="inputdiv"><label for="email_address">email:</label></div>
	<input type="text" name="email" size="20" maxlength="30" /><br /><br />

	<div class="inputdiv"><label for="password">password:</label></div>
	<input type="password" name="password" size="20" maxlength="15" /><br /><br />

	<div class="inputdiv">&nbsp;</div>
	
	<input type="hidden" name="login" value="true" />
	<input type="submit" value="Sign In" /><br /><br />

	<div class="inputdiv">&nbsp;</div>
 	<a href="{$PHP_SELF}?page=register" >Don't have a password yet?</a><br />
	<div class="inputdiv">&nbsp;</div>
	<a href="{$PHP_SELF}?page=reset_pass">Forgot your password?</a>
</form>
</div>
{include file='html_footer.tpl'}
