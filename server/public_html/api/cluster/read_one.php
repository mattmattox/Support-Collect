<?php
// required headers
header("Access-Control-Allow-Origin: http://support-collect.local/");
header("Content-Type: application/json; charset=UTF-8");

// include database and object files
include_once '../config/core.php';
include_once '../config/database.php';
include_once '../objects/cluster.php';
//include_once '../validate_customer_token.php';
include_once '../validate_admin_token.php';

// instantiate database and cluster object
$database = new Database();
$db = $database->getConnection();

// initialize object
$cluster = new Cluster($db);

// set ID property of record to read
$cluster->id = isset($_GET['id']) ? $_GET['id'] : die();

// read the details of cluster to be edited
$cluster->readOne();

if($cluster->name!=null){
	// create array
	$cluster_arr = array(
		"id" =>  $cluster->id,
		"environmentid" => $cluster->environmentid,
		"status" => $cluster->status,
		"type" => $cluster->type,
		"health" => $cluster->health
	);

	// set response code - 200 OK
	http_response_code(200);

	// make it json format
	echo json_encode($cluster_arr);
}

else{
	// set response code - 404 Not found
    http_response_code(404);

	// tell the cluster cluster does not exist
	echo json_encode(array("message" => "cluster does not exist."));
}
?>
