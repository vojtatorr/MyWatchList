<?php
require_once('WatchList.php');
include('DbConnect.php');

$conn = new DbConnect();
$dbConnection = $conn->connect();
$instanceWatchList = new WatchList($dbConnection);

if (isset($_POST['add'])) {
    // Series name from form
    $series_name = $_POST['series_name'];

    // Handling file upload
    $target_dir = "img/";  // Folder to store uploaded images
    $file_name = basename($_FILES["fileToUpload"]["name"]);
    $target_file = $target_dir . $file_name;
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Check if the uploaded file is a real image
    $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
    if ($check !== false) {
        $uploadOk = 1;
    } else {
        echo "File is not an image.";
        $uploadOk = 0;
    }

    // Check if file already exists
    if (file_exists($target_file)) {
        echo "Sorry, file already exists.";
        $uploadOk = 0;
    }

    // Check file size (5MB max)
    if ($_FILES["fileToUpload"]["size"] > 5000000) {
        echo "Sorry, your file is too large.";
        $uploadOk = 0;
    }

    // Allow only certain file formats
    if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
        echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        $uploadOk = 0;
    }

    // If everything is OK, upload the file
    if ($uploadOk == 1) {
        if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
            echo "The file " . htmlspecialchars($file_name) . " has been uploaded.";

            // Save the series and the image file path to the database
            $instanceWatchList->addSeries($series_name, $target_file);

            // Redirect to the main page (index.php or wherever you want to go)
            header("Location: index.php");
            exit();
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    }
}
?>