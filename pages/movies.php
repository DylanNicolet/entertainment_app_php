<?php
session_start();
include("../database.php");

// Get only movies with "category" set as "Movie" from the database
$sql = "SELECT * FROM data WHERE category = 'Movie'";
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
    <title>Entertainment Web App | Movies</title>
    <link rel="icon" type="image/png" href="../assets/logo.svg">
    <link rel="stylesheet" href="../sass/App.css">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
</head>

<body class="movies">
    <?php include("../layouts/header.php"); ?>

    <main>
        <section id="search">
            <img class="search__icon" src="../assets/icon-search.svg" alt="search icon">
            <input type="text" class="search__input" name="search_input" placeholder="Search for movies">
        </section>

        <section class="main-content">
            <h2 class="main-content__title">Movies</h2>

            <section class="main-content__content">
                <?php
                foreach ($all_data as $data) {
                    include("../components/main_content_card.php");
                }
                ?>
            </section>
        </section>
    </main>
</body>

</html>