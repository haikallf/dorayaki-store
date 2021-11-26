<?php 
if(!isset($_SESSION)) 
    { 
        session_start(); 
    } 

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
        print_r($soapcheckdorayaki);
        return $soapcheckdorayaki;

    } catch (Exception $e) {
        echo $e->getMessage();
        return [];
    }
}

    
function updateStok($idItem, $nama, $stok) {
    $path = "../db/database.db";
    $db = new SQLite3($path);
    $query1 = $db->query("SELECT * FROM item WHERE idItem = '$idItem';");

    $fetch1 = array();
    while ($row = $query1->fetchArray(SQLITE3_ASSOC)) {
        array_push($fetch1, $row);
    }

    if (count($fetch1) == 0) {
        $query2 = $db->query("INSERT INTO item (idItem, namaItem, deskripsi, harga, stok, gambar, available) VALUES ('$idItem', '$nama', '-', 0, '$stok', '-', 0);");
    }
    else {
        $query3 = $db->query("UPDATE item SET stok = stok + '$stok' WHERE idItem = '$idItem';");
    }
    
    $db->close();
    unset($db);
    unset($fetch1);
    }


function stokDariPabrik($arr) {
    // array(0) { }
    // array(2) { [0]=> array(1) { [1]=> string(2) "15" } [1]=> array(1) { [7]=> string(1) "2" } }
    if (!isset($arr) || empty($arr) || $arr == null || count($arr) == 0){
        return;
    }
    else {
        echo "ID ITEMM";
        for ($i = 0; $i < count($arr); $i++){
            updateStok(intval($arr[$i]["idItem"]), $arr[$i]["nama"], intval($arr[$i]["quantity"]) );
        }
    }
    
}
?>