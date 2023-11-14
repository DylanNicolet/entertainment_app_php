<div class="homepage-card">
    <img src="<?php echo "../" . $data['img_reg_sm']; ?>" alt="<?php echo $data['title']; ?>" class="homepage-card__image">

    <button class="homepage-card__bookmark-btn">
        <section class="bookmark-icon"></section>
    </button>

    <section class="homepage-card__info">
        <p><?php echo $data['year']; ?></p>
        &#x2022;
        <section class="info-category">
            <img src="<?php echo $data['category'] == 'Movie' ? '../assets/icon-category-movie.svg' : '../assets/icon-category-tv.svg'; ?>" alt="">
            <p><?php echo $data['category']; ?></p>
        </section>
        &#x2022;
        <p><?php echo $data['rating']; ?></p>
        <h2 class="info-title"><?php echo $data['title']; ?></h2>
    </section>
</div>