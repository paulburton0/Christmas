<?php

$mysql_server   = 'localhost';
$mysql_username = 'lpburton_christm';
$mysql_password = 'L1nd3R19';
$mysql_db_name  = 'lpburton_people';

$mysql_link = mysql_connect($mysql_server, $mysql_username, $mysql_password);

if (!$mysql_link) {
    die('Could not connect: ' . mysql_error());
}

mysql_select_db($mysql_db_name);

?>
