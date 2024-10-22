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

    // Get the color from the color input
    $show_color = isset($_POST['show_color']) ? $_POST['show_color'] : null; // Adjusted this line

    // Handle the image file upload
    $target_dir = "showImg/";  // Folder to store uploaded images
    $file_name = basename($_FILES["fileToUpload"]["name"]);
    $target_file = $target_dir . $file_name;

    // Assuming the file was uploaded correctly
    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
        // Now store the image path, show name, and show status in the database
        $instanceWatchList->addshow($show_name, $target_file, $show_status, $show_color);  // Pass $show_color as well
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
                        <input type="file" name="fileToUpload" id="fileToUpload" accept="image/*"  onchange="previewImage()"> <br>

                        <!-- Image preview -->
                        <img id="imgPreview" src="#" alt="Selected image" style="display:none; max-width: 300px; height: auto; margin-top:10px;" />

                        <!-- Display the average color and its code -->
                            <div>
                            <label for="show_color">Color of show box </label>
                            <input type="color" id="show_color" name="show_color" value="<?= htmlspecialchars($showToEdit['show_color']); ?>" />
                        </div>
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
                            <input type="radio" name="show-status" value="1" class="form-check-input" id="status_to_watch" required>
                            <label class="form-check-label" for="status_to_watch">To watch</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input type="radio" name="show-status" value="2" class="form-check-input" id="status_watching" required>
                            <label class="form-check-label" for="status_watching">Watching</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input type="radio" name="show-status" value="3" class="form-check-input" id="status_pause" required>
                            <label class="form-check-label" for="status_pause">Pause</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input type="radio" name="show-status" value="4" class="form-check-input" id="status_finished" required>
                            <label class="form-check-label" for="status_finished">Finished</label>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <input class="btn btn-primary my-2" type="submit" name="add" value="Add show" />
                </div>
            </div>
        </form>
    </div>

    <!-- JavaScript for image preview and color calculation -->
    <script>
        function previewImage() {
    const file = document.getElementById('fileToUpload').files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function (e) {
            const imgPreview = document.getElementById('imgPreview');
            imgPreview.src = e.target.result;
            imgPreview.style.display = 'block';  // Show the new image

            // Create a new image object
            const img = new Image();
            img.src = e.target.result;

            // When the image loads, calculate the average color
            img.onload = function() {
                const avgColor = calculateAverageColor(img);
                
                // Set the average color to the color input
                document.getElementById('show_color').value = avgColor;
            };
        };
        reader.readAsDataURL(file);
    }
}

    function calculateAverageColor(image) {
        // Create a canvas to draw the image
        const canvas = document.createElement('canvas');
        const context = canvas.getContext('2d');

        // Set canvas size to image size
        canvas.width = image.width;
        canvas.height = image.height;

        // Draw the image onto the canvas
        context.drawImage(image, 0, 0, image.width, image.height);

        // Get the pixel data from the image
        const imageData = context.getImageData(0, 0, canvas.width, canvas.height);
        const pixels = imageData.data;

        // Variables to accumulate the RGB values
        let r = 0, g = 0, b = 0;

        // Loop through all the pixels
        for (let i = 0; i < pixels.length; i += 4) {
            r += pixels[i];     // Red
            g += pixels[i + 1]; // Green
            b += pixels[i + 2]; // Blue
        }

        // Calculate the average RGB values
        const pixelCount = pixels.length / 4;
        r = Math.floor(r / pixelCount);
        g = Math.floor(g / pixelCount);
        b = Math.floor(b / pixelCount);

        // Convert the RGB values to a hex color code
        return rgbToHex(r, g, b);
    }

    function rgbToHex(r, g, b) {
        return "#" + ((1 << 24) + (r << 16) + (g << 8) + b).toString(16).slice(1).toUpperCase();
    }

    // Handle manual color selection
    document.getElementById('show_color').addEventListener('input', function() {
        // When a user manually selects a color, ensure the calculated color is not used
        document.getElementById('colorCodeInput').value = this.value;
    });
</script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>

