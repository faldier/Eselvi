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
  //Get brands from

  $brandsQuery = $conn -> query("SELECT * FROM brands ORDER BY brand");
  $errors = array();
  //Edit BRAND
  if(isset($_GET['edit']) && !empty($_GET['edit'])){
    $edit_id = (int)$_GET['edit'];
    $edit_id = sanitize($edit_id);
    $edit_result = $conn->query("SELECT * FROM brands WHERE id = '$edit_id'");
    $eBRand = mysqli_fetch_assoc($edit_result);
  }

  //Delete BRAND
  if(isset($_GET['delete']) && !empty($_GET['delete'])){
    delete_row("brands" , $_GET['delete'] ,"brands.php", $conn);
  }
  //If add form is submitter
  if (isset($_POST['add_submit'])) {
    $brand = sanitize($_POST['brand']);
    // Check if brand is blank
    if($_POST['brand'] == ''){
      $errors[] .= 'U must enter a brand';
    }

    //Check if brand exist in DataBase
    $sql = "SELECT * FROM brands WHERE brand = '$brand'";
    if (isset($_GET['edit'])) {
      $sql = "SELECT FROM brands WHERE brand = 'brand' AND id !='$edit_id'";
    }
    $resulta = $conn -> query($sql);
    $count = mysqli_num_rows($resulta);
    if($count>0){
      $errors[] .=$brand." That brand already exist. Please choose another brand name...";
    }

    //Display errors
    if (!empty($errors)) {
      echo display_errors($errors);
    }else {
      //Add brand to database
      $sql = "INSERT INTO brands (brand) VALUES ('$brand')";
      if (isset($_GET['edit'])) {
        $sql = "UPDATE brands SET brand = '$brand' WHERE id = '$edit_id'";
      }
      $conn->query($sql);
      header('Location: brands.php');
    }
  }
 ?>
 <h2 class="text-center">Brands</h2><hr>

 <!-- BRAND FORM -->
 <div class="d-flex justify-content-center">
    <form class="form-inline" action="brands.php <?=((isset($_GET['edit']))?'?edit='.$edit_id:'');?>" method="post">
      <div class="form-group">
        <?php
          $brand_value = '';
          if(isset($_GET['edit'])){
            $brand_value = $eBRand['brand'];
          }else {
            if (isset($_POST['brand'])) {
              $brand_value = sanitize($_POST['brand']);
            }
          }
         ?>

        <label for="brand"><?=((isset($_GET['edit']))?'Edit':'Add'); ?> Brand  </label>
        <input type="text" name="brand" id="brand" class="form-control ml-3" value="<?=$brand_value;  ?>">
        <?php if(isset($_GET['edit'])): ?>
          <a href="brands.php" class="btn btn-light">Close</a>
        <?php endif; ?>
        <input type="submit" name="add_submit" value="<?= ((isset($_GET['edit']))?'Edit':'Add'); ?> Brand" class="btn btn-md btn-success ml-2">
      </div>
    </form>
 </div>
<hr>


 <table class="table-brand table table-bordered table-striped ">
   <thead>
     <th></th><th>Brand</th> <th></th>
   </thead>
   <tbody>
     <tr>
       <?php while($brand = mysqli_fetch_assoc($brandsQuery)): ?>
       <td><a href="brands.php?edit=<?=$brand['id'];?>" class="btn btn-xs btn-outline-primary">Edit</a></td>
       <td><?= $brand['brand']; ?></td>
       <td><a href="brands.php?delete=<?=$brand['id'];?>" class="btn btn-xs btn-outline-primary">Remove</a></td>
     </tr>
   <?php endwhile; ?>
   </tbody>
 </table>
<?php ob_end_flush(); ?>
