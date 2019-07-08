<?php
ob_start();
    require_once $_SERVER['DOCUMENT_ROOT'].'/system/init.php';
  include 'includes/head.php';
  include 'includes/navigation.php';
  if(!is_logged_in()){
    login_error_redirect();
  }
  if(!has_permission()){
    permission_error_redirect();
  }
 ?>
<?php ob_end_flush(); ?>
