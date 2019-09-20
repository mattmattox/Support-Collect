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
include_once '../objects/environment.php';

// get database connection
$database = new Database();
$db = $database->getConnection();

// instantiate product object
$environment = new Environment($db);

// get posted data
$data = json_decode(file_get_contents("php://input"));

// set product property values
$environment->firstname = isset($data->firstname) ? $data->firstname : "";
$environment->lastname = isset($data->lastname) ? $data->lastname : "";
$environment->email = isset($data->email) ? $data->email : "";
$environment->password = isset($data->password) ? $data->password : "";
$environment->access_level = "Customer";

// create the Environment
if(
    $environment->firstname &&
    $environment->lastname &&
    $environment->email &&
    $environment->password &&
    $environment->create()
){

    // set response code
    http_response_code(200);

    // display message: Environment was created
    echo json_encode(array("message" => "Environment was created."));
}

// message if unable to create Environment
else{

    // set response code - 400 bad request
    http_response_code(400);

    // display message: unable to create Environment
    echo json_encode(array("message" => "Unable to create Environment."));
}
?>
