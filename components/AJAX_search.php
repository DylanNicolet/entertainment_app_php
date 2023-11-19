<?php
    include("../database.php");

    // Retrieve data from the AJAX 
    $searchValue = $_POST['searchValue'];
    $page = $_POST['page'];
    $userID = $_POST['userID'];

    // Get search data from database
    $sql = "SELECT * FROM data WHERE title LIKE '%$searchValue%'";
    $result = $conn->query($sql);
    if (!$result) {
        die("Query failed: " . $conn->error);
    }

    $all_search_data = [];

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $all_search_data[] = $row;
        }
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