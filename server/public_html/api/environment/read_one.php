<?php
// required headers
header("Access-Control-Allow-Origin: http://support-collect.local/");
header("Content-Type: application/json; charset=UTF-8");

// include database and object files
include_once '../config/core.php';
include_once '../config/database.php';
include_once '../objects/environment.php';
//include_once '../validate_customer_token.php';
include_once '../validate_admin_token.php';

// instantiate database and environment object
$database = new Database();
$db = $database->getConnection();

// initialize object
$environment = new Environment($db);

// set ID property of record to read
$environment->id = isset($_GET['id']) ? $_GET['id'] : die();

// read the details of environment to be edited
$environment->readOne();

if($environment->name!=null){
	// create array
	$environment_arr = array(
		"id" =>  $environment->id,
		"name" => $environment->name,
		"endpoint" => $environment->endpoint,
		"accesskey" => $environment->accesskey,
		"secretkey" => $environment->secretkey
	);

	// set response code - 200 OK
	http_response_code(200);

	// make it json format
	echo json_encode($environment_arr);
}

else{
	// set response code - 404 Not found
    http_response_code(404);

	// tell the environment environment does not exist
	echo json_encode(array("message" => "environment does not exist."));
}
?>
