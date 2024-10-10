<?php
require_once('WatchList.php');
include('DbConnect.php');

// Create database connection
$conn = new DbConnect();
$dbConnection = $conn->connect();
$instanceWatchList = new WatchList($dbConnection);


$selShows = $instanceWatchList->getActiveShows();

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

    <!-- Shows list container -->
    <div class="container px-5 py-5">
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