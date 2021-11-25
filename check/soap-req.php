<?php 
session_start();
if (!isset($_SESSION['username'])) {

    header("Location: ../pages/login.php");
    exit;
    return;

}

try {
    $sock = socket_create(AF_INET, SOCK_DGRAM, SOL_UDP);
    socket_connect($sock, "8.8.8.8", 53);
    socket_getsockname($sock, $name);
    // local machine's external IP address
    $localAddr = strval($name);

    $id = 1;
    $qty = 2;

    $soapclient = new SoapClient('http://localhost:8081/ws/req?wsdl');
    $param = new stdClass();
    $param->arg0 = $localAddr;
    $param->arg1 = $_SESSION['username'];
    $param->arg2 = $id;
    $param->arg3 = $qty;
    $response = $soapclient->RequestDorayakiPabrik($param);

    $soapreqdorayaki = json_decode(json_encode($response), true);
    var_dump($soapreqdorayaki);
    var_dump($soapreqdorayaki["return"]);
   

} catch (Exception $e) {
    echo $e->getMessage();
}

?>