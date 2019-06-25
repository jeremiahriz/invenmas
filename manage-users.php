<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
  <link rel="stylesheet" href="./css/main.css">
  <title>Invenmas | Manage Users</title>
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

  if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit'])) {
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $email = $_POST['email'];
    $role = $_POST['role'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    try {
      if (!empty($firstname) && !empty($lastname) && !empty($email) && !empty($role) && !empty($password)) {
        $sql = $conn->prepare(
          "SELECT * FROM users WHERE email = ?"
        );
        $sql->execute(array($email));
        $rows = $sql->fetch();
        if (count($rows['id']) > 0) {
          echo $email . ' is already in use';
        } else {
          $sql = $conn->prepare(
            "INSERT INTO users(id,firstname,lastname,email,role,password) VALUES (?,?,?,?,?,?)"
          );
          $sql->execute(array(null, $firstname, $lastname, $email, $role, $password));
        }
      } else {
        echo 'Fill all fields';
      }
    } catch (PDOException $e) {
      echo $e->getMessage();
    }

    CloseCon($conn);
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
      </section>
    </section>

    <section class="main__section user__section">
      <aside class="aside">
        <nav class="aside__nav">
          <ul>
            <li class="aside__li">
              <a href="./settings.php" class="aside__a">
                <img class="aside__img" src="./images/person.svg" alt="Admin">
                <span>Admin Profile</span>
              </a>
            </li>
            <li class="aside__li active">
              <a href="javascipt:void(0)" class="aside__a">
                <img class="aside__img" src="./images/persons-active.svg" alt="Manage Users">
                <span>Manage Users</span>
              </a>
            </li>
          </ul>
        </nav>
      </aside>

      <section class="user__subsection">
        <div class="user__card">
          <div class="user__card--row">
            <h3 class="user__card--title">Manage Users</h3>
            <a href="./add-item.php" class="btn__primary btn btn__primary--add" data-toggle="modal" data-target="#addUser">Add User</a>
          </div>
          <hr>

          <section>
            <table class="user__table">
              <thead class="table__card-thead">
                <th class="table__card--th">S/N</th>
                <th class="table__card--th">Name</th>
                <th class="table__card--th">Email</th>
                <th class="table__card--th">Role</th>
                <th class="table__card--th">Action</th>
              </thead>

              <tbody>
                <?php

                $sql = $conn->prepare(
                  "SELECT * FROM users"
                );
                $sql->execute(array($u_id));
                $rows = $sql->fetchAll();
                $count = 1;
                foreach ($rows as $row) {
                  $id = $row['id'];
                  echo '<tr class="table__card--tr">';
                  echo '<td class="table__card--td">' . $count . '</td>';
                  echo '<td class="table__card--td">' . $row['firstname'] . ' ' . $row['lastname'] . '</td>';
                  echo '<td class="table__card--td">' . $row['email'] . '</td>';
                  echo '<td class="table__card--td">' . $row['role'] . '</td>';
                  echo '
                  <td class="table__card--td">
                    <button class="btn btn__primary--flat" data-toggle="modal" onclick="setUser(' . $id . ')" data-target="#editUserModal">Edit</button>
                    <div class="dropdown">
                      <a class="dropdown-toggle" data-toggle="dropdown">
                        <img src="./images/menu.svg" alt="Menu">
                      </a>
                      <div class="dropdown-menu dropdown-menu-right">
                        <a class="dropdown-item" href="#" onClick=deleteUser(' . $id . ')>Remove User</a>
                      </div>
                    </div>
                  </td>';
                  echo '</tr>';
                  $count++;
                }
                ?>
              </tbody>
            </table>
          </section>
        </div>
      </section>
    </section>

    <div class="modal" id="addUser">
      <div class="modal-dialog">
        <div class="modal-content py-3 px-4">
          <div class="modal-header py-2 px-0">
            <h4>Add New User</h4>
          </div>
          <div class="modal-body py-3 px-0">
            <form action="" id="addNewUser" method='POST' enctype="multipart/form-data">
              <table class="user__table">
                <tr>
                  <td>
                    <div class="form-box">
                      <label for="firstname">First Name</label>
                      <input type="text" name="firstname" placeholder="First Name">
                    </div>
                  </td>
                </tr>

                <tr>
                  <td>
                    <div class="form-box">
                      <label for="lastname">Last Name</label>
                      <input type="text" name="lastname" placeholder="Last Name">
                    </div>
                  </td>
                </tr>

                <tr>
                  <td>
                    <div class="form-box">
                      <label for="email">Email</label>
                      <input type="email" name="email">
                    </div>
                  </td>
                </tr>

                <tr>
                  <td>
                    <div class="form__box">
                      <label for="password"> Create Password</label>
                      <input type="text" name="password" placeholder="Password">
                    </div>
                  </td>
                </tr>

                <tr>
                  <td>
                    <div class="form-box">
                      <label for="role">Role</label>
                      <select name="role" class="form-control">
                        <option>Super Admin</option>
                        <option>Admin</option>
                        <option>User</option>
                      </select>
                    </div>
                  </td>
                </tr>

                <tr>
                  <td>
                    <button type="submit" name="submit" onclick="addUser()" class="btn btn__fullwidth btn__primary">Add User</button>
                  </td>
                </tr>
              </table>
            </form>
          </div>
        </div>
      </div>
    </div>

    <div class="modal" id="editUserModal">
      <div class="modal-dialog">
        <div class="modal-content py-3 px-4">
          <div class="modal-header py-2 px-0">
            <h4>Edit User</h4>
          </div>
          <div class="modal-body py-3 px-0">
            <form action="" id="editUser" method='POST' enctype="multipart/form-data">
              <table class="user__table">
                <tr>
                  <td>
                    <div class="form-box">
                      <label for="firstname">First Name</label>
                      <input type="text" name="firstname" placeholder="First Name">
                    </div>
                  </td>
                </tr>

                <tr>
                  <td>
                    <div class="form-box">
                      <label for="lastname">Last Name</label>
                      <input type="text" name="lastname" placeholder="Last Name">
                    </div>
                  </td>
                </tr>

                <tr>
                  <td>
                    <div class="form-box">
                      <label for="email">Email</label>
                      <input type="email" name="email" disabled>
                    </div>
                  </td>
                </tr>

                <tr>
                  <td>
                    <div class="form-box">
                      <label for="role">Role</label>
                      <select name="role" class="form-control">
                        <option>Super Admin</option>
                        <option>Admin</option>
                        <option>User</option>
                      </select>
                    </div>
                  </td>
                </tr>

                <tr>
                  <td>
                    <button type="submit" name="submitEdit" onclick="editUser()" class="btn btn__fullwidth btn__primary">Save Changes</button>
                    <!-- <button type="button" class="btn btn__fullwidth btn__primary" data-toggle="modal" data-target="#successModal">Add User</button> -->
                  </td>
                </tr>
              </table>
            </form>
          </div>
        </div>
      </div>
    </div>

    <div class="modal" id="successModal">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-body text-center py-5">
            <img src="./images/success.svg" alt="success">
            <br><br><br>
            <h2 class="font-weight-normal">User Added successfully!</h2>
            <br><br>
            <a href="./settings.php" class="btn btn-primary w-100 text-white">View users</a>
          </div>
        </div>
      </div>
    </div>

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