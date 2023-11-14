<?php
session_start();
include("../database.php");

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

            <section>
                <?php
                foreach ($all_data as $data) {
                    if ($data["is_trending"]) {
                        echo "Title: " . $data["title"] . "<br>";
                    }
                }
                ?>
            </section>
        </section>

        <section class="recommended main-content">
            <h2 class="main-content__title">Recommended for you</h2>

            <section class="main-content__content">
                <?php
                foreach ($all_data as $data) {
                    if (!$data["is_trending"]) {
                        include("../components/homepage_card.php");
                    }
                }
                ?>
            </section>
        </section>
    </main>
</body>

</html>