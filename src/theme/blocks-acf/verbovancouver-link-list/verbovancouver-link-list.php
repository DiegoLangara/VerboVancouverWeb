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
    <?php
    foreach ($fields['list_items'] as $item) : ?>

        <a class="link-list-item" target="<?= $item['link']['target'] ?>" href="<?= $item['link']['url'] ?>">
            <div><?php echo wp_get_attachment_image($item["icon"]); ?><span class="link-list-label"><?= $item['label'] ?></span> </div><span class="link-list-cta"> <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32.79 28.13"><path fill="currentColor" d="m18.67 0-.71.71 12.91 12.86H0v1h30.87L17.96 27.42l.71.71 14.12-14.06L18.67 0z"/></svg></svg></span>
        </a>

    <?php endforeach; ?>
</div>
