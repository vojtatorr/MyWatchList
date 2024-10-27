<?php
session_start();

require_once('WatchList.php');
include('DbConnect.php');

$conn = new DbConnect();
$dbConnection = $conn->connect();
$instanceWatchList = new WatchList($dbConnection);

if (isset($_POST['add'])) {
    // Get and sanitize input data
    $show_name = htmlspecialchars(trim($_POST['show_name']));
    $show_status = isset($_POST['show_status']) ? $_POST['show_status'] : null;
    $show_color = isset($_POST['show_color']) ? $_POST['show_color'] : '#ffffff'; // Default color

    // Handle the image file upload
    $target_dir = "showImg/";
    $file_name = basename($_FILES["fileToUpload"]["name"]);
    $target_file = $target_dir . $file_name;

    // Validate file upload
    $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
    if (in_array($_FILES["fileToUpload"]["type"], $allowed_types) && $_FILES["fileToUpload"]["size"] < 5000000) {
        // Resize the image to 286x400px with a 5:7 aspect ratio
        if (resizeImage($_FILES["fileToUpload"]["tmp_name"], $target_file, 286, 400)) {
            // Store the show details in the database
            $instanceWatchList->addshow($show_name, $target_file, $show_status, $show_color);
            $_SESSION['message'] = "Show '$show_name' has been added successfully!";
            $_SESSION['message_type'] = "success"; // Optional: for CSS styling
        } else {
            $_SESSION['message'] = "Sorry, there was an error resizing your file.";
            $_SESSION['message_type'] = "error"; // Optional: for CSS styling
        }
    } else {
        $_SESSION['message'] = "Invalid file type or size.";
        $_SESSION['message_type'] = "error";
    }

    // Redirect to the index page
    header("Location: index.php");
    exit();
}

// Function to resize the image
function resizeImage($source_file, $target_file, $target_width, $target_height) {
    $image_info = getimagesize($source_file);
    $source_width = $image_info[0];
    $source_height = $image_info[1];

    // Determine the image type
    switch ($image_info['mime']) {
        case 'image/jpeg':
            $source_image = imagecreatefromjpeg($source_file);
            break;
        case 'image/png':
            $source_image = imagecreatefrompng($source_file);
            break;
        case 'image/gif':
            $source_image = imagecreatefromgif($source_file);
            break;
        default:
            return false;
    }

    // Create a blank image with the target dimensions
    $target_image = imagecreatetruecolor($target_width, $target_height);

    // Resize the original image into the target image
    imagecopyresampled($target_image, $source_image, 0, 0, 0, 0, $target_width, $target_height, $source_width, $source_height);

    // Save the resized image
    switch ($image_info['mime']) {
        case 'image/jpeg':
            imagejpeg($target_image, $target_file);
            break;
        case 'image/png':
            imagepng($target_image, $target_file);
            break;
        case 'image/gif':
            imagegif($target_image, $target_file);
            break;
        default:
            return false;
    }

    // Free up memory
    imagedestroy($source_image);
    imagedestroy($target_image);

    return true;
}
?>

<!-- HTML -->
<!DOCTYPE html>
<html lang="en">
<head>
    <?php include 'head.php'; ?>
</head>

<body>
    <?php include 'navbar.php'; ?>

    <div class="container my-4 p-2 add-show-container">
        <form action="addShow.php" method="post" enctype="multipart/form-data">
        <div class="row">
            <!-- Column for image (col-md-4 for medium screens and larger, col-12 for small screens) -->
            <div class="col-sm-12 col-md-4 justify-content-center align-items-center">
                <div class="d-flex flex-column">
                    <label class="text-center">Select image</label>
                    <input type="file" name="fileToUpload" id="fileToUpload" accept="image/*" onchange="previewImage()"> <br>

                    <img id="imgPreview" src="#" alt="Selected image" style="display:none; max-width: 300px; height: auto; margin-top:10px;" />

                    <div>
                        <label for="show_color">Color of show box</label>
                        <input type="color" id="show_color" name="show_color" value="#ffffff" />
                    </div>
                </div>
            </div>

            <!-- Column for other content (col-md-8 for medium screens and larger, col-12 for small screens) -->
            <div class="col-sm-12 col-md-8 justify-content-center">
    <div class="mb-3">
        <label class="form-label">Name</label>
        <input type="text" class="form-control" name="show_name" required> <br>
    </div>

    <div class="mb-3 status-radiobuttons text-center">
    <!-- Label for show status, centered horizontally -->
    <label class="form-label">Show status</label>

    <!-- Wrap radio buttons in a div to ensure they are grouped below the label -->
    <div>
        <div class="form-check form-check-inline">
            <input type="radio" name="show_status" value="1" class="form-check-input" id="status_to_watch" required>
            <label class="form-check-label" for="status_to_watch">To watch</label>
        </div>
        <div class="form-check form-check-inline">
            <input type="radio" name="show_status" value="2" class="form-check-input" id="status_watching" required>
            <label class="form-check-label" for="status_watching">Watching</label>
        </div>
        <div class="form-check form-check-inline">
            <input type="radio" name="show_status" value="3" class="form-check-input" id="status_pause" required>
            <label class="form-check-label" for="status_pause">Pause</label>
        </div>
        <div class="form-check form-check-inline">
            <input type="radio" name="show_status" value="4" class="form-check-input" id="status_finished" required>
            <label class="form-check-label" for="status_finished">Finished</label>
        </div>
    </div>
</div>


                    <input class="btn btn-primary my-2" type="submit" name="add" value="Add show" />
                </div>
            </div>
        </form>
    </div>

    <script>
        function previewImage() {
            const file = document.getElementById('fileToUpload').files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    const imgPreview = document.getElementById('imgPreview');
                    imgPreview.src = e.target.result;
                    imgPreview.style.display = 'block';  // Show the new image

                    const img = new Image();
                    img.src = e.target.result;

                    img.onload = function() {
                        const avgColor = calculateAverageColor(img);
                        document.getElementById('show_color').value = avgColor;
                    };
                };
                reader.readAsDataURL(file);
            }
        }

        function calculateAverageColor(image) {
            const canvas = document.createElement('canvas');
            const context = canvas.getContext('2d');

            canvas.width = image.width;
            canvas.height = image.height;

            context.drawImage(image, 0, 0, image.width, image.height);

            const imageData = context.getImageData(0, 0, canvas.width, canvas.height);
            const pixels = imageData.data;

            let r = 0, g = 0, b = 0;

            for (let i = 0; i < pixels.length; i += 4) {
                r += pixels[i];
                g += pixels[i + 1];
                b += pixels[i + 2];
            }

            const pixelCount = pixels.length / 4;
            r = Math.floor(r / pixelCount);
            g = Math.floor(g / pixelCount);
            b = Math.floor(b / pixelCount);

            return rgbToHex(r, g, b);
        }

        function rgbToHex(r, g, b) {
            return "#" + ((1 << 24) + (r << 16) + (g << 8) + b).toString(16).slice(1).toUpperCase();
        }

        document.getElementById('show_color').addEventListener('input', function() {
            // Ensures the selected color is used
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>