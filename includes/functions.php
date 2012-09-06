<?php
include('db_connect.php');

function get_names(){

	$names  = array();
	array_push($names, 'Select your name...');
	$sql    = "SELECT `name` from `people` ORDER BY `id` ASC";
	$result = mysql_query($sql);

	while($row = mysql_fetch_assoc($result)){
		array_push($names, $row['name']);
	}

	return($names);
}


function user_login($email, $password){

	$email = mysql_real_escape_string($email);

	$password_crypt = crypt($password, $email);
	
	$sql	= "SELECT * FROM `people` WHERE `email` = '{$email}' and `password` = '{$password_crypt}'";
	
	$result = mysql_query($sql);

	if(!$result){
		$error_msg = "Could not successfully run query ({$sql}) from DB: " . mysql_error();
		mail_admin($error_msg, null);
		$_SESSION['error'] = 'Your request can not be completed at this time. The Webmaster has been notified of the problem. Please try again later.';
		return false;
	}

	if(mysql_num_rows($result) == 0){
		$_SESSION['error'] = 'Could not log you in using those credentials. Check the email and password and try again.';
		return false;
	}
	
	$row    = mysql_fetch_assoc($result);
	
	if($row['active'] == 'Y'){
		setcookie("ChristmasCookie", $password_crypt, time()+3600);
		$_SESSION = $row;
		return true;
	}

	else{
		$_SESSION['error'] = 'There was a problem logging you in. Please try again.';
		$error_msg = "Could not successfully run query ({$sql}) from DB: " . mysql_error();
		mail_admin($error_msg, null);
		return false;
	}
}


function register($registration){

	$registration['email'] = mysql_real_escape_string($registration['email']);
	$registration['retyped_email'] = mysql_real_escape_string($registration['retyped_email']);
	$registration['password'] = mysql_real_escape_string($registration['password']);
	$registration['retyped_password'] = mysql_real_escape_string($registration['retyped_password']);
	
	if(($registration['email'] == '' ) || ($registration['retyped_email'] == '') || ($registration['password'] == '') || ($registration['retyped_password'] == '')){
		$_SESSION['error'] = 'Please complete the registration form.';
		return false;
	}

	if($registration['email'] != $registration['retyped_email']){
		$_SESSION['error'] = 'The email addresses do not match';
		return false;
	}

	if($registration['password'] != $registration['retyped_password']){
		$_SESSION['error'] = 'The passwords do not match';
		return false;
	}

	if(!preg_match("/^[-\w._]+@[-\w]+\.\w+$/", $registration['email'])){
		$_SESSION['error'] = 'Please type a valid email address.';
		return false;
	}

	$password_crypt = crypt($registration['password'], $registration['email']);

	$sql = "SELECT `password` FROM `people` WHERE 1";

	$result = mysql_query($sql);

	while($row = mysql_fetch_assoc($result)){
		if($password_crypt == $row['password']){
			$_SESSION['error'] = 'That email/password combination is already in use.<br />Please choose a different email or password.';
			return false;
		}
	}

	$sql = "SELECT * FROM `people` WHERE `id` = '{$registration['id']}'";

	$result = mysql_query($sql);

	$row = mysql_fetch_assoc($result);
	
	if($row['active'] == 'N'){
	
		$sql = "UPDATE `people` SET `email` = '{$registration['email']}', `password` = '{$password_crypt}' WHERE `id` = '{$registration['id']}' LIMIT 1";
	
		mysql_query($sql);
	
		if(mysql_affected_rows() != 1){
			$error_msg = "Could not successfully run query ({$sql}) from DB: " . mysql_error();
			mail_admin($error_msg, $_SESSION['name']);
			$_SESSION['error'] = 'Your request can not be completed at this time. The Webmaster has been notified of the problem. Please try again later.';
			return false;
		}

		else{return true;}
	}

	else{
		$_SESSION['error'] = 'The user account is already activated. Please log in to access the site.';
		return false;
	}
}



