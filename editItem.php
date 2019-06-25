<?php
include './config.php';
$conn = OpenCon();
$id = $_REQUEST['id'];
$name = $_REQUEST['editname'];
$quantity = $_REQUEST['editquantity'];
$tag = $_REQUEST['edittag'];
$min_qty = $_REQUEST['editminquantity'];
$max_qty = $_REQUEST['editmaxquantity'];
try {
  if (!empty($name) && !empty($tag) && !empty($min_qty) && !empty($max_qty)) {
    $sql = $conn->prepare(
      "UPDATE items SET `name` = ?, quantity = ?, tag = ?, min_quantity = ?, max_quantity = ? WHERE id = ?"
    );
    $sql->execute(array($name, $quantity, $tag, $min_qty, $max_qty, $id));
  } else {
    echo 'Fill all fields';
  }
} catch (PDOException $e) {
  echo $e->getMessage();
}
CloseCon($conn);
