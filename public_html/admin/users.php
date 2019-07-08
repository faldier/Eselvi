<?php
ob_start();
    require_once $_SERVER['DOCUMENT_ROOT'].'/system/init.php';
  if(!is_logged_in()){
    login_error_redirect();
  }
  if(!has_permission()){
    permission_error_redirect();
  }

  include 'includes/head.php';
  include 'includes/navigation.php';
  include 'includes/php_functions.php';

  if(isset($_GET['delete'])){
    delete_row("users" , $_GET['delete'] ,"users.php", $conn);
  }

  if(isset($_GET['add'])){
    $username = ((isset($_POST['username']))?sanitize($_POST['username']):'');
    $email = ((isset($_POST['email']))?sanitize($_POST['email']):'');
    $password = ((isset($_POST['password']))?sanitize($_POST['password']):'');
    $confirm = ((isset($_POST['confirm']))?sanitize($_POST['confirm']):'');
    $permissions = ((isset($_POST['permissions']))?sanitize($_POST['permissions']):'');
    $errors =array();

    if($_POST){
      $emailQuery = $conn->query("SELECT * FROM users WHERE email = '$email'");
      $emailCount = mysqli_fetch_assoc($emailQuery);

      if($emailCount != 0){
        $errors[] = 'That email alreade exist in our database';
      }

      $usernameQuery = $conn->query("SELECT * FROM users WHERE username = '$username'");
      $userCount = mysqli_fetch_assoc($usernameQuery);

      if($userCount != 0){
        $errors[] = 'That username is already been taken';
      }


      $required = array('username', 'email', 'password', 'confirm');
      foreach ($required as $fields) {
        if(empty($_POST[$fields])){
          $errors[] = 'You must fill out all fields';
        }
        break;
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
        $conn->query("INSERT INTO users(username, email, password, permissions) VALUES('$username', '$email', '$hashed', '$permissions')");
        header('Location: users.php');
      }
    }
    ?>
    <h2 class="text-center">Add A New User</h2><hr>
    <form action="users.php?add=1" method="post">
      <div class="form-group col-md-6">
        <label for="name">Username:</label>
        <input type="text" name="username" id="username" class="form-control" value="">
      </div>
      <div class="form-group col-md-6">
        <label for="email">Email:</label>
        <input type="email" name="email" id="email" class="form-control" value="">
      </div>
      <div class="form-group col-md-6">
        <label for="password">Password:</label>
        <input type="password" name="password" id="password" class="form-control" value="">
      </div>
      <div class="form-group col-md-6">
        <label for="confirm">Confirm Password:</label>
        <input type="password" name="confirm" id="confirm" class="form-control" value="">
      </div>
      <div class="form-group col-md-6">
        <label for="permissions">User Permissions</label>
        <select class="form-control" id="permissions" name="permissions">
          <option value="0">Normal</option>
          <option value="1">Admin</option>
        </select>
      </div>
      <div class="form-group col-md-6 text-right">
        <a href="users.php" class="btn btn-light">Cancel</a>
        <input type="submit" value="Add User" class="btn btn-primary">
      </div>
    </form>

    <?php
  }else{
    $userQuery = $conn->query("SELECT * FROM users ORDER BY username");
    ?>
    <h2>Users</h2><hr>
    <a href="users.php?add=1" class="btn btn-success float-right" id="add-product-btn">Add new user</a>
    <table class="table table-bordered table-striped table-ondensed">
      <thead>
        <th></th>
        <th>Name</th>
        <th>Email</th>
        <th>Join Date</th>
        <th>Last Login</th>
        <th>Permissions</th>
      </thead>
      <tbody>
        <?php while ($user = mysqli_fetch_assoc($userQuery)): ?>
          <tr>
            <td><a href="users.php?delete=<?=$user['id'];?>" class="btn btn-success">Delete</a></td>
            <td><?=$user['username'];?></td>
            <td><?=$user['email'];?></td>
            <td><?=pretty_date($user['join_date']);?></td>
            <td><?=(($user['last_login'] == '0000-00-00 00:00:00')?'Never':pretty_date($user['last_login']));?></td>
            <td><?=(($user['permissions'] == 0)? 'Normal': 'Admin');?></td>
          </tr>
      <?php endwhile; ?>
    </tbody>
  </table>


<?php
}

 ?>
<?php ob_end_flush(); ?>
