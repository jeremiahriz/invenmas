<?php
include './config.php';
if (isset($_POST['print'])) {
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=data.csv');
    $output = fopen("php://output", "w");
    fputcsv($output, array('Name', 'Quantity', 'Tag', 'Min Quantity', 'Max Quantity'));
    $conn = OpenCon();
    $sql = $conn->prepare(
        "SELECT * FROM items ORDER BY `id` DESC"
    );
    $sql->execute();
    while ($rows = $sql->fetch()) {
        fputcsv($output, array($rows['name'], $rows['quantity'], $rows['tag'], $rows['min_quantity'], $rows['max_quantity']));
    }
    CloseCon($conn);
    fclose($output);
}
