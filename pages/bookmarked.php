<?php
session_start();
include("../database.php");

// Get bookmarks and id of current user from database, used in "main_content_card.php"
$userID = $_SESSION["user_id"];
$sql_user_bookmarks = "SELECT bookmarks FROM users WHERE id = $userID";
$result = $conn->query($sql_user_bookmarks);
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $bookmarks = json_decode($row['bookmarks'], true);
}

// Get all data from database
$sql = "SELECT * FROM data";
$result = $conn->query($sql);
$all_data = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $all_data[] = $row;
    }
}

// Filter all_data for only bookmarked data
$only_bookmarked_data = [];
foreach ($all_data as $data) {
    $data_id = intval($data["id"]);
    
    // Use strict comparison to check if the value is found and not at index 0
    $is_this_bookmarked = array_search($data_id, $bookmarks, true);

    if ($is_this_bookmarked !== false) {
        $only_bookmarked_data[] = $data;
    }
}

// Encode the array as JSON
$only_bookmarked_data_json = json_encode($only_bookmarked_data);

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Entertainment Web App | Bookmarked</title>
    <link rel="icon" type="image/png" href="../assets/logo.svg">
    <link rel="stylesheet" href="../sass/App.css">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
</head>

<body class="bookmarked">
    <?php include("../layouts/header.php"); ?>

    <main>
        <section id="search">
            <img class="search__icon" src="../assets/icon-search.svg" alt="search icon">
            <input type="text" class="search__input" name="search_input" placeholder="Search for bookmarked shows">
        </section>

        <section class="main-content">
            <h2 class="main-content__title">Bookmarked Movies</h2>

            <section class="main-content__content">
                <?php
                foreach ($only_bookmarked_data as $data) {
                    include("../components/main_content_card.php");
                }
                ?>
            </section>
        </section>

        <section class="search main-content" style="display: none;"></section>
    </main>
</body>

<script>
    $(document).ready(function() {
        var onlyBookmarkedData = <?php echo $only_bookmarked_data_json; ?>;

        // Search functionality
        let searchInput = $(".search__input");

        searchInput.on("input", function(){
            if (searchInput.val().length > 2) {
                $(".main-content").hide();
                $(".search.main-content").show();

                $.ajax({
                    url: '../components/AJAX_search.php',
                    type: 'POST',
                    data: {
                        searchValue: searchInput.val(),
                        page: "bookmarked",
                        userID: <?php echo $userID ?>,
                        onlyBookmarkedData: JSON.stringify(onlyBookmarkedData)
                    },
                    success: function(response) {
                        $(".search.main-content").html(response);
                    },
                    error: function(error) {
                        console.error(error);
                    }
                });

            } else if (searchInput.val().length == 0){
                $(".main-content").show();
                $(".search.main-content").hide();
            }
        });

    });
</script>

</html>