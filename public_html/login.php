<?php
    ob_start();
  require_once $_SERVER['DOCUMENT_ROOT'].'/system/init.php';
  include 'includes/head.php';
  include 'includes/navigation.php';


  $email = ((isset($_POST['email']))?sanitize($_POST['email']):'');
  $email = trim($email);
  $password = ((isset($_POST['password']))?sanitize($_POST['password']):'');
  $password = trim($password);

  $errors = array();

  if($_POST){
    // Form Validation
    if(empty($_POST['email']) || empty($_POST['password'])){
      $errors[] = 'You must provide email and password.';
    }

    // Password is more than 6 characters
    if(strlen($password) < 6){
      $errors[] = 'Password must be at least 6 characters.';
    }

    //Validate email
    if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
      $errors[] = 'You must enter a valid email.';
    }

    // Check if email exist in the database
    $usersQuery = $conn->query("SELECT * FROM users WHERE email = '$email'");
    $user = mysqli_fetch_assoc($usersQuery);
    $userCount  = mysqli_num_rows($usersQuery);

    if($userCount < 1){
      $errors[] = 'That email doesnt exist in our database.';
    }

    if(!password_verify($password, $user['password'])){
      $errors[] = 'The password does not match.';
    }

    // check for errors
    if(!empty($errors)){
      echo display_errors($errors);
    }else {
      //log user
      $user_id=$user['id'];
      login($user_id);
    }
  }?>

  <div class="row justify-content-md-center">
    <div class="col-md-6">
      <h2 class="text-center">Login</h2><br>
      <form  action="login.php" method="post">
        <div class="form-group">
          <label for="email">Email:</label>
          <input type="email" name="email" id="email" class="form-control" value="<?=$email;?>">
        </div>
        <div class="form-group">
          <label for="password">Password:</label>
          <input type="password" name="password" id="password" class="form-control" value="<?=$password;?>">
        </div>
        <div class="form-group">
          <input type="submit" name="" class="btn btn-success" value="Login">
        </div>
      </form>
    </div>
 </div>

<?php include 'includes/footer.php'; ?>
<?php ob_end_flush(); ?>
