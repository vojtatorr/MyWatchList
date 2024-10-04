<?php
require_once('WatchList.php');
include('DbConnect.php');

// Create the database connection
$conn = new DbConnect();
$dbConnection = $conn->connect();
$instanceWatchList = new WatchList($dbConnection);

if (isset($_POST['add'])) {
    // Get the series name from the form
    $series_name = $_POST['series_name'];

    // Handle the image file upload
    $target_dir = "img/";  // Folder to store uploaded images
    $file_name = basename($_FILES["fileToUpload"]["name"]);
    $target_file = $target_dir . $file_name;

    // Assuming the file was uploaded correctly
    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
        // Now store the image path and series name in the database
        $instanceWatchList->addSeries($series_name, $target_file);  // Both arguments passed
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
                    <a class="nav-link active" aria-current="page" href="addSeries.php">Add series</a>
                </li>
            </ul>
        </div>
    </div>
</nav>


    
  <!-- Series container -->
    <div class="container px-5 py-5">
        <div class="container m-2 text-center add-shows-container">

        <form action="addSeries.php" method="post" enctype="multipart/form-data">
    <!-- Series data -->
    <label>Series name </label>
    <input type="text" name="series_name" required> <br>

    <!-- Image Upload -->
    <label>Select image to upload:</label>
    <input type="file" name="fileToUpload" id="fileToUpload" required> <br>

    <!-- Submit Button -->
    <input class="btn btn-primary my-2" type="submit" name="add" value="Add series" />
    </form>
        </div>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>