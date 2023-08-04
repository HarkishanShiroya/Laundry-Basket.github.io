<?php
$host='localhost';
$username='root';
$password='';
$dbname = "login_register";
$conn=mysqli_connect($host,$username,$password,"$dbname","3307");
if(!$conn)
 {
//  die('Connection error:' .mysql_error());
 }
 if ($conn)
 {
//  echo "Connection established successfully";
 }?>