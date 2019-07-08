<?php
  require_once $_SERVER['DOCUMENT_ROOT'].'/system/init.php';
  $results_per_page = 12;

  if (isset($_POST["category_id"])) {
    $category_id = $_POST["category_id"];
  }

  if (isset($_POST["action"])) {
    $query = "SELECT * FROM products WHERE category = '$category_id'";

    //CHECK IS ANY SORTING FILTERS ARE ADD

    if(isset($_POST["minimum_price"], $_POST["maximum_price"]) && !empty($_POST["minimum_price"]) && !empty($_POST["maximum_price"])){
        $query .= "AND price BETWEEN '".$_POST["minimum_price"]."' AND '".$_POST["maximum_price"]."'";
    }

    if (isset($_POST["brand"])) {
        $brand_filter = implode("','", $_POST["brand"]);
        $query .= " AND brand_id IN('".$brand_filter."')";
    }

    if (isset($_POST["ram"])){
        $ram_filter = implode("','", $_POST["ram"]);
        $query .= " AND ram IN('".$ram_filter."')";
    }

    if (isset($_POST["cpu"])){
        $cpu_filter = implode("','", $_POST["cpu"]);
        $query .= " AND cpu IN('".$cpu_filter."')";
    }

    if (isset($_POST["gpu"])){
        $gpu_filter = implode("','", $_POST["gpu"]);
        $query .= " AND gpu IN('".$gpu_filter."')";
    }

    if (isset($_POST["display"])){
        $display_filter = implode("','", $_POST["display"]);
        $query .= " AND display IN('".$display_filter."')";
    }

    if (isset($_POST["priceSort"])){
      $price_filter =  implode("','", $_POST["priceSort"]);
      if ($price_filter == "low"){
        $query .= " ORDER BY price ASC ";
      }else{
          $query .= " ORDER BY price DESC ";
      }
  }

  if(isset($_POST['products_on_page'])){
    $products_on_page = $_POST['products_on_page'][0];
    $results_per_page = (int)$products_on_page;
  }

  if(isset($_POST['page'])){
    $page = $_POST['page'];
  }

    // TAKE PRODUCTS BASED ON ADDED FILTERS
    $statement = $conn->query($query);
    $number_of_results = mysqli_num_rows($statement);
    $number_of_pages = ceil($number_of_results/$results_per_page);
    $this_page_first_result = ($page-1)*$results_per_page;
    $query .= " LIMIT " . $this_page_first_result . ',' .  $results_per_page;
    $statement = $conn->query($query);

    //ECHO THE PRODUCTS
    $output = '';
    if ($number_of_results > 0) {
        while ($result = mysqli_fetch_assoc($statement)) {
          $firstImage = explode(',',$result['images']);

          $output .= '
            <div class="mb-5 pl-5 col-md-3 ">
              <a href="product.php?product='.$result['id'].'">
                <img height="140px" src="'. $firstImage[0] .'" alt="" class="rounded mx-auto d-block" >
              </a>
              <p class="text-center"><strong> '. $result['title'] .'</strong></p>
              <p class="mt-1 ml-5 ">CPU : '. $result['cpu'] .' Cores</p>
              <p class="ml-5 ">RAM : '. $result['ram'] .' GB</p>
              <p class="ml-5 ">GPU : '. $result['gpu'] .' GB</p>
              <p class="ml-5 ">Display : '. $result['display'] .'"</p>
              <div class="row ">
                <ul class="">
                  <li class="float-left mt-1 ml-4"><p class="new_price">'. $result['price'] .' $</p></li>
                </ul>
              </div>
              <div class="row justify-content-center">
                <a href="product.php?product='.$result['id'].'"  class="btn btn-outline-success btn-block" id="detailsButton" >Details</a>
              </div>
            </div>
            ';
        }

        $output .='
        <div class="text-center col-md-12">';

        for ($page=1;$page<=$number_of_pages;$page++) {
              $output .= ' <a class="btn btn-sm btn-success" href="products.php?category='. $category_id .'&page='. $page .'" > '. $page .' </a> ';
            }
            $output .='</div>';

    } else {
      echo $brand_filter;
        $output = '<h3>No Data Found</h3>';
    }
    echo $output;
}
