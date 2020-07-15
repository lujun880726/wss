<?php
//error_reporting(0);
ini_set('display_errors', 'on');
//ini_set('display_startup_errors', 'on');

error_reporting(E_ALL);


$hostname_tankdb = "123.56.87.175:8888";    //database host
$database_tankdb = "tankdb";       //database name
$username_tankdb = "listen";         //mysql user name
$password_tankdb = "listen2019";             //mysql password
$tankdb = mysqli_connect($hostname_tankdb, $username_tankdb, $password_tankdb) or trigger_error(mysqli_error(),E_USER_ERROR);
mysqli_query($tankdb,"set names 'utf8'");

require "function.class.php";

$language = "cn";
$advsearch = get_item( 'advsearch' );
$outofdate = get_item( 'outofdate' ) ;
?>
<?php require "multilingual/language_$language".".php"; ?>