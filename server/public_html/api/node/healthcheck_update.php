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
include_once '../objects/node.php';
//include_once '../validate_customer_token.php';
include_once '../validate_admin_token.php';

// get database connection
$database = new Database();
$db = $database->getConnection();

// instantiate node object
$node = new Node($db);

// get posted data
$data = json_decode(file_get_contents("php://input"));

// set product property values
$node->health = $data->health;
$node->id = $data->id;

// update the node
if($node->healthcheckupdate()){

    // set response code
    http_response_code(200);

    // response in json format
    echo json_encode(
            array(
                "message" => "Health Check updated"
            )
        );
}

// message if unable to update node
else{
    // set response code
    http_response_code(401);

    // show error message
    echo json_encode(array("message" => "Unable to update Health check for node.", "data" => $decoded->data));
}
?>
