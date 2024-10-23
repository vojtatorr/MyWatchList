<?php
require_once('WatchList.php');
include('DbConnect.php');

// Create database connection
$conn = new DbConnect();
$dbConnection = $conn->connect();
$instanceWatchList = new WatchList($dbConnection);

// Check if a search query has been submitted via GET method
$show_name = filter_input(INPUT_GET, 'show_name', FILTER_SANITIZE_STRING);

if (!empty($show_name)) {
    // If a search query is provided, filter the show by the provided name
    $selshow = $instanceWatchList->filtershow($show_name);
} else {
    // If no search query, show all shows
    $selshow = $instanceWatchList->getWatchList();
}
?>

<!-- HTML -->

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
            <input class="form-control" name="show_name" type="text" placeholder="Show name" aria-label="Search show" style="width: 600px;">
            <button class="btn btn-primary" type="submit">Search</button>
        </form>
    </div>

    <!-- Show list container -->
    <div class="container px-5 py-2">
        <div class="row">
            <?php if (!empty($selshow)): ?>
                <?php foreach ($selshow as $show): ?>
                    <!-- Each show container inside a column -->
                    <div class="col-12 col-md-4 col-lg-2">
                        <div class="container m-2 show-container text-center" style="background-color: <?= htmlspecialchars($show['show_color']) ?: '#ffffff'; ?>;">
                            <a class="btn m-0 p-0" href="editShow.php?id=<?= htmlspecialchars($show['id_show']); ?>">
                                <!-- Show image -->
                                <img src="<?= htmlspecialchars($show['img_dir']); ?>" alt="<?= htmlspecialchars($show['show_name']); ?>" class="img-fluid rounded-img">
                                <!-- Show title -->
                                <p class="text-center m-1 show-title"><?= htmlspecialchars($show['show_name']); ?></p>
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="text-center">No shows found.</p>
            <?php endif; ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>
