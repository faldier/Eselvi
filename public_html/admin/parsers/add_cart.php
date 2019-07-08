<?php
  require_once $_SERVER['DOCUMENT_ROOT'].'/Computers/system/init.php';
  $product_id = sanitize($_POST['id']);
    $cart_expire = time() + (86400 * 30);


  $domain = ($_SERVER['HTTP_HOST'] != 'localhost')?'.'.$_SERVER['HTTP_HOST']:false;
  $query = $conn->query("SELECT * FROM products WHERE id = '{$product_id}'");
  $product = mysqli_fetch_assoc($query);
  $cart_name = 'Favorite_Cookie';

  //check to see if the cart cookie exist
  // if($cart_id != ''){
  //   $cartQuery = $conn-> query("SELECT * FROM cart WHERE id = '{$cart_id}'");
  //   $cart = mysqli_fetch_assoc($cartQuery);
  //   $previous_items = json_decode($cart['items'], true);
  //   $item_match = 0;
  //   $new_items = array();
  //   foreach ($previous_items as $pitem) {
  //     if($item[0]['id'] == $pitem['id']){
  //       $item_match = 1;
  //     }
  //     $new_items[] = $pitem;
  //   }
  //   if($item_match != 1){
  //     $new_items = array_merge($item, $previous_items);
  //   }
  //   $items_json = json_encode($new_items);
  //   $cart_expire = date("Y-m-d H:i:s", strtotime("+30 days"));
  //   $conn ->query("UPDATE cart SET items = '{$items_json}', expire_date ='{$cart_expire}' WHERE id = '{$cart_id}'");
  //   setcookie(CART_COOKIE, '',1,"/", $domain, false);
  //   setcookie(CART_COOKIE, $cart_id, CART_COOKIE_EXPIRE, '/', $domain, false);
  if(isset($_COOKIE[$cart_name])) {
    $ids = $_COOKIE[$cart_name];
    $ids .='-';
     $ids .= $product_id;
    setrawcookie ($cart_name, rawurlencode($ids), $cart_expire, '/', $domain, false);
  }else{
    //add the cart to the database and set cookie


    $conn ->query("INSERT INTO cart (items, expire_date) VALUES ('{$product_id}','{$cart_expire}')");
    $cart_id = $conn->insert_id;
    setcookie($cart_name, $product_id, $cart_expire, '/', $domain, false);
  }
 ?>
