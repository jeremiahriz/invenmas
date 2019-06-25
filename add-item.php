<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
  <link rel="stylesheet" href="./css/main.css">
  <title>Invenmas | Add New Item</title>
</head>

<body>
  <?php
  session_start();
  include './config.php';
  $_SESSION['sku'] = getSKU();

  if (isset($_POST['logout'])) {
    session_destroy();
    header('Location: index.php');
  }
  ?>
  <main>
    <header class="header__primary">
      <div class="main__section header__primary--flex">
        <div>
          <img src="./images/logo-white.svg" alt="Invenmas">
        </div>
        <div class="dropdown">
          <div class="dropdown-toggle header__primary--items" data-toggle="dropdown">
            <span>
              <?php echo $_SESSION['firstname'] . ' ' . $_SESSION['lastname']; ?></span>
            <?php
            if (!empty($_SESSION['image'])) {
              echo '<img class="img__avatar" src="data:image/png;base64,' . base64_encode($_SESSION['image']) . '" alt="' . $_SESSION['firstname'] . '" width="44px" height="44px">';
            } else {
              echo '
            <img class="img__avatar" src="./images/avatar.jpg" alt="' . $_SESSION['firstname'] . '" width="44px">';
            }
            ?>
          </div>
          <div class="dropdown-menu py-0 dropdown-menu-right">
            <form method="POST">
              <button class="dropdown-item btn btn__accent" name="logout" href="#">Log out</button>
            </form>
          </div>
        </div>
      </div>
    </header>

    <section class="header__white">
      <section class="main__section dashboard__header">
        <div class='additem__text'>
          <span>SKU: <strong><?php echo $_SESSION['sku']; ?></strong></span>
        </div>

        <div class='additem__text'>
          <span>created <?php echo formatDate(); ?></span>
        </div>
      </section>
    </section>

    <section class="main__section">
      <div class="additem__card">
        <h2 class="additem__card--h2">New Stock Details</h2>
        <hr class="additem__card--hr">
        <form onsubmit='addNewItem()' id="addNewItem" method="POST" enctype="multipart/form-data">
          <div class="row">
            <div class="col-12 form-group">
              <label class="add__item--label" for="stockname">Stock Name</label>
              <input type="text" name="stockname" placeholder="Stock Name">
            </div>
            <div class="col-12 col-md-6 form-group">
              <label class="add__item--label" for="stockqty">Quantity</label>
              <input type="text" name="stockqty" placeholder="Stock Quantity">
            </div>
            <div class="col-12 col-md-6 form-group">
              <label class="add__item--label" for="tag">Tag</label>
              <input type="text" name="tag" placeholder="Tag">
            </div>
            <div class="col-12 col-md-6 form-group">
              <label class="add__item--label" for="minlevel">Minimum Qty Level</label>
              <input type="number" name="minlevel" placeholder="">
            </div>
            <div class="col-12 col-md-6 form-group">
              <label class="add__item--label" for="maxlevel">Maximum Qty Level</label>
              <input type="number" name="maxlevel" placeholder="">
            </div>
          </div>
          <button type="button" onclick="addNewItem()" class="btn btn__primary btn__fullwidth" name="submit">Add Item</button>
          <a class="additem__card--cancel" href="./dashboard.php">Cancel</a>
        </form>
      </div>
    </section>

    <button type="button" id="showItemModal" style='display: none' data-toggle="modal" data-target="#myModal">
      Open modal
    </button>

    <!-- The Modal -->
    <div class="modal" id="myModal">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-body text-center py-5">
            <img src="./images/success.svg" alt="success">
            <br><br><br>
            <h2 class="font-weight-normal">Item added successfully!</h2>
            <br><br>
            <a href="./dashboard.php" class="btn btn-primary w-100 text-white">View items</a>
          </div>
        </div>
      </div>
    </div>

    <footer class="footer__white">
      <div class="main__section">
        <h6 class="footer__white--h6">All Rights Reserved  |  Built by <strong>Jeremiah
            Righteous</strong></h6>
      </div>
    </footer>

  </main>

</body>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

<script src="./js/main"></script>

</html>