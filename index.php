<?php
require_once('WatchList.php');
include('DbConnect.php');

// Create database connection
$conn = new DbConnect();
$dbConnection = $conn->connect();
$instanceWatchList = new WatchList($dbConnection);

// Check if a search query has been submitted via GET method
if (isset($_GET['shows_name']) && !empty($_GET['shows_name'])) {
    // If a search query is provided, filter the shows by the provided name
    $shows_name = $_GET['shows_name'];
    $selShows = $instanceWatchList->filterShows($shows_name);
} else {
    // If no search query, show all shows
    $selShows = $instanceWatchList->getWatchList();
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
                    <a class="nav-link active" aria-current="page" href="addShows.php">Add shows</a>
                </li>
            </ul>
        </div>
            <form class="d-flex ms-auto align-items-center" method="get" action="index.php">
                <input class="form-control me-2" name="shows_name" type="text" placeholder="Shows name" aria-label="Search shows">
            <button class="btn btn-outline-success" type="submit">Search</button>
            </form>

    </div>
</nav>

    <!-- Shows list container -->
    <div class="container px-5 py-5">
        <div class="row">
            <?php foreach ($selShows as $shows): ?>
                <!-- Each show container inside a column -->
                <div class="col-3">
                    <div class="container m-2 shows-container">
                        <!-- Shows image -->
                        <img src="<?php echo htmlspecialchars($shows['img_dir']); ?>" alt="<?php echo htmlspecialchars($shows['shows_name']); ?>" class="img-fluid rounded-img">
                        <!-- Shows title -->
                        <p class="text-center justify-content-center m-1 show-title"><?php echo htmlspecialchars($shows['shows_name']); ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>