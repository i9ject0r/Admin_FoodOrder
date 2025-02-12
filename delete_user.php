<?php
include("./db_conn.php");
error_reporting(0);
session_start();

mysqli_query($conn,"DELETE FROM users WHERE u_id = '".$_GET['id']."'");
header("Location: user.php");
?>