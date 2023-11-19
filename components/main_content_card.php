<?php
    $is_bookmarked = false;
    $data_id = intval($data["id"]);

    //Check if this item is already bookmarked
    if (isset($bookmarks) && in_array($data_id, $bookmarks)) {
        $is_bookmarked = true;
    }
?>

<div class="main-content-card" id="main-content-card_<?php echo $data['id']; ?>">
    <img src="<?php echo "../" . $data['img_reg_sm']; ?>" alt="<?php echo $data['title']; ?>" class="main-content-card__image">

    <button class="main-content-card__bookmark-btn <?php echo $is_bookmarked ? "--active" : "" ?>" id="bookmark-btn_<?php echo $data['id']; ?>">
        <section class="bookmark-icon"></section>
    </button>

    <section class="main-content-card__info">
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
</div>

<script>
    $(document).ready(function() {
        // Handle bookmark click
        $('#bookmark-btn_<?php echo $data['id']; ?>').click(function() {
            // AJAX request to update the user's bookmarks
            $.ajax({
                type: 'POST',
                url: '../components/AJAX_update_bookmarks.php',
                data: {
                    dataID: <?php echo $data_id; ?>,
                    userID: <?php echo $userID; ?>
                },
                success: function(response) {
                    $('#bookmark-btn_<?php echo $data['id']; ?>').toggleClass('--active');

                    // Remove the whole card only on page Bookmarked
                    if($('body.bookmarked').length){
                        $('#main-content-card_<?php echo $data['id']; ?>').remove();
                    }
                },
                error: function(error) {
                    console.error(error); // Log any errors
                }
            });
        });
    });
</script>