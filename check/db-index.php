<?php 
$db = "./db/database.db";
function loadAllItem() {
    // udah di sort
    $db = new SQLite3($GLOBALS['db']);
    $query = $db->query("SELECT * FROM item a NATURAL JOIN (SELECT idItem FROM item_quantity GROUP BY idItem ORDER BY SUM(quantity) DESC) b;");
    $data = array();
    
    // yang udah pernah dibeli
    while ($row = $query->fetchArray(SQLITE3_ASSOC)) {
        array_push($data, $row);
    }

    // yang belum pernah dibeli
    $query2 = $db->query("SELECT * FROM item a WHERE idItem NOT IN (SELECT idItem FROM item_quantity);");
    while ($row = $query2->fetchArray(SQLITE3_ASSOC)) {
        array_push($data, $row);
    }

    $db->close();

    return $data;
}

function loadAllItemPagination($start, $perPage) {
    // udah di sort
    $db = new SQLite3($GLOBALS['db']);
    $query = $db->query("SELECT * FROM item a NATURAL JOIN (SELECT idItem FROM item_quantity GROUP BY idItem ORDER BY SUM(quantity) DESC) b;");
    $data = array();
    
    // yang udah pernah dibeli
    while ($row = $query->fetchArray(SQLITE3_ASSOC)) {
        array_push($data, $row);
    }

    // yang belum pernah dibeli
    $query2 = $db->query("SELECT * FROM item a WHERE idItem NOT IN (SELECT idItem FROM item_quantity);");
    while ($row = $query2->fetchArray(SQLITE3_ASSOC)) {
        array_push($data, $row);
    }

    $paginationData = array();
    for ($i = $start; $i < $start + $perPage; $i++){
        if($i < count($data)){
            array_push($paginationData, $data[$i]);
        }
        
    }

    $db->close();

    return $paginationData;
}


function loadAllAvailableItem() {
    // udah di sort
    $db = new SQLite3($GLOBALS['db']);
    $query = $db->query("SELECT * FROM item a NATURAL JOIN (SELECT idItem FROM item_quantity GROUP BY idItem ORDER BY SUM(quantity) DESC) b WHERE available = 1;");
    $data = array();
    
    // yang udah dibeli
    while ($row = $query->fetchArray(SQLITE3_ASSOC)) {
        array_push($data, $row);
    }

    // yang belum pernah dibeli
    $query2 = $db->query("SELECT * FROM item a WHERE available = 1 AND idItem NOT IN (SELECT idItem FROM item_quantity);");
    while ($row = $query2->fetchArray(SQLITE3_ASSOC)) {
        array_push($data, $row);
    }

    $db->close();
    return $data;
}

function loadAllAvailableItemPagination($start, $perPage) {
    // udah di sort
    $db = new SQLite3($GLOBALS['db']);
    $query = $db->query("SELECT * FROM item a NATURAL JOIN (SELECT idItem FROM item_quantity GROUP BY idItem ORDER BY SUM(quantity) DESC) b WHERE available = 1;");
    $data = array();
    
    // yang udah dibeli
    while ($row = $query->fetchArray(SQLITE3_ASSOC)) {
        array_push($data, $row);
    }

    // yang belum pernah dibeli
    $query2 = $db->query("SELECT * FROM item a WHERE available = 1 AND idItem NOT IN (SELECT idItem FROM item_quantity);");
    while ($row = $query2->fetchArray(SQLITE3_ASSOC)) {
        array_push($data, $row);
    }

    $paginationData = array();
    for ($i = $start; $i < $start + $perPage; $i++){
        if($i < count($data)){
            array_push($paginationData, $data[$i]);
        }
        
    }

    $db->close();
    return $paginationData;
}

// function loadAllItem() {
//     $db = new SQLite3($GLOBALS['db']);
//     $query = $db->query("SELECT * FROM item;");
//     $data = array();
    
//     while ($row = $query->fetchArray(SQLITE3_ASSOC)) {
//         array_push($data, $row);
//     }
//     $db->close();
//     return $data;
// }

// function loadAllAvailableItem() {
//     $db = new SQLite3($GLOBALS['db']);
//     $query = $db->query("SELECT * FROM item WHERE available = 1;");
//     $data = array();
    
//     while ($row = $query->fetchArray(SQLITE3_ASSOC)) {
//         array_push($data, $row);
//     }
//     $db->close();
//     return $data;
// }

function syncStockAndQuantity() {
    $db = new SQLite3($GLOBALS['db']);
    $query = $db->query("UPDATE item SET available = 0 WHERE stok = 0;");
}

function cekhashedToken($token) {
    $db = new SQLite3($GLOBALS['db']);
    $query = $db->query("SELECT * FROM user");
    while ($row = $query->fetchArray(SQLITE3_ASSOC)) {
        if (hash('sha256',$row['username']) == $token) {
            return $row;
        }
    }
    return 0;
}


// $itemArray = loadAllItem();
?>
