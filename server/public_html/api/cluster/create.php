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
include_once '../objects/cluster.php';

// get database connection
$database = new Database();
$db = $database->getConnection();

// instantiate product object
$cluster = new Cluster($db);

// get posted data
$data = json_decode(file_get_contents("php://input"));

// set product property values
$cluster->environmentid = isset($data->environmentid) ? $data->environmentid : "";
$cluster->name = isset($data->name) ? $data->name : "";
$cluster->status = isset($data->status) ? $data->status : "";
$cluster->type = isset($data->type) ? $data->type : "";

// create the cluster
if(
    $cluster->environmentid &&
    $cluster->name &&
    $cluster->status &&
    $cluster->type &&
    $cluster->create()
){

    // set response code
    http_response_code(200);

    // display message: cluster was created
    echo json_encode(array("message" => "cluster was created."));
}

// message if unable to create cluster
else{

    // set response code - 400 bad request
    http_response_code(400);

    // display message: unable to create cluster
    echo json_encode(array("message" => "Unable to create cluster."));
}
?>
