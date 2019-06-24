<?php
include './config.php';
session_start();
$conn = OpenCon();
$id = $_SESSION['id'];
if (empty($_FILES['newImage'])) {
  echo 'Image missing';
} else {
  $image = file_get_contents($_FILES['newImage']['tmp_name']);
  try {
    $sql = $conn->prepare(
      "UPDATE users SET `image` = ? WHERE id = ?"
    );
    $sql->execute(array($image, $id));
    $_SESSION['image'] = $image;
  } catch (PDOException $e) {
    echo $e->getMessage();
  }
}
CloseCon($conn);