function send_activation($email, $id){
	
	$sql = "SELECT `name` FROM `people` WHERE `id` = '$id' LIMIT 1";
	$result = mysql_query($sql);
	$name = mysql_fetch_array($result);
	$name = $name[0];
	$address_crypt = crypt($email, $id);
	$subject       = $name.', please activate your Christmas website account';
	$body          = "<html>\r\n
			  <body>\r\n
			  Dear {$name},<br />\r\n
		          In order to use the christmas.mountaintopweather.com web site, you need to activate your account.\r\n
			  Please click the link below to do so.<br />\r\n
			  <a href=\"http://christmas.mountaintopweather.com/index.php?page=activate&id={$id}&code={$address_crypt}\">http://christmas.mountaintopweather.com/index.php?page=activate&id={$id}&code={$address_crypt}</a><br />\r\n
			  If the above address is not a clickable link, please copy it and paste it into your browser's address bar to activate your account.\r\n
			  </body>\r\n
			  </html>";

	$headers  = 'MIME-Version: 1.0' . "\r\n";
	$headers .= 'From: Mountaintop Santa <mountaintopsanta@mountaintopweather.com>' . "\r\n";
	$headers .= 'Content-type: text/html; charset=iso-8859-1';


	mail($email, $subject, $body, $headers);
}


function activate_account($id, $address_crypt){

	$sql = "SELECT * FROM `people` WHERE `id` = '$id'";
	
	$result = mysql_query($sql);
	
	$row = mysql_fetch_assoc($result);

	if($row['active'] == 'Y'){
		$_SESSION['error'] = 'Your account is already activated. Please log in to access the site.';
		return false;
	}
	
	if(crypt($row['email'], $id) == $address_crypt){
		$sql = "UPDATE `people` SET `active` = 'Y' WHERE `id` = '{$id}'";
		mysql_query($sql);
		if(mysql_affected_rows() != 1){
			$error_msg = "Could not successfully run query ({$sql}) from DB: " . mysql_error();
			mail_admin($error_msg, $_SESSION['name']);
			$_SESSION['error'] = 'Your request can not be completed at this time. The Webmaster has been notified of the problem. Please try again later.';

			return false;
		}
		setcookie("ChristmasCookie", $address_crypt, time()+3600);
		$_SESSION = $row;
		if(draw_name($row)){
			return true;
		}

		else{
			$error_msg = "Could not successfully run query ({$sql}) from DB: " . mysql_error();
			mail_admin($error_msg, $_SESSION['name']);
			$_SESSION['error'] = 'Your request can not be completed at this time. The Webmaster has been notified of the problem. Please try again later.';
			return false;
		}
	}
	
	else{return false;}
}


function draw_name($row){

	if(isset($row['assigned_person_id'])){
		return true;
	}

	if(!($assignee_row = get_assignee($row))){
		return false;
	}
	
	$assigner_sql = "UPDATE `people` SET `assigned_person_id` = '{$assignee_row['id']}' WHERE `id` = '{$row['id']}'";
	
	mysql_query($assigner_sql);

	if(mysql_affected_rows() != 1){
		return false;
	}

	$assigned_sql = "UPDATE `people` SET `is_assigned` = 'Y' WHERE `id` = '{$assignee_row['id']}'";
	
	mysql_query($assigned_sql);

	if(mysql_affected_rows() != 1){
		return false;
	}

	$_SESSION['assigned_person_id'] = $assignee_row['id'];

	email_assigned($row['id'], $assignee_row['name']);
	
	return true;

}

function lookup_assigned($id){

	$sql = "SELECT * FROM `people` WHERE `id` = {$id}";

	$result = mysql_query($sql);

	$row = mysql_fetch_assoc($result);

	return($row);
}

function reset_password($id, $email){

$email = mysql_real_escape_string($email);
	
	$new_pass = createRandomPassword();

	$password_crypt = crypt($new_pass, $email);
	
	$sql = "UPDATE `people` SET `password` = '{$password_crypt}' WHERE `id` = '{$id}'";
	
	mysql_query($sql);
		if(mysql_affected_rows() != 1){
			$error_msg = "Could not successfully run query ({$sql}) from DB: " . mysql_error();
			mail_admin($error_msg, $_SESSION['name']);
			$_SESSION['error'] = 'Your request can not be completed at this time. The Webmaster has been notified of the problem. Please try again later.';
		}
	password_reset_email($id, $email, $new_pass);

}

