<?php

$db = "./db/database2.db";

function findItemByID($id) {
    $db = new SQLite3($GLOBALS['db']);
    $query = $db->query("SELECT * FROM item WHERE idItem = '$id';");
    $data = array();
    
    while ($row = $query->fetchArray(SQLITE3_ASSOC)) {
        array_push($data, $row);
    }
    $db->close();
    unset($db);
    return $data;
}

function addToCart($username, $idItem, $quantity) {
    $db = new SQLite3($GLOBALS['db']);
    $query1 = $db->query("SELECT * FROM cart WHERE idItem = '$idItem';");

    $fetch1 = array();
    while ($row = $query1->fetchArray(SQLITE3_ASSOC)) {
        array_push($fetch1, $row);
    }

    if (count($fetch1) == 0) {
        $query2 = $db->query("INSERT INTO cart (username, idItem, quantity) VALUES ('$username', '$idItem', '$quantity');");
    }
    else {
        $query3 = $db->query("UPDATE cart SET quantity = quantity + '$quantity' WHERE idItem = '$idItem';");
    }
    
    $db->close();
    unset($db);
    unset($fetch1);
}

function setQuantityCart($username, $idItem, $quantity){
    $db = new SQLite3($GLOBALS['db']);
    $query = $db->query("UPDATE cart SET quantity = '$quantity' WHERE idItem = '$idItem' AND username = '$username';");
    $db->close();
    unset($db);
}

function getCartItem($username) {
    $db = new SQLite3($GLOBALS['db']);
    $query = $db->query("SELECT * FROM cart WHERE username = '$username';");

    $cartItem = array();
    while ($row = $query->fetchArray(SQLITE3_ASSOC)) {
        array_push($cartItem, $row);
    }

    $db->close();
    unset($db);
    return $cartItem;
}

function cartItemSubtotal($username) {
    $cartItem = getCartItem($username);

    $subtotal = 0;
    $totalItem = 0;

    for($i = 0; $i < count($cartItem); $i++){
        $item = findItemByID($cartItem[$i]["idItem"]);
        $subtotal += $cartItem[$i]["quantity"] * $item[0]["harga"];
        $totalItem += $cartItem[$i]["quantity"];
    }

    unset($cartItem);
    $subtotalArray["subtotal"] = $subtotal;
    $subtotalArray["totalItem"] = $totalItem;
    return $subtotalArray;
}

function buyItemFomCart($username, $tanggal) {
    $cartItem = getCartItem($username);
    $db = new SQLite3($GLOBALS['db']);

    $query0 = $db->query("SELECT * FROM pembelian;");

    $dummy = array();
    while ($row = $query0->fetchArray(SQLITE3_ASSOC)) {
        array_push($dummy, $row);
    }

    $idPembelian = count($dummy);
    $idPembelian += 1;
    var_dump("idd", $idPembelian);
    $query2 = $db->query("INSERT INTO pembelian (idPembelian, username, tanggal) VALUES ('$idPembelian', '$username', '$tanggal');");

    for($i = 0; $i < count($cartItem); $i++){
        $qty = $cartItem[$i]['quantity'];
        $id = $cartItem[$i]['idItem'];
        // $item = findItemByID($cartItem[$i]["idItem"]);
        // var_dump($item[0]);
        $query = $db->query("UPDATE item SET stok -= 1 WHERE idItem = '1';");
        $query3 = $db->query("INSERT INTO item_quantity (idPembelian, idItem, quantity) VALUES ('$idPembelian', '$cartItem[$i]['idItem']', '$cartItem[$i]['quantity']');");
    }

    $db->close();
    unset($db);
}

function editItem($idItem, $columnName, $newValue) {
    $db = new SQLite3($GLOBALS['db']);
    $query = $db->query("UPDATE item SET '$columnName' = '$newValue' WHERE idItem = '$idItem';");
    $db->close();
    unset($db);
}

function loadAllItem() {
    $db = new SQLite3($GLOBALS['db']);
    $query = $db->query("SELECT * FROM item;");
    $data = array();
    
    while ($row = $query->fetchArray(SQLITE3_ASSOC)) {
        array_push($data, $row);
    }
    $db->close();
    return $data;
}

// $ada = loadAllItem();

// var_dump($ada[0]["namaItem"]);

// buyItemFomCart("haikallf", "sad");

// $cartItem = getCartItem("haikallf");
// var_dump($cartItem[0]['quantity']);

// $cartItem = getCartItem("haikalf");
// $db = new SQLite3($GLOBALS['db']);

// $query0 = $db->query("SELECT * FROM pembelian;");

// $dummy = array();
// while ($row = $query0->fetchArray(SQLITE3_ASSOC)) {
//     array_push($dummy, $row);
// }

// var_dump(count($dummy));