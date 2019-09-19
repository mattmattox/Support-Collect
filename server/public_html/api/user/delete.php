<?php
// required headers
header("Access-Control-Allow-Origin: http://support-collect.local/");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// include database and object file
include_once '../config/core.php';
include_once '../config/database.php';
include_once '../objects/user.php';
include_once '../validate_admin_token.php';

// get database connection
$database = new Database();
$db = $database->getConnection();

// prepare user object
$user = new User($db);

// get user id
$data = json_decode(file_get_contents("php://input"));

// set user id to be deleted
$user->id = $data->id;

// delete the user
if($user->delete()){

	// set response code - 200 ok
	http_response_code(200);

	// tell the user
	echo json_encode(array("message" => "User was deleted."));
}

// if unable to delete the user
else{

	// set response code - 503 service unavailable
	http_response_code(503);

	// tell the user
	echo json_encode(array("message" => "Unable to delete user."));
}
?>
