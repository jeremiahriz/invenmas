<?php
include './config.php';
$conn = OpenCon();
$query = $_GET['query'];

$query = htmlspecialchars($query);

if (empty($query)) {

  $sql = $conn->prepare("SELECT * FROM items");
} else {

  $sql = $conn->prepare("SELECT * FROM items
      WHERE (`sku` LIKE '%" . $query . "%') OR (`name` LIKE '%" . $query . "%')");
}

$sql->execute();
$rows = $sql->fetchAll();
$count = 0;
foreach ($rows as $row) {
  $id = $row['id'];
  echo '<tr class="table__card--tr">';
  echo '<td class="table__card--td">' . $row['sku'] . '</td>';
  echo '<td class="table__card--td">' . $row['name'] . '</td>';
  echo '<td class="table__card--td table__td--qty">' . $row['quantity'] . '</td>';
  echo '<td class="table__card--td">' . $row['tag'] . '</td>';
  echo '<td class="table__card--td">
          <button class="btn btn__accent show--reduce" onClick="reduce(' . $id . ', ' . $count . ')">Reduce</button>
          <button class="btn btn__primary" onClick="add(' . $id . ', ' . $count . ')">Add</button>
          <div class="dropdown">
            <a class="dropdown-toggle" data-toggle="dropdown">
              <img src="./images/menu.svg" alt="Menu">
            </a>
            <div class="dropdown-menu dropdown-menu-right">
              <a class="dropdown-item" href="#" onclick="setItem(' . $id . ')" data-toggle="modal" data-target="#editItemModal">Edit Item</a>
            </div>
          </div>
        </td>';
  echo '</tr>';
  $count++;
}
CloseCon($conn);
