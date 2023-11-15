<?php
include("../database.php");

// Retrieve data from the AJAX request
$newBookmark = intval($_POST['dataID']);
$userID = $_POST['userID'];


// Retrieve existing bookmarks for the user
$sqlSelect = "SELECT bookmarks FROM users WHERE id = ?";
$stmtSelect = $conn->prepare($sqlSelect);
$stmtSelect->bind_param("i", $userID);
$stmtSelect->execute();
$stmtSelect->bind_result($existingBookmarks);
$stmtSelect->fetch();
$stmtSelect->close();

// Decode existing bookmarks JSON string to array
$existingBookmarksArray = json_decode($existingBookmarks, true);

// Check if bookmark already exists
$keyToRemove = array_search($newBookmark, $existingBookmarksArray);
if ($keyToRemove !== false) {
    // Remove bookmark if it already exists
    array_splice($existingBookmarksArray, $keyToRemove, 1);
} else {
    // Append the new bookmark to the array
    $existingBookmarksArray[] = $newBookmark;
}

// Encode the updated array back to JSON
$updatedBookmarks = json_encode($existingBookmarksArray);

// Update the user's record in the database
$sqlUpdate = "UPDATE users SET bookmarks = ? WHERE id = ?";
$stmtUpdate = $conn->prepare($sqlUpdate);
$stmtUpdate->bind_param("si", $updatedBookmarks, $userID);
$stmtUpdate->execute();
$stmtUpdate->close();

// Close the database connection
$conn->close();
?>