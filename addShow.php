<?php

require_once('WatchList.php');
include('DbConnect.php');

$conn = new DbConnect();
$dbConnection = $conn->connect();
$instanceWatchList = new WatchList($dbConnection);

if (isset($_POST['add'])) {
    // Get the show name from the form
    $show_name = $_POST['show_name'];

    // Get the show status from the form (radio buttons)
    $show_status = isset($_POST['show-status']) ? $_POST['show-status'] : null;

    // Handle the image file upload
    $target_dir = "img/";  // Folder to store uploaded images
    $file_name = basename($_FILES["fileToUpload"]["name"]);
    $target_file = $target_dir . $file_name;

    // Assuming the file was uploaded correctly
    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
        // Now store the image path, show name, and show status in the database
        $instanceWatchList->addshow($show_name, $target_file, $show_status);  // All arguments passed
    } else {
        echo "Sorry, there was an error uploading your file.";
    }

    // Redirect to the index page (or wherever you want)
    header("Location: index.php");
    exit();
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

    <div class="container my-4 p-2 add-show-container">
        <form action="addshow.php" method="post" enctype="multipart/form-data">
            <div class="row">
                <!-- Column for image (col-4) -->
                <div class="col-4">
                    <div class="d-flex flex-column">
                        <label class="text-center">Select image</label>
                        <input type="file" name="fileToUpload" id="fileToUpload" accept="image/*" required onchange="previewImage()"> <br>

                        <!-- Image preview -->
                        <img id="imgPreview" src="#" alt="Selected image" style="display:none; max-width: 300px; height: auto; margin-top:10px;" />
                    </div>
                </div>

                <div class="col-8">
                    <!-- Show title -->
                    <div class="mb-3">
                        <label class="form-label">Name</label>
                        <input type="text" class="form-control" name="show_name" required> <br>
                    </div>

                    <!-- Radio buttons for show status -->
                    <div class="mb-3 status-radiobuttons">
                        <label class="form-label">Show status</label>
                        <div class="form-check form-check-inline">
                            <input type="radio" name="show-status" value="1" class="form-check-input" id="status_to_watch">
                            <label class="form-check-label" for="status_to_watch">To watch</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input type="radio" name="show-status" value="2" class="form-check-input" id="status_watching">
                            <label class="form-check-label" for="status_watching">Watching</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input type="radio" name="show-status" value="3" class="form-check-input" id="status_pause">
                            <label class="form-check-label" for="status_pause">Pause</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input type="radio" name="show-status" value="4" class="form-check-input" id="status_finished">
                            <label class="form-check-label" for="status_finished">Finished</label>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <input class="btn btn-primary my-2" type="submit" name="add" value="Add show" />
                </div>
            </div>
        </form>
    </div>

    <!-- JavaScript for image preview -->
    <script>
        function previewImage() {
            const file = document.getElementById('fileToUpload').files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    document.getElementById('imgPreview').src = e.target.result;
                    document.getElementById('imgPreview').style.display = 'block';
                };
                reader.readAsDataURL(file);
            }
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>
