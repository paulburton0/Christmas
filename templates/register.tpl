{* Smarty *}

{include file='html_header.tpl' title='Register'}

<body>

{if $error}
<div id="error" style="display: block;">{$error}
<p id="error_toggler"><a href="javascript:hideError('error');">Hide</a></p></div>
{/if}


<div id="c_error" style="display: none;"></div>

<div id="main">
<form name="register_form" id="main_register_form" method="post"
action="{$PHP_SELF}?page=reg_confirm" onSubmit="return validateRegistration(document.register_form.id.value, getElementsByTagName('input'));">

<p>Please register using your everyday email. Wish list updates from the person
you draw and other reminders will be sent to this address. You'll also use this
email address to log in to the website.</p>
<p>It's OK if you use the same email address as another user as long as you choose a unique password.</p>
<p>Your password may include any characters except space, but can not be the
same as your email address.</p>

	<div class="inputdiv"><label for="id">name:</label></div>
	{html_options name=id options=$names selected=$names[0]}<br /><br />

	<div class="inputdiv"><label for="email">email:</label></div>
	<input type="text" name="email" maxlength="30" size="20" /><br /><br />

	<div class="inputdiv"><label for="retyped_email">retype email:</label></div>
	<input type="text" name="retyped_email" maxlength="30" size="20" /><br /><br />

	<div class="inputdiv"><label for="password">password:</label></div>
	<input type="password" name="password" maxlength="15" size="20" /><br /><br />

	<div class="inputdiv"><label for="retyped_password">retype password:</label></div>
	<input type="password" name="retyped_password" maxlength="15" size="20" /><br /><br />
	<div class="inputdiv">&nbsp;</div>
	<input type="hidden" name="registered" value="1" />
	
	<input type="submit" value="Register" /><br />
</form>
</div>
{include file='html_footer.tpl'}
