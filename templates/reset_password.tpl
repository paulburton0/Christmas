{* Smarty *}

{include file='html_header.tpl' title='Reset Your Password'}

<body>

{if $error}
<div id="error" style="display: block;">{$error}
<p id="error_toggler"><a href="javascript:hideError('error');">Hide</a></p></div>
{/if}

<div id="main">
<p>Select your name and type the email you used to register and click "Retrieve Password."</p>

<p>You will receive an email at the address you used to register. Follow the
instructions in the email to reset your password.</p>


<form name="reset_password" id="main_reset_password" method="post" action="{$PHP_SELF}?page=password">

	<div class="inputdiv"><label for="id">name:</label></div>
	{html_options name=id options=$names selected=$names[0]}<br /><br />

	<div class="inputdiv"><label for="email">Registration email:</label></div>
	<input type="text" size="20" maxlength="30" name="email" /><br /><br />
	<div class="inputdiv">&nbsp;</div>
	<input type="submit" value="Retrieve Password" />
</form>
</div>
{include file='html_footer.tpl'}	
