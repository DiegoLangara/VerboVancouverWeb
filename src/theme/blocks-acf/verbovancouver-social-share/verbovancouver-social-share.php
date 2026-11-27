<?php

/**
 * ACF Block Template.
 *
 * @param   array $block The block settings and attributes.
 * @param   string $content The block inner HTML (empty).
 * @param   bool $is_preview True during AJAX preview.
 * @param   (int|string) $post_id The post ID this block is saved to.
 */


 $classes = [ 'wp-block-vv-' . substr( $block['name'], 12 ) ];
if ( !empty( $block['className'] ) ) $classes[] = trim( $block['className'] );
if ( !empty( $block['color'] ) ) $classes[] = 'has-text-color has-' . $block['color'] . '-color';
if ( !empty( $block['backgroundColor'] ) ) $classes[] = 'has-background has-' . $block['backgroundColor'] . '-background-color';
// Use <InnerBlocks /> in template to allow inner blocks
?>
<div <?php if ( !empty( $block['anchor'] ) ) print 'id="' . esc_attr( $block['anchor'] ) . '" '; ?>class="<?php print implode( ' ', $classes ); ?>">



<div class="<?= $classes[0] ?>__inner">
    <span class="social-label">Share</span>
    <div class="social-list">
    <a class="social-item" href="https://www.linkedin.com/sharing/share-offsite/?url=<?= get_the_permalink() ?>" target="_blank" rel="noopener noreferrer">
            <i class="fab fa-linkedin-in"></i>
        </a>

        <a class="social-item" href="http://www.facebook.com/share.php?u=<?= get_the_permalink() ?>" target="_blank" rel="noopener noreferrer">
            <i class="fab fa-facebook-f"></i>
        </a>

       

        <a class="social-item" href="https://twitter.com/intent/tweet?text=<?= urlencode(get_the_title()) ?>&url=<?= get_the_permalink() ?>" target="_blank" rel="noopener noreferrer">
            <svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 512 512" fill="currentColor"><path d="M389.2 48h70.6L305.6 224.2 487 464H345L233.7 318.6 106.5 464H35.8L200.7 275.5 26.8 48H172.4L272.9 180.9 389.2 48zM364.4 421.8h39.1L151.1 88h-42L364.4 421.8z"/></svg>
        </a> 

        <a class="social-item" href="mailto:test@testt.com?subject=<?= urlencode(get_the_title()) ?>&body=<?= get_the_permalink() ?>" >
        <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 17 17" fill="none">
<path d="M5.19635 6.01838C5.41954 5.3161 5.80792 4.67762 6.32889 4.15652L9.13572 1.34942C9.99966 0.485403 11.1714 0 12.3932 0C13.615 0 14.7868 0.485403 15.6507 1.34942C16.5146 2.21345 17 3.38531 17 4.60722C17 5.82914 16.5146 7.001 15.6507 7.86502L13.544 9.97188C13.5855 9.3085 13.5195 8.64051 13.3475 7.99555L14.5651 6.77935C15.1414 6.20306 15.4651 5.42145 15.4651 4.60646C15.4651 3.79146 15.1414 3.00985 14.5651 2.43357C13.9889 1.85728 13.2074 1.53353 12.3924 1.53353C11.5775 1.53353 10.796 1.85728 10.2198 2.43357L7.41447 5.24066C7.03331 5.62182 6.75919 6.09658 6.61963 6.61726C6.48007 7.13793 6.48 7.68617 6.61942 8.20688C6.75885 8.72759 7.03285 9.20243 7.41391 9.58368C7.79496 9.96493 8.26964 10.2392 8.79024 10.3788L7.60026 11.5705C6.93544 11.2527 6.35653 10.7802 5.91212 10.1924C5.46771 9.60462 5.17078 8.91881 5.04623 8.19252C4.92168 7.46623 4.97316 6.72065 5.19635 6.01838Z" fill="currentColor"/>
<path d="M1.3493 9.13498L3.45595 7.02812C3.41383 7.6932 3.48022 8.35916 3.65249 9.00291L2.43487 10.2191C1.85864 10.7954 1.53491 11.577 1.53491 12.392C1.53491 13.207 1.85864 13.9886 2.43487 14.5649C3.0111 15.1412 3.79264 15.4649 4.60755 15.4649C5.42247 15.4649 6.20401 15.1412 6.78024 14.5649L9.58553 11.7578C9.96362 11.38 10.2366 10.9101 10.3774 10.3945C10.5183 9.87885 10.5221 9.3354 10.3886 8.81785C10.2551 8.30028 9.98878 7.82654 9.61607 7.44344C9.24337 7.06033 8.77715 6.78114 8.26349 6.63347C8.32914 6.52205 8.40859 6.41938 8.49996 6.32788L9.39974 5.42801C10.0646 5.74573 10.6435 6.2183 11.0879 6.80608C11.5323 7.39385 11.8292 8.07966 11.9538 8.80595C12.0783 9.53224 12.0268 10.2778 11.8036 10.9801C11.5805 11.6824 11.1921 12.3208 10.6711 12.8419L7.86427 15.6506C7.00033 16.5146 5.82858 17 4.60678 17C3.38499 17 2.21324 16.5146 1.3493 15.6506C0.485355 14.7866 0 13.6147 0 12.3928C0 11.1709 0.485355 9.999 1.3493 9.13498Z" fill="currentColor"/>
</svg>
        </a> 

    </div>
</div>
</div>