function createRandomPassword(){

	$chars = "abcdefghijkmnopqrstuvwxyz023456789";

	srand((double)microtime()*1000000);

	$i = 0;

	$pass = '' ;

	while ($i <= 7) {

		$num = rand() % 33;
		$tmp = substr($chars, $num, 1);
		$pass = $pass . $tmp;
		$i++;
	}

	return $pass;
}

function password_reset_email($id, $email, $password){

	$sql = "SELECT `name` FROM `people` WHERE `id` = '{$id}' LIMIT 1";
	$result = mysql_query($sql);
	$name = mysql_fetch_array($result);
	$name = $name[0];

	$subject       = $name.', here\'s your new password for christmas.mountaintopweather.com';

	$body          = "<html>\r\n
			  <body>\r\n
			  Dear {$name},<br />\r\n
		          Below is your new password for christmas.mountaintopweather.com.<br />\r\n
			  <br />\r\n
			  Password: {$password}<br />\r\n
			  <br />\r\n
			  Go to <a href=\"http://christmas.mountaintopweather.com/\">http://christmas.mountaintopweather.com</a><br />\r\n
			  to log in with this new password.<br />\r\n
			  It is recommended that you change your password after logging in.\r\n
			  </body>\r\n
			  </html>";

	$headers  = 'MIME-Version: 1.0' . "\r\n";
	$headers .= 'From: Mountaintop Santa <mountaintopsanta@mountaintopweather.com>' . "\r\n";
	$headers .= 'Content-type: text/html; charset=iso-8859-1';


	mail($email, $subject, $body, $headers);
}

function email_assigned($id, $assignee_name){

	$sql = "SELECT * FROM `people` WHERE `id` = '{$id}' LIMIT 1";
	$result = mysql_query($sql);
	$row = mysql_fetch_assoc($result);
	$name = $row['name'];

	$subject       = $name.', here\'s the name you drew at christmas.mountaintopweather.com';

	$body          = "<html>\r\n
			  <body>\r\n
			  Dear {$name},<br />\r\n
		          Below is the name you drew at christmas.mountaintopweather.com.<br />\r\n
			  <br />\r\n
			  {$assignee_name}<br />\r\n
			  <br />\r\n
			  Go to <a href=\"http://christmas.mountaintopweather.com/\">http://christmas.mountaintopweather.com</a><br />\r\n
			  see whether {$assignee_name} has added a wish list yet.<br />\r\n
			  </body>\r\n
			  </html>";

	$headers  = 'MIME-Version: 1.0' . "\r\n";
	$headers .= 'From: Mountaintop Santa <mountaintopsanta@mountaintopweather.com>' . "\r\n";
	$headers .= 'Content-type: text/html; charset=iso-8859-1';


	mail($row['email'], $subject, $body, $headers);
}

function update_email($new_email, $retyped_new_email){

	$new_email = mysql_real_escape_string($new_email);
	$retyped_new_email = mysql_real_escape_string($retyped_new_email);

	if($new_email != $retyped_new_email){
		$_SESSION['error'] = 'The emails do not match.';
		return false;
	}

	if($_SESSION['email'] == $new_email){
		$_SESSION['error'] = 'The new email can not be the same as the current email.';
		return false;
	}

	if(!preg_match("/^[-\w._]+@[-\w]+\.\w+$/", $new_email)){
		$_SESSION['error'] = 'Please type a valid email adress.';
		return false;
	}

	else{
		$sql = "UPDATE `people` SET `email` = '{$new_email}' WHERE `id` = '{$_SESSION['id']}'";
		mysql_query($sql);
		if(mysql_affected_rows() != 1){
			$error_msg = "Could not successfully run query ({$sql}) from DB: " . mysql_error();
			mail_admin($error_msg, $_SESSION['name']);
			$_SESSION['error'] = 'Your request can not be completed at this time. The Webmaster has been notified of the problem. Please try again later.';
			return false;
		}

		$_SESSION['email'] = $new_email;
		return true;
	}

}

