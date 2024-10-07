<?php
require_once('WatchList.php');
include('DbConnect.php');

// Create the database connection
$conn = new DbConnect();
$dbConnection = $conn->connect();
$instanceWatchList = new WatchList($dbConnection);

if (isset($_POST['add'])) {
    // Get the shows name from the form
    $shows_name = $_POST['shows_name'];

    // Get the show status from the form (radio buttons)
    $show_status = isset($_POST['show-status']) ? $_POST['show-status'] : null;

    // Handle the image file upload
    $target_dir = "img/";  // Folder to store uploaded images
    $file_name = basename($_FILES["fileToUpload"]["name"]);
    $target_file = $target_dir . $file_name;

    // Assuming the file was uploaded correctly
    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
        // Now store the image path, shows name, and show status in the database
        $instanceWatchList->addShows($shows_name, $target_file, $show_status);  // All arguments passed
    } else {
        echo "Sorry, there was an error uploading your file.";
    }

    // Redirect to the index page (or wherever you want)
    header("Location: index.php");
    exit();
}
?>



<!-- HTML -->
</html>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="scss/custom.css" />
    <title>Watch list</title>
</head>


<body>
  <!-- Navbar -->
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="index.php">MyWatchList</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="addshows.php">Add shows</a>
                </li>
            </ul>
        </div>
    </div>
</nav>


    
  <!-- shows container -->
    <div class="container px-5 py-5">
        <div class="container m-2 text-center add-shows-container">

        <form action="addshows.php" method="post" enctype="multipart/form-data">
    <!-- shows data -->
    <div class="container m-2">
        <label>Name </label>
        <input type="text" name="shows_name" required> <br>
    </div>

    <!-- Image Upload -->
    <div class="container m-2">
        <label>Select image</label>
        <input type="file" name="fileToUpload" id="fileToUpload" required> <br>
    </div>

    <!-- Radio buttons -->
    <div class="container status-radiobuttons">
        <label>Show status</label>
        <div class="container">
            <label>To watch</label>
            <input type="radio" name="show-status" value="1">
            <label>Watching</label>
            <input type="radio" name="show-status" value="2">
            <label>Pause</label>
            <input type="radio" name="show-status" value="3">
            <label>Finished</label>
            <input type="radio" name="show-status" value="4">
        </div>
    </div>


    <!-- Submit Button -->
    <input class="btn btn-primary my-2" type="submit" name="add" value="Add shows" />

    </form>
        </div>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>