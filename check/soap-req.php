<?php 

function SoapRequest($username, $idItem, $quantity) {


    try {
        $sock = socket_create(AF_INET, SOCK_DGRAM, SOL_UDP);
        socket_connect($sock, "8.8.8.8", 53);
        socket_getsockname($sock, $name);
        // local machine's external IP address
        $localAddr = strval($name);

    
        $soapclient = new SoapClient('http://localhost:8081/ws/req?wsdl');
        $param = new stdClass();
        $param->arg0 = $localAddr;
        $param->arg1 = $username;
        $param->arg2 = $idItem;
        $param->arg3 = $quantity;
        $response = $soapclient->RequestDorayakiPabrik($param);
    
        $soapreqdorayaki = json_decode(json_encode($response), true);
        $soapreqdorayaki = $soapreqdorayaki["return"];

        echo '<script language="javascript">';
        echo 'alert("'.$soapreqdorayaki.'")';
        echo '</script>';
    
    } catch (Exception $e) {
        echo $e->getMessage();
    }

}
?>