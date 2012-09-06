{* Smarty *}

{include file='html_header.tpl' title='Confirm Your Registration'}

<body>

{if $error}
<div id="error" style="display: block;">{$error}
<p id="error_toggler"><a href="javascript:hideError('error');">Hide</a></p></div>
{/if}

<div id="main">
<p>An email has been sent you the address you provided. Please click the link
included in the email to complete your registration.</p>

<p><a href="index.php">Return to the Sign In page</a></p>
</div>
{include file='html_footer.tpl'}
