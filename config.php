<?php
function OpenCon()
{
    $dbhost = "localhost";
    $dbuser = "root";
    $dbpass = "";
    $db = "invenmas";
    try {
        $conn = new PDO("mysql:host=$dbhost;dbname=$db;charset = utf8", $dbuser, $dbpass);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        echo "Connection failed: " . $e->getMessage();
    }
    return $conn;
}

function CloseCon($conn)
{
    $conn = null;
}

function getSKU()
{
    $conn = OpenCon();
    $rand1 = substr(str_shuffle(str_repeat("ABCDEFGHIJKLMNOPQRSTUVWXYZ", 5)), 0, 4);
    $rand2 = substr(str_shuffle(str_repeat("0123456789", 5)), 0, 4);
    $sql = $conn->prepare(
        "SELECT sku FROM items"
    );
    $random = $rand1 . $rand2;
    $sql->execute();
    while ($row = $sql->fetch()) {

        if ($row['sku'] == $random) {
            getSKU();
        }
    }
    return $random;
}
function formatDate()
{
    $date = date('d');
    $month = date('m');
    $year = date('Y');
    if ($date == 1 || $date == 21 || $date == 31) {
        $date .= 'st';
    } else if ($date == 2 || $date == 22) {
        $date .= 'nd';
    } else if ($date == 3 || $date == 23) {
        $date .= 'rd';
    } else {
        $date .= 'th';
    }

    switch ($month) {
        case 1:
            $month = 'Jan';
            break;

        case 2:
            $month = 'Feb';
            break;

        case 3:
            $month = 'Mar';
            break;

        case 4:
            $month = 'Apr';
            break;

        case 5:
            $month = 'May';
            break;

        case 6:
            $month = 'Jun';
            break;

        case 7:
            $month = 'Jul';
            break;

        case 8:
            $month = 'Aug';
            break;

        case 9:
            $month = 'Sept';
            break;

        case 10:
            $month = 'Oct';
            break;

        case 11:
            $month = 'Nov';
            break;

        case 12:
            $month = 'Dec';
            break;

        default:
            $month = 'Jan';
    }

    return $date . ' ' . $month . ', ' . $year;
}
