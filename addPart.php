<?php
require_once('WatchList.php');
include('DbConnect.php');

// Create the database connection
$conn = new DbConnect();
$dbConnection = $conn->connect();
$instanceWatchList = new WatchList($dbConnection);

if (isset($_POST['add'])) {
    // Get the part name from the form
    $part_name = $_POST['part_name'];
    $id_show = $_POST['id_show'];
    $op = $_POST['op'];
    $ed = $_POST['ed'];

    $instanceWatchList->addPart($part_name,$id_show, $op, $ed);

    // Redirect to the index page (or wherever you want)
    header("Location: index.php");
    exit();
}
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


    
  <!-- parts container -->
    <div class="container px-5 py-5">
        <div class="container m-2 text-center add-shows-container">

    <form action="addPart.php" method="post" enctype="multipart/form-data">
    <!-- parts data -->

    <div class="container m-2">
        <label>Name </label>
        <input type="text" name="part_name" required> <br>
    </div>

    <div class="container m-2">
        <label>Show name </label>
        <input type="number" name="id_show" required> <br>
    </div>

    <div class="container m-2">
        <label>OP </label>
        <input type="text" name="op" required> <br>
    </div>

    <div class="container m-2">
        <label>ED </label>
        <input type="text" name="ed" required> <br>
    </div>



    <!-- Submit Button -->
    <input class="btn btn-primary my-2" type="submit" name="add" value="Add part" />

    </form>
        </div>
    </div>

    


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>