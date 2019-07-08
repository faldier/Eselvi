<?php
ob_start();
    require_once $_SERVER['DOCUMENT_ROOT'].'/system/init.php';
  include 'includes/head.php';
  include 'includes/navigation.php';
  include 'includes/php_functions.php';
  if(!is_logged_in()){
    login_error_redirect();
  }
  if(!has_permission()){
    permission_error_redirect();
  }


  // If edit or add product
  // Show add product form
  // Else
  // View all products

    if(isset($_GET['delete'])){
      delete_row("products" , $_GET['delete'] ,"products.php", $conn);
    }
    $dbpath = '';
    if(isset($_GET['add']) || isset($_GET['edit'])){

       $title = ((isset($_POST['title']) && $_POST['title'] != '')?sanitize($_POST['title']):'');
       $brand =((isset($_POST['brand']) && !empty($_POST['brand']))?sanitize($_POST['brand']):'');
       $category =((isset($_POST['category']) && !empty($_POST['category']))?sanitize($_POST['category']):'');
       $price = ((isset($_POST['price']) && $_POST['price'] != '')?sanitize($_POST['price']):'');
       $list_price = ((isset($_POST['list_price']) && $_POST['list_price'] != '')?sanitize($_POST['list_price']):'');
       $description = ((isset($_POST['description']) && $_POST['description'] != '')?sanitize($_POST['description']):'');
       $cpu = ((isset($_POST['cpu']) && $_POST['cpu'] != '')?sanitize($_POST['cpu']):'');
       $gpu = ((isset($_POST['gpu']) && $_POST['gpu'] != '')?sanitize($_POST['gpu']):'');
       $display = ((isset($_POST['display']) && $_POST['display'] != '')?sanitize($_POST['display']):'');
       $ram = ((isset($_POST['ram']) && $_POST['ram'] != '')?sanitize($_POST['ram']):'');
       $specification = ((isset($_POST['specification']) && $_POST['specification'] != '')?sanitize($_POST['specification']):'');
       $saved_image ='';
       $saved_photo = '';

      if(isset($_GET['edit'])){

        $namesimg = isset($_GET['imgNames']);
        $nameimg = isset($_POST['imgNames']);
        $edit_id=(int)$_GET['edit'];
        $productResults = $conn->query("SELECT * FROM products WHERE id = '$edit_id'");
        $product = mysqli_fetch_assoc($productResults);
        if(isset($_GET['delete_image'])){
          $imgi = (int)$_GET['imgi'] - 1;
          $images = explode(',',$product['images']);
          $image_url = $_SERVER['DOCUMENT_ROOT'].$images[$imgi];
          unlink($image_url);
          unset($images[$imgi]);
          $imageString = implode(',',$images);
          $conn->query("UPDATE products SET images = '{$imageString}' WHERE id ='$edit_id'");
          header('Location: products.php?edit='.$edit_id);
        }
        $title = ((isset($_POST['title']) && !empty($_POST['title']))?sanitize($_POST['title']):$product['title']);
        $brand =((isset($_POST['brand']) && !empty($_POST['brand']))?sanitize($_POST['brand']):$product['brand_id']);
        $category =((isset($_POST['category']) && !empty($_POST['category']))?sanitize($_POST['category']):$product['category']);
        $saved_image = (($product['images'] != '')?$product['images']:'');
        $dbpath = $saved_image;

      }

      if($_POST){

        $errors = array();
        $required = array('title', 'brand', 'price', 'cpu', 'gpu','ram', 'display', 'category');
        $allowed = array('png', 'jpg' ,'jpeg', 'gif','JPEG');
        $tmpLoc = array();
        $uploadPath = array();
        foreach ($required as $field) {
          if($_POST[$field] == ''){
            $errors[] = 'All Field With astrisk are required.';
            break;
          }
        }
        $photoCount = count($_FILES['photo']['name']);
        $photoName =  $_FILES['photo']['name'][0];


        if($photoName != ''){

          for($i = 0; $i<$photoCount; $i++){

            $name = $_FILES['photo']['name'][$i];

            $nameArray = explode('.' , $name);
            $fileName = $nameArray[0];
            $fileExt = $nameArray[1];

            $mime = explode('/' , $_FILES['photo']['type'][$i]);

            $mimeType = $mime[0];
            $mimeExt= $mime[1];
            $tmpLoc[] =$_FILES['photo']['tmp_name'][$i];

            $fileSize = $_FILES['photo']['size'][$i];

            $uploadName = md5(rand()).'.'.$fileExt;


            $uploadPath[] = BASEURL. 'images/products/'.$uploadName;
             if($i != 0 || $saved_image != ''){

              $dbpath .= ',';

             }
            $dbpath .= 'images/products/' .$uploadName;

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
          }

        }
        if(!empty($errors)){
          echo display_errors($errors);
        }else{
          if($photoName != ''){

              for($i = 0; $i<$photoCount; $i++){
                move_uploaded_file($tmpLoc[$i],$uploadPath[$i]);
              }
          }
         $insertSql= "INSERT INTO products (title, price, promotion_price, brand_id, category, specification, images, description, cpu, gpu, ram, display)
         VALUES ('$title','$price','$list_price','$brand','$category','$specification','$dbpath','$description', '$cpu', '$gpu', '$ram', '$display')";
          if(isset($_GET['edit'])){

          $insertSql = "UPDATE products SET title = '$title', price='$price',  promotion_price ='$list_price',  brand_id = '$brand', category = '$category', specification = '$specification', images='$dbpath', description='$description', cpu='$cpu', gpu='$gpu', ram='$ram', display='$display'  WHERE id = '$edit_id'";

          }
          $conn->query($insertSql);

          header('Location:products.php');

      }
    }


      ?>

      <h2 class="text-center"><?=((isset($_GET['edit']))?'Edit ':'Add a new ');?>Product</h2>
      <div class="col-md-12 justify-content-center">
      <form class="" action="products.php?<?=((isset($_GET['edit']))?'edit='.$edit_id:'add=1');?>" method="post" enctype="multipart/form-data">
        <div class="form-row">

          <div class="form-group col-md-3 ">
            <label for="title">Title*:</label>
            <input type="text" name="title" id="title" class="form-control " value="<?=$title?>">
          </div>
          <div class="form-group col-md-3">
            <label for="brand">Brand *</label>
            <select class="form-control" id="brand" name="brand">
              <?php
                $brandQuery =$conn->query("SELECT * FROM brands");
                ?>

                <option value=""<?=(($brand == '')?'selected':'');?>></option>
              <?php while ($brandResult = mysqli_fetch_assoc($brandQuery)):?>

                  <option value="<?=$brandResult['id'];?>"<?=(($brand == $brandResult['id'])?'selected':'');?>><?=$brandResult['brand']?></option>
                <?php endwhile; ?>
            </select>
          </div>
          <div class="form-group col-md-3">
            <label for="category">Category *</label>
            <select class="form-control" id="category" name="category">
              <?php
                $categoryQuery =$conn->query("SELECT * FROM categories");?>
                <option value=""<?=(($category == '')?'selected':'');?>></option>
                <?php while ($categoryResult = mysqli_fetch_assoc($categoryQuery)):?>
                  <option value="<?=$categoryResult['id'];?>"<?=(($category == $categoryResult['id'])?'selected':'');?>><?=$categoryResult['category'];?></option>
                <?php endwhile; ?>
            </select>
          </div>


          </div>

          <div class="form-row">

            <div class="form-group col-md-2">
              <label for="price">Price *</label>
              <input id="price" type="text" name="price" class="form-control" value="<?=((isset($_GET['edit']))?$product['price']:'');?>">
            </div>
            <div class="form-group col-md-2">
              <label for="list_price">List *</label>
              <input id="list_price" type="text" name="list_price" class="form-control" value="<?=((isset($_GET['edit']))?$product['promotion_price']:'');?>">
            </div>
            <div class="form-group col-md-2">
              <label for="cpu">CPU:</label>
              <select id="cpu" class="form-control" name="cpu">

                <option value="<?= (isset($_GET['edit'])?$product['cpu']:''); ?>"><?= (isset($_GET['edit'])?$product['cpu'].' cores':''); ?></option>


                <option value="1">1 Core</option>
                <option value="2">2 Cores</option>
                <option value="4">4 Cores</option>
                <option value="8">8 Cores</option>
              </select>
            </div>
            <div class="form-group col-md-2">
              <label for="ram">RAM:</label>
              <select id="ram" class="form-control" name="ram">

                <option value="<?= (isset($_GET['edit'])?$product['ram']:''); ?>"><?= (isset($_GET['edit'])?$product['ram'].' GB':''); ?></option>
                <option value="1">1 GB</option>
                <option value="2">2 GB</option>
                <option value="4">4 GB</option>
                <option value="6">6 GB</option>
                <option value="8">8 GB</option>
                <option value="16">16 GB</option>
                <option value="32">32 GB</option>
              </select>
            </div>
            <div class="form-group col-md-2">
              <label for="gpu">GPU:</label>
              <select id="gpu" class="form-control" name="gpu">
                <option value="<?= (isset($_GET['edit'])?$product['gpu']:''); ?>"><?= (isset($_GET['edit'])?$product['gpu'].' GB':''); ?></option>

                <option value="1">1 GB</option>
                <option value="2">2 GB</option>
                <option value="4">4 GB</option>
                <option value="6">6 GB</option>
                <option value="8">8 GB</option>
              </select>
            </div>
            <div class="form-group col-md-2">
              <label for="display">Display:</label>
              <select id="display" class="form-control" name="display">
                <option value="<?= (isset($_GET['edit'])?$product['display']:''); ?>"><?= (isset($_GET['edit'])?$product['display'].' "':''); ?></option>
                <option value="4">4"</option>
                <option value="5">5"</option>
                <option value="6">6"</option>
                <option value="7">7"</option>
                <option value="15">15"</option>
                <option value="17">17"</option>
                <option value="19">19"</option>
                <option value="23">23"</option>
              </select>
            </div>
          </div>

          <div class="form-row">

            <div class="form-group col-md-6">
              <button class=" col-md-6 btn btn-secondary form-control btn-sm" onclick="jQuery('#sizesModal').modal('toggle');return false;">Specification</button>
              <textarea name="specification" type="text"  class="form-control" id="specification" readonly rows="6"><?=((isset($_GET['edit']))?$product['specification']:'');?></textarea>
            </div>


            <div class="form-group col-md-6">
              <label for="description">Description</label>
              <textarea class="form-control" id="description" name="description" rows="6"  ><?=((isset($_GET['edit']))?$product['description']:'');?></textarea>
            </div>
        </div>


        <div class="form-row">


        <div class="form-group col-md-10">
          <label class="d-block"for="photo">Product PHOTO</label>
          <?php if($saved_image !=''):

            $imgi = 1;
            $images = explode(',' ,$saved_image);
            ?>
            <?php foreach ($images as $image ): ?>
          <div class="saved-image d-inline-block col-md-2">
            <img width="100px" src="../<?=$image;?>" alt="saved image"><br>
            <a href="products.php?delete_image=1&edit=<?= $edit_id;?>&imgi=<?=$imgi;?>" class="text-danger">Delete Image</a>

          </div>
          <?php
          $imgi++;
          endforeach; ?>
        <?php endif; ?>
          <input type="file"  name="photo[]" id="photo" class="form-control-file" multiple>
        </div>

        <div class="form-group col-md-2">
          <div class="form-check">
            <a href="products.php" class="btn btn-danger float-right ml-3">Cancel</a>
            <input type="submit" value="submit" class=" btn btn-success float-right  ">

          </div>
        </div>
          </div>
      </form>





      <!-- Modal -->
    <div class="modal fade " id="sizesModal" tabindex="-1" role="dialog" aria-labelledby="sizesModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="sizesModalLabel">Specification</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="container-fluid row">
            <?php for($i=1; $i<=8; $i++):?>
              <div class="form-group col-md-4">
                <label for="component><?=$i;?>">component: </label>
                <input type="text" name="component<?=$i;?>" id="component<?=$i;?>" value="<?=((!empty($sArray[$i-1]))?$sArray[$i-1]:''); ?>" class="form-control">
              </div>
              <div class="form-group col-md-8">
                <label for="component_detail<?=$i;?>">component_detail:</label>
                <input type="text" name="component_detail<?=$i;?>" id="component_detail<?=$i;?>" value="<?=((!empty($qArray[$i-1]))?$qArray[$i-1]:''); ?>" class="form-control">
              </div>
            <?php endfor; ?>
              </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary" data-dismiss="modal" onclick="updateSizes();jQuery('sizesModal').modal('toggle');return false;">Save changes</button>
          </div>
        </div>
      </div>
    </div>
    <?php
    }else{
      ?>
      <h2 class="text-center">Products</h2>
      <a href="products.php?add=1" class="btn btn-success " id="add-Product-button">Add Product</a>
      <hr>
      <table class="table table-bordered table-condensed table-striped">
        <thead>  <th></th>  <th>Product</th>  <th>Price</th> <th>Categories</th> <th>Brand</th> <th>Discount</th>  </thead>
        <tbody>
          <?php $productQuery = $conn->query("SELECT * FROM products");

          while ($product = mysqli_fetch_assoc($productQuery)):
             $category_id = $product['category'];
             $categoryQuery = $conn->query("SELECT * FROM categories WHERE id = '$category_id'");
             $categoryResult = mysqli_fetch_assoc($categoryQuery);

             $brand_id = $product['brand_id'];
             $brandQuery = $conn->query("SELECT * FROM brands WHERE id = '$brand_id'");
             $brandResult = mysqli_fetch_assoc($brandQuery);
          ?>
            <tr>
              <td>
                <a href="products.php?edit=<?=$product['id'];?>" class="btn btn-xs btn-dark">Edit</a>
                <a href="products.php?delete=<?=$product['id'];?>" class="btn btn-xs btn-dark">Delete</a>
              </td>
              <td><?= $product['title']; ?></td>
              <td><?=money($product['price']); ?></td>
              <td><?=$categoryResult['category'];?></td>
              <td><?= $brandResult['brand']; ?></td>
              <td>
                <a href="products.php?featured=<?=(($product['promotion'] == 0)?'1':'0');?>&id=<?=$product['id'];?>"><?=(($product['promotion']==1)?'-':'+');?>
                </a>&nbsp <?=(($product['promotion'] == 1)?'Featured Product':'');?>
              </td>
            </tr>
          <?php endwhile; ?>
        </tbody>
      </table>

      <?php

    }
 ?>
 <?php ob_end_flush(); ?>
<script>

    function updateSizes(){
      var specification = '';
      for (var i=1 ; i<=8; i++){
        if (jQuery('#component' +i).val() !='' && jQuery('#component_detail' +i).val() !='' ) {
          specification += jQuery('#component' +i).val()+','+jQuery('#component_detail' +i).val() +',';

        }
      }
      jQuery('#specification').val(specification);
    }

</script>
