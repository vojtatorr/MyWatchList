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
    <!-- Head -->
    <?php include 'head.php'; ?>
</head>


<body>
  <!-- Navbar -->
  <?php include 'navbar.php'; ?>

    <!-- Search bar -->
    <div class="container mt-5">
        <form class="d-flex align-items-center justify-content-center" method="get" action="showsList.php">
            <input class="form-control" name="shows_name" type="text" placeholder="Shows name" aria-label="Search shows" style="width: 600px;">
            <button class="btn btn-primary" type="submit">Search</button>
        </form>
    </div>

    <!-- Shows list container -->
    <div class="container px-5 py-2">
        <div class="row">
            <?php foreach ($selShows as $shows): ?>
                <!-- Each show container inside a column -->
                <div class="col-2">
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