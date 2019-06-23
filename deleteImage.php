<?php
include './config.php';
session_start();
$conn = OpenCon();
$id = $_REQUEST['id'];
try {
  $sql = $conn->prepare(
    "DELETE FROM users WHERE `id` = ?"
  );
  $sql->execute(array($id));
  unset($_SESSION['image']);
} catch (PDOExcetion $e) {
  echo $e->getMessage();
}
CloseCon($conn);
