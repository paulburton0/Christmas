{* Smarty *}

{include file='html_header.tpl' title='Your Password Has Been Reset'}

<body>

{if $error}
<div id="error" style="display: block;">{$error}
<p id="error_toggler"><a href="javascript:hideError('error');">Hide</a></p></div>
{/if}

<div id="main">
<p>Your password has been reset and an email has been sent you the address you
provided.</p>
<p>Please read the email and follow the instructions to login using your new
password.</p>

<p><a href="index.php">Return to the login page</a></p>

</div>

{include file='html_footer.tpl'}