function update_password($old_password, $new_password, $retyped_new_password){

	if(isset($_SESSION['error'])){
		$_SESSION['error'] .= '<br />';
	}

	if($new_password != $retyped_new_password){

		$_SESSION['error'] .= 'The passwords do not match.';
		return false;
	}

	if(crypt($old_password, $_SESSION['email']) != $_SESSION['password']){
		
		$_SESSION['error'] .= 'Incorrect old password.';
		return false;
	}

	if($old_password == $new_password){
		$_SESSION['error'] .= 'Old and new passwords can not be identical.';
		return false;
	}
	
	else{
		$password_crypt = crypt($new_password, $_SESSION['email']);
		$sql = "UPDATE `people` SET `password` = '{$password_crypt}' WHERE `id` = '{$_SESSION['id']}'";
		mysql_query($sql);
		if(mysql_affected_rows() != 1){
			$error_msg = "Could not successfully run query ({$sql}) from DB: " . mysql_error();
			mail_admin($error_msg, $_SESSION['name']);
			$_SESSION['error'] .= 'Your request can not be completed at this time. The Webmaster has been notified of the problem. Please try again later.';
			return false;
		}
		
		else{
			$_SESSION['error'] .= 'Your password has been successfully changed.';
			$_SESSION['password'] = $password_crypt;
			return true;
		}
	}

}

function get_wishlist($id){

	if($id == $_SESSION['id']){
		$sql = "SELECT `id`, `title`, `description`, `links` FROM `wishlist` WHERE `person_id` = '{$id}' AND (`status` = 'N' OR `status` IS NULL)";
		$list = array('<b>Actions</b>','<b>Title</b>','<b>Description</b>','<b>Links</b>');

	}

	else{
		$sql = "SELECT `title`, `description`, `links` FROM `wishlist` WHERE `person_id` = '{$id}' AND (`status` = 'N' OR `status` IS NULL)";
		$list = array('<b>Title</b>','<b>Description</b>','<b>Links</b>');

	}

	$result = mysql_query($sql);

	if(mysql_num_rows($result) == 0){
		return false;
	}


	while($row = mysql_fetch_row($result)){

		if($id == $_SESSION['id']){
			$row[0] = "<a href=\"{$_SERVER['PHP_SELF']}?page=wishlist&action=deleteitem&id={$row['0']}\"><img src=\"http://mountaintopweather.com/christmas/images/delete.jpeg\" alt=\"Click to delete this row\" title=\"Click to delete this row\" /></a>";
		}

		foreach($row as $item){
			$item = stripslashes($item);
			$item = nl2br($item);
			array_push($list, $item);
		}
	}

	return($list);
}

function add_item($data){

	if(($data['title'] == '') || ($data['description'] == '')){
		$_SESSION['error'] = 'You must complete at least the Title and Description fields.';
		return false;
	}
	foreach($data as $unclean){
		$clean = strip_tags($unclean);
	}

	$all_links = '';

	if(isset($data['links_list'])){

		foreach($data['links_list'] as $link){
			if(ereg("http://", $link)){
				$link = '<a href="'.$link.'" target="_blank">'.$link.'</a>';
			}
			else{$link = '<a href="http://'.$link.'" target="_blank">'.$link.'</a>';}
			$all_links .= $link .'<br />';
		}
	}

	$title 		= mysql_real_escape_string($data['title']);
	$description 	= mysql_real_escape_string($data['description']);
	$links 		= mysql_real_escape_string($links);

	$sql = "INSERT INTO `wishlist` VALUES('', '{$_SESSION['id']}', '{$title}', '{$description}', '{$all_links}', NULL, 'N')";

	mysql_query($sql);

	if(mysql_affected_rows() != 1){
		$error_msg = "Could not successfully run query ({$sql}) from DB: " . mysql_error();
		mail_admin($error_msg, $_SESSION['name']);
		$_SESSION['error'] = 'Your request can not be completed at this time. The Webmaster has been notified of the problem. Please try again later.';
		return false;
	}
	
	return true;	
	
}


