<?php 
session_start();

function SoapCheckRequest() {
    try {
        $sock = socket_create(AF_INET, SOCK_DGRAM, SOL_UDP);
        socket_connect($sock, "8.8.8.8", 53);
        socket_getsockname($sock, $name);
        // local machine's external IP address
        $localAddr = strval($name);
        $soapclient = new SoapClient('http://localhost:8081/ws/checkreq?wsdl');
        $param = new stdClass();
        $param->arg0 = $_SESSION['username'];
        $response = $soapclient->CheckRequest($param);
    
        // pake list ini buat list di halaman request dorayaki
        $soapcheckdorayaki = json_decode(json_encode($response), true);
        $soapcheckdorayaki = json_decode($soapcheckdorayaki["return"], true);
    
        return $soapcheckdorayaki;

    } catch (Exception $e) {
        echo $e->getMessage();
        return [];
    }


function updateStok($idItem) {
    $db = new SQLite3($GLOBALS['db']);
    $query1 = $db->query("SELECT * FROM item WHERE idItem = $idItem;");

    $fetch1 = array();
    while ($row = $query1->fetchArray(SQLITE3_ASSOC)) {
        array_push($fetch1, $row);
    }

    if (count($fetch1) == 0) {
        $query2 = $db->query("INSERT INTO item (idItem, quantity) VALUES ('$idItem', '$quantity');");
    }
    else {
        $query3 = $db->query("UPDATE item SET quantity = quantity + '$quantity' WHERE idItem = '$idItem';");
    }
    
    $db->close();
    unset($db);
    unset($fetch1);
    }
}
function stokDariPabrik($arr) {
    // [{1,3},{2,4}]
    // tambah stok kalo id udah ada
    // varian baru kalau id belom ada
    
    
}
?>