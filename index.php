<?php
require_once('WatchList.php');
include('DbConnect.php');

// Create database connection
$conn = new DbConnect();
$dbConnection = $conn->connect();
$instanceWatchList = new WatchList($dbConnection);


$selShows = $instanceWatchList->getActiveShows();

$selParts = $instanceWatchList->getShowParts();

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
    <div class="container my-5">

    <?php foreach ($selShows as $shows): ?>
    <div class="container my-4 p-2 active-show-container">
        <div class="row">
            <div class="col-3">
                <!-- Shows image -->
                <img src="<?php echo htmlspecialchars($shows['img_dir']); ?>" alt="<?php echo htmlspecialchars($shows['shows_name']); ?>" class="img-fluid rounded-img" style="max-width: 400px; height: auto;">
            </div>
            <div class="col-9 align-items-center">
                <!-- Shows title -->
                <p class="p-2 text-center show-title"><?php echo htmlspecialchars($shows['shows_name']); ?></p>

                <!-- Parts and Episodes -->
                <?php foreach ($selParts as $parts): ?>
            <div class="row mb-2">
        <div class="col-2 d-flex justify-content-center align-items-center">
            <!-- Part name -->
            <div class="container part-name">
    <p class="text-center m-0 "><?php echo htmlspecialchars($parts['part_name']); ?></p>
</div>
        </div>
        <div class="col-10">
            <div class="row">
                <!-- 12 columns filling the row -->
                <div class="col-1 m-1 d-flex justify-content-center align-items-center episode-box">
                    0
                </div>
                <div class="col-1 m-1 d-flex justify-content-center align-items-center episode-box">
                    1
                </div>
                <div class="col-1 m-1 d-flex justify-content-center align-items-center episode-box">
                    2
                </div>
                <div class="col-1 m-1 d-flex justify-content-center align-items-center episode-box">
                    3
                </div>
                <div class="col-1 m-1 d-flex justify-content-center align-items-center episode-box">
                    4
                </div>
                <div class="col-1 m-1 d-flex justify-content-center align-items-center episode-box">
                    5
                </div>
                <div class="col-1 m-1 d-flex justify-content-center align-items-center episode-box">
                    6
                </div>
                <div class="col-1 m-1 d-flex justify-content-center align-items-center episode-box">
                    7
                </div>
                <div class="col-1 m-1 d-flex justify-content-center align-items-center episode-box">
                    8
                </div>
                <div class="col-1 m-1 d-flex justify-content-center align-items-center episode-box">
                    9
                </div>
                <div class="col-1 m-1 d-flex justify-content-center align-items-center episode-box">
                    10
                </div>
                <div class="col-1 m-1 d-flex justify-content-center align-items-center episode-box">
                    11
                </div>
            </div>
        </div>
    </div>
<?php endforeach; ?>
            </div>
        </div>
    </div>
<?php endforeach; ?>

</div>

      

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>
