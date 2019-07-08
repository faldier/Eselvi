<?php
$promotionQuery = $conn->query("SELECT * FROM products WHERE promotion = 1 ");


 ?>
  <div class="row">
    <div class="col-md-2">
      <img class="arrowImg " id="goToPrevSlide" src="images/arrowPrev.png" alt="">
    </div>
    <div class="col-md-8">
      <div class="row">
        <ul id="promoProductsSlider" >
          <?php while($promotion=mysqli_fetch_assoc($promotionQuery)):
              $productImages = explode(',',$promotion['images']);
             ?>

            <li class="col-md-3" id="sliderLi">
              <div class="productDiv">
                <img src="<?=$productImages[0];?>" alt="">
                <div class="titleProductDiv">
                  <p class="text-center mt-2"><?=$promotion['title']; ?></p>
                </div>

                <div class="row justify-content-center">
                    <ul class="">
                      <li class="float-left ml-2"><p class="new_price"><?=$promotion['promotion_price'] ;?> $</p></li>
                      <li class="float-left ml-2"><p class="old_price"><?=$promotion['price'];?></p></li>
                    </ul>

                  </div>
                  <div class="row justify-content-center">
                    <a href="product.php?product=<?=$promotion['id'];?>"  class="btn btn-outline-success btn-block" id="detailsButton" >Details</a>

                  </div>


              </div>
            </li>

          <?php endwhile; ?>
        </ul>
      </div>
    </div>
    <div class="col-md-2">
      <img class="arrowImg " id="goToNextSlide" src="images/arrowNext.png" alt="">
    </div>
  </div>
