<?php
 session_start();
 unset($_SESSION['user']);
  unset($_SESSION['admin']);
 header('location:index.php');
 ?>