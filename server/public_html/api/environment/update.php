<?php
// required headers
header("Access-Control-Allow-Origin: http://support-collect.local/");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// required to encode json web token
include_once '../config/core.php';

// files needed to connect to database
include_once '../config/database.php';
include_once '../objects/environment.php';
include_once '../validate_customer_token.php';

// get database connection
$database = new Database();
$db = $database->getConnection();

// instantiate environment object
$environment = new Environment($db);

// get posted data
$data = json_decode(file_get_contents("php://input"));

// set product property values
$environment->name = $data->name;
$environment->endpoint = $data->endpoint;
$environment->accesskey = $data->accesskey;
$environment->secretkey = $data->secretkey;
$environment->id = $data->id;

// update the environment
if($environment->update()){

    // set response code
    http_response_code(200);

    // response in json format
    echo json_encode(
            array(
                "message" => "Environment was updated."
            )
        );
}

// message if unable to update environment
else{
    // set response code
    http_response_code(401);

    // show error message
    echo json_encode(array("message" => "Unable to update environment.", "data" => $decoded->data));
}
?>
