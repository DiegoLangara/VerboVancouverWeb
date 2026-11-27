<?php

/**
 * ACF Block Template.
 *
 * @param   array $block The block settings and attributes.
 * @param   string $content The block inner HTML (empty).
 * @param   bool $is_preview True during AJAX preview.
 * @param   (int|string) $post_id The post ID this block is saved to.
 */

/* Create repeater field for 'items' with 'title' and 'content' in it, also a 'schema' true/false field in ACF */

$fields = get_fields();

$classes = [ 'wp-block-vv-' . substr( $block['name'], 12 ) ];
if ( !empty( $block['className'] ) ) $classes[] = trim( $block['className'] );
if ( !empty( $block['color'] ) ) $classes[] = 'has-text-color has-' . $block['color'] . '-color';
if ( !empty( $block['backgroundColor'] ) ) $classes[] = 'has-background has-' . $block['backgroundColor'] . '-background-color';
// Use <InnerBlocks /> in template to allow inner blocks

// Check if schema should be output (default to true if field doesn't exist)
$output_schema = isset($fields['schema']) ? $fields['schema'] : true;

// Initialize schema items array
$schemaitems = array();

?>
<div <?php if ( !empty( $block['anchor'] ) ) print 'id="' . esc_attr( $block['anchor'] ) . '" '; ?>class="<?php print implode( ' ', $classes ); ?>">

    <?php
    foreach($fields['items'] as $item) {
        $title = $item['title'];
        $content = $item['content'];
        $id = random_int(999, 9999999);
        ?>
        <article class="accordion-item"  id="accordion-item-<?= $id ?>" data-id="<?= $id ?>"  >
            <header class="accordion-title">
                <h3 class="accordion-heading"><?= $title ?></h3><span class="accordion-icon"></span>
            </header>
            <div class="accordion-body-wrapper">
                <div class="accordion-body-inner">
                    <?= $content ?>
                </div>
            </div>
        </article>

    <?php 
        // Build schema items if schema output is enabled
        if ($output_schema) {
            $schemaitems[] = [
                '@type' => 'Question',
                'name' => wp_strip_all_tags($title),
                'acceptedAnswer' => [
                    '@type' => 'Answer',
                    'text' => wp_strip_all_tags($content),
                ]
            ];
        }
    } // End foreach

    // Output schema if enabled and there are items
    if ($output_schema && !empty($schemaitems)) {
        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'FAQPage',
            "url" => get_the_permalink(),
            "@id" => get_the_permalink() . '#faq',
            'mainEntity' => $schemaitems
        ];
        
        print '<script type="application/ld+json">' . wp_json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . '</script>';
    }
    ?>

</div>