<?php
  $servername = "localhost";
  $username = "eselvion";
  $password = "memoari93";
  $database = "eselvion_UniversalElectronics";

  $conn = new mysqli($servername, $username, $password ,$database);

   session_start();

  if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
  }
  require_once $_SERVER['DOCUMENT_ROOT'].'/config.php';
  require_once BASEURL.'helpers/helpers.php';
  //
  $cart_id = '';
  if(isset($_COOKIE['Favorite_Cookie'])){
    $cart_id = sanitize($_COOKIE['Favorite_Cookie']);
  }

  if(isset($_SESSION['Session_user'])){
    $user_id = $_SESSION['Session_user'];
    $userQuery = $conn->query("SELECT * FROM users WHERE id = '$user_id'");
    $user_data = mysqli_fetch_assoc($userQuery);

  }

 ?>
