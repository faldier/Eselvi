<?php
    require_once $_SERVER['DOCUMENT_ROOT'].'/system/init.php';
  include 'includes/head.php';
  include 'includes/navigation.php';

  if(isset($_GET['category'])){
    $category_id = $_GET['category'];
  }

  if (!isset($_GET['page'])) {
    $page = 1;
  } else {
    $page = $_GET['page'];
  }?>

  <hr>
  <div class="row">
    <div class="col-md-2 pl-4">
      <div class="list-group">
        <div class="list-group-item">
          <h3>Price</h3>
          <input type="hidden" id="hidden_minimum_price" value="0" />
          <input type="hidden" id="hidden_maximum_price" value="65000" />
          <p id="price_show">0 - 5000</p>
          <div id="price_range"></div>
        </div>
      </div>
      <div class="list-group">
        <h4 class="list-group-item">Brands</h4>

      <?php
        $brand_idQuery = $conn ->query ("SELECT DISTINCT (brand_id) FROM products WHERE category = '$category_id'");
        while ( $brand_id = mysqli_fetch_assoc($brand_idQuery)):
          $brandsQuery = $conn->query("SELECT * FROM brands WHERE id = '$brand_id[brand_id]'");
            while ( $brand = mysqli_fetch_assoc($brandsQuery)):  ?>
              <div class="list-group-item checkbox">
                <label>
                  <input type="checkbox" class="common_selector brand" value="<?= $brand['id']; ?>"><?= $brand['brand'];?>
                </label>
              </div>
            <?php endwhile; ?>
        <?php endwhile; ?>
      </div>

      <div class="list-group">
        <h4 class="list-group-item">RAM</h4>

        <?php
        $ramQuery = $conn->query("SELECT DISTINCT(ram) FROM products WHERE category = '$category_id' ORDER BY ram ASC");
        while ($ram = mysqli_fetch_assoc($ramQuery)): ?>
          <div class="list-group-item checkbox">
            <label><input type="checkbox" class="common_selector ram" value="<?php echo $ram['ram']; ?>"  > <?php echo $ram['ram']; ?> GB</label>
          </div>
        <?php endwhile; ?>
    </div>

    <div class="list-group">
      <h4 class="list-group-item">Cores</h4>

      <?php
        $cpuQuery = $conn->query("SELECT DISTINCT(cpu) FROM products WHERE category = '$category_id' ORDER BY cpu ASC");
        while ($cpu = mysqli_fetch_assoc($cpuQuery)): ?>
          <div class="list-group-item checkbox">
            <label><input type="checkbox" class="common_selector cpu" value="<?= $cpu['cpu']; ?>"  > <?= $cpu['cpu']; ?> Cores</label>
          </div>
        <?php endwhile; ?>
    </div>

    <div class="list-group">
      <h4 class="list-group-item">GPU</h4>

      <?php
        $gpuQuery = $conn->query("SELECT DISTINCT(gpu) FROM products WHERE category = '$category_id' ORDER BY gpu ASC");
        while ($gpu = mysqli_fetch_assoc($gpuQuery)): ?>
          <div class="list-group-item checkbox">
            <label><input type="checkbox" class="common_selector gpu" value="<?= $gpu['gpu']; ?>"  > <?= $gpu['gpu']; ?> GB</label>
          </div>
        <?php endwhile; ?>
    </div>

    <div style="height: 180px; overflow-y: auto; overflow-x: hidden;" class="list-group">
      <h4 class="list-group-item">Display</h4>

      <?php
        $displayQuery = $conn->query("SELECT DISTINCT(display) FROM products WHERE category = '$category_id' ORDER BY display ASC");
        while ($display = mysqli_fetch_assoc($displayQuery)): ?>
          <div class="list-group-item checkbox">
            <label><input type="checkbox" class="common_selector display" value="<?= $display['display']; ?>"  > <?= $display['display']; ?>"</label>
          </div>
        <?php endwhile; ?>
    </div>

  </div>

  <div class="col-md-10">
    <p>Our week best offers</p>
    <?php include 'includes/promotion_product_slider.php' ?>
    <hr>
    <p class="d-inline-block " >Sort by price</p>
    <select class="common_selector col-md-2 d-inline-block form-control form-control-sm ">
      <option class="priceSort" value="low">small to high</option>
      <option class="priceSort" value="high">high to small</option>
    </select>
    <p class="d-inline-block ">Show product</p>
    <select class="common_selector col-md-2 d-inline-block form-control form-control-sm ">
      <option class="products_on_page" value="12">12</option>
      <option class="products_on_page" value="24">24</option>
      <option class="products_on_page" value="36">36</option>
    </select>
    <hr>

    <div class="row filter_data">
      <!--DON'T DELETE - div where products have to be echo -->
    </div>
  </div>
</div>

<?php include 'includes/footer.php';?>

  <script>

  $(document).ready(function(){

    $( function() {
      filter_data();

      function filter_data(){

          var action = 'fetch_data';
          var minimum_price = $('#hidden_minimum_price').val();
          var maximum_price = $('#hidden_maximum_price').val();
          var brand = get_filter('brand');
          var ram = get_filter('ram');
          var cpu = get_filter('cpu');
          var gpu = get_filter('gpu');
          var display = get_filter('display');
          var priceSort = get_filter('priceSort');
          var products_on_page = get_filter('products_on_page');
          var page=<?= $page ?>;
          var category_id =<?= $category_id ?>;

          $.ajax({
              url:"parsers/products_fetch.php",
              method:"POST",
              data:{
                action:action,
                brand:brand,
                ram:ram,
                cpu:cpu,
                gpu:gpu,
                display:display,
                priceSort:priceSort,
                page:page,
                products_on_page:products_on_page,
                minimum_price:minimum_price,
                maximum_price:maximum_price,
                category_id:category_id
               },
              success:function(data){
                  $('.filter_data').html(data);
              }
          });
      }

      function get_filter(class_name){
          var filter = [];
          $('.'+class_name+':checked').each(function(){
              filter.push($(this).val());
          });
          console.log(filter);
          return filter;
      }


      $('.common_selector').click(function(){
          filter_data();
      });

      $('#price_range').slider({
        range:true,
        min:0,
        max:5000,
        values:[50, 5000],
        step:25,
        slide:function(event, ui){
          $('#price_show').html(ui.values[0] + ' - ' + ui.values[1]);
          $('#hidden_minimum_price').val(ui.values[0]);
          $('#hidden_maximum_price').val(ui.values[1]);
        },
        stop:function(event, ui){
          filter_data();
        }
        });

      });
    });
  </script>
