<?php
  require_once $_SERVER['DOCUMENT_ROOT'].'/system/init.php';
include 'includes/head.php';
include 'includes/navigation.php';

  if (isset($_COOKIE['Favorite_Cookie'])) {
      $cart_id = sanitize($_COOKIE['Favorite_Cookie']);
      $items = explode('-', $cart_id); ?>

      <div class="row justify-content-center">
        <div class="col-md-10">
          <?php
            foreach ($items as $item):
              $productQuery =$conn->query("SELECT * FROM products WHERE id = $item");
              $product = mysqli_fetch_assoc($productQuery); ?>
              <div class="row mt-4">
                <div class="col-md-4">
                  <img class="align-middle" width="100%"src="images/products/4c9aedc3bd97f862c5b0dd2b9c658ff3.jpg" alt="">
                </div>
                <div class="col-md-2">
                  <p class="text-center h4 mt-4"><?=$product['title']?></p>
                  <p class="text-center h5 mt-1"><?=$product['price']?></p>
                  <p class="text-center h5 mt-1"><?=$product['promotion_price']?></p>
                </div>
                <div class="col-md-4">
                  <table class=" table-striped mt-4 ml-4">
                    <?php
                      $specification = explode(',' , $product['specification']);
                      for ($i=1; $i < count($specification); $i++):  ?>
                        <tr>
                          <td><?=$specification[$i-1];?></td>
                          <td><?=$specification[$i];?></td>
                        </tr>
                        <?php $i++; ?>
                    <?php endfor; ?>
                  </table>
                </div>
                <div class="col-md-2">
                  <a class="btn  btn-success mt-4"> <i class="fa fa-folder"></i> View</a>
                  <a class="btn btn-success mt-1"><i class="fa fa-shopping-cart"></i>  Buy</a>
                </div>
              </div>
              <hr>
          <?php endforeach; ?>
        </div>
    </div>

    <?php
  } else {
      echo "You dont add any product to favorites";
  }?>
