<?php
require_once('WatchList.php');
include('DbConnect.php');

$conn = new DbConnect();
$dbConnection = $conn->connect();
$instanceWatchList = new WatchList($dbConnection);

if (isset($_GET['query'])) {
    $searchTerm = $_GET['query'];
    $shows = $instanceWatchList->searchShows($searchTerm);
    
    // Return the results as JSON
    echo json_encode($shows);
}

?>