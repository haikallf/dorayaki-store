<?php 
$db = "../db/database.db";
$db2 = "./db/database.db";
function addItem($idItem, $namaItem, $deskripsi,int $harga,int $stok, $gambar,int $available) {
    $db = new SQLite3($GLOBALS['db']);
    $query = $db->query("INSERT INTO item(idItem, namaItem, deskripsi, harga, stok, gambar, available) VALUES ('$idItem', '$namaItem', '$deskripsi', '$harga', '$stok', '$gambar', '$available');");
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

function filterAllItemByName($nama) {
    $db = new SQLite3($GLOBALS['db']);
    $query = $db->query("SELECT * FROM item WHERE namaItem LIKE '%$nama%';");
    $data = array();
    
    while ($row = $query->fetchArray(SQLITE3_ASSOC)) {
        array_push($data, $row);
    }
    $db->close();
    unset($db);
    return $data;
}
function filterAvailableItemByName($nama) {
    $db = new SQLite3($GLOBALS['db']);
    $query = $db->query("SELECT * FROM item WHERE available = 1 AND namaItem LIKE '%$nama%';");
    $data = array();
    
    while ($row = $query->fetchArray(SQLITE3_ASSOC)) {
        array_push($data, $row);
    }
    $db->close();
    unset($db);
    return $data;
}

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

function editItem($username, $idItem, $columnName, $newValue) {
    $db = new SQLite3($GLOBALS['db']);

    // khusus stok
    if ($columnName == 'stok') {
        // ambil stok saat ini
        $current = $db->query("SELECT stok from item WHERE idItem = '$idItem'")->fetchArray(SQLITE3_ASSOC);
        $cur = $current['stok'];
        $selisih = $newValue - $cur;
        $tanggal = date("Y-m-d h:i:sa", strtotime("now"));
        insertToRiwayat($username, $idItem, $tanggal, $selisih);
    }
    $query = $db->query("UPDATE item SET '$columnName' = '$newValue' WHERE idItem = '$idItem';");
    
    $db->close();
    unset($db);
}

function deleteItem($idItem) {
    $db = new SQLite3($GLOBALS['db']);
    $query = $db->query("UPDATE item SET available = 0 WHERE idItem = '$idItem';");
    $db->close();
    unset($db);
    echo "<script>alert('Dorayaki berhasil dihapus');</script>";
    echo "<script>location.href='../index.php'</script>";
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

function buyItemFromCart($username, $tanggal) {
    $cartItem = getCartItem($username);
    $db = new SQLite3($GLOBALS['db']);

    $idPembelian = $db->query("SELECT COUNT(idPembelian) FROM pembelian;")->fetchArray(SQLITE3_ASSOC)["COUNT(idPembelian)"];
    $idPembelian += 1;
    $query2 = $db->query("INSERT INTO pembelian (idPembelian, username, tanggal) VALUES ('$idPembelian', '$username', '$tanggal');");
    
    $exceed = 0;
    for($i = 0; $i < count($cartItem); $i++) {
        $quantity = $cartItem[$i]['quantity'];
        $idItem = $cartItem[$i]['idItem'];
        $numStock = $db->query("SELECT stok FROM item WHERE idItem = '$idItem';")->fetchArray(SQLITE3_ASSOC)["stok"];
        if ($numStock < $quantity) {
            $exceed = 1;
            break;
        }
    }

    if ($exceed == 0 ) {
        for($i = 0; $i < count($cartItem); $i++){
            $item = findItemByID($cartItem[$i]["idItem"]);
            $quantity = $cartItem[$i]['quantity'];
            $idItem = $cartItem[$i]['idItem'];
            $query = $db->query("UPDATE item SET stok = stok - '$quantity' WHERE idItem = '$idItem';");
            $query2 = $db->query("INSERT INTO item_quantity (idPembelian, idItem, quantity) VALUES ('$idPembelian', '$idItem', '$quantity');");
            $query3 = $db->query("DELETE FROM cart WHERE username = '$username';");
            insertToRiwayat($username,$idItem,$tanggal,(-1*$quantity));
        }
    } else {
        echo "<script>alert('quantity input melebihi stock tersedia')</script>";
    }

    $db->close();
    unset($db);
}

function buyItem($username, $tanggal, $idItem, $quantity) {
    $db = new SQLite3($GLOBALS['db']);
    $numStock = $db->query("SELECT stok FROM item WHERE idItem = '$idItem';")->fetchArray(SQLITE3_ASSOC)["stok"];
    if ($numStock >= $quantity){
        $idPembelian = $db->query("SELECT COUNT(idPembelian) FROM pembelian;")->fetchArray(SQLITE3_ASSOC)["COUNT(idPembelian)"];
        $idPembelian += 1;
        $query2 = $db->query("INSERT INTO pembelian (idPembelian, username, tanggal) VALUES ('$idPembelian', '$username', '$tanggal');");
        $item = findItemByID($idItem);
        $idItem = $item[0]["idItem"];
        $query = $db->query("UPDATE item SET stok = stok - '$quantity' WHERE idItem = '$idItem';");
        $query2 = $db->query("INSERT INTO item_quantity (idPembelian, idItem, quantity) VALUES ('$idPembelian', '$idItem', '$quantity');");
        insertToRiwayat($username,$idItem,$tanggal,(-1*$quantity));
    } else {
        echo "<script>alert('quantity dari barang yang dibeli melebihi jumlah stok yang tersedia')</script>";
    }

    $db->close();
    unset($db);
}

function delFromCart($username, $idItem){
    $db = new SQLite3($GLOBALS['db']);
    $query3 = $db->query("DELETE FROM cart WHERE username = '$username' AND idItem = '$idItem';");
    // $db->close();
    // unset($db);
}

function addNewVar($name, $deskripsi, $harga, $stock,  $img_loc) {
    $db = new SQLite3($GLOBALS['db']);
    $idItem = $db->query("SELECT COUNT(idItem) FROM item;")->fetchArray(SQLITE3_ASSOC)["COUNT(idItem)"];
    $idItem += 1;
    $query = $db->query("INSERT INTO item (idItem, namaItem, deskripsi, harga, stok, gambar) VALUES ('$idItem', '$name', '$deskripsi', '$harga', '$stock',  '$img_loc');");

    $db->close();
    unset($db);
}

function syncStockAndQuantity() {
    $db = new SQLite3($GLOBALS['db']);
    $query = $db->query("UPDATE item SET available = 0 WHERE stok = 0;");
}

function countSoldItem($id) {
    $db = new SQLite3($GLOBALS['db']);
    $res = $db->query("SELECT SUM(quantity) FROM item_quantity WHERE idItem = '$id';")->fetchArray(SQLITE3_ASSOC)["SUM(quantity)"];
    $db->close();
    unset($db);
    return $res;
}

function insertToRiwayat($username, $idItem, $tanggal, $quantity) {
    $db = new SQLite3($GLOBALS['db']);
    $query = $db->query("INSERT INTO riwayat(username, idItem, tanggal, quantity) VALUES ('$username', '$idItem', '$tanggal', '$quantity');");
}

function setAvailable($idItem) {
    $db = new SQLite3($GLOBALS['db']);
    $query = $db->query("UPDATE item SET available = 1 WHERE idItem = '$idItem';");
    // $query = $db->query("UPDATE item SET stok = 1 WHERE idItem = '$idItem' AND stok = 0;");
    $db->close();
    unset($db);
}
// $username = "tes"; $idItem = 1; $tanggal = '12122112'; $quantity = 10;
// insertToRiwayat($username, $idItem, $tanggal, (-1*$quantity));
?>
