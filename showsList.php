<?php
require_once('WatchList.php');
include('DbConnect.php');

// Create database connection
$conn = new DbConnect();
$dbConnection = $conn->connect();
$instanceWatchList = new WatchList($dbConnection);

$initialLimit = 18; 

// Check if a search query has been submitted via GET method
$show_name = filter_input(INPUT_GET, 'show_name', FILTER_SANITIZE_STRING);

if (!empty($show_name)) {
    // If a search query is provided, filter the show by the provided name
    $selshow = $instanceWatchList->filtershow($show_name);
} else {
    // If no search query, show all shows
    $selshow = $instanceWatchList->getWatchListByLimit(0, $initialLimit);
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

    <!-- Search bar -->
    <div class="container mt-5">
        <form class="d-flex align-items-center justify-content-center" method="get" action="showsList.php">
            <input class="form-control" name="show_name" type="text" placeholder="Show name" aria-label="Search show" style="width: 600px;">
            <button class="btn btn-primary" type="submit">Search</button>
        </form>
    </div>

    <!-- Show list container -->
    <div class="container px-5 py-2">
        <div class="row">
            <?php if (!empty($selshow)): ?>
                <?php foreach ($selshow as $show): ?>
                    <!-- Each show container inside a column -->
                    <div class="col-12 col-md-4 col-lg-2">
                        <div class="container m-2 show-container text-center" style="background-color: <?= htmlspecialchars($show['show_color']) ?: '#ffffff'; ?>;">
                            <a class="btn m-0 p-0" href="editShow.php?id=<?= htmlspecialchars($show['id_show']); ?>">
                                <!-- Show image -->
                                <img src="<?= htmlspecialchars($show['img_dir']); ?>" alt="<?= htmlspecialchars($show['show_name']); ?>" class="img-fluid rounded-img">
                                <!-- Show title -->
                                <p class="text-center m-1 show-title"><?= htmlspecialchars($show['show_name']); ?></p>
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="text-center">No shows found.</p>
            <?php endif; ?>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function () {
        let offset = 18; // Start after the initial 18 shows
        const limit = 12; // Load 12 shows per scroll
        let loading = false;

        async function loadMoreShows() {
            if (loading) return; // Prevent multiple requests at once
            loading = true;

            try {
                const response = await fetch(`loadMoreShows.php?offset=${offset}&limit=${limit}`);
                const shows = await response.json();

                if (shows.length > 0) {
                    const container = document.querySelector('.row');
                    shows.forEach(show => {
                        const showElement = document.createElement('div');
                        showElement.classList.add('col-12', 'col-md-4', 'col-lg-2');
                        showElement.innerHTML = `
                            <div class="container m-2 show-container text-center" style="background-color: ${show.show_color || '#ffffff'};">
                                <a class="btn m-0 p-0" href="editShow.php?id=${show.id_show}">
                                    <img src="${show.img_dir}" alt="${show.show_name}" class="img-fluid rounded-img">
                                    <p class="text-center m-1 show-title">${show.show_name}</p>
                                </a>
                            </div>
                        `;
                        container.appendChild(showElement);
                    });

                    // Increment the offset by 12 after each load
                    offset += limit;
                } else {
                    // No more shows to load; remove the scroll event listener
                    window.removeEventListener('scroll', handleScroll);
                }
            } catch (error) {
                console.error('Error loading shows:', error);
            } finally {
                loading = false; // Reset loading flag
            }
        }

        function handleScroll() {
            // Check if the user has scrolled near the bottom of the page
            if (window.innerHeight + window.scrollY >= document.body.offsetHeight - 500) {
                loadMoreShows();
            }
        }

        // Attach the scroll event listener
        window.addEventListener('scroll', handleScroll);
    });
</script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>
