<?php
$db_server = "localhost";
$db_user = "root";
$db_pass = "";
$db_name = "entertainement_web_app";
$conn = "";

try {
    $conn = mysqli_connect($db_server, $db_user, $db_pass, $db_name);
} catch (mysqli_sql_exception) {
    echo "Could not connect to database";
}