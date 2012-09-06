<?php
include 'includes/db_connect.php';

$email = 'auntasuzie@verizon.net';
$pw = 'Password';

$pw_crypt = crypt($pw, $email);

$sql = "UPDATE `people` SET `password` = '{$pw_crypt}' WHERE `id` = '5'";

mysql_query($sql);
?>
