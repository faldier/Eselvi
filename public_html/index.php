<?php
    require_once $_SERVER['DOCUMENT_ROOT'].'/system/init.php';
  include 'includes/head.php';
  include 'includes/navigation.php';
?>
    <div class="col-md-12 mt-5 mb-5">
      <ul id="bannerSlider">
        <?php
          $bannersQuery = $conn->query("SELECT * FROM banners WHERE state = 1");
          while ($banners = mysqli_fetch_assoc($bannersQuery)):
        ?>
            <li>
              <img class="bannerImg" src="<?=$banners['img_path'];?>" alt="">
            </li>
        <?php endwhile; ?>
      </ul>
    </div>

    <div class="text-center promoLinksDiv">
      <a href="products.php?category=1">Computers</a>
      <a href="products.php?category=2">Laptops</a>
      <a href="products.php?category=3">Phones</a>
      <a href="products.php?category=0">Promotions</a>
    </div>
    <hr>
    <br>
    <?php
      $productQuery = $conn->query("SELECT * FROM products limit 12");
    ?>
    <div class="row">
      <?php
        while($product = mysqli_fetch_assoc($productQuery)):
        $firstImage = explode(',' , $product['images']);
      ?>
        <div class="mb-5 pl-5 col-md-3 ">
          <a href="product.php?product=<?=$product['id']?>">
            <img height="140px" src="<?=$firstImage[0];?>" alt="" class="rounded mx-auto d-block" >
          </a>
          <p class="text-center"><strong><?=$product['title'];?></strong></p>
          <p class="mt-1 ml-5 ">CPU : <?=$product['cpu'];?> Cores</p>
          <p class="ml-5 ">RAM : <?=$product['ram'];?> GB</p>
          <p class="ml-5 ">Display : <?=$product['display'];?>"</p>
          <div class="row">
            <ul class="">
              <li class="float-left mt-1 ml-4"><p class="new_price"><?=$product['price'] ;?> $</p></li>
            </ul>
          </div>
          <div class="row justify-content-center">
            <a href="product.php?product=<?=$product['id'];?>"  class="btn btn-outline-success btn-block" id="detailsButton" >Details</a>
          </div>
        </div>
      <?php endwhile; ?>
    </div>
    <hr>
    <?php include 'includes/promotion_product_slider.php'; ?>
    <br>
    <hr>
    <br>
    <div class="customersReviewDiv row justify-content-md-center">
      <div class="col-md-6">
        <ul  id="reviewsSlider" >
          <?php
            $reviewsQuery=$conn->query("SELECT * FROM reviews WHERE review<>''");
            $reviewNumber = 0;
            while($review = mysqli_fetch_assoc($reviewsQuery)):
            $user_id = $review['user_id'];
            $product_id = $review['product_id'];
            $userNameQuery = $conn->query("SELECT username FROM users WHERE id = $user_id");
            $userName = mysqli_fetch_assoc($userNameQuery);
            $productNameQuery = $conn->query("SELECT title FROM products WHERE id = $product_id ");
            $productName = mysqli_fetch_assoc($productNameQuery);
          ?>

          <li id="sliderLi" >
            <div class="ml-5">
              <p class="h5"><strong><?=$userName['username']; ?></strong></p>
              <p class="mt-2"><?= $productName['title'];?></p>
              <div class="mt-1 starrr<?=$reviewNumber;?>" value="<?=$review['vote'];?>"></div>
              <div class="text-wrap" style="height:100px">
                <p class="pl-1 text-muted"><?=$review['review'];?></p>
              </div>
            </div>
          </li>

          <?php
            $reviewNumber++;
            endwhile;
          ?>
        </ul>
      </div>
    </div>
    <br>

    <?php include 'includes/footer.php'; ?>

    </body>
  </html>

<script>
      var reviewsCount = <?=$reviewNumber;?>;
      for(i=0; i<reviewsCount; i++){
        var className = "starrr" + i;
        var value = $('.'+className).attr('value');
        $('.'+className).starrr({
          readOnly: true,
          rating: value
        })
      }
</script>
