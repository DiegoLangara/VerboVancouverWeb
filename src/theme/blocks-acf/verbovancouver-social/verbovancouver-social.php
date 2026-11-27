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
// Use <InnerBlocks /> in template to allow inner blocks
?>
<div <?php if ( !empty( $block['anchor'] ) ) print 'id="' . esc_attr( $block['anchor'] ) . '" '; ?>class="<?php print implode( ' ', $classes ); ?>">
    <div class="social-list">
        <?php
        $socials = get_field("social_media", "options");
        foreach ($socials as $social) :

            if ($social["social_network"] === 'fa-twitter') : 
            ?>
            <a class="social-item" href="<?= $social["profile_url"] ?>" target="_blank" rel="noopener noreferrer">
            <svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 512 512" style="fill: currentColor;"><!--! Font Awesome Free 6.4.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. --><path d="M389.2 48h70.6L305.6 224.2 487 464H345L233.7 318.6 106.5 464H35.8L200.7 275.5 26.8 48H172.4L272.9 180.9 389.2 48zM364.4 421.8h39.1L151.1 88h-42L364.4 421.8z"/></svg>
            </a>
            <?php else: ?>
            <a class="social-item" href="<?= $social["profile_url"] ?>" target="_blank" rel="noopener noreferrer">
                <i class="fab <?= $social["social_network"] ?>"></i>
            </a>
        <?php endif; endforeach;
        ?>
    </div>
</div>
