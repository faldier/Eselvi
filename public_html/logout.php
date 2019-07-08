<?php
    require_once $_SERVER['DOCUMENT_ROOT'].'/system/init.php';
  unset($_SESSION['Session_user']);
  header('Location: index.php');
?>
