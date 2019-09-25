<?php
// required headers
header("Access-Control-Allow-Origin: http://support-collect.local/");
header("Content-Type: application/json; charset=UTF-8");

// include database and object files
include_once '../config/core.php';
include_once '../config/database.php';
include_once '../objects/cluster.php';
include_once '../utilities.php';
//include_once '../validate_customer_token.php';
include_once '../validate_admin_token.php';

// utilities
$utilities = new Utilities();

// instantiate database and cluster object
$database = new Database();
$db = $database->getConnection();

// initialize object
$cluster = new Cluster($db);

// get keywords
$keywords=isset($_GET["s"]) ? $_GET["s"] : "";

// query clusters
$stmt = $cluster->searchPaging($keywords, $from_record_num, $records_per_page);
$num = $stmt->rowCount();

// check if more than 0 record found
if($num>0){

	// clusters array
	$clusters_arr=array();

	// retrieve our table contents
	// fetch() is faster than fetchAll()
	// http://stackoverflow.com/questions/2770630/pdofetchall-vs-pdofetch-in-a-loop
	while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
		// extract row
		// this will make $row['name'] to
		// just $name only
		extract($row);

		$cluster_item=array(
			"id" => $id
		);

		array_push($clusters_arr, $cluster_item);
	}


	// set response code - 200 OK
	http_response_code(200);

	// make it json format
	echo json_encode($clusters_arr);
}

else{

	// set response code - 404 Not found
	http_response_code(404);

	// tell the cluster clusters does not exist
    echo json_encode(
		array("message" => "No clusters found.")
	);
}
?>
