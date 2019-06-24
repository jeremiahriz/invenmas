<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
  <link rel="stylesheet" href="./css/main.css">
  <title>Invenmas | Register</title>
</head>

<body>

  <?php
  include './config.php';
  $conn = OpenCon();
  $image = null;

  if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit'])) {

    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $email = $_POST['email'];
    $role = $_POST['role'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    if (isset($_FILES['dp']['tmp_name']) && !empty($_FILES['dp']['tmp_name'])) {
      $image = file_get_contents($_FILES['dp']['tmp_name']);
    } else {
      echo 'You should select a file to upload !!';
    }
    try {
      if (!empty($firstname) && !empty($lastname) && !empty($email) && !empty($role) && !empty($password)) {
        $sql = $conn->prepare(
          "SELECT * FROM users WHERE email = ?"
        );
        $sql->execute(array($email));
        $rows = $sql->fetch(PDO::FETCH_ASSOC);
        if (count($rows['id']) > 0) {
          echo 'User already exists';
        } else {
          $sql = $conn->prepare(
            "INSERT INTO users(id,firstname,lastname,email,`role`,`password`,`image`) VALUES (?,?,?,?,?,?,?)"
          );
          $sql->execute(array(null, $firstname, $lastname, $email, $role, $password, $image));
          $sql = $conn->prepare(
            "SELECT * FROM users WHERE email = ?"
          );
          $sql->execute(array($email));
          while ($row = $sql->fetch()) {
            session_start();
            $_SESSION["loggedin"] = true;
            $_SESSION["id"] = $row['id'];
            $_SESSION["firstname"] = $firstname;
            $_SESSION["lastname"] = $lastname;
            $_SESSION["email"] = $email;
            $_SESSION["role"] = $role;
            $_SESSION['image'] = $image;
            header('Location: dashboard.php');
          }
        }
      } else {
        echo 'Fill all fields';
      }
    } catch (PDOException $e) {
      // echo $sq l  .  " < br>" . $e->getMessage()
      echo $e->getMessage();
    }

    CloseCon($conn);
  }
  ?>
  <main class="main__nofooter">
    <header class="header__white">
      <div class="main__section">
        <img src="./images/logo.svg" alt="Invenmas">
      </div>
    </header>

    <section class="main__section login__center">
      <div class="register__card">
        <h1>Create an account</h1>
        <h3>Set up your account for use in Invenmas</h3>
        <form action="" method='POST' enctype="multipart/form-data" class="login__card--form">
          <div class="form__box">
            <label for="firstname">First Name</label>
            <input type="text" name="firstname" placeholder="Jane">
          </div>
          <div class="form__box">
            <label for="lastname">Last Name</label>
            <input type="text" name="lastname" placeholder="Doe">
          </div>
          <div class="form__box">
            <label for="email">Email</label>
            <input type="email" name="email" placeholder="Email Address">
          </div>
          <div class="form__box">
            <label for="role">Role</label>
            <select name="role" class="form-control">
              <option>Super Admin</option>
              <option>Admin</option>
              <option>User</option>
            </select>
          </div>
          <div class="form__box">
            <label for="dp">Upload a picture</label>
            <input type="file" name="dp" accept="image/*">
          </div>
          <div class="form__box">
            <label for="password"> Create Password</label>
            <input type="password" name="password" placeholder="Password">
          </div>
          <div class="form__box">
            <button class="login__card--button btn__large" name="submit" type="submit">Register</button>
          </div>
        </form>
        <div class="login__card--forgot">
          <h3>Already have an account? <a href="./index.php" class="login__card--reset">Login</a></h3>
        </div>
      </div>
    </section>

  </main>
</body>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

</html>