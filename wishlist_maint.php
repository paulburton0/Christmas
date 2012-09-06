<?php

include('includes/db_connect.php');

$sql = "SELECT `id`, `person_id`, `status` FROM `wishlist` WHERE `status` IS NOT NULL";

$result = mysql_query($sql);

if(mysql_num_rows($result) == 0){
	exit;
}

$ids 	     = array();
$add_rows    = array();
$delete_rows = array();

while($row = mysql_fetch_assoc($result)){
	array_push($ids, $row['person_id']);
	if($row['status'] == 'N'){
		array_push($add_rows, $row['id']);
	}

	elseif($row['status'] == 'D'){
		array_push($delete_rows, $row['id']);
	}

	else{
		echo('There was an error processing the wish list updates: '. mysql_error());	
	}
}

$ids = array_unique($ids);

foreach($ids as $id){
	$sql = "SELECT `name`, `email` FROM `people` WHERE `assigned_person_id` = '{$id}'";
	$result = mysql_query($sql);
	if(mysql_num_rows($result) != 0){
		$row = mysql_fetch_assoc($result);
		if(!wishlist_email($row['name'], $row['email'], $id)){
			echo('Could not send the wish list update email.');
		}
	}
}

foreach($delete_rows as $row){
	$sql = "DELETE FROM `wishlist` WHERE `id` = '{$row}'";
	mysql_query($sql);
	if(mysql_affected_rows() == 0){
		echo('There was a problem deleting rows: '. mysql_error());
	}
}

foreach($add_rows as $row){
	$sql = "UPDATE `wishlist` SET `status` = NULL WHERE `id` = '{$row}'";
	mysql_query($sql);
	if(mysql_affected_rows() == 0){
		echo('There was a problem updating new rows: '. mysql_error());
	}

}



function wishlist_email($name, $email, $id){

	$sql = "SELECT `title`, `description`, `links`, `status` FROM `wishlist` WHERE `person_id` = '{$id}' AND `status` IS NOT NULL";

	$result = mysql_query($sql);

	if(mysql_num_rows($result) == 0){
		echo('Could not get the updated wishlist items in order to make the table: '. mysql_error());
		return false;
	}

	$new_rows = array();
	$deleted_rows = array();

	while( $row = mysql_fetch_assoc($result)){
		if($row['status'] == 'N'){
			array_push($new_rows, $row);
		}

		elseif($row['status'] == 'D'){
			array_push($deleted_rows, $row);
		}
	}

	if(isset($new_rows[0])){
		$new_table  = "<p>Added Rows</p>";
		$new_table .= "<table border=\"1\" cellpadding=\"3\">\r\n";
		$new_table .= "<tr>\r\n";
		$new_table .= "<td class=\"wishlist_td\"><b>Title</b></td>\r\n";
		$new_table .= "<td class=\"wishlist_td\"><b>Description</b></td>\r\n";
		$new_table .= "<td class=\"wishlist_td\"><b>Links</b></td>\r\n";
		$new_table .= "</tr>\r\n";

		foreach($new_rows as $new_row){
			$new_table .= "<tr>\r\n";

			while ($new_cell = current($new_row)) {
				if (key($new_row) == ('status')) {
       					next($new_row);
				}

				else{
					$new_table .= "<td class=\"wishlist_td\">{$new_cell}</td>\r\n";
					next($new_row);
				}
			}

			$new_table .= "</tr>\r\n";
		}

		$new_table .= "</table>\r\n";
	}

	if(isset($deleted_rows[0])){
		$deleted_table  = "<p>Deleted Rows</p>";
		$deleted_table .= "<table border=\"1\" cellpadding=\"3\">\r\n";
		$deleted_table .= "<tr class=\"wishlist_td\">\r\n";
		$deleted_table .= "<td class=\"wishlist_td\"><b>Title</b></td>\r\n";
		$deleted_table .= "<td class=\"wishlist_td\"><b>Description</b></td>\r\n";
		$deleted_table .= "<td class=\"wishlist_td\"><b>Links</b></td>\r\n";
		$deleted_table .= "</tr>\r\n";

		foreach($deleted_rows as $deleted_row){
			$deleted_table .= "<tr>\r\n";
			while ($deleted_cell = current($deleted_row)) {
    				if (key($deleted_row) == 'status') {
       					next($deleted_row);
				}

				else{
					$deleted_table .= "<td class=\"wishlist_td\">{$deleted_cell}</td>\r\n";
					next($deleted_row);
				}
			}
			$deleted_table .= "</tr>\r\n";
		}

		$deleted_table .= "</table>\r\n";
	}

	$sql = "SELECT `name` FROM `people` WHERE `id` = '{$id}'";

	$result = mysql_query($sql);

	if(mysql_num_rows($result) != 1){
		echo('Could not get the assignee\'s name: '. mysql_error());
	}

	$assigned_name = mysql_fetch_row($result);

	$subject       = "{$name},  here's a wish list update for you";

	$body          = <<<EOB

<html>\r\n
<head>\r\n
</head>\r\n

<body>\r\n
<div id="main">\r\n

<p>Dear {$name},</p>\r\n
<p>{$assigned_name[0]}, the person you drew at <a href="http://christmas.mountaintopweather.com">christmas.mountaintopweather.com</a> has updated his or her wish list.</p>\r\n

<p>The following changes were made:</p>

{$new_table}

{$deleted_table}

<p>Log in at <a href="http://christmas.mountaintopweather.com">christmas.mountaintopweather.com</a> to see the updated wish list.</p>\r\n

</div>\r\n
</body>\r\n
</html>

EOB;

	$headers  = "MIME-Version: 1.0\r\n";
	$headers .= "From: Mountaintop Santa <mountaintopsanta@mountaintopweather.com>\r\n";
	$headers .= "Content-type: text/html; charset=iso-8859-1\r\n";

	mail($email, $subject, $body, $headers);

	return true;
}

