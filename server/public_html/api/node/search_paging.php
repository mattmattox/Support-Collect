<?php
// required headers
header("Access-Control-Allow-Origin: http://support-collect.local/");
header("Content-Type: application/json; charset=UTF-8");

// include database and object files
include_once '../config/core.php';
include_once '../config/database.php';
include_once '../objects/node.php';
include_once '../utilities.php';
//include_once '../validate_customer_token.php';
include_once '../validate_admin_token.php';

// utilities
$utilities = new Utilities();

// instantiate database and node object
$database = new Database();
$db = $database->getConnection();

// initialize object
$node = new Node($db);

// get keywords
$keywords=isset($_GET["s"]) ? $_GET["s"] : "";

// query nodes
$stmt = $node->searchPaging($keywords, $from_record_num, $records_per_page);
$num = $stmt->rowCount();

// check if more than 0 record found
if($num>0){

	// nodes array
	$nodes_arr=array();
	$nodes_arr["records"]=array();
	$nodes_arr["paging"]=array();

	// retrieve our table contents
	// fetch() is faster than fetchAll()
	// http://stackoverflow.com/questions/2770630/pdofetchall-vs-pdofetch-in-a-loop
	while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
		// extract row
		// this will make $row['name'] to
		// just $name only
		extract($row);

		$node_item=array(
			"id" => $id,
			"environmentid" => $environmentid,
			"clusterid" => $clusterid,
			"name" => $name,
			"os_family" => $os_family,
			"os_version" => $os_version,
			"docker_version" => $docker_version,
			"role_etcd" => $role_etcd,
			"role_control" => $role_control,
			"role_worker" => $role_worker,
			"status" => $status,
			"health" => $health
		);

		array_push($nodes_arr["records"], $node_item);
	}


	// include paging
	$total_rows=$node->countSearch($keywords);
	$page_url="{$home_url}node/search_paging.php?s={$keywords}&";
	$paging=$utilities->getPaging($page, $total_rows, $records_per_page, $page_url);
	$nodes_arr["paging"]=$paging;

	// set response code - 200 OK
	http_response_code(200);

	// make it json format
	echo json_encode($nodes_arr);
}

else{

	// set response code - 404 Not found
	http_response_code(404);

	// tell the node nodes does not exist
    echo json_encode(
		array("message" => "No nodes found.")
	);
}
?>
