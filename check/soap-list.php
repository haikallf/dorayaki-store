<?php 

try {
    $soapclient = new SoapClient('http://localhost:8081/ws/list?wsdl');
    $response = $soapclient->ListDorayakiPabrik();

    // pake list ini buat list di halaman request dorayaki
    $soaplist = json_decode(json_encode($response), true);
    var_dump($soaplist);

} catch (Exception $e) {
    echo $e->getMessage();
}

?>