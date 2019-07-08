<?php
  ob_start();
  function display_errors($errors){
    $display = '<ul>';
    foreach ($errors as $error) {
      $display .= '<li class="text-center text-danger">'.$error.'</li>';
    }
    $display .='</ul>';
    return $display;
  }

  function sanitize($dirty){
    return htmlentities ($dirty, ENT_QUOTES, "UTF-8");
  }

  function money($number){
    return '$'.number_format($number, 2);
  }

  function login($user_id){
    $_SESSION['Session_user'] = $user_id;
    global $conn;
    $date = date("Y-m-d H:i:s");
    $conn->query("UPDATE users SET last_login='$date' WHERE id = '$user_id'");
    header('Location:index.php');
  }

  function is_logged_in(){
    if(isset($_SESSION['Session_user']) && $_SESSION['Session_user'] > 0){
      return true;
    }
    return false;
  }

  function login_error_redirect(){
    header('Location:../login.php');
  }


  function has_permission(){
    global $user_data;

    if($user_data['permissions'] == '1'){
      return true;
    }
    return false;
  }

  function permission_error_redirect(){
    header('Location:../index.php');
  }

  function pretty_date($date){
    return date("M d, Y h:i A" , strtotime($date));
  }

 ?>
<?php ob_end_flush(); ?>
