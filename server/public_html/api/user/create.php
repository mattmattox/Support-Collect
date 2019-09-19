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
include_once '../objects/user.php';

// get database connection
$database = new Database();
$db = $database->getConnection();

// instantiate product object
$user = new User($db);

// get posted data
$data = json_decode(file_get_contents("php://input"));

// set product property values
$user->firstname = isset($data->firstname) ? $data->firstname : "";
$user->lastname = isset($data->lastname) ? $data->lastname : "";
$user->email = isset($data->email) ? $data->email : "";
$user->password = isset($data->password) ? $data->password : "";
$user->access_level = "Customer";

// create the user
if(
    $user->firstname &&
    $user->lastname &&
    $user->email &&
    $user->password &&
    $user->create()
){

    // set response code
    http_response_code(200);

    // display message: user was created
    echo json_encode(array("message" => "User was created."));
}

// message if unable to create user
else{

    // set response code - 400 bad request
    http_response_code(400);

    // display message: unable to create user
    echo json_encode(array("message" => "Unable to create user."));
}
?>
