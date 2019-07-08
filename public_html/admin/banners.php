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

  if(isset($_GET['add'])){

    $name = $_FILES['banner']['name'];
    $nameArray = explode('.' , $name);
    $fileName = $nameArray[0];
    $fileExt = $nameArray[1];

    $mime = explode('/' , $_FILES['banner']['type']);
    $mimeType = $mime[0];
    $mimeExt= $mime[1];

    $fileSize = $_FILES['banner']['size'];

    $tmpLoc = $_FILES['banner']['tmp_name'];

    $uploadName = md5(rand()).'.'.$fileExt;

    $uploadPath = BASEURL. 'images/banners/'.$uploadName;
    $dbpath = 'images/banners/' .$uploadName;

    $allowed = array('png', 'jpg' ,'jpeg', 'gif','JPEG');

    if($mimeType != 'image'){
      $errors[] = 'The file must be an image';
    }

    if(!in_array($fileExt, $allowed)){
      $errors[] = 'the files extension must be a png, jpg, jpeg or gif.';
    }

    if ($fileSize>15000000){
      $error[] = 'the files size must be under 15mb';
    }

    if($fileExt != $mimeExt && ($mimeExt == 'jpeg' && $fileExt !='jpg')){
      $errors[] = 'File extension doesn not match the file.';
    }

    if(!empty($errors)){
      echo display_errors($errors);
    }else {
        move_uploaded_file($tmpLoc, $uploadPath);
        $insertSql= "INSERT INTO banners (img_path) VALUES ('$dbpath')";
        $conn->query($insertSql);
        header('Location:banners.php');
    }
  }

    if(isset($_GET['del'])){
        delete_row("banners" , $_GET['del'] ,"banners.php", $conn);
    }

    if(isset($_GET['state'])){
      $state = $_GET['state'];
      $id = $_GET['id'];
      $updateSql = "UPDATE banners SET state = $state WHERE id = '$id'";
      $conn->query($updateSql);
      header('location:banners.php');
    }?>

   <br>
   <div class="row justify-content-center">
     <div class="col-md-6">
       <form class="" action="banners.php?add" method="post" enctype="multipart/form-data">
         <input type="submit" class="btn btn-success " name="submit" value="Add New Banner">
         <input type="file" name="banner" id="banner">
       </form>
     </div>
   </div>

  <br>
  <table class="table table-bordered table-condensed table-striped">
    <thead>  <th>Active</th>  <th>Image</th>  <th>Delete</th>  </thead>
    <tbody>
      <?php
        $bannersQuery = $conn->query("SELECT * FROM banners");
        while ($banners = mysqli_fetch_assoc($bannersQuery)):?>
          <tr>
            <td class="text-center align-middle">
              <a href="banners.php?state=<?=(($banners['state']==0)?'1':'0');?>&id=<?=$banners['id'];?>"class="btn btn-success"><?=(($banners['state']==0)?'Activate':'Deactivate');?></a>
            </td>
            <td class="text-center align-middle">
              <img width="500px"src="../<?=$banners['img_path'];?>" alt="">
            </td>
            <td class="text-center align-middle">
              <a href="banners.php?del=<?=$banners['id'];?>"class="btn btn-danger ">Delete</a>
            </td>
          </tr>
      <?php endwhile; ?>

    </tbody>
    <?php ob_end_flush(); ?>
