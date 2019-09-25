<?php
// required headers
header("Access-Control-Allow-Origin: http://support-collect.local/");
header("Content-Type: application/json; charset=UTF-8");

// include database and object files
include_once '../config/core.php';
include_once '../config/database.php';
include_once '../objects/node.php';
//include_once '../validate_customer_token.php';
include_once '../validate_admin_token.php';

// instantiate database and node object
$database = new Database();
$db = $database->getConnection();

// initialize object
$node = new Node($db);

// set ID property of record to read
$node->id = isset($_GET['id']) ? $_GET['id'] : die();

// read the details of node to be edited
$node->readOne();

if($node->name!=null){
	// create array
	$node_arr = array(
		"id" =>  $node->id,
		"environmentid" => $node->environmentid,
		"clusterid" => $node->clusterid,
		"name" => $node->name,
		"os_family" => $node->os_family,
		"os_version" => $node->os_version,
		"docker_version" => $node->docker_version,
		"role_etcd" => $node->role_etcd,
		"role_control" => $node->role_control,
		"role_worker" => $node->role_worker,
		"status" => $node->status,
		"health" => $node->health
	);

	// set response code - 200 OK
	http_response_code(200);

	// make it json format
	echo json_encode($node_arr);
}

else{
	// set response code - 404 Not found
    http_response_code(404);

	// tell the node node does not exist
	echo json_encode(array("message" => "node does not exist."));
}
?>
