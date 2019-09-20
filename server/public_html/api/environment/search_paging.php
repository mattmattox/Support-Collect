<?php
// required headers
header("Access-Control-Allow-Origin: http://support-collect.local/");
header("Content-Type: application/json; charset=UTF-8");

// include database and object files
include_once '../config/core.php';
include_once '../config/database.php';
include_once '../objects/environment.php';
include_once '../utilities.php';
//include_once '../validate_customer_token.php';
include_once '../validate_admin_token.php';

// utilities
$utilities = new Utilities();

// instantiate database and environment object
$database = new Database();
$db = $database->getConnection();

// initialize object
$environment = new Environment($db);

// get keywords
$keywords=isset($_GET["s"]) ? $_GET["s"] : "";

// query environments
$stmt = $environment->searchPaging($keywords, $from_record_num, $records_per_page);
$num = $stmt->rowCount();

// check if more than 0 record found
if($num>0){

	// environments array
	$environments_arr=array();
	$environments_arr["records"]=array();
	$environments_arr["paging"]=array();

	// retrieve our table contents
	// fetch() is faster than fetchAll()
	// http://stackoverflow.com/questions/2770630/pdofetchall-vs-pdofetch-in-a-loop
	while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
		// extract row
		// this will make $row['name'] to
		// just $name only
		extract($row);

		$environment_item=array(
			"id" => $id,
      "name" => $name,
      "endpoint" => $endpoint,
			"accesskey" => $accesskey,
			"secretkey" => $secretkey
		);

		array_push($environments_arr["records"], $environment_item);
	}


	// include paging
	$total_rows=$environment->countSearch($keywords);
	$page_url="{$home_url}environment/search_paging.php?s={$keywords}&";
	$paging=$utilities->getPaging($page, $total_rows, $records_per_page, $page_url);
	$environments_arr["paging"]=$paging;

	// set response code - 200 OK
	http_response_code(200);

	// make it json format
	echo json_encode($environments_arr);
}

else{

	// set response code - 404 Not found
	http_response_code(404);

	// tell the environment environments does not exist
    echo json_encode(
		array("message" => "No environments found.")
	);
}
?>
