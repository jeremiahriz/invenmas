<?php
include './config.php';
$conn = OpenCon();
$id = $_REQUEST['id'];
$sql = $conn->prepare(
  "SELECT quantity FROM items WHERE id = ?"
);
$sql->execute(array($id));
$rows = $sql->fetch(PDO::FETCH_ASSOC);
$sql = $conn->prepare(
  "UPDATE items SET `quantity` = ? WHERE `id` = ?"
);
$new_qty = $rows['quantity'] + 1;
$sql->execute(array($new_qty, $id));
echo $new_qty;
CloseCon($conn);
