{* Smarty *}

{include file='html_header.tpl' title='Change your email and password'}

<body>

{include file='links.tpl'}

{if $error}
<div id="error" style="display: block;">{$error}
<p id="error_toggler"><a href="javascript:hideError('error');">Hide</a></p>
</div>
{/if}

<div id="c_error" style="display: none;">

</div>

<div id="main">
<p>Use this form to change your email or your password. If you wish to change only your email or only your password, leave the other section blank. Click Update to complete the change.</p>
 
<p>Change your email:</p>

<form name="info_change_form" method="post" action="{$PHP_SELF}?page=infochange&action=updateinfo" onSubmit="return validateInfoChange(getElementsByTagName('input'));">
	<p>Your current email address is: {$session.email}</p>
	<input type="hidden" name="oldemail" value="{$session.email}" />
	<div class="inputdiv"><label for="new_email">New email:</label></div>
	<input type="text" name="new email" size="20" maxlength="30" /><br /><br />

	<div class="inputdiv"><label for="retyped_new_email">Retype New email:</label></div>
	<input type="text" name="retyped_new email" size="20" maxlength="30" /><br /><br />
<hr />
<p>Change your password:</p>

	<div class="inputdiv"><label for="old_password">Old Password:</label></div>
	<input type="password" name="old_password" size="20" maxlength="30" /><br /><br />
	<div class="inputdiv"><label for="new_password">New Password:</label></div>
	<input type="password" name="new_password" size="20" maxlength="30" /><br /><br />
	
	<div class="inputdiv"><label for="retyped_new_password">Retype New Password:</label></div>
	<input type="password" name="retyped_new_password" size="20" maxlength="30" /><br /><br />
	<div class="inputdiv">&nbsp;</div>
	<input type="submit" value="Update" />

</form>
</div>
{include file='html_footer.tpl'}

