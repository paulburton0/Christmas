{* Smarty *}

{include file='html_header.tpl' title='Welcome'}

<body>

{include file='links.tpl'}

{if $error}
<div id="error" style="display: block;">{$error}
<p id="error_toggler"><a href="javascript:hideError('error');">Hide</a></p></div>
{/if}

<div id="main">
<p>Welcome, {$name}</p>

<p>Your assigned person is {$assigned.name}.</p>

{if $wishlist}
<p>Here's {$assigned.name}'s wish list:</p>
{html_table loop=$wishlist cols=3 table_attr='border="0" id="wishlist"' tr_attr=$tr td_attr='class="wishlist_td"'}
{else}
<p>{$assigned.name} hasn't added a wish list yet.</p>
{/if}
</div>

{include file='html_footer.tpl'}
