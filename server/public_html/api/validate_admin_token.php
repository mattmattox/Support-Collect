<?php
// required to decode jwt
include_once 'libs/php-jwt-master/src/BeforeValidException.php';
include_once 'libs/php-jwt-master/src/ExpiredException.php';
include_once 'libs/php-jwt-master/src/SignatureInvalidException.php';
include_once 'libs/php-jwt-master/src/JWT.php';
use \Firebase\JWT\JWT;

// get posted data
$data = json_decode(file_get_contents("php://input"));

// get jwt
$jwt=isset($data->jwt) ? $data->jwt : "";
if($jwt==""){ $jwt=isset($_REQUEST["jwt"]) ? $_REQUEST["jwt"] : ""; }

// if jwt is not empty
if($jwt){

    // if decode succeed, show user details
    try {
        // decode jwt
        $decoded = JWT::decode($jwt, $key, array('HS256'));

        // if admin, respond ok
        if($decoded->data->access_level=="Admin"){
            // set response code
            http_response_code(200);
        }

        // else deny access
        else{

            // set response code
            http_response_code(401);

            echo json_encode(array("message" => "Access denied."));
            exit;
        }
    }

    // if decode fails, it means jwt is invalid
    catch (Exception $e){

        // set response code
        http_response_code(401);

        // tell the user access denied  & show error message
        echo json_encode(array(
            "message" => "Access denied.",
            "error" => $e->getMessage()
        ));
        exit;
    }
}

// show error message if jwt is empty
else{

    // set response code
    http_response_code(401);

    // tell the user access denied
    echo json_encode(array("message" => "Access denied."));
    exit;
}
?>
