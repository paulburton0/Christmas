{* Smarty *}

{include file='html_header.tpl' title='Your Wish List'}

<body>

{include file='links.tpl'}

{if $error}
<div id="error" style="display: block;">{$error}
<p id="error_toggler"><a href="javascript:hideError('error');">Hide</a></p></div>
{/if}

<div id="c_error" style="display: none;"></div>

<div id="main">
<p>Edit Your Wish List</p>
<div id="desc" style="display: block;">
<p>You may add as many items to your wish list as you want. Each item must have at least a <b>Title</b> and a <b>Description</b>. If you wish, you may add links to websites containing more information about an item. To add a link, type or paste a link into the <b>Link</b> field and click <b>Add Link</b>. The link appears in the box. To remove a link, select it in the box, and click <b>Remove Link</b>.</p>
<p>When you're finished adding a description and links, you may add the item to your wish list by clicking <b>Add Item</b>. To delete an item from the list, click the <b>Delete</b> button on that row.</p>
</div>
<div id="toggler"><a href="javascript:toggleVisibility('desc', 'toggler');">hide instructions</a></div>
<form name="add_wishlist_item" method="post"
action="{$PHP_SELF}?page=wishlist&action=additem" onsubmit="selectAllOptions('links_list'); return validateWishlist(getElementById('item_title'), getElementById('item_description'));">

	<label for="title"><b>Title:</b></label><br />
	<input id="item_title" type="text" size="80" maxlength="80" name="title" /><br /><br />

	<label for="description"><b>Description:</b></label><br />
	<textarea id="item_description" name="description" cols="80" rows="4"></textarea><br /><br />

	<label for="link">Link:</label><br />
	<input type="text" size="80" maxlength="500" name="link" /><br /><br />

	{html_options id=links_list name='links_list[]' multiple="yes" size=4 options=$links}<br />

	<input type="button" value="Add Link" onclick="appendLink(this.form.links_list, this.form.link.value, this.form.link.value); this.form.link.value = '';" />
	<input type="button" value="Remove Link" onclick="removeLink(this.form.links_list);" /><br /><br />

	<input type="submit" value="Add Item to Wish List" />

</form>

<br />

{if $wishlist}
{html_table loop=$wishlist cols=4 table_attr='border="0" id="wishlist"' tr_attr=$tr td_attr='class="wishlist_td"'}
{/if}

</div>
{include file='html_footer.tpl'}
