<?php
include("./db_conn.php");
error_reporting(0);
session_start();

mysqli_query($conn,"DELETE FROM dishes WHERE d_id = '".$_GET['id']."'");
header("location:all_menu.php");
?>