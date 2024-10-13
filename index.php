<?php
require_once('WatchList.php');
include('DbConnect.php');

// Create database connection
$conn = new DbConnect();
$dbConnection = $conn->connect();
$instanceWatchList = new WatchList($dbConnection);

// Get active shows
$selShows = $instanceWatchList->getActiveShows();
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
                <?php 
                // Get parts for the current show using the show's id
                $selParts = $instanceWatchList->getShowParts($shows['id_shows']); // Call using $instanceWatchList

                foreach ($selParts as $parts): ?>
                <div class="row mb-2">
                    <div class="col-2 d-flex flex-column justify-content-center align-items-center">
                        <!-- Part name -->
                        <div class="container part-name">
                            <p class="text-center m-0"><?php echo htmlspecialchars($parts['part_name']); ?></p>
                        </div>

                        <!-- OP and ED containers side by side with equal width -->
                        <div class="container d-flex justify-content-center op-ed-container">

                        <div class="op-container flex-grow-1">
                            <p class="m-0">OP</p>

                            <!-- Hidden hover box -->
                            <div class="hover-box">
                                <?php echo htmlspecialchars($parts['op']); ?>
                            </div>
                        </div>

                        <div class="ed-container flex-grow-1">
                            <p class="m-0">ED</p>
       
                        <!-- Hidden hover box -->
                        <div class="hover-box">
                                <?php echo htmlspecialchars($parts['ed']); ?>
                            </div>
                            </div>
                         </div>
                    </div>
                    <div class="col-10">
                        <div class="row">
                            <?php
                            // Split the string 'num_of_ep' into an array using explode
                            $episode_numbers = explode(',', $parts['num_of_ep']); // e.g., "1,2,3,4,5"
                            
                            // Split the string 'watched_ep' into an array
                            $watched_ep_array = explode(',', $parts['watched_ep']); // e.g., "1,2"

                            // Loop through each episode number in the array
                            foreach ($episode_numbers as $episode_num) {
                                // Check if the current episode is in the watched episodes array
                                if (in_array($episode_num, $watched_ep_array)){
                                    echo '
                                    <div class="col-1 m-1 d-flex justify-content-center align-items-center episode-box-watched ">
                                        <!-- Content of the episode box, e.g., episode number -->
                                        <p class="m-0">' . $episode_num . '</p>
                                    </div>';
                                } else {
                                    echo '
                                    <div class="col-1 m-1 d-flex justify-content-center align-items-center episode-box-unwatched">
                                        <!-- Content of the episode box, e.g., episode number -->
                                        <p class="m-0">' . $episode_num . '</p>
                                    </div>';
                                }
                            }
                            ?>
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

