<?php
require_once('WatchList.php');
include('DbConnect.php');

// Create the database connection
$conn = new DbConnect();
$dbConnection = $conn->connect();
$instanceWatchList = new WatchList($dbConnection);

if (isset($_POST['add'])) {
    // Get the part name from the form
    $part_name = $_POST['part_name'];
    $id_show = $_POST['id_show'];
    $op = $_POST['op'];
    $ed = $_POST['ed'];

    $instanceWatchList->addPart($part_name,$id_show, $op, $ed);

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
                        <a class="nav-link active" aria-current="page" href="showsList.php">Shows</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="addShows.php">Add shows</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="addPart.php">Add parts</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>


    
  <!-- parts container -->
    <div class="container px-5 py-5">
        <div class="container m-2 text-center add-shows-container">

    <form action="addPart.php" method="post" enctype="multipart/form-data">
    <!-- parts data -->

    <div class="container m-2">
        <label>Name </label>
        <input type="text" name="part_name" required> <br>
    </div>

    <div class="container m-2">
        <label>Show name </label>
        <input type="number" name="id_show" required> <br>
    </div>

    <div class="container m-2">
        <label>OP </label>
        <input type="text" name="op" required> <br>
    </div>

    <div class="container m-2">
        <label>ED </label>
        <input type="text" name="ed" required> <br>
    </div>



    <!-- Submit Button -->
    <input class="btn btn-primary my-2" type="submit" name="add" value="Add part" />

    </form>
        </div>
    </div>

    


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>