<?php
include("./db_conn.php");
error_reporting(0);
session_start();

mysqli_query($conn,"DELETE FROM users_orders WHERE o_id = '".$_GET['order_del']."'");
header("location:order.php");  

?>
