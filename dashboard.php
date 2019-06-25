<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
  <link rel="stylesheet" href="./css/main.css">
  <title>Invenmas | Welcome to Invenmas</title>
</head>

<body>
  <?php
  include './config.php';
  session_start();
  $u_id = $_SESSION['id'];
  if (!$u_id) {
    header('Location: index.php');
  }
  $conn = OpenCon();

  if (isset($_POST['logout'])) {
    session_destroy();
    header('Location: index.php');
  }

  $sql = $conn->prepare(
    "SELECT * FROM items"
  );
  $sql->execute(array($u_id));
  $rows = $sql->fetchAll();

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
            <li class="dashboard__list--li active">
              <a href="javascipt:void(0)">
                <img src="./images/dashboard.svg" alt="Dashboard">Dashboard</a>
            </li>
            <li class="dashboard__list--li">

              <a href="./report.php"><img src="./images/report.svg" alt="Report">Report</a>
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
          <a href="./add-item.php" class="btn__primary btn btn__primary--add">Add Item</a>
        </div>
      </section>
    </section>

    <section class="main__section">
      <div class="table__card">
        <div class="table__title">
          <h2 class="table__title--h4">Inventory</h2>
          <div class="d-flex align-items-center">
            <form method="GET" class="table__form" id="search-form">
              <div class="table__form--div">
                <input type="text" name="query" onkeyup="search()" />
                <button class="btn"><img src="./images/search.svg" alt="search"></button>
              </div>
            </form>
            <a href="javascript:void(0)" class="table__title--a active" id="search-icon"><img src="./images/search.svg" alt="search"></a>
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
            <th class="table__card--th">Action</th>
          </thead>

          <tbody id="show__tbody">
            <?php
            $count = 0;
            foreach ($rows as $row) {
              $id = $row['id'];
              echo '<tr class="table__card--tr">';
              echo '<td class="table__card--td">' . $row['sku'] . '</td>';
              echo '<td class="table__card--td">' . $row['name'] . '</td>';
              echo '<td class="table__card--td table__td--qty">' . $row['quantity'] . '</td>';
              echo '<td class="table__card--td">' . $row['tag'] . '</td>';
              echo '
              <td class="table__card--td">
                <button class="btn btn__accent show--reduce" onClick="reduce(' . $id . ', ' . $count . ')">Reduce</button>
                <button class="btn btn__primary" onClick="add(' . $id . ', ' . $count .  ')">Add</button>
                <div class="dropdown">
                  <a class="dropdown-toggle" data-toggle="dropdown">
                    <img src="./images/menu.svg" alt="Menu">
                  </a>
                  <div class="dropdown-menu dropdown-menu-right">
                    <a class="dropdown-item mb-3" href="#" onclick="setItem(' . $id . ')" data-toggle="modal" data-target="#editItemModal">Edit Item</a>
                    <a class="dropdown-item" href="#" onclick="deleteItem(' . $id . ')">Delete Item</a>
                  </div>
                </div>
              </td>';
              echo '</tr>';
              $count += 1;
            }
            ?>
          </tbody>
        </table>

        <?php
        // if (count($rows) > 0) {
        //   echo '
        // <button class="btn btn__primary btn__fullwidth">Load More Items</button>';
        // }
        ?>
      </div>
    </section>

    <div class="modal" id="editItemModal">
      <div class="modal-dialog">
        <div class="modal-content py-3 px-4">
          <div class="modal-header py-2 px-0 row">
            <h4>Edit Item</h4>
            <h4 id="editItemSKU"></h4>
          </div>
          <div class="modal-body py-3 px-0">
            <form action="" id="editItem" method='POST' enctype="multipart/form-data">
              <table class="user__table">
                <tr>
                  <td>
                    <div class="form-box">
                      <label for="editname">Name</label>
                      <input type="text" name="editname" placeholder="First Name">
                    </div>
                  </td>
                </tr>

                <tr>
                  <td>
                    <div class="form-box">
                      <label for="edittag">Tag</label>
                      <input type="text" name="edittag" placeholder="Last Name">
                    </div>
                  </td>
                </tr>

                <tr>
                  <td>
                    <div class="form-box">
                      <label for="editquantity">Quantity</label>
                      <input type="text" name="editquantity" placeholder="Last Name">
                    </div>
                  </td>
                </tr>

                <tr>
                  <td>
                    <div class="form-box">
                      <label for="editminquantity">Minimum Quantity</label>
                      <input type="text" name="editminquantity" placeholder="Last Name">
                    </div>
                  </td>
                </tr>

                <tr>
                  <td>
                    <div class="form-box">
                      <label for="editmaxquantity">Maximum Quantity</label>
                      <input type="text" name="editmaxquantity" placeholder="Last Name">
                    </div>
                  </td>
                </tr>

                <tr>
                  <td>
                    <button type="submit" name="submitEdit" onclick="editItem()" class="btn btn__fullwidth btn__primary">Save Changes</button>
                  </td>
                </tr>
              </table>
            </form>
          </div>
        </div>
      </div>
    </div>

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

<script src="./js/main"></script>

</html>