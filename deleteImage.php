<?php
include './config.php';
session_start();
$conn = OpenCon();
$id = $_REQUEST['id'];
try {
  $sql = $conn->prepare(
    "UPDATE users SET `image`=? WHERE `id` = ?"
  );
  $sql->execute(array(null, $id));
  unset($_SESSION['image']);
} catch (PDOExcetion $e) {
  echo $e->getMessage();
}
CloseCon($conn);
