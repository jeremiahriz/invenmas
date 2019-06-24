<?php
include './config.php';
$conn = OpenCon();
$id = $_REQUEST['id'];
$sql = $conn->prepare(
  "SELECT * FROM users WHERE id = ?"
);
$sql->execute(array($id));
while ($rows = $sql->fetch(PDO::FETCH_ASSOC)) {
  $arr = array('firstname' => $rows['firstname'], 'lastname' => $rows['lastname'], 'role' => $rows['role'], 'email' => $rows['email']);
  echo json_encode($arr);
}
CloseCon($conn);
