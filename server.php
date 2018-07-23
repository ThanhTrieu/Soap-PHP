<?php
// dung server soap
// luc di thi - cac ban ko can phai lam cai nay - nguoi ta cung cap dia chi link server
require_once 'lib/nusoap.php';
require_once 'database/database.php';
// khoi tao server
$server = new nusoap_server();
// tao namespace - ten service cua ban
$namespace = "http://localhost/soap/server.php?wsdl";
// cau hinh cho server cua ban thong qua doi tuong WSDL cua soap
$server->configureWSDL('server',$namespace);
// sau nay : 'server' dai dien cho link server
// cau hinh namespace len phia server
//  khơi chay
$server->wsdl->schemaTargetNamespace = $namespace;
// muon server tra ve du lieu nhu the nao (can tra ve nhung truong nao va nhung truong do co kieu du lieu ra sao).
$server->wsdl->addComplexType(
    'Person', // name of ComplexType
    'complexType', // khai bao : su dung complextpye
    'struct',
    'all',
    '',
    array(
        'id' => array('name'=>'id','type'=>'xsd:id'),
        'username' => array('name'=>'username', 'type' => 'xsd:string'),
        'email' => array('name' => 'email', 'type' => 'xsd:string')
    )
);

$server->wsdl->addComplexType(
    'Login', // name of ComplexType
    'complexType', // khai bao : su dung complextpye
    'struct',
    'all',
    '',
    array(
        'id' => array('name'=>'id','type'=>'xsd:id'),
        'username' => array('name'=>'username', 'type' => 'xsd:string'),
        'email' => array('name' => 'email', 'type' => 'xsd:string'),
        'password' => array('name' => 'password', 'type' => 'xsd:string')
    )
);

// sau do toi can dang ky de su dung cai complextype do
$server->register(
    'getData', // name function handle data
    array(),
    array('return' => 'tns:Person'),
    'urn:server',
    'urn:server#getData',
    'rpc',
    true,
    'encoded',
    'list data users'
);

$server->register(
    'searchUsers',
    array(
        'keyword' => 'xsd:string'
    ),
    array(
        'return' => 'tns:Person'
    ),
    'urn:server',
    'urn:server#searchUsers',
    'rpc',
    true,
    'encoded',
    'search users'
);

$server->register(
    'checkLogin',
    array(
        'user' => 'xsd:string',
        'pass' => 'xsd:string'
    ),
    array('return' => 'tns:Login'),
    'urn:server',
    'urn:server#checkLogin',
    'rpc',
    true,
    'encoded',
    'login user'
);

function getdata(){
    return [
        'id' => 1,
        'username' => 'abc',
        'email' => 'abc@gmail.com',
        'password' => '12345',
        'phone' => '09876543'
    ];
}

function checkLogin($user, $pass)
{
    $data = [];
    $conn = connection();
    $sql = "SELECT * FROM admins AS a WHERE a.username = :username AND a.password = :password LIMIT 1";
    $stmt = $conn->prepare($sql);
    if($stmt){
        $stmt->bindParam(':username',$user, PDO::PARAM_STR);
        $stmt->bindParam(':password',$pass, PDO::PARAM_STR);
        if($stmt->execute()){
            if($stmt->rowCount() > 0){
                $data = $stmt->fetch(PDO::FETCH_ASSOC);
            }
        }
        $stmt->closeCursor();
    }
    disconnection($conn);
    return $data;
}

function searchUsers($keyword){
    $data = [];
    $conn = connection();
    $key = "%".$keyword."%";
    $sql = "SELECT * FROM admins AS a WHERE a.username LIKE :key LIMIT 1";
    $stmt = $conn->prepare($sql);
    if($stmt){
        $stmt->bindParam(':key',$key, PDO::PARAM_STR);
        if($stmt->execute()){
            if($stmt->rowCount() > 0){
                $data = $stmt->fetch(PDO::FETCH_ASSOC);
            }
        }
        $stmt->closeCursor();
    }
    disconnection($conn);
    return $data;
}

// dang ky server de no chay - ben phia client se bat dc du lieu
$HTTP_RAW_POST_DATA = isset($HTTP_RAW_POST_DATA) ? $HTTP_RAW_POST_DATA : file_get_contents('php://input');

$server->service($HTTP_RAW_POST_DATA);


