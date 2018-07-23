<?php
require_once 'lib/nusoap.php';
//http://localhost/soap/server.php?wsdl : giam thi no cung cap
$wsdl = "http://localhost/soap/server.php?wsdl";

$client = new nusoap_client($wsdl, 'wsdl');
$err = $client->getError();
if($err){
    print_r($client->getError());
}
// goi server
$keyword = $_POST['key'] ?? '';
$keyword = strip_tags($keyword);
$result = $client->call('searchUsers', array('keyword' => $keyword));

require 'view/data_view.php';