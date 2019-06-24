<?php
include './config.php';
$conn = OpenCon();
$id = $_REQUEST['id'];
try {
  $sql = $conn->prepare(
    "DELETE FROM users WHERE `id` = ?"
  );
  $sql->execute(array($id));
} catch (PDOExcetion $e) {
  echo $e->getMessage();
}
CloseCon($conn);
