<?php
  require_once $_SERVER['DOCUMENT_ROOT'].'/system/init.php';
include 'includes/head.php';
include 'includes/navigation.php';

if(isset($_GET['add'])){
  $username = ((isset($_POST['username']))?sanitize($_POST['username']):'');
  $email = ((isset($_POST['email']))?sanitize($_POST['email']):'');
  $password = ((isset($_POST['password']))?sanitize($_POST['password']):'');
  $confirm = ((isset($_POST['confirm']))?sanitize($_POST['confirm']):'');

  if($_POST){
    $errors =array();
    $emailQuery = $conn->query("SELECT * FROM users WHERE email= '$email'");
    $emailCount = mysqli_fetch_assoc($emailQuery);

    $userQuery = $conn->query("SELECT * FROM users WHERE username= '$username'");
    $userCount = mysqli_fetch_assoc($userQuery);

    $required = array('username', 'email', 'password', 'confirm');

    foreach ($required as $fields) {
      if(empty($_POST[$fields])){
        $errors[] = 'You must fill out all fields';
      }
      break;
    }

    if($emailCount != 0){
      $errors[] = 'That email alreade exist in our database';
    }
    if($userCount != 0){
      $errors[] = 'That username alreade exist in our database';
    }

    if(strlen($password)<6){
      $errors[]="your password must be at least 6 characters";
    }

    if($password != $confirm){
      $errors[]="your passwords does not match";
    }

    if(!filter_var($email,FILTER_VALIDATE_EMAIL)){
      $errors[]="You must enter a valid Email";
    }

    if(!empty($errors)){
      echo (display_errors($errors));

    }else{
        // Add user to database
        $hashed = password_hash($password,PASSWORD_DEFAULT);
        $conn->query("INSERT INTO users(username, email, password) VALUES('$username', '$email', '$hashed')");
        header('Location: index.php');

    }
  }
}?>

    <form action="register.php?add=1" method="post">
      <div class="form-group col-md-6">
        <label for="username">Username:</label>
        <input type="text" name="username" id="username" class="form-control" >
      </div>
      <div class="form-group col-md-6">
        <label for="email">Email:</label>
        <input type="email" name="email" id="email" class="form-control" >
      </div>
      <div class="form-group col-md-6">
        <label for="password">Password:</label>
        <input type="password" name="password" id="password" class="form-control" >
      </div>
      <div class="form-group col-md-6">
        <label for="confirm">Confirm Password:</label>
        <input type="password" name="confirm" id="confirm" class="form-control" >
      </div>
      <input type="submit" value="Sing up" class="btn btn-primary">
    </form>
<?php include 'includes/footer.php'; ?>
