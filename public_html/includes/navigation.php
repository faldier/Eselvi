<?php
  $categoriesQuery = $conn->query("SELECT * FROM categories");
 ?>

  <nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="collapse navbar-collapse" id="navbarNav">
      <a class="navbar-brand" href="index.php">
        <img src="images/logo1.png" class="LogoImg"  alt="Universal Electronics Logo">
      </a>
      <ul class="navbar-nav">
        <?php
          while($categories = mysqli_fetch_assoc($categoriesQuery)):
            $category_id = $categories['id'];?>
            <li class="nav-item">
              <a class="nav-link" href="products.php?category=<?=$category_id ;?>"><?=$categories['category'] ?></a>
            </li>
          <?php endwhile; ?>
        <li class="nav-item">
          <a class="nav-link text-danger" href="products.php?category=0"><strong>Promotions</strong></a>
        </li>
        <li class="nav-item">
          <form class="form-inline">
            <input class="form-control mr-sm-3" type="search" placeholder="Search" aria-label="Search">
            <button class="btn btn-outline-success btn-sm mr-sm-3" type="submit">Search</button>
          </form>
        </li>

      <?php
        if(is_logged_in()):  ?>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              <?= $user_data['username']; ?>
            </a>
            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
              <a class="dropdown-item" href="#">My cart</a>
              <a class="dropdown-item" href="#">My favorites</a>
              <div class="dropdown-divider"></div>
              <a class="dropdown-item" href="logout.php">Log out</a>
            </div>
          </li>

        <?php else:?>

          <li class="nav-item">
            <a class="nav-link btn btn-success btn-sm ml-3" href="login.php">Login</a>
          </li>
          <li class="nav-item">
            <a class="nav-link btn btn-success btn-sm ml-3" href="register.php">Register</a>
          </li>

        <?php endif;  ?>

      </ul>
  </div>
</nav>
