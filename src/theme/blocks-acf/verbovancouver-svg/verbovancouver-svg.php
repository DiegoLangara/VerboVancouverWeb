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

$svg  = $fields['svg_code'] ?? '';
$link = is_array( $fields['link'] ?? null ) ? $fields['link'] : [];

$link_url    = ! empty( $link['url'] )    ? $link['url']    : '';
$link_target = ! empty( $link['target'] ) ? $link['target'] : '_self';
$link_title  = ! empty( $link['title'] )  ? $link['title']  : '';

// Fallback accessible text if no title was provided in ACF
$sr_text = $link_title !== '' ? $link_title : __( 'Learn more', 'your-textdomain' );

// If target is _blank, we should note it for screen readers
$is_blank    = ( '_blank' === $link_target );
$sr_blank    = $is_blank ? ' <span class="screen-reader-text">' . esc_html__( '(opens in a new tab)', 'your-textdomain' ) . '</span>' : '';
?>
<div <?php if ( !empty( $block['anchor'] ) ) print 'id="' . esc_attr( $block['anchor'] ) . '" '; ?>class="<?php print implode( ' ', $classes ); ?>">
    <?php if ( $link_url ) : ?>
        <a href="<?php echo esc_url( $link_url ); ?>"
           target="<?php echo esc_attr( $link_target ); ?>"
           <?php if ( $is_blank ) : ?>rel="noopener noreferrer"<?php endif; ?>
           <?php if ( $link_title !== '' ) : ?>aria-label="<?php echo esc_attr( $link_title ); ?>"<?php endif; ?>>
            <?php
            // SVG is decorative when paired with the SR text below
            echo $svg;
            ?>
            <span class="screen-reader-text"><?php echo esc_html( $sr_text ); ?></span><?php echo $sr_blank; ?>
        </a>
    <?php else : ?>
        <?php echo $svg; ?>
    <?php endif; ?>
</div>