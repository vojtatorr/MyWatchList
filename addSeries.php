<?php
require_once('WatchList.php');
include('DbConnect.php');

$conn = new DbConnect();
$dbConnection = $conn->connect();
$instanceWatchList = new WatchList($dbConnection);

if (isset($_POST['add'])) {
    $series_name = $_POST['series_name'];
    $img_dir = $_POST['img_dir'];
   /*  $finished = $_POST['finished']; */
    $instanceWatchList->addSeries($img_dir, $series_name/* , $finished */);
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

            <form action="addSeries.php" method="post">
                <!-- Series data -->
                <label>Series name </label>
                <input type="text" name="series_name"> <br>
                <label>IMG</label>
                <input type="text" name="img_dir"> <br>
                <!-- <label>Finished </label>
                <input type="checkbox" name="finished" value="finished"> <br> -->
                <!-- Parts data -->
                <!-- <label>Part name </label>
                <input type="text" name="part_name"> <br>
                <label>OP </label>
                <input type="text" name="op"> <br>
                <label>ED </label>
                <input type="text" name="ed"> <br> -->
                <!-- Episodes data -->
                <!-- <label>Number </label>
                <input type="number" name="ep_num"> <br>
                <label>Watche </label>
                <input type="checkbox" name="watched" value=""><br> -->
                <input class="btn btn-primary my-2" type="submit" name="add" value="Add series" />
            </form>
        </div>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>