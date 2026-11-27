<?php

/**
 * ACF Block Template.
 *
 * @param   array $block The block settings and attributes.
 * @param   string $content The block inner HTML (empty).
 * @param   bool $is_preview True during AJAX preview.
 * @param   (int|string) $post_id The post ID this block is saved to.
 */

$fields = get_fields();

$classes = [ 'wp-block-vv-' . substr( $block['name'], 12 ) ];
if ( !empty( $block['className'] ) ) $classes[] = trim( $block['className'] );
if ( !empty( $block['color'] ) ) $classes[] = 'has-text-color has-' . $block['color'] . '-color';
if ( !empty( $block['backgroundColor'] ) ) $classes[] = 'has-background has-' . $block['backgroundColor'] . '-background-color';


$post_type = '';
$taxonomy_filters = get_object_taxonomies( $post_type ); // Can be set manually for more control
$post_per_page = 12;


function select2_terms ($taxonomy) {
    return get_terms(
        array(
            'taxonomy' => $taxonomy,
            'hide_empty' => true,
        )
    );
}


$page = htmlspecialchars($_GET["pg"]) ? : 1;

$tax_query = array();

foreach ($taxonomy_filters as $taxonomy) {
    if(check_terms($taxonomy) !== '') {
        $tax_query[] = array (
            'taxonomy' => $taxonomy,
            'field' => 'slug',
            'terms' => check_terms($taxonomy),
            'operator' => 'AND',
        );
    }
}


$all_args = array(
    'posts_per_page'    => $post_per_page,
    'offset'            => $post_per_page*($page-1),
    'post_type'		    => $post_type,
    'post_status'       => 'publish',
    'tax_query'         => $tax_query,
    'orderby'           => 'menu_order',
    'order'             => 'ASC',
);


$loop_query = new WP_Query($all_args);

?>
<div <?php if ( !empty( $block['anchor'] ) ) print 'id="' . esc_attr( $block['anchor'] ) . '" '; ?>class="<?php print implode( ' ', $classes ); ?>" data-post-type="<?= $post_type ?>">
    <?php if (count($taxonomy_filters)) : ?>
    <div class="filter-wrapper">
        <div class="filter-select">
            <?php foreach ($taxonomy_filters as $filter) :
                $labels = get_taxonomy_labels($filter);
                ?>

            <select class="select2-filter select2" data-taxonomy="<?= $filter ?>" data-placeholder="<?php $labels->singular_name ?>">
                <option></option>
                <option value="all">All</option>
                <?php foreach(select2_terms($filter) as $term) {
                    if ($term->slug == check_terms($filter)) {
                        echo '<option selected value="'.$term->slug.'">'.$term->name.'</option>';
                    } else {
                        echo '<option value="'.$term->slug.'">'.$term->name.'</option>';
                    }
                }
                ?>
            </select>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>
    <div class="loop-inner-grid">

        <?php

        if ($loop_query->have_posts()) : while ($loop_query->have_posts()) : $loop_query->the_post();

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
        <?php
        endwhile;
        else:
            echo '<p style="text-align: center;padding: 120px 60px;width: 100%;"><strong>There are no projects matching your search. Please try different filters.</strong></p>';

        endif;
        ?>
    </div>

    <div class="loop-pagination">
        <div class="page-back page-arrow wp-block-button__link has-primary-color has-text-color" style="visibility: hidden;">
            <span class="wp-block-button__arrow"><svg style="width: 15px;transform: rotate(90deg);" id="Layer_2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 21.57 28.38"><polygon class="cls-1" points="10.79 4.87 0 0 10.74 28.13 10.74 28.38 10.79 28.25 10.83 28.38 10.83 28.13 21.57 0 10.79 4.87" fill="#E4845D" /></svg></span>
        </div>
        <div class="pagenums">
            <?php
            $totalPosts = $loop_query->found_posts;
            $pages = $totalPosts / $post_per_page;

            for ($i=1; $i <= ceil($pages); $i++) {
                if ($page === $i) {
                    echo '<a href="#" data-page="'.$i.'" class="pagination-link has-small-heading-font-size current-page">'.$i.'</a>';
                } else {
                    echo '<a href="#" data-page="'.$i.'" class="pagination-link has-small-heading-font-size">'.$i.'</a>';
                }

            }
            $styleString = '';
            if ($pages <= 1) {
                $styleString = 'style="visibility: hidden;"';
            }
            ?>
        </div>
        <div class="page-next page-arrow wp-block-button__link has-primary-color has-text-color" <?=$styleString ?>>
            <span class="wp-block-button__arrow"><svg style="width: 15px;transform: rotate(-90deg);" id="Layer_2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 21.57 28.38"><polygon class="cls-1" points="10.79 4.87 0 0 10.74 28.13 10.74 28.38 10.79 28.25 10.83 28.38 10.83 28.13 21.57 0 10.79 4.87" fill="#E4845D" /></svg></span>
        </div>
    </div>
</div>



<?php wp_reset_query(); ?>
