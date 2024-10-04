<!-- < ?php
require_once('WatchList.php');
include('DbConnect.php');

$conn = new DbConnect();
$dbConnection = $conn->connect();
$instanceWatchList = new WatchList($dbConnection);
$watchlist = $instanceWatchList->getWatchList();

?> -->


<!-- HTML -->
</html>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="scss/custom.css" />
    <title>Watch list</title>
</head>


<body>
  <!-- Navbar -->
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="index.php">MyWatchList</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="addSeries.php">Add series</a>
                </li>
            </ul>
        </div>
    </div>
</nav>


    
  <!-- Series list container -->
<div class="container px-5 py-5">
    <div class="row">
        <div class="col-6">
            <!-- Shows containers -->
            <div class="container m-2 shows-container">
                <div class="row">
                    <div class="col-6 show-img">
                        <img src="img/tokyo_ghoul.jpg" alt="tokyo_ghoul" class="img-fluid rounded-img">
                    </div>
                    <div class="col-6 d-flex align-items-center justify-content-center show-title">
                        <p>Tokyo Ghoul</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-6">
            <!-- Shows containers -->
            <div class="container m-2 shows-container">
                <div class="row">
                    <div class="col-6 show-img">
                        <img src="img/tokyo_ghoul.jpg" alt="tokyo_ghoul" class="img-fluid rounded-img">
                    </div>
                    <div class="col-6 d-flex align-items-center justify-content-center show-title">
                        <p>Tokyo Ghoul</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>