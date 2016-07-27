<?php
$debug=false;
$link = mysql_connect('localhost', 'root', '091095');
if (!$link) {
    die('Could not connect: ' . mysql_error());
}

$db_selected = mysql_select_db( 'project' , $link);
if (!$db_selected) {
    die ("Can't use internet_database : " . mysql_error());
}
mysql_query("SET NAMES utf8");