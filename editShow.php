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

if (isset($_POST['editShow'])) {
    $show_name = $_POST['show_name'];
    $show_color = $_POST['show_color']; // This is the selected color from the color picker
    $show_status = isset($_POST['show-status']) ? $_POST['show-status'] : null;
    
    // Default to current image directory if no new file is uploaded
    $target_file = $showToEdit['img_dir'];  // Use the current image path by default

    // Handle file upload if a file was provided
    if (!empty($_FILES["fileToUpload"]["name"])) {
        $target_dir = "showImg/";
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
    $instanceWatchList->editShow($show_name, $target_file, $show_status, $id_show, $show_color); // Use selected color

    // Redirect to index.php after update
    header("Location: index.php");
    exit();
}


if (isset($_POST['editPart'])) {
    $part_name = $_POST['part_name'];
    $op = $_POST['op'];
    $ed = $_POST['ed'];
    $num_of_ep = $_POST['num_of_ep'];
    $show_color = $_POST['show_color'];
    $id_part = $_POST['id_part']; // Use the hidden input to get the part ID
    
    // Call the method to edit the part in the database
    $instanceWatchList->editPart($part_name, $op, $ed, $num_of_ep, $id_part);

    // Redirect to index.php after update
    header("Location: index.php");
    exit();
}

// Deleting show
if (isset($_GET['deleteShow'])) {
    $id_show = $_GET['deleteShow'];

    // Fetch the show details first to get the image path
    $showToDelete = $instanceWatchList->getShow($id_show);
    
    // Delete the image file if it exists
    if (file_exists($showToDelete['img_dir'])) {
        unlink($showToDelete['img_dir']);  // Delete the image file
    }

    // Fetch all parts associated with the show
    $partsToDelete = $instanceWatchList->getShowParts($id_show);
    
    // Loop through each part and delete them
    foreach ($partsToDelete as $part) {
        // Optionally delete any images related to parts if they exist
        if (file_exists($part['img_dir'])) {
            unlink($part['img_dir']);  // Delete the part's image file if it exists
        }
        // Delete the part from the database
        $instanceWatchList->deletePart($part['id_part']);
    }

    // Now delete the show from the database
    $instanceWatchList->deleteShow($id_show);
    
    header("Location: index.php");
    exit();
}

// Deleting part
if (isset($_GET['deletePart'])) {
    $id_part = $_GET['deletePart'];
    $instanceWatchList->deletePart($id_part);
    header("Location: index.php");
    exit();
}

// Add part
if (isset($_POST['add'])) {
    // Get the part name from the form
    $part_name = $_POST['part_name'];
    $id_show = $_POST['id_show'];
    $op = $_POST['op'];
    $ed = $_POST['ed'];
    $ep_num = $_POST['ep_num'];

    $instanceWatchList->addPart($part_name,$id_show, $op, $ed, $ep_num);

    // Redirect to the index page (or wherever you want)
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





    <div class="container my-4 p-2 add-show-container">
    <form action="editShow.php?id=<?= $id_show ?>" id="myForm" method="post" enctype="multipart/form-data">
        <div class="row"> <!-- Row wraps the two columns -->

            <!-- Column for image (col-4) -->
            <div class="col-md-4 col-12 d-flex justify-content-center align-items-center">
                <div class="d-flex flex-column align-items-center">
                    <!-- Label and file input for selecting a new image -->
                    <div class="mb-2 text-center">
                        <label for="fileToUpload">Select new image</label>
                        <input type="file" name="fileToUpload" id="fileToUpload" accept="image/*" required onchange="previewImage()"> <br>
                    </div>
                    
                    <!-- Image preview section -->
                    <div class="m-1">
                        <!-- Preview the newly selected image -->
                        <img id="imgPreview" src="#" alt="Selected image" style="display:none; width: auto; max-height: 400px; margin-top:10px;" />

                        <!-- Show the current image if no new one is selected -->
                        <img id="currentImage" src="<?= htmlspecialchars($showToEdit['img_dir']); ?>" alt="Current Image" style="max-width: 400px; height: auto;">
                    </div>
                    <!-- Color display section -->
                        <div>
                            <label for="show_color">Color of show box </label>
                            <input type="color" id="show_color" name="show_color" value="<?= htmlspecialchars($showToEdit['show_color']); ?>" />
                        </div>
                </div>



                </div>
            <div class="col-md-8 col-12 align-items-center">
                <!-- Show title -->
                <div class="mb-3">
                    <label for="show_name" class="form-label">Name</label>
                    <input type="text" class="form-control" id="show_name" name="show_name" value="<?= htmlspecialchars($showToEdit['show_name']); ?>" required>
                </div>

                <!-- Radio buttons for show status -->
                <div class="mb-3 status-radiobuttons">
                    <label class="form-label">Show status</label>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" id="status_to_watch" name="show-status" value="1" <?= ($showToEdit['show_status'] == 1) ? 'checked' : ''; ?>>
                        <label class="form-check-label" for="status_to_watch">To watch</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" id="status_watching" name="show-status" value="2" <?= ($showToEdit['show_status'] == 2) ? 'checked' : ''; ?>>
                        <label class="form-check-label" for="status_watching">Watching</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" id="status_pause" name="show-status" value="3" <?= ($showToEdit['show_status'] == 3) ? 'checked' : ''; ?>>
                        <label class="form-check-label" for="status_pause">Pause</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" id="status_finished" name="show-status" value="4" <?= ($showToEdit['show_status'] == 4) ? 'checked' : ''; ?>>
                        <label class="form-check-label" for="status_finished">Finished</label>
                    </div>
                </div>

                <!-- Hidden input to trigger the 'editShow' logic in PHP -->
                <input type="hidden" name="editShow" value="1">
    </form>


    <?php 
                // Get parts for the current show using the show's id
    $selParts = $instanceWatchList->getShowParts($id_show );

                foreach ($selParts as $parts): ?>
                <div class="row mb-2">
                    <div class="col-sm-12 col-md-2 d-flex flex-column justify-content-center align-items-center">
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
                    <div class="col-sm-12 col-md-10">
                        <div class="row">
                            <?php
                            // Split the string 'num_of_ep' into an array using explode
                            $episode_numbers = explode(',', $parts['num_of_ep']); // e.g., "1,2,3,4,5"
                            
                            // Split the string 'watched_ep' into an array
                            $watched_ep_array = explode(',', $parts['watched_ep']); // e.g., "1,2"

                            // Loop through each episode number in the array
                            foreach ($episode_numbers as $episode_num) {
                                // Check if the current episode is in the watched episodes array
                                    echo '
                                    <div class="col-1 m-1 d-flex justify-content-center align-items-center episode-box-edit ">
                                        <!-- Content of the episode box, e.g., episode number -->
                                        <p class="m-0">' . $episode_num . '</p>
                                    </div>';

                            }
                            ?>
                        </div>
                    </div>
                </div>



<!-- Delete and Edit part -->
<div class="container mt-1 mb-3">
    <!-- Delete Button -->
    <a class="btn btn-danger me-3" href="editShow.php?deletePart=<?= $parts['id_part']; ?>&id=<?= $id_show; ?>" onclick="return confirm('Are you sure you want to delete this part?');">Delete</a>
    
    <!-- Edit button -->
        <button class="btn btn-primary " type="button" data-bs-toggle="collapse" data-bs-target="#addPart<?= $parts['id_part']; ?>" aria-expanded="false" aria-controls="addPart<?= $parts['id_part']; ?>">
            Edit part
        </button>
    
    <div class="collapse" id="addPart<?= $parts['id_part']; ?>">
        <div class="card card-body">
            <form action="editShow.php?editPart=<?= $parts['id_part']; ?>&id=<?= $id_show; ?>" method="post" enctype="multipart/form-data">
                <!-- Hidden field for show ID -->
                <input type="hidden" name="id_show" value="<?= htmlspecialchars($id_show); ?>">
                <input type="hidden" name="id_part" value="<?= htmlspecialchars($parts['id_part']); ?>"> <!-- Hidden input for part ID -->

                <!-- Part Name input -->
                <div class="container m-2">
                    <label>Part Name </label>
                    <input type="text" name="part_name" value="<?= htmlspecialchars($parts['part_name']); ?>" required> <br>
                </div>

                <!-- OP input -->
                <div class="container m-2">
                    <label>OP </label>
                    <input type="text" name="op" value="<?= htmlspecialchars($parts['op']); ?>"> <br>
                </div>

                <!-- ED input -->
                <div class="container m-2">
                    <label>ED </label>
                    <input type="text" name="ed" value="<?= htmlspecialchars($parts['ed']); ?>"> <br>
                </div>

                <!-- Number of Episodes input -->
                <div class="container m-2">
                    <label>Number of Episodes </label>
                    <?php 
                        // Split the num_of_ep string into an array
                        $episodeArray = explode(',', $parts['num_of_ep']);
                        
                        // Get the last episode number
                        $lastEpisodeNumber = end($episodeArray); // Retrieves the last element of the array
                    ?>
                    <input type="text" name="num_of_ep" value="<?= htmlspecialchars($lastEpisodeNumber); ?>" required> <br>
                </div>

                <!-- Submit Button -->
                <input class="btn btn-primary my-2" type="submit" name="editPart" value="Edit part" />
            </form>
        </div>
  


                        </div>
                        
                        </div>
                        
                <?php endforeach; ?>


                <!-- Add part -->
                <div class="container mt-4">
    <p>
        <button class="btn btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#addPart" aria-expanded="false" aria-controls="addPart">
            Add part
        </button>
    </p>
    <div class="collapse" id="addPart">
        <div class="card card-body">

        <form action="editShow.php?id=<?= $id_show ?>" method="post" enctype="multipart/form-data">
                
                <!-- Hidden field for show ID -->
                <input type="hidden" name="id_show" value="<?= htmlspecialchars($id_show); ?>">

                <!-- Part Name input -->
                <div class="container m-2">
                    <label>Part Name </label>
                    <input type="text" name="part_name" required> <br>
                </div>

                <!-- OP input -->
                <div class="container m-2">
                    <label>OP </label>
                    <input type="text" name="op"> <br>
                </div>

                <!-- ED input -->
                <div class="container m-2">
                    <label>ED </label>
                    <input type="text" name="ed"> <br>
                </div>

                <!-- Number of Episodes input -->
                <div class="container m-2">
                    <label>Number of Episodes </label>
                    <input type="number" name="ep_num" required> <br>
                </div>

                <!-- Submit Button -->
                <input class="btn btn-primary my-2" type="submit" name="add" value="Add part" />
            </form>
            
        </div>
    </div>
</div>
</div> 
</div> 


    <!-- External buttons for submitting and deleting -->
    <div class="d-flex mb-3">
        <!-- Submit Button -->
        <button class="btn btn-primary flex-fill me-2" type="button" id="externalButton">Edit show</button>

        <!-- Delete Button -->
        <a class="btn btn-danger flex-fill" href="editShow.php?deleteShow=<?= $id_show; ?>" onclick="return confirm('Are you sure you want to delete this show?');">Delete</a>
    </div>
</div>
        

<script>
    function previewImage() {
        const file = document.getElementById('fileToUpload').files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function (e) {
                const imgPreview = document.getElementById('imgPreview');
                const currentImage = document.getElementById('currentImage');

                // Set the new image's source to the file selected
                imgPreview.src = e.target.result;
                imgPreview.style.display = 'block';  // Show the new image
                currentImage.style.display = 'none'; // Hide the current image

                // Create a new image object
                const img = new Image();
                img.src = e.target.result;

                // When the image loads, calculate the average color
                img.onload = function() {
                    const avgColor = calculateAverageColor(img);
                    
                    // Set the average color to the color input
                    document.getElementById('show_color').value = avgColor;

                    // Assign the calculated color to the hidden input for average color
                    document.getElementById('colorCodeInput').value = avgColor;
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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script>
    document.getElementById("externalButton").addEventListener("click", function() {
        document.getElementById("myForm").submit();
    });
    </script>
</body>

</html>