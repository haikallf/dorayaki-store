<?php 
$db = "../db/database.db";

// admin
function historyOneItem($idItem) {
    $db = new SQLite3($GLOBALS['db']);
    $query = $db->query("SELECT * FROM riwayat WHERE idItem = '$idItem' ORDER BY tanggal DESC;");
    $data = array();
    
    while ($row = $query->fetchArray(SQLITE3_ASSOC)) {
        array_push($data, $row);
    }
    $db->close();
    return $data;
}

function historyByUser($username) {
    $db = new SQLite3($GLOBALS['db']);
    $query = $db->query("SELECT * FROM riwayat WHERE username = '$username' ORDER BY tanggal DESC;");
    $data = array();
    
    while ($row = $query->fetchArray(SQLITE3_ASSOC)) {
        array_push($data, $row);
    }
    $db->close();
    return $data;
}

function findItemName($idItem) {
    $db = new SQLite3($GLOBALS['db']);
    $query = $db->query("SELECT namaItem FROM item WHERE idItem = '$idItem';")->fetchArray(SQLITE3_ASSOC)["namaItem"];
    return $query;
}

function findItemImg($idItem) {
    $db = new SQLite3($GLOBALS['db']);
    $query = $db->query("SELECT gambar FROM item WHERE idItem = '$idItem';")->fetchArray(SQLITE3_ASSOC)["gambar"];
    return $query;
}
?>