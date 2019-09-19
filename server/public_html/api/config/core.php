<?php
// show error reporting
error_reporting(E_ALL);

// set your default time-zone
date_default_timezone_set('America/Chicago');

// home page url
$home_url="http://support-collect.local/";

// page given in URL parameter, default page is one
$page = isset($_GET['page']) ? $_GET['page'] : 1;

// set number of records per page
$records_per_page = 5;

// calculate for the query LIMIT clause
$from_record_num = ($records_per_page * $page) - $records_per_page;

// variables used for jwt
$key = "support-collect";
$iss = "http://support-collect.local/";
$aud = "http://support-collect.local/";
$iat = 1356999524;
$nbf = 1357000000;
?>
