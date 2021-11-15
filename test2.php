<?php 
$db = "./db/database.db";
function loadAllItem() {
    // udah di sort
    $db = new SQLite3($GLOBALS['db']);
    $query = $db->query("SELECT * FROM item a NATURAL JOIN (SELECT idItem FROM item_quantity GROUP BY idItem ORDER BY SUM(quantity) DESC) b;");
    $data = array();
    
    while ($row = $query->fetchArray(SQLITE3_ASSOC)) {
        array_push($data, $row);
    }
    $db->close();
    var_dump($data);

    return $data;
}

function loadAllAvailableItem() {
    // udah di sort
    $db = new SQLite3($GLOBALS['db']);
    $query = $db->query("SELECT * FROM item a NATURAL JOIN (SELECT idItem FROM item_quantity GROUP BY idItem ORDER BY SUM(quantity) DESC) b WHERE available = 1;");
    $data = array();
    
    while ($row = $query->fetchArray(SQLITE3_ASSOC)) {
        array_push($data, $row);
    }
    $db->close();
    var_dump($data);

    return $data;
}

json_encode(loadAllAvailableItem());
?>
