<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
  <link rel="stylesheet" href="./css/main.css">
  <title>Invenmas | Log In</title>
</head>

<body>

  <?php
  include './config.php';
  session_start();
  if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
    header("location: dashboard.php");
    exit;
  }
  $conn = OpenCon();

  if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];
    try {
      if (!empty($email) && $password != null) {
        $sql = $conn->prepare(
          "SELECT * FROM users WHERE email = ?"
        );
        $sql->execute(array($email));
        while ($row = $sql->fetch()) {
          if (password_verify($password, $row['password'])) {
            session_start();
            $_SESSION["loggedin"] = true;
            $_SESSION["id"] = $row['id'];
            $_SESSION["firstname"] = $row['firstname'];
            $_SESSION["lastname"] = $row['lastname'];
            $_SESSION["email"] = $row['email'];
            $_SESSION["role"] = $row['role'];
            $_SESSION['image'] = $row['image'];

            header('Location: dashboard.php');
          } else {
            echo 'Invalid name/password';
          }
        }
      } else {
        echo 'Fill all fields';
      }
    } catch (PDOException $e) {
      echo $e->getMessage();
    }
  }

  CloseCon($conn);
  ?>

  <main class="main__nofooter">

    <header class="header__white">
      <div class="main__section">
        <img src="./images/logo.svg" alt="Invenmas">
      </div>
    </header>

    <section class="main__section login__center">
      <div class="login__card">
        <h1>Welcome Back</h1>
        <h3>Login to your account</h3>
        <form action="" class="login__card--form" method="POST">
          <div class="form__box">
            <label for="email">Email</label>
            <input type="email" name="email" placeholder="Email Address">
          </div>
          <div class="form__box">
            <label for="password">Password</label>
            <input type="password" name="password" placeholder="Password">
          </div>
          <div class="form__box">
            <button class="login__card--button btn__large" name="submit" type="submit">Log In</button>
          </div>
        </form>
        <div class="login__card--forgot">
          <h3>Forgot Password? <a href="" class="login__card--reset">Reset</a></h3>
        </div>
      </div>
    </section>

  </main>

</body>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

</html>