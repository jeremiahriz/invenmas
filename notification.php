<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
  <link rel="stylesheet" href="./css/main.css">
  <title>Invenmas | Notifications</title>
</head>

<body>

  <?php
  session_start();
  include './config.php';

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
            <img class="img__avatar" src="./images/avatar.png" alt="' . $_SESSION['firstname'] . '" width="44px">';
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
            <li class="dashboard__list--li">

              <a href="./report.php"><img src="./images/report.svg" alt="Report">Report</a>
            </li>
            <li class="dashboard__list--li active">
              <a href="javascript:void(0)">
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
      <div class="not__card">
        <label class="additem__card--label stock__card--secondary">All Activities</label>
        <hr class="stock__card--hr">
        <table class="not__card--table">
          <tr class="not__card--tr">
            <td class="not__card--td">
              <img class="not__card--avatar" src="./images/avatar.png" alt="Avatar" width="32px">
            </td>
            <td class="not__card--td">
              Jeremiah Righteous reduced 32 quanties from the item ABCZ12354.
            </td>
            <td class="not__card--td">
              <a href="#" data-toggle="tooltip" class="not__card--tooltip" data-placement="top" title="January 16, 2018">10h</a>
            </td>
          </tr>
          <tr class="not__card--tr">
            <td class="not__card--td">
              <img class="not__card--avatar" src="./images/avatar.png" alt="Avatar" width="32px">
            </td>
            <td class="not__card--td">
              Jeremiah Righteous reduced 32 quanties from the item ABCZ12354.
            </td>
            <td class="not__card--td">
              <a href="#" data-toggle="tooltip" class="not__card--tooltip" data-placement="top" title="January 16, 2018">10h</a>
            </td>
          </tr>
          <tr class="not__card--tr">
            <td class="not__card--td">
              <img class="not__card--avatar" src="./images/avatar.png" alt="Avatar" width="32px">
            </td>
            <td class="not__card--td">
              Jeremiah Righteous reduced 32 quanties from the item ABCZ12354.
            </td>
            <td class="not__card--td">
              <a href="#" data-toggle="tooltip" class="not__card--tooltip" data-placement="top" title="January 16, 2018">10h</a>
            </td>
          </tr>
        </table>
        <!-- <button class="btn btn__primary btn__fullwidth">Load More Items</button> -->
      </div>
    </section>

    <footer class="footer__white">
      <div class="main__section">
        <h6>All Rights Reserved | About | Help | Feedback | built by <strong>Jeremiah Righteous</strong></h6>
      </div>
    </footer>

  </main>

</body>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

<script src="./js/main.js"></script>

</html>