<?php 
require_once("database.php");
// setiap dipanggil, load ulang database pada id itu
$id = $_REQUEST["id"];
$all = findItemByID($id);
$sold = countSoldItem($id);
// hitung jumlah terjual


echo json_encode(
    array(
        "all" => $all,
        "sold" => $sold
    )
);
?>