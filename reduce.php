<?php
include './config.php';
$conn = OpenCon();
$output = null;
$id = $_REQUEST['id'];
$sql = $conn->prepare(
  "SELECT quantity FROM items WHERE id = ?"
);
$sql->execute(array($id));
$rows = $sql->fetch(PDO::FETCH_ASSOC);
// foreach ($rows as $row) {
if ($rows['quantity'] > 0) {
  $sql = $conn->prepare(
    "UPDATE items SET `quantity` = ? WHERE `id` = ?"
  );
  $new_qty = $rows['quantity'] - 1;
  $sql->execute(array($new_qty, $id));
  $output = 'success';
} else {
  $output = 'Insufficient Quantity';
}
// }
echo $output;
CloseCon($conn);
