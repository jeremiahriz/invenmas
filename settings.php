<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
  <link rel="stylesheet" href="./css/main.css">
  <title>Invenmas | Admin Settings</title>
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
  $role_index = null;
  $role = $_SESSION['role'];
  if ($role == 'Super Admin') {
    $role_index = 1;
  } else if ($role == 'Admin') {
    $role_index = 2;
  } else {
    $role_index = 3;
  }
  $firstname = $_SESSION['firstname'];
  $lastname = $_SESSION['lastname'];
  $email = $_SESSION['email'];

  if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit'])) {
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    if ($role_index == 1)
      $new_role = $_POST['role'];
    else $new_role = $_SESSION['role'];
    if (!empty($firstname) && !empty($lastname) && !empty($new_role)) {
      try {
        $sql = $conn->prepare(
          "UPDATE users SET `firstname` = ?, lastname = ?, `role` = ? WHERE `id` = ?"
        );
        $sql->execute(array($firstname, $lastname, $new_role, $u_id));
        $_SESSION["firstname"] = $firstname;
        $_SESSION["lastname"] = $lastname;
        $_SESSION["role"] = $new_role;
        header('Location: settings.php');
      } catch (PDOException $e) {
        echo $e->getMessage();
      }

      CloseCon($conn);
    } else {
      echo 'Fill all fields';
    }
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
            <li class="dashboard__list--li">
              <a href="./notification.php">
                <img src="./images/notification.svg" alt="Notification">Notification</a>
            </li>
            <li class="dashboard__list--li active">
              <a href="javascript:void(0)">
                <img src="./images/settings.svg" alt="Settings">Settings</a>
            </li>
          </ul>
        </div>

        <div>
          <a href="./add-item.php" class="btn__primary btn btn__primary--add">Add Item</a>
        </div>
      </section>
    </section>

    <section class="main__section user__section">
      <aside class="aside">
        <nav class="aside__nav">
          <ul>
            <li class="aside__li active">
              <a href="javascript:void(0)" class="aside__a">
                <img class="aside__img" src="./images/person-active.svg" alt="Admin">
                <span>Admin Profile</span>
              </a>
            </li>
            <?php if ($role_index == 1) {
              echo '<li class="aside__li">
              <a href="./manage-users.php" class="aside__a">
                <img class="aside__img" src="./images/persons.svg" alt="Manage Users">
                <span>Manage Users</span>
              </a>
            </li>';
            } ?>
          </ul>
        </nav>
      </aside>

      <section class="user__subsection">
        <div class="user__card">
          <h3 class="user__card--title">Admin Profile</h3>
          <hr>

          <div class="user__row">
            <section class="user__row--section">
              <form method='POST'>
                <table class="user__table">
                  <tr>
                    <td>
                      <div class="form-box">
                        <label for="firstname">First Name</label>
                        <input type="text" name="firstname" value="<?php echo $firstname; ?>" placeholder="First Name">
                      </div>
                    </td>
                  </tr>

                  <tr>
                    <td>
                      <div class="form-box">
                        <label for="lastname">Last Name</label>
                        <input type="text" name="lastname" value="<?php echo $lastname; ?>" placeholder="Last Name">
                      </div>
                    </td>
                  </tr>

                  <tr>
                    <td>
                      <div class="form-box">
                        <label for="email">Email</label>
                        <input type="email" name="email" disabled value="<?php echo $email; ?>">
                      </div>
                    </td>
                  </tr>

                  <tr>
                    <td>
                      <div class="form-box">
                        <label for="role">Role</label>
                        <select name="role" class="form-control" <?php if ($role_index !== 1) {
                                                                    echo 'disabled';
                                                                  } ?>>
                          <option <?php if ($role_index == 1) echo 'selected'; ?>>Super Admin</option>
                          <option <?php if ($role_index == 2) echo 'selected'; ?>>Admin</option>
                          <option <?php if ($role_index == 3) echo 'selected'; ?>>User</option>
                        </select>
                      </div>
                    </td>
                  </tr>
                </table>
                <div class="user__form--submit">
                  <button name="submit" type="submit" class="btn btn__primary" onclick="alert('Profile updated successfully')">Save Changes</button>
                </div>
              </form>
            </section>


            <section class="user__row--section">
              <div>
                <div class="user__innerdiv">
                  <?php
                  if (!empty($_SESSION['image'])) {
                    echo '<img class="img__avatar" src="data:image/png;base64,' . base64_encode($_SESSION['image']) . '" alt="' . $_SESSION['firstname'] . '" width="100px" height="100px">';
                  } else {
                    echo '
                  <img class="img__avatar" src="./images/avatar.png" alt="' . $_SESSION['firstname'] . '" width="100px">';
                  }
                  ?>
                  <div class="user__innerdiv mt-3">
                    <form id="uploadPicture" enctype="multipart/form-data">
                      <div class="form__box position-relative" style="display: inline-block">
                        <button type="button" class="btn btn__accent" onclick="showNewUpload()">Upload New Picture</button>
                        <input type="file" name="dp" id="newUploadInput" style="top:0;left:0;padding:0;opacity:0;cursor:pointer" accept="image/*" class="position-absolute"></div>
                    </form>
                    <?php if (!empty($_SESSION['image'])) echo '<button type="button" class="btn btn__delete" onclick="deleteImage(' . $_SESSION['id'] . ')">Delete</button>'; ?>
                  </div>
                </div>
                <div class="user__innerdiv">
                  <a>Change Password</a>
                </div>
            </section>
          </div>
        </div>
      </section>
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

<script src="./js/main"></script>

</html>