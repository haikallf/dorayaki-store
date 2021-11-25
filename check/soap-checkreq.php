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
}
?>