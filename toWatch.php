<?php
require_once('WatchList.php');
include('DbConnect.php');

// Create database connection
$conn = new DbConnect();
$dbConnection = $conn->connect();
$instanceWatchList = new WatchList($dbConnection);

// Get active show
$selshow = $instanceWatchList->getToWatchshow();
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
        <form class="d-flex align-items-center justify-content-center" method="get" action="showList.php">
            <input class="form-control" name="show_name" type="text" placeholder="show name" aria-label="Search show" style="width: 600px;">
            <button class="btn btn-primary" type="submit">Search</button>
        </form>
    </div>

    <!-- show list container -->
    <div class="container px-5 py-2">
        <div class="row">
            <?php foreach ($selshow as $show): ?>
                <!-- Each show container inside a column -->
                <div class="col-2">
                    <div class="container m-2 show-container" style="background-color: <?php echo htmlspecialchars($show['show_color']) ?: '#ffffff'; ?>;">
                    <a class="btn m-0 p-0" href="editShow.php?id=<?php echo $show['id_show']; ?>">
                        <!-- show image -->
                        <img src="<?php echo htmlspecialchars($show['img_dir']); ?>" alt="<?php echo htmlspecialchars($show['show_name']); ?>" class="img-fluid rounded-img">
                        <!-- show title -->
                        <p class="text-center justify-content-center m-1 show-title"><?php echo htmlspecialchars($show['show_name']); ?></p>
                    </a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>