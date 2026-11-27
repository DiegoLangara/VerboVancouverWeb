<?php

/**
 * Verbo Block Template.
 *
 * @param   array $block The block settings and attributes.
 * @param   string $content The block inner HTML (empty).
 * @param   bool $is_preview True during AJAX preview.
 * @param   (int|string) $post_id The post ID this block is saved to.
 */

// Load values and assign defaults.
$careers = get_field('careers');

$classes = [ 'wp-block-vv-' . substr( $block['name'], 12 ) ];
if ( !empty( $block['className'] ) ) $classes[] = trim( $block['className'] );
if ( !empty( $block['color'] ) ) $classes[] = 'has-text-color has-' . $block['color'] . '-color';
if ( !empty( $block['backgroundColor'] ) ) $classes[] = 'has-background has-' . $block['backgroundColor'] . '-background-color';
// Use <InnerBlocks /> in template to allow inner blocks
?>
<div <?php if ( !empty( $block['anchor'] ) ) print 'id="' . esc_attr( $block['anchor'] ) . '" '; ?>class="<?php print implode( ' ', $classes ); ?>">
    <?php if($careers): ?>
        <?php foreach($careers as $career):

            $title = esc_html( $career['title'] );
            $id = random_int(999, 9999999);
            ?>

            <article class="accordion-item"  id="accordion-item-<?= $id ?>" data-id="<?= $id ?>"  >
                <header class="accordion-title">
                    <h3 class="accordion-heading"><?= $title ?></h3><span class="accordion-icon"></span>
                </header>
                <div class="accordion-body-wrapper">
                    <div class="accordion-body-inner">
                        <div class="career__item__date">
                            <?php if($career['post_date']): ?>
                                <div>
                                    <h4><sup>Date Posted</sup></h4>
                                    <p><?php echo $career['post_date']; ?></p>
                                </div>
                            <?php endif; ?>
                            <?php if($career['post_deadline']): ?>
                                <div>
                                    <h4><sup>Application Deadline</sup></h4>
                                    <p><?php echo $career['post_deadline']; ?></p>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="wp-block-columns">
                            <div class="wp-block-column">
                                <?php echo $career['content_left']; ?>
                            </div>
                            <div class="wp-block-column">
                                <?php echo $career['content_right']; ?>
                            </div>
                        </div>
                        <div class="wp-block-buttons">
                            <div class="wp-block-button aligncenter">
                                <a class="wp-block-button__link career-button" href="#!/apply-now" data-position="<?php echo esc_html($career['title']); ?>">Apply Now</a>
                            </div>
                        </div>
                    </div>
                </div>
            </article>
        <?php endforeach;?>
    <?php endif; ?>
</div>