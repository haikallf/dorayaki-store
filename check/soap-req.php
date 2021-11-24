<?php 

try {
    $soapclient = new SoapClient('http://localhost:8081/ws/req?wsdl');
    $response = $soapclient->RequestDorayakiPabrik();
    
    // ini pesan konfirmasi request berhasil atau tidak
    $soapreq = json_decode(json_encode($response), true);
    var_dump($soapreq);
    
} catch (Exception $e) {
    echo $e->getMessage();
}

?>