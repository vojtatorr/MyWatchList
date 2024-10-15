<?php
require_once('WatchList.php');
include('DbConnect.php');

// Create the database connection
$conn = new DbConnect();
$dbConnection = $conn->connect();
$instanceWatchList = new WatchList($dbConnection);
$showToEdit = [];

if (isset($_GET['id'])) {  // Access 'id' from URL
    $id_show = $_GET['id'];
    $showToEdit = $instanceWatchList->getShow($id_show);  // Fetch the show details
}

if (isset($_POST['edit'])) {
    $show_name = $_POST['show_name'];
    $show_status = isset($_POST['show-status']) ? $_POST['show-status'] : null;
    
    // Default to current image directory if no new file is uploaded
    $target_file = $showToEdit['img_dir'];  // Use the current image path by default

    // Handle file upload if a file was provided
    if (!empty($_FILES["fileToUpload"]["name"])) {
        $target_dir = "img/";
        $file_name = basename($_FILES["fileToUpload"]["name"]);
        $target_file = $target_dir . $file_name;

        // Check if the upload is successful
        if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
            // Delete the old image if it exists
            if (file_exists($showToEdit['img_dir'])) {
                unlink($showToEdit['img_dir']);  // Delete the old image
            }
        } else {
            echo "Sorry, there was an error uploading your file.";
            $target_file = $showToEdit['img_dir'];  // Retain current image path if upload fails
        }
    }

    // Update the show in the database, using the current or new image path
    $instanceWatchList->editShow($show_name, $target_file, $show_status, $id_show);

    // Redirect to index.php after update
    header("Location: index.php");
    exit();
}

// Deleting show
if (isset($_GET['delete'])) {
    $id_show = $_GET['delete'];
    $instanceWatchList->deleteShow($id_show);
    header("Location: index.php");
    exit();
}

?>

<!-- HTML -->
<!DOCTYPE html>
<html lang="en">

<head>
    <?php include 'head.php'; ?>
</head>

<body>

    <!-- Navbar -->
    <?php include 'navbar.php'; ?>

    <div class="container px-5 py-5">
        <div class="container m-2 text-center add-show-container">

            <form action="editshow.php?id=<?= $id_show ?>" method="post" enctype="multipart/form-data">
                <!-- Hidden input for id_show -->
                <input type="hidden" name="id_show" value="<?= $id_show; ?>">

                <!-- show data -->
                <div class="container m-2">
                    <label>Name </label>
                    <input type="text" name="show_name" value="<?= htmlspecialchars($showToEdit['show_name']); ?>" required> <br>
                </div>

                <!-- Image Upload -->
                <div class="container m-2">
                    <label>Select image (if you want to update the image)</label>
                    <input type="file" name="fileToUpload" id="fileToUpload"> <br>
                    <p>Current Image: <img src="<?= htmlspecialchars($showToEdit['img_dir']); ?>" alt="Current Image" width="100"></p>
                </div>

                <!-- Radio buttons for show status -->
                <div class="container status-radiobuttons">
                    <label>Show status</label>
                    <div class="container">
                        <label>To watch</label>
                        <input type="radio" name="show-status" value="1" <?= ($showToEdit['show_status'] == 1) ? 'checked' : ''; ?>>
                        <label>Watching</label>
                        <input type="radio" name="show-status" value="2" <?= ($showToEdit['show_status'] == 2) ? 'checked' : ''; ?>>
                        <label>Pause</label>
                        <input type="radio" name="show-status" value="3" <?= ($showToEdit['show_status'] == 3) ? 'checked' : ''; ?>>
                        <label>Finished</label>
                        <input type="radio" name="show-status" value="4" <?= ($showToEdit['show_status'] == 4) ? 'checked' : ''; ?>>
                    </div>
                </div>

                <!-- Submit Button -->
                <input class="btn btn-primary my-2" type="submit" name="edit" value="Edit show" />
            </form>

            <!-- Delete -->
            <a class="btn btn-warning" href="editshow.php?delete=<?= $id_show; ?>" onclick="return confirm('Are you sure you want to delete this show?');">Delete</a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
</body>

</html>
