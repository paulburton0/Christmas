{* Smarty *}

<div id="links">
<a href="index.php?page=main"><span class="links">Main</span></a>
<a href="index.php?page=wishlist"><span class="links">Wish List</span></a>
<a href="index.php?page=infochange"><span class="links">Change Email/Password</span></a>
{if $admin == 'Y'}
<a href="index.php?page=admin"><span class="links">Admin</span></a>
{/if}
<a href="index.php?page=logout"><span class="links">Log Out</span></a>
</div>
