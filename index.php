<?php
session_start();
require_once('WatchList.php');
include('DbConnect.php');

// Create database connection
$conn = new DbConnect();
$dbConnection = $conn->connect();
$instanceWatchList = new WatchList($dbConnection);

// Get active shows
$selshow = $instanceWatchList->getActiveshow();

if (isset($_POST['saveEp']) && isset($_POST['watched_episodes'])) {
    // Sanitize input
    $watched_episodes = filter_input(INPUT_POST, 'watched_episodes', FILTER_SANITIZE_STRING, FILTER_REQUIRE_ARRAY);

    foreach ($watched_episodes as $part_id => $episodes) {
        // Convert checked episodes array into a comma-separated string
        $watched_episodes_string = implode(',', $episodes);

        // Step 1: Update the watched_ep column in the parts table
        if (!$instanceWatchList->updateWatchedEpisodes($part_id, $watched_episodes_string)) {
            $_SESSION['message'] = "Failed to update episodes for part ID: $part_id";
            $_SESSION['message_type'] = "error";
            header('Location: index.php');
            exit;
        }
    }

    $_SESSION['message'] = "Watched episodes updated successfully!";
    $_SESSION['message_type'] = "success"; // Set success message
    header('Location: index.php');
    exit;
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

  <!-- Show list container -->
  <div class="container my-5">

  <?php if (isset($_SESSION['message'])): ?>
            <div class="alert alert-<?php echo $_SESSION['message_type']; ?>">
                <?php
                echo $_SESSION['message'];
                unset($_SESSION['message']); // Clear the message after displaying it
                ?>
            </div>
        <?php endif; ?>

  <form method="post" action="index.php">
    <div class="d-flex justify-content-center my-2">
        <input class="btn btn-primary" type="submit" name="saveEp" value="Save changes" style="width: 400px;" />
    </div>

    <?php foreach ($selshow as $show): ?>
    <div class="container my-4 p-2 active-show-container" 
        style="background-color: <?= htmlspecialchars($show['show_color']) ?: '#ffffff'; ?>;"> <!-- Set background color -->
        <div class="row">
            <div class="col-md-3 col-12 d-flex justify-content-center">
                <img src="<?= htmlspecialchars($show['img_dir']); ?>" alt="<?= htmlspecialchars($show['show_name']); ?>" class="img-fluid rounded-img" style="max-width: 400px; height: auto;">
            </div>
            <div class="col-md-9 col-12 align-items-center">
                <a href="editShow.php?id=<?= htmlspecialchars($show['id_show']); ?>" class="edit-link">
                    <p class="p-2 text-center show-title"><?= htmlspecialchars($show['show_name']); ?></p>
                </a>

                <?php
                // Get parts for the current show using the show's id
                $selParts = $instanceWatchList->getShowParts($show['id_show']);
                if (!empty($selParts)) {
                    foreach ($selParts as $parts): ?>
                        <div class="container part-container">
                            <div class="row mb-2">
                                <div class="col-sm-12 col-md-2 d-flex flex-column justify-content-center align-items-center">
                                    <div class="container part-name">
                                        <p class="text-center m-0"><?= htmlspecialchars($parts['part_name']); ?></p>
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-10">
                                    <div class="row">
                                        <?php
                                        $episode_numbers = explode(',', $parts['num_of_ep']);
                                        $watched_ep_array = explode(',', $parts['watched_ep']);
                                        foreach ($episode_numbers as $episode_num) {
                                            $unique_id = '-show-' . htmlspecialchars($show['show_name']) . '-part-' . htmlspecialchars($parts['part_name']) . '-ep-' . $episode_num;
                                            echo '<input type="checkbox" class="btn-check episode-box-' . (in_array($episode_num, $watched_ep_array) ? 'watched' : 'unwatched') . '" name="watched_episodes[' . $parts['id_part'] . '][]" id="' . $unique_id . '" value="' . $episode_num . '" ' . (in_array($episode_num, $watched_ep_array) ? 'checked' : '') . ' autocomplete="off">';
                                            echo '<label class="btn col-1 m-1 d-flex justify-content-center align-items-center episode-box-' . (in_array($episode_num, $watched_ep_array) ? 'watched' : 'unwatched') . '" for="' . $unique_id . '">' . $episode_num . '</label>';
                                        }
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; 
                } ?>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
  </form>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

<!-- Check box buttons logic -->
  <script>
    function toggleWatchedStatus(checkbox) {
        var checkboxId = checkbox.id;
        var label = document.querySelector('label[for="' + checkboxId + '"]');

        // If the checkbox is checked (watched)
        if (checkbox.checked) {
            checkbox.classList.remove('episode-box-unwatched');
            checkbox.classList.add('episode-box-watched');
            
            label.classList.remove('episode-box-unwatched');
            label.classList.add('episode-box-watched');
        } else {
            // If the checkbox is unchecked (unwatched)
            checkbox.classList.remove('episode-box-watched');
            checkbox.classList.add('episode-box-unwatched');
            
            label.classList.remove('episode-box-watched');
            label.classList.add('episode-box-unwatched');
        }
    }
</script>
</body>
</html>