function delete_item($id){

	$sql = "UPDATE `wishlist` SET `status` = 'D' WHERE `id` = '{$id}'";

	mysql_query($sql);

	if(mysql_affected_rows() != 1){
		$error_msg = "Could not successfully run query ({$sql}) from DB: " . mysql_error();
		mail_admin($error_msg, $_SESSION['name']);
		$_SESSION['error'] = 'Your request can not be completed at this time. The Webmaster has been notified of the problem. Please try again later.';
		return false;
	}
	
	unset($_GET['action']);
	return true;	
	
}


function mail_admin($error_msg, $user){

	$time	       = date('j-n-Y H:i:s');

	$subject       = 'christmas.mountaintopweather.com Error Report';

	$body          = "<html>\r\n
			  <body>\r\n
			  There was an error on the christmas.mountaintopweather.com website.<br />\r\n
		          Below is the error that was encountered:<br />\r\n
			  <br />\r\n
			  {$error_msg}<br />\r\n
			  <br />\r\n
			  The user who encountered the error is:<br />\r\n
			  {$user}<br />\r\n
			  at {$time}<br />\r\n
			  </body>\r\n
			  </html>";

	$headers  = 'MIME-Version: 1.0' . "\r\n";
	$headers .= 'From: Mountaintop Santa <mountaintopsanta@mountaintopweather.com>' . "\r\n";
	$headers .= 'Content-type: text/html; charset=iso-8859-1';

	$to_email = 'paulburto@gmail.com';


	mail($to_email, $subject, $body, $headers);
}

function reset_db(){

	$sql = "UPDATE `people` SET `email` = NULL, `password` = NULL, `assigned_person_id` = NULL, `is_assigned` = 'N', `active` = 'N' WHERE `is_admin` = 'N'";
	
	mysql_query($sql);

	$sql = "UPDATE `people` SET `assigned_person_id` = NULL, `is_assigned` = 'N' WHERE `is_admin` = 'Y'";
	
	mysql_query($sql);

	$_SESSION['is_assigned'] = 'N';
	unset($_SESSION['assigned_person_id']);

	$ids = array();

	$sql = "SELECT `id` FROM `people` WHERE `location` = 'W' ORDER BY RAND() LIMIT 1";

	$result = mysql_query($sql);

	$row = mysql_fetch_row($result);

	$assigner_id = $row[0];

	$sql = "SELECT `id` FROM `people` WHERE `location` = 'W' AND `spouse_id` != '{$assigner_id}' AND `id` != '{$assigner_id}' ORDER BY RAND() LIMIT 1";

	$result = mysql_query($sql);

	$row = mysql_fetch_row($result);

	$assigned_id = $row[0];

	$sql = "UPDATE `people` SET `assigned_person_id` = '{$assigned_id}' WHERE `id` = '{$assigner_id}' LIMIT 1";

	mysql_query($sql);

	if(mysql_affected_rows() == 0){

		$_SESSION['error'] = 'Could not pre-set the people table: '. mysql_error();
		return false;
	}

	$sql = "UPDATE `people` SET `is_assigned` = 'Y' WHERE `id` = '{$assigned_id}' LIMIT 1";

	mysql_query($sql);

	if(mysql_affected_rows() == 0){

		$_SESSION['error'] = 'Could not preset the people table: '. mysql_error();
		return false;
	}


	$sql = "TRUNCATE `wishlist`";

	mysql_query($sql);

	return true;

}


function get_assignee($row){

	$sql = "SELECT * FROM `people` WHERE `id` != '{$row['id']}' AND `id` != '{$row['spouse_id']}' AND `location` != '{$row['location']}' AND `is_assigned` = 'N' ORDER BY RAND() LIMIT 1";
	$result = mysql_query($sql);
	return(mysql_fetch_assoc($result));
}
