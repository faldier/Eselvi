<?php
    require_once $_SERVER['DOCUMENT_ROOT'].'/system/init.php';
  include 'includes/head.php';
  include 'includes/navigation.php';


  if (!empty($_POST)){
    // Insert review to the database if we have it
    $product_id = sanitize($_GET['product']);
    $review_text = $_POST['review_text'];
    $review_vote = $_POST['vote'];
    $insertSql= "INSERT INTO reviews (user_id, review, vote, product_id)
    VALUES ('$user_id', '$review_text', $review_vote, $product_id)";
    $conn->query($insertSql);
  }

  // Check if product is selected And display it
  if(isset($_GET['product'])){

    $product_id = sanitize($_GET['product']);
    $productQuery = $conn->query("SELECT * FROM products WHERE id = $product_id");
    $product = mysqli_fetch_assoc($productQuery);
    $productImages = explode(',',$product['images']);
    $specification = explode(',',$product['specification']);
    $sql =$conn->query("SELECT ROUND(AVG(vote) ,0)  from reviews where product_id = $product_id");
    $votes = mysqli_fetch_row($sql);
  ?>
  <br>
  <div class="row">
    <div class="col-md-6">
      <ul id="productImgSlider">
        <?php for($i=0;$i<count($productImages);$i++): ?>
          <li data-thumb="<?=$productImages[$i];?>">
            <img class="ml-4" width="90%"  src="<?=$productImages[$i];?>" />
          </li>
        <?php endfor; ?>
      </ul>
    </div>
    <div class="col-md-6 ">
      <h3 class="text-center"><?=$product['title'] ?></h3>
      <p>Price - <?=$product['price'];?></p>
      <?php if($product['promotion'] == 1): ?>
        <p>Sales price - <?=$product['promotion_price'];?></p>
      <?php endif; ?>
      <hr>
      <p class="d-inline">add favorite</p>
      <a class="btn btn-success" onclick="add_to_cart();">add to shopping</a>
      <hr>

      <?php
          $reviewsCountQuery = $conn->query("SELECT * FROM reviews where product_id = $product_id");
          $reviewsCount = mysqli_num_rows($reviewsCountQuery);
          if($reviewsCount != 0):
          ?>

          <h5><?=$reviewsCount?> reviews on this product</h5>
        <div class='starrr'></div>
      <?php else:
        ?>
        <p>no reviews for this product</p>
      <?php endif;

      if(is_logged_in()):

        $userVoteQuery = $conn->query("SELECT * FROM reviews WHERE user_id = $user_id AND product_id = $product_id");
        $userVoteCount = 0;

        if ($userVoteQuery->num_rows > 0) {
          $userVote = mysqli_fetch_assoc($userVoteQuery);
          $userVoteCount = count($userVote);
         }

        if($userVoteCount != 0): ?>
        <p>you already vote with <?=$userVote['vote']?> stars</p>

      <?php
        else:
      ?>
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#review_modal">
          Leave a review
        </button>
    <?php
      endif;
    else:
    ?>
      <p class="">Log in to leave a review</p>
    <?php endif;
      ?>

      <ul id="singleReviewsSlider" >
        <?php
        $reviewsQuery=$conn->query("SELECT * FROM reviews WHERE product_id = $product_id AND review<>'' ");
        $revieNumber = 0;
        while($review = mysqli_fetch_assoc($reviewsQuery)):
          $user_id = $review['user_id'];
          $product_id = $review['product_id'];
          $userNameQuery = $conn->query("SELECT username FROM users WHERE id = $user_id ");
          $userName = mysqli_fetch_assoc($userNameQuery);
          $productNameQuery = $conn->query("SELECT title FROM products WHERE id = $product_id ");
          $productName = mysqli_fetch_assoc($productNameQuery);?>

          <li id="sliderLi" >
            <div class="">
              <br>
              <p class="h5"><strong><?=$userName['username']; ?></strong></p>
              <div class="mt-1 starrr<?=$revieNumber;?>" value="<?=$review['vote'];?>"></div>
              <div class="text-wrap" style="height:100px">
                <p class="pl-1 text-muted"><?=$review['review'];?></p>
              </div>
            </div>
          </li>
        <?php
        //Count the review number
          $revieNumber++;
          endwhile;
          ?>
      </ul>
    </div>
  </div>

  <div class="row mt-5">
    <div class="col-md-6">
      <p class="text-center h5">Specification</p>
      <hr>
      <table class="table table-striped">
        <tbody>
          <?php for ($i=1; $i < count($specification); $i++):  ?>
          <tr>
            <td><?=$specification[$i-1];?></td>
            <td><?=$specification[$i];?></td>
          </tr>
          <?php $i++; ?>
        <?php endfor; ?>
        </tbody>
      </table>
    </div>
    <div class="col-md-6">
      <p class="text-center h5">Description</p>
      <hr>
      <p class="m-4 h6"><?=$product['description']?></p>
    </div>
  </div>
  <hr>
  <br>


  <?php
  include 'includes/promotion_product_slider.php';
  include 'includes/footer.php';
  include 'includes/reviews_modal.php';
  } ?>

  <script>
    var rate = "<?php echo $votes[0]; ?>";

    $('.starrr').starrr({
    readOnly: true,
    rating: rate
    })
  </script>
  <script>
        var reviesCount = <?=$revieNumber;?>;
        for(i=0; i<reviesCount; i++){
          var className = "starrr" + i;
          var value = $('.'+className).attr('value');
          $('.'+className).starrr({
            readOnly: true,
            rating: value
          })
        }
  </script>

  <script>
  function add_to_cart(){

    var id = <?=$_GET['product'];?>

      jQuery.ajax({
        url : '/admin/parsers/add_cart.php',
        method : "post",
        data : {id:id},
        success : function(data){
          console.log(data);
          // location.reload();
        },
        error : function(){
          alert: ("Something went wrong");
        }
      });

  }
  </script>
