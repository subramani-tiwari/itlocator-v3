<?php
$host="localhost";
$user="itlocato_vbforum";
$password="itlocato_vbforum";
$database="itlocato_forum";
$connection =mysql_connect($host,$user,$password);
if(!@$connection)
{
	die("Connection not established");
}

mysql_select_db($database);


$query = "SELECT totalcount FROM vb_node WHERE nodeid ='2'";

$result=mysql_query($query) or die(mysql_error());

$new= mysql_fetch_array($result);



?>