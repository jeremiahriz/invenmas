<?php
include './config.php';
$conn = OpenCon();
$id = $_REQUEST['id'];
$firstname = $_REQUEST['firstname'];
$lastname = $_REQUEST['lastname'];
$email = $_REQUEST['email'];
$role = $_REQUEST['role'];
try {
  if (!empty($firstname) && !empty($lastname) && !empty($email) && !empty($role)) {
    $sql = $conn->prepare(
      "UPDATE users SET firstname = ?, lastname = ?, email = ?, `role`= ? WHERE id = ?"
    );
    $sql->execute(array($firstname, $lastname, $email, $role, $id));
  } else {
    echo 'Fill all fields';
  }
} catch (PDOException $e) {
  echo $e->getMessage();
}
CloseCon($conn);
