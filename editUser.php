<?php
include './config.php';
session_start();
$conn = OpenCon();
$id = $_REQUEST['id'];
$firstname = $_REQUEST['firstname'];
$lastname = $_REQUEST['lastname'];
$role = $_REQUEST['role'];
try {
  if (!empty($firstname) && !empty($lastname) && !empty($role)) {
    $sql = $conn->prepare(
      "UPDATE users SET firstname = ?, lastname = ?, `role`= ? WHERE id = ?"
    );
    $sql->execute(array($firstname, $lastname, $role, $id));
    $_SESSION['firstname'] = $firstname;
    $_SESSION['lastname'] = $lastname;
    $_SESSION['role'] = $role;
  } else {
    echo 'Fill all fields';
  }
} catch (PDOException $e) {
  echo $e->getMessage();
}
CloseCon($conn);
