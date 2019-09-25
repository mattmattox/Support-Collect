<?php
// required headers
header("Access-Control-Allow-Origin: http://support-collect.local/");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// files needed to connect to database
include_once '../config/core.php';
include_once '../config/database.php';
include_once '../objects/node.php';

// get database connection
$database = new Database();
$db = $database->getConnection();

// instantiate product object
$node = new Node($db);

// get posted data
$data = json_decode(file_get_contents("php://input"));

// set product property values
$node->environmentid = isset($data->environmentid) ? $data->environmentid : "";
$node->clusterid = isset($data->clusterid) ? $data->clusterid : "";
$node->name = isset($data->name) ? $data->name : "";
$node->os_family = isset($data->os_family) ? $data->os_family : "";
$node->os_version = isset($data->os_version) ? $data->os_version : "";
$node->docker_version = isset($data->docker_version) ? $data->docker_version : "";
$node->role_etcd = isset($data->role_etcd) ? $data->role_etcd : "";
$node->role_control = isset($data->role_control) ? $data->role_control : "";
$node->role_worker = isset($data->role_worker) ? $data->role_worker : "";

// create the node
if(
    $node->environmentid &&
    $node->clusterid &&
    $node->name &&
    $node->os_family &&
    $node->os_version &&
    $node->docker_version &&
    $node->role_etcd &&
    $node->role_control &&
    $node->role_worker &&
    $node->create()
){

    // set response code
    http_response_code(200);

    // display message: node was created
    echo json_encode(array("message" => "node was created."));
}

// message if unable to create node
else{

    // set response code - 400 bad request
    http_response_code(400);

    // display message: unable to create node
    echo json_encode(array("message" => "Unable to create node."));
}
?>
