<?php
include './config.php';
$conn = OpenCon();
$firstname = $_REQUEST['firstname'];
$lastname = $_REQUEST['lastname'];
$email = $_REQUEST['email'];
$role = $_REQUEST['role'];
$password = password_hash($_REQUEST['password'], PASSWORD_DEFAULT);
try {
  if (!empty($firstname) && !empty($lastname) && !empty($email) && !empty($role) && !empty($password)) {
    $sql = $conn->prepare(
      "SELECT * FROM users WHERE email = ?"
    );
    $sql->execute(array($email));
    $rows = $sql->fetch(PDO::FETCH_ASSOC);
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
