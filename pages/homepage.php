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
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $all_data[] = $row;
    }
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Entertainment Web App | Home</title>
    <link rel="icon" type="image/png" href="../assets/logo.svg">
    <link rel="stylesheet" href="../sass/App.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
</head>

<body class="home">
    <?php include("../layouts/header.php"); ?>

    <main>
        <section id="search">
            <img class="search__icon" src="../assets/icon-search.svg" alt="search icon">
            <input type="text" class="search__input" name="search_input" placeholder="Search for movies or TV series">
        </section>

        <section class="trending">
            <h2 class="trending__title">Trending</h2>

            <section class="swiper">
                <section class="swiper-wrapper">
                    <?php
                    foreach ($all_data as $data) {
                        //Check if this item is already bookmarked
                        $is_bookmarked = false;

                        if (isset($bookmarks) && in_array($data["id"], $bookmarks)) {
                            $is_bookmarked = true;
                        }

                        if ($data["is_trending"]) { ?>
                            <section class="swiper-slide trending-slide">
                                <img src="<?php echo "../" . $data['img_trend_sm']; ?>" alt="<?php echo $data['title']; ?>" class="trending-slide__image">

                                <button 
                                    class="trending-slide__bookmark-btn <?php echo $is_bookmarked ? "--active" : "" ?>" 
                                    id="trending-slide__bookmark-btn-<?php echo $data['id'] ?>" 
                                    data-id="<?php echo $data['id'] ?>"
                                >
                                    <section class="bookmark-icon"></section>
                                </button>

                                <section class="trending-slide__info">
                                    <p><?php echo $data['year']; ?></p>
                                    &#x2022;
                                    <section class="info-category">
                                        <img src="<?php echo $data['category'] == 'Movie' ? '../assets/icon-category-movie.svg' : '../assets/icon-category-tv.svg'; ?>">
                                        <p><?php echo $data['category']; ?></p>
                                    </section>
                                    &#x2022;
                                    <p><?php echo $data['rating']; ?></p>
                                    <h2 class="info-title"><?php echo $data['title']; ?></h2>
                                </section>
                            </section>
                    <?php }
                    } ?>

                </section>
            </section>
        </section>

        <section class="recommended main-content">
            <h2 class="main-content__title">Recommended for you</h2>

            <section class="main-content__content">
                <?php
                foreach ($all_data as $data) {
                    if (!$data["is_trending"]) {
                        include("../components/main_content_card.php");
                    }
                }
                ?>
            </section>
        </section>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script>
        // Initialise swiper
        const swiper = new Swiper('.swiper', {
            slidesPerView: 1.5,
            loop: true
        });

        // Handle trending bookmark click
        $(document).ready(function() {
            $('.trending-slide__bookmark-btn').click(function() {
                var dataID = parseInt($(this).data("id"));

                // AJAX request to update the user's bookmarks
                $.ajax({
                    type: 'POST',
                    url: '../components/AJAX_update_bookmarks.php',
                    data: {
                        dataID: dataID,
                        userID: <?php echo $userID; ?>
                    },
                    success: function(response) {
                        $('#trending-slide__bookmark-btn-' + dataID).toggleClass("--active");
                    },
                    error: function(error) {
                        console.error(error); // Log any errors
                    }
                });
            });
        });
    </script>
</body>

</html>