<?php
require_once('WatchList.php');
include('DbConnect.php');

$conn = new DbConnect();
$dbConnection = $conn->connect();
$instanceWatchList = new WatchList($dbConnection);

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
    <!-- Head -->
    <?php include 'head.php'; ?>

</head>

<body>

    <!-- Navbar -->
    <?php include 'navbar.php'; ?>

    <!-- parts container -->
    <div class="container px-5 py-5">
        <div class="container m-2 text-center add-show-container">

            <form action="addPart.php" method="post" enctype="multipart/form-data">
                <!-- parts data -->

                <div class="container m-2">
                    <label>Part Name </label>
                    <input type="text" name="part_name" required> <br>
                </div>

                <div class="container m-2">
                    <label>Show Name </label>
                    <input type="text" name="show_name" id="show_name" autocomplete="off" required>
                    <input type="hidden" name="id_show" id="id_show"> <!-- Hidden field to store the selected show's ID -->
                    <div id="autocomplete-results" class="autocomplete-suggestions"></div>
                </div>

                <div class="container m-2">
                    <label>OP </label>
                    <input type="text" name="op" required> <br>
                </div>

                <div class="container m-2">
                    <label>ED </label>
                    <input type="text" name="ed" required> <br>
                </div>

                <div class="container m-2">
                    <label>Number of Episodes </label>
                    <input type="number" name="ep_num" required> <br>
                </div>

                <!-- Submit Button -->
                <input class="btn btn-primary my-2" type="submit" name="add" value="Add part" />
            </form>
        </div>
    </div>

    <script>
        document.getElementById('show_name').addEventListener('input', function () {
            let query = this.value;
            if (query.length > 1) { // Fetch suggestions only if user types more than 1 character
                fetch('getShows.php?query=' + query)
                    .then(response => response.json())
                    .then(data => {
                        let autocompleteResults = document.getElementById('autocomplete-results');
                        autocompleteResults.innerHTML = ''; // Clear previous suggestions

                        // Display the matching shows
                        data.forEach(show => {
                            let suggestion = document.createElement('div');
                            suggestion.classList.add('autocomplete-suggestion');
                            suggestion.textContent = show.show_name;

                            // Store show ID and name
                            suggestion.addEventListener('click', function () {
                                document.getElementById('show_name').value = show.show_name; // Set show name
                                document.getElementById('id_show').value = show.id_show; // Set hidden show ID
                                autocompleteResults.innerHTML = ''; // Clear suggestions
                            });

                            autocompleteResults.appendChild(suggestion);
                        });
                    });
            }
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
</body>

</html>
