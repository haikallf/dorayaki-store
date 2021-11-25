<?php 

try {
    $sock = socket_create(AF_INET, SOCK_DGRAM, SOL_UDP);
    socket_connect($sock, "8.8.8.8", 53);
    socket_getsockname($sock, $name);
    // local machine's external IP address
    $localAddr = strval($name);
    var_dump($localAddr);

    $soapclient = new SoapClient('http://localhost:8081/ws/list?wsdl');
    $param = new stdClass();
    $param->arg0 = $localAddr;
    $response = $soapclient->ListDorayakiPabrik($param);

    // pake list ini buat list di halaman request dorayaki
    $soaplist = json_decode(json_encode($response), true);
    var_dump($soaplist);
    

} catch (Exception $e) {
    echo $e->getMessage();
}

?>