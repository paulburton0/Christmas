{* Smarty *}

{include file='html_header.tpl' title='Welcome'}

<body>

{include file='links.tpl'}

{if $error}
<div id="error" style="display: block;">{$error}
<p id="error_toggler"><a href="javascript:hideError('error');">Hide</a></p></div>
{/if}


<div id="main">
<p><a href="{$PHP_SELF}?page=admin&action=reset">Reset the database</a></p>
{if !$assigned_person}
<p><a href="{$PHP_SELF}?page=admin&action=draw">Draw a name</a></p>
{/if}
</div>

{include file='html_footer.tpl'}
