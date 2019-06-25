<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
  <link rel="stylesheet" href="./css/main.css">
  <title>Invenmas | Report Analysis</title>
</head>

<body>
  <?php
  include './config.php';
  session_start();
  $u_id = $_SESSION['id'];
  $rows = [];
  $items = [];
  if (!$u_id) {
    header('Location: index.php');
  }
  $conn = OpenCon();

  $sql = $conn->prepare(
    "SELECT * FROM items"
  );
  $sql->execute();
  $rows = $sql->fetchAll();

  if (isset($_POST['low_qty'])) {
    $items = [];
    $sql->execute(array($u_id));
    $result = $sql->fetchAll();
    foreach ($result as $row) {
      $arr2 = [];
      if ($row['quantity'] <= $row['min_quantity'] && $row['quantity'] != 0) {
        array_push($arr2, $row['sku'], $row['name'], $row['quantity'], $row['tag'], $row['max_quantity']);
        array_push($items, $arr2);
      }
    }
  } else 
  if (isset($_POST['in_stock'])) {
    $items = [];
    $sql->execute(array($u_id));
    $result = $sql->fetchAll();
    foreach ($result as $row) {
      $arr2 = [];
      if ($row['quantity'] <= $row['max_quantity'] && $row['quantity'] >= $row['min_quantity']) {
        array_push($arr2, $row['sku'], $row['name'], $row['quantity'], $row['tag'], $row['max_quantity']);
        array_push($items, $arr2);
      }
    }
  } else 
  if (isset($_POST['over_stocked'])) {
    $items = [];
    $sql->execute(array($u_id));
    $result = $sql->fetchAll();
    foreach ($result as $row) {
      $arr2 = [];
      if ($row['quantity'] > $row['max_quantity']) {
        array_push($arr2, $row['sku'], $row['name'], $row['quantity'], $row['tag'], $row['max_quantity']);
        array_push($items, $arr2);
      }
    }
  } else 
  if (isset($_POST['out_stock'])) {
    $items = [];
    $sql->execute(array($u_id));
    $result = $sql->fetchAll();
    foreach ($result as $row) {
      $arr2 = [];
      if ($row['quantity'] == 0) {
        array_push($arr2, $row['sku'], $row['name'], $row['quantity'], $row['tag'], $row['max_quantity']);
        array_push($items, $arr2);
      }
    }
  } else {
    $items = execQuery($sql);
  }

  function execQuery($sql)
  {
    $sql->execute(array($_SESSION['id']));
    $result = $sql->fetchAll();
    $arr = [];

    foreach ($result as $row) {
      $arr2 = [];
      array_push($arr2, $row['sku'], $row['name'], $row['quantity'], $row['tag'], $row['max_quantity']);
      array_push($arr, $arr2);
    }
    return $arr;
  }

  function reOrder($qty, $max)
  {
    if ($max >= $qty) return $max - $qty;
    return 0;
  }

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
        <div class="dashboard__list">
          <ul class="dashboard__list--ul">
            <li class="dashboard__list--li">
              <a href="./dashboard.php">
                <img src="./images/dashboard.svg" alt="Dashboard">Dashboard</a>
            </li>
            <li class="dashboard__list--li active">

              <a href="javascript:void(0)"><img src="./images/report.svg" alt="Report">Report</a>
            </li>
            <li class="dashboard__list--li">
              <a href="./notification.php">
                <img src="./images/notification.svg" alt="Notification">Notification</a>
            </li>
            <li class="dashboard__list--li">
              <a href="./settings.php">
                <img src="./images/settings.svg" alt="Settings">Settings</a>
            </li>
          </ul>
        </div>

        <div>
          <form action="print.php" method="POST">
            <button name='print' type="submit" class="btn__primary btn btn__primary--add">Print Report</button>
          </form>
        </div>
      </section>
    </section>

    <section class="main__section">
      <div class="stock__card">
        <label class="additem__card--label stock__card--secondary">Stock Level Analysis</label>
        <hr class="stock__card--hr">
        <form method="POST" id="stockitems">
          <div class="stock__items">
            <button class="stock__items--btn" name="total_items">
              <div class="stock__items--item">
                <h2><?php
                    if (count($rows) <= 0) echo 0;
                    else echo count($rows);
                    ?></h2>
                <span class="additem__card--label">TOTAL STOCKS</span>
              </div>
            </button>
            <img src="./images/hr.svg" alt="Horizontal Line">
            <button class="stock__items--btn" name="in_stock">
              <div class="stock__items--item">
                <h2><?php
                    $total = 0;
                    foreach ($rows as $row) {
                      if ($row['quantity'] >= $row['min_quantity'] && $row['quantity'] <= $row['max_quantity']) {
                        $total++;
                      }
                    }
                    echo $total;
                    ?></h2>
                <span class="additem__card--label">AVERAGE STOCKS</span>
              </div>
            </button>
            <img src="./images/hr.svg" alt="Horizontal Line">
            <button class="stock__items--btn" name="low_qty">
              <div class="stock__items--item">
                <h2><?php
                    $total = 0;
                    foreach ($rows as $row) {
                      if ($row['quantity'] <= $row['min_quantity'] && $row['quantity'] != 0) $total++;
                    }
                    echo $total;
                    ?></h2>
                <span class="additem__card--label">UNDERSTOCKS</span>
              </div>
            </button>
            <img src="./images/hr.svg" alt="Horizontal Line">
            <button class="stock__items--btn" name="out_stock">
              <div class="stock__items--item">
                <h2><?php
                    $total = 0;
                    foreach ($rows as $row) {
                      if ($row['quantity'] == 0) $total++;
                    }
                    echo $total;
                    ?></h2>
                <span class="additem__card--label">OUT-OF-STOCKS</span>
              </div>
            </button>
            <img src="./images/hr.svg" alt="Horizontal Line">
            <button class="stock__items--btn" name="over_stocked">
              <div class="stock__items--item">
                <h2><?php
                    $total = 0;
                    foreach ($rows as $row) {
                      if ($row['quantity'] > $row['max_quantity']) $total++;
                    }
                    echo $total;
                    ?></h2>
                <span class="additem__card--label">OVERSTOCKS</span>
              </div>
            </button>
          </div>
        </form>
      </div>
    </section>

    <section class="main__section">
      <div class="table__card">
        <div class="table__title">
          <h2 class="table__title--h4"><?php if (isset($_POST['low_qty'])) echo 'Understock Items';
                                        else if (isset($_POST['over_stocked'])) echo 'OverStock Items';
                                        else if (isset($_POST['out_stock'])) echo 'Out-of-Stock Items';
                                        else if (isset($_POST['in_stock'])) echo 'Average Stock Items';
                                        else echo 'Total Stock Items'; ?></h2>
          <div>
            <!-- <a href="javascript:void(0)" class="table__title--a"><img src="./images/search.svg" alt="search"></a> -->
            <a href="javascript:void(0)" class="table__title--a"><img src="./images/sort.svg" alt="sort"></a>
            <a href="javascript:void(0)" class="table__title--a"><img width="24px" src="./images/filter.svg" alt="filter"></a>
          </div>
        </div>
        <table class="table__card--table">
          <thead class="table__card--thead">
            <th class="table__card--th">SKU</th>
            <th class="table__card--th">Item Description</th>
            <th class="table__card--th">Quantity</th>
            <th class="table__card--th">Tag</th>
            <th class="table__card--th">Reorder Quantity</th>
          </thead>

          <tbody>
            <?php
            foreach ($items as $row) {
              echo '<tr class="table__card--tr">';
              echo '<td class="table__card--td">' . $row[0] . '</td>';
              echo '<td class="table__card--td">' . $row[1] . '</td>';
              echo '<td class="table__card--td">' . $row[2] . '</td>';
              echo '<td class="table__card--td">' . $row[3] . '</td>';
              echo '<td class="table__card--td">' . reOrder($row[2], $row[4]) . '</td>';
              echo '</tr>';
            }
            ?>
          </tbody>
        </table>
        <?php
        // if (count($items) > 0) {
        //   echo '
        // <button class="btn btn__primary btn__fullwidth">Load More Items</button>';
        // }
        ?>
      </div>
    </section>

    <footer class="footer__white">
      <div class="main__section">
        <h6>All Rights Reserved | Built by <strong>Jeremiah Righteous</strong></h6>
      </div>
    </footer>

  </main>

</body>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

<script src="./js/main.js"></script>

</html>