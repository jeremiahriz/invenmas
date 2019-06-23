<?php
include './config.php';
$conn = OpenCon();
$id = $_REQUEST['id'];
$sql = $conn->prepare(
  "SELECT * FROM users WHERE id = ?"
);
$sql->execute(array($id));
$rows = $sql->fetch(PDO::FETCH_ASSOC);
echo json_encode($rows);
CloseCon($conn);
