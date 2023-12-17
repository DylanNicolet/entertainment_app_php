<?php
    include("../database.php");

    // Retrieve data from the AJAX 
    $searchValue = $_POST['searchValue'];
    $page = $_POST['page'];
    $userID = $_POST['userID'];
    $all_search_data = [];

    $sql_user_bookmarks = "SELECT bookmarks FROM users WHERE id = $userID";
    $resultBookmarks = $conn->query($sql_user_bookmarks);
    if ($resultBookmarks->num_rows > 0) {
        $row = $resultBookmarks->fetch_assoc();
        $bookmarks = json_decode($row['bookmarks'], true);
    }

    if ($page != 'bookmarked') {
        // Get search data from database
        $sql = "SELECT * FROM data WHERE title LIKE '%$searchValue%'"; // Homepage
        if ($page === 'movies') {
            $sql = "SELECT * FROM data WHERE title LIKE '%$searchValue%' AND category = 'Movie'";
        } else if ($page === 'tv_series') {
            $sql = "SELECT * FROM data WHERE title LIKE '%$searchValue%' AND category = 'TV Series'";
        }

        $result = $conn->query($sql);
        if (!$result) {
            die("Query failed: " . $conn->error);
        }

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $all_search_data[] = $row;
            }
        }
    } else {
        $rawOnlyBookmarkedData = $_POST['onlyBookmarkedData'];
        $onlyBookmarkedData = json_decode($rawOnlyBookmarkedData, true);

        $all_search_data = array_filter($onlyBookmarkedData, function ($item) use ($searchValue) {
            return stripos($item['title'], $searchValue) !== false;
        });
    }

    $result_length = count($all_search_data);
?>

    <!-- Response -->
    <h2 class="main-content__title">Found <?php echo $result_length ?> results for '<?php echo ucwords($searchValue) ?>'</h2>
    <section class="main-content__content">
        <?php
            foreach ($all_search_data as $data) {
                include("main_content_card.php");
            }
        ?>
    </section>

<?php
    $conn->close();
?>