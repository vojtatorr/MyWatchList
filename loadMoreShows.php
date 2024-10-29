<?php
require_once('WatchList.php');
include('DbConnect.php');

// Create database connection
$conn = new DbConnect();
$dbConnection = $conn->connect();
$instanceWatchList = new WatchList($dbConnection);

// Get offset and limit from AJAX request
$offset = isset($_GET['offset']) ? (int)$_GET['offset'] : 0;
$limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 12; // Load 12 shows per request after initial load

// Fetch additional shows based on offset and limit
$selshow = $instanceWatchList->getWatchListByLimit($offset, $limit);
echo json_encode($selshow);
?>