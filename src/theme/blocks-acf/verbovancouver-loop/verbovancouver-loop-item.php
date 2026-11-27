<?php

    $heading = get_the_title();
    $id = get_the_ID();
    $permalink = get_the_permalink();

    ?>


    <div class="loop-item">
        <a id="loop-<?= $id ?>" href="<?= $permalink ?>">
            <div class="loop-item__image-area">
                <?php
                echo get_the_post_thumbnail($id, 'large', array('class' => 'loop-image') );
                ?>
            </div>
            <div class="loop-item__content">
                <div class="loop-item__heading">
                    <h3 class="loop-item__h3"><?= $heading ?></h3>
                </div>

            </div>
        </a>
    </div>