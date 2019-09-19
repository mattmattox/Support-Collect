<?php
// required headers
header("Access-Control-Allow-Origin: http://support-collect.local/");
header("Content-Type: application/json; charset=UTF-8");

// include database and object files
include_once '../config/core.php';
include_once '../config/database.php';
include_once '../objects/user.php';
include_once '../validate_admin_token.php';

// instantiate database and user object
$database = new Database();
$db = $database->getConnection();

// initialize object
$user = new User($db);

// set ID property of record to read
$user->id = isset($_GET['id']) ? $_GET['id'] : die();

// read the details of user to be edited
$user->readOne();

if($user->firstname!=null){
	// create array
	$user_arr = array(
		"id" =>  $user->id,
		"firstname" => $user->firstname,
		"lastname" => $user->lastname,
		"email" => $user->email,
		"access_level" => $user->access_level
	);

	// set response code - 200 OK
	http_response_code(200);

	// make it json format
	echo json_encode($user_arr);
}

else{
	// set response code - 404 Not found
    http_response_code(404);

	// tell the user user does not exist
	echo json_encode(array("message" => "User does not exist."));
}
?>
