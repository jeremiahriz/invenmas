<?php
session_start();
include './config.php';

$conn = OpenCon();
$name = $_REQUEST['stockname'];
$quantity = (int)$_REQUEST['stockqty'];
$tag = $_REQUEST['tag'];
$minValue = (int)$_REQUEST['minlevel'];
$maxValue = (int)$_REQUEST['maxlevel'];
$u_id = (int)$_SESSION['id'];
try {
  if (!empty($name) && !empty($quantity) && !empty($tag)) {
    $sql = $conn->prepare(
      "INSERT INTO items (`id`,`u_id`,`name`,`sku`,`quantity`,`tag`,`min_quantity`,`max_quantity`,`date_created`) VALUES (?,?,?,?,?,?,?,?,?)"
    );
    $sql->execute(array(null, $u_id, $name, $_SESSION['sku'], $quantity, $tag, $minValue, $maxValue, date('Y/m/d')));
    echo true;
  } else {
    echo false;
  }
} catch (PDOException $e) {
  echo $e->getMessage();
}

CloseCon($conn);
