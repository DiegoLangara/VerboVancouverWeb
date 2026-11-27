<?php

defined( 'ABSPATH' ) || die( 'Direct file access is prohibited.' );

if ( ! isset( $content_width ) ) $content_width = 1920;

if ( !defined( 'VV_FONT_URL' ) ) define( 'VV_FONT_URL', 'https://fonts.googleapis.com/css2?family=Archivo:wdth,wght@62..125,400..800&family=Manrope:wght@400;500;600;700&display=swap' );
if ( !defined( 'VV_GMAPS_KEY' ) ) define( 'VV_GMAPS_KEY', '' );

/**
 * URL de una imagen incluida en el tema (assets/images), para valores por defecto de los bloques.
 */
function vv_theme_image( $file ) {
  return get_template_directory_uri() . '/assets/images/' . ltrim( $file, '/' );
}

class VerboTheme {

  public static function run() {

    // Init
    add_action( 'after_switch_theme', [ __CLASS__, 'after_switch_theme' ] );
    add_action( 'after_setup_theme', [ __CLASS__, 'after_setup_theme' ] );
    add_action( 'init', [ __CLASS__, 'init' ] );
    add_action( 'acf/init', [ __CLASS__, 'acf_init' ] );
    add_action( 'block_categories', [ __CLASS__, 'custom_block_categories' ] );
    add_action('acf/render_field_settings/type=text', [ __CLASS__, 'add_readonly_and_disabled_to_text_field']);
    add_action('acf/render_field_settings/type=number', [ __CLASS__, 'add_readonly_and_disabled_to_text_field']);

    // Admin
    add_action( 'update_option_page_for_posts', [ __CLASS__, 'update_option_page_for_posts' ], 10, 2 );
    add_action( 'enqueue_block_editor_assets', [ __CLASS__, 'enqueue_block_editor_assets' ] );
    add_action( 'admin_bar_init', [ __CLASS__, 'admin_bar_init' ] );

    add_filter( 'admin_body_class', [ __CLASS__, 'page_admin_body_class' ] );
    add_filter( 'login_message', [ __CLASS__, 'custom_login_message' ] );
    add_filter( 'upload_mimes', [ __CLASS__, 'upload_mimes' ] );
    add_filter( 'display_post_states', [ __CLASS__, 'display_post_states' ], 10, 2 );
    add_filter( 'block_editor_settings_all', [ __CLASS__, 'block_editor_settings_all' ], 10, 2 );

    // Content
    add_action( 'wp_enqueue_scripts', [ __CLASS__, 'wp_enqueue_scripts' ] );

    add_filter( 'block_type_metadata_settings', [ __CLASS__, 'block_type_metadata_settings' ], 10, 2);

    add_filter( 'render_block', [ __CLASS__, 'render_block' ], 10, 2);
    add_filter( 'excerpt_length', [ __CLASS__, 'excerpt_length' ], 10 );
    add_filter( 'excerpt_more', [ __CLASS__, 'excerpt_more' ], 10 );
    add_filter( 'the_content', [ __CLASS__, 'the_content' ], 1000 );
    add_filter( 'wpseo_separator_options', [ __CLASS__, 'wpseo_separator_options' ], 10 );

    // Formidable
    add_action( 'frm_field_input_html', [ __CLASS__, 'frm_field_input_html' ], 10, 2 );

    add_filter( 'frm_field_div_classes', [ __CLASS__, 'frm_field_div_classes' ], 10, 2 );

    // REST
    add_action( 'rest_api_init', [ __CLASS__, 'rest_api_init' ] );

    // Shortcodes
    add_shortcode( 'year', function( $atts ) {
      $atts = shortcode_atts( [
        'unixtime' => current_time( 'timestamp' ),
      ], $atts, 'verbovancouver' );

      return date( 'Y', $atts['unixtime'] );
    } );

    // Add full size to srcset for images without full size
    add_filter( 'wp_calculate_image_srcset', function( $sources, $size_array, $image_src, $image_meta, $attachment_id ) {
    if ( ! isset( $image_meta['sizes']['full'] ) && ! empty( $image_meta['file'] ) ) {
        $upload_dir = wp_get_upload_dir();
        $sources[ $image_meta['width'] ] = [
            'url'        => $upload_dir['baseurl'] . '/' . $image_meta['file'],
            'descriptor' => 'w',
            'value'      => $image_meta['width'],
        ];
    }
    return $sources;
}, 10, 5 );

    add_shortcode('acf_field', function ($atts) {
    $atts = shortcode_atts(array(
        'field' => '',
        'sub_field' => '', // New parameter for array sub-fields
        'post_id' => get_the_ID()
    ), $atts);
    
    if (empty($atts['field'])) return '';
    
    $field_value = get_field($atts['field'], $atts['post_id']);
    
    // If it's an array and sub_field is specified
    if (is_array($field_value) && !empty($atts['sub_field'])) {
        return $field_value[$atts['sub_field']] ?? '';
    }
    
    return $field_value;
});

       //then add shortcode like this: [acf_field field="location"]
       //if its an arran use this [acf_field field="link" sub_field="url"]

       
  }

  // Init
  public static function after_switch_theme() {

    update_option( 'image_default_link_type', 'none' );
  }
  
  public static function after_setup_theme() {

    set_post_thumbnail_size( 2000, 2000, false );
  
    remove_theme_support( 'automatic-feed-links' );
    remove_theme_support( 'post-thumbnails' );
    remove_theme_support( 'post-formats' );
    remove_theme_support( 'custom-background' );
    remove_theme_support( 'custom-header' );
    remove_theme_support( 'menus' );
    remove_theme_support( 'widgets' );

    add_theme_support( 'wp-block-styles' );
    add_theme_support( 'customize-selective-refresh-widgets' );

    add_theme_support( 'woocommerce' );
    add_theme_support( 'wc-product-gallery-zoom' );
    add_theme_support( 'wc-product-gallery-lightbox' );
    add_theme_support( 'wc-product-gallery-slider' );
  
    add_theme_support( 'title-tag' );
    add_theme_support( 'html5', [
      'search-form', 
      'comment-form',	
      'comment-list',	
      'gallery', 
      'caption',
    ] );
    
  }

  public static function init() {

    add_theme_support( 'post-thumbnails', [ 
      'post',
      'page',
      'product',
    ] );
    add_post_type_support( 'page', 'excerpt' );
    add_post_type_support( 'post', 'excerpt' );
  
    // Remove wp emoji support.
    remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
    remove_action( 'wp_print_styles', 'print_emoji_styles' );
    remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
    remove_action( 'admin_print_styles', 'print_emoji_styles' );
    
    add_editor_style( 'assets/css/verbovancouver-editor.css' );
  }

  public static function acf_init() {

     acf_update_setting('google_api_key', vv_gmaps_key() );

    if( function_exists('acf_add_options_page') ) {

      acf_add_options_page( [
        'page_title' => 'Site Settings',
        'menu_title' => 'Customize',
        'menu_slug' => 'verbovancouver-site-settings',
        'capability' => 'manage_options',
        'parent_slug' => 'options-general.php',
        'icon_url' => 'dashicons-editor-insertmore',
        'autoload' => true,
        'update_button' => __('Update', 'acf'),
        'updated_message' => __('Updated', 'acf'),
      ] );

    }
    if( function_exists('acf_add_local_field_group') ):


    acf_add_local_field_group(array(
      'key' => 'group_640fafcd2f06d',
      'title' => '404 Page',
      'fields' => array(
        array(
          'key' => 'field_640fafcdb3410',
          'label' => 'Heading',
          'name' => '404_heading',
          'aria-label' => '',
          'type' => 'text',
          'instructions' => '',
          'required' => 0,
          'conditional_logic' => 0,
          'wrapper' => array(
            'width' => '',
            'class' => '',
            'id' => '',
          ),
          'default_value' => 'Page not found',
          'maxlength' => '',
          'placeholder' => '',
          'prepend' => '',
          'append' => '',
        ),
        array(
          'key' => 'field_640fafe3b3411',
          'label' => 'Message',
          'name' => '404_message',
          'aria-label' => '',
          'type' => 'wysiwyg',
          'instructions' => '',
          'required' => 0,
          'conditional_logic' => 0,
          'wrapper' => array(
            'width' => '',
            'class' => '',
            'id' => '',
          ),
          'default_value' => 'It looks like the page you are looking for either doesn\'t exist or has been moved. Try heading back to our <a href="/">homepage</a>.',
          'tabs' => 'all',
          'toolbar' => 'full',
          'media_upload' => 1,
          'delay' => 0,
        ),
      ),
      'location' => array(
        array(
          array(
            'param' => 'options_page',
            'operator' => '==',
            'value' => 'verbovancouver-site-settings',
          ),
        ),
      ),
      'menu_order' => 0,
      'position' => 'normal',
      'style' => 'default',
      'label_placement' => 'top',
      'instruction_placement' => 'label',
      'hide_on_screen' => '',
      'active' => true,
      'description' => '',
      'show_in_rest' => 0,
    ));

    acf_add_local_field_group(array(
      'key' => 'group_vv_google_maps',
      'title' => 'Google Maps',
      'fields' => array(
        array(
          'key' => 'field_vv_gmaps_api_key',
          'label' => 'Google Maps API Key',
          'name' => 'gmaps_api_key',
          'type' => 'text',
          'instructions' => 'Leave blank to fall back to the key defined in wp-config.php (VV_GMAPS_KEY).',
          'required' => 0,
          'conditional_logic' => 0,
          'wrapper' => array( 'width' => '', 'class' => '', 'id' => '' ),
          'default_value' => '',
          'maxlength' => '',
          'placeholder' => '',
          'prepend' => '',
          'append' => '',
        ),
      ),
      'location' => array(
        array(
          array(
            'param' => 'options_page',
            'operator' => '==',
            'value' => 'verbovancouver-site-settings',
          ),
        ),
      ),
      'menu_order' => 0,
      'position' => 'normal',
      'style' => 'default',
      'label_placement' => 'top',
      'instruction_placement' => 'label',
      'hide_on_screen' => '',
      'active' => true,
      'description' => '',
      'show_in_rest' => 0,
    ));
      
      endif;		


  // Additional custom blocks
  $custom_blocks = array('accordion', 'hero-luz', 'pilares', 'visita', 'predica', 'comunidad', 'pasos');

  foreach ($custom_blocks as $block) {
    // Register the block
    register_block_type( __DIR__ . '/blocks-acf/verbovancouver-' . $block );

    // Register the script
    wp_register_script(
        'verbovancouver-' . $block . '-js',
        get_template_directory_uri() . '/blocks-acf/verbovancouver-' . $block . '/verbovancouver-' . $block . '.js',
        ['jquery'], // Dependencies
        Verbo::theme_version(),
        true // Load in footer
    );

    // Enqueue the script
    wp_enqueue_script('verbovancouver-' . $block . '-js');

    // Register and enqueue the CSS
    wp_register_style(
        'verbovancouver-' . $block . '-css',
        get_template_directory_uri() . '/blocks-acf/verbovancouver-' . $block . '/verbovancouver-' . $block . '.css',
        [],
        Verbo::theme_version()
    );
    wp_enqueue_style('verbovancouver-' . $block . '-css');

    // Include custom fields
    require_once(__DIR__ . '/blocks-acf/verbovancouver-' . $block . '/verbovancouver-fields.php');
}


    


  }

  public static function add_readonly_and_disabled_to_text_field($field) {
    acf_render_field_setting( $field, array(
        'label'      => __('Read Only?','acf'),
        'instructions'  => '',
        'type'      => 'radio',
        'name'      => 'readonly',
        'choices'    => array(
            0        => __("No",'acf'),
            1        => __("Yes",'acf'),
        ),
        'layout'  =>  'horizontal',
    ));
    acf_render_field_setting( $field, array(
        'label'      => __('Disabled?','acf'),
        'instructions'  => '',
        'type'      => 'radio',
        'name'      => 'disabled',
        'choices'    => array(
            0        => __("No",'acf'),
            1        => __("Yes",'acf'),
        ),
        'layout'  =>  'horizontal',
    ));
}

  // Admin
  public static function update_option_page_for_posts( $old_value, $new_value ) {

    $post = $new_value ? get_post( $new_value ) : null;
    if ( $post && empty( $post->post_content ) ) {
      wp_update_post( [
        'ID' => $post->ID,
        'post_content' => ' ',
      ] );
    }
  }

  public static function enqueue_block_editor_assets() {
    
    wp_enqueue_style( 'verbovancouver-fonts', VV_FONT_URL, [], null );
    wp_enqueue_style( 'verbovancouver-fonts-local', get_theme_file_uri( 'assets/css/verbovancouver-fonts.css' ), [ 'verbovancouver-fonts' ], Verbo::theme_version() );
    wp_enqueue_style( 'verbovancouver-base-style', get_theme_file_uri( 'assets/css/verbovancouver-editor.css' ), [ 'verbovancouver-fonts-local' ], Verbo::theme_version() );
  
    wp_enqueue_script( 'verbovancouver-block-editor', get_theme_file_uri( 'assets/js/verbovancouver-editor.js' ), [ 'wp-blocks', 'wp-dom', 'wp-edit-post', 'wp-editor' ], Verbo::theme_version());
  }

  public static function admin_bar_init() {

    remove_action( 'wp_head', '_admin_bar_bump_cb' );
  }

  public static function upload_mimes( $mimes ) {

    $mimes['svg'] = 'image/svg+xml';
    return $mimes;
  }

  public static function display_post_states( $post_states, $post ) {

    global $wp_rewrite;

    if ( 0 === (int)$post->post_parent && $post->post_name === $wp_rewrite->search_base ) {
      $post_states['page_for_search_results'] = _x( 'Search Results Page', 'page label' );
    }

    return $post_states;
  }

  public static function block_editor_settings_all( $editor_settings, $block_editor_context ) {

    return $editor_settings;
  }

  public static function page_admin_body_class($classes) {
    global $post;
    $classes .= ' page-id-'.$post->ID.' ';

    return $classes;
}

  // Content
  public static function wp_enqueue_scripts() {
    
    wp_enqueue_script('jquery');
    wp_enqueue_script('jquery-ui-core');

   wp_enqueue_script( 'verbovancouver-libs', get_template_directory_uri() . '/assets/js/verbovancouver-libs.js', [ 'jquery' ], Verbo::theme_version(), true );
    wp_enqueue_script( 'verbovancouver-splittext', 'https://cdn.jsdelivr.net/npm/split-type@0.3.0/umd/index.min.js', [ 'jquery' ], Verbo::theme_version(), true );
    wp_enqueue_script( 'select2', 'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js', [ 'jquery' ], Verbo::theme_version(), true );
    wp_enqueue_script( 'verbovancouver', get_template_directory_uri() . '/assets/js/verbovancouver.js', [ 'jquery', 'verbovancouver-libs', 'verbovancouver-splittext' ], Verbo::theme_version(), true );
    
    wp_enqueue_style( 'verbovancouver-fonts', VV_FONT_URL, [], null );
    wp_enqueue_style( 'verbovancouver-fonts-local', get_template_directory_uri() . '/assets/css/verbovancouver-fonts.css', [ 'verbovancouver-fonts' ], Verbo::theme_version() );
    wp_enqueue_style( 'select2', 'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css', Verbo::theme_version() );
    wp_enqueue_style( 'verbovancouver', get_template_directory_uri() . '/assets/css/verbovancouver.css', [], Verbo::theme_version() );
  }

  public static function block_type_metadata_settings( $settings, $metadata ) {

    if ( 'core/post-content' === $metadata['name'] ) {

      if ( 'render_block_core_post_content' === $settings['render_callback'] ) {
        $settings['render_callback'] = [ __CLASS__, 'render_block_core_post_content'];
      }
    }

    return $settings;
  }

  public static function render_block( $block_content, $block ) {

    if ( !is_admin() ) {

      if ( 'core/navigation' === $block['blockName'] ) {
        $block_content = str_replace('<svg width="24" height="24" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M5 5v1.5h14V5H5zm0 7.8h14v-1.5H5v1.5zM5 19h14v-1.5H5V19z" /></svg>', '<b></b><span class="screen-reader-text">Menu</span>', $block_content);
      }

      if ( 'core/cover' === $block['blockName'] ) {
        $block_content = str_replace(' playsinline', '', $block_content);
        $block_content = str_replace(' autoplay', ' autoplay playsinline', $block_content);
      }

      // Linkedin icon swap
       $custom_social_icons = [
        'instagram' => '<svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="none" aria-hidden="true" focusable="false"><path fill="currentColor" d="M21.64 15.339c-.083 1.736-.48 3.274-1.753 4.548-1.274 1.273-2.812 1.67-4.548 1.753-1.786.1-7.161.1-8.964 0-1.736-.083-3.274-.48-4.548-1.753C.554 18.613.157 17.075.074 15.339c-.099-1.786-.099-7.161 0-8.964.083-1.736.48-3.274 1.753-4.548C3.101.554 4.64.157 6.375.074c1.786-.099 7.161-.099 8.964 0 1.736.083 3.274.48 4.548 1.753 1.273 1.274 1.67 2.812 1.753 4.548.1 1.786.1 7.161 0 8.947zm-1.886-4.482c0-1.587.133-4.978-.43-6.4a3.63 3.63 0 0 0-2.067-2.067c-1.422-.563-4.829-.43-6.4-.43s-4.978-.133-6.4.43A3.63 3.63 0 0 0 2.39 4.457c-.563 1.422-.43 4.829-.43 6.4s-.133 4.978.43 6.4a3.63 3.63 0 0 0 2.067 2.068c1.422.562 4.829.43 6.4.43s4.978.132 6.4-.43a3.63 3.63 0 0 0 2.068-2.068c.562-1.422.43-4.829.43-6.4m-3.34 0a5.565 5.565 0 0 1-5.573 5.573 5.555 5.555 0 0 1-5.574-5.573 5.555 5.555 0 0 1 5.574-5.573 5.555 5.555 0 0 1 5.573 5.573m-1.952 0a3.62 3.62 0 0 0-3.621-3.622 3.62 3.62 0 0 0-3.622 3.622 3.62 3.62 0 0 0 3.622 3.622 3.62 3.62 0 0 0 3.621-3.622m2.183-7.095A1.31 1.31 0 0 0 15.34 5.07c0 .728.579 1.306 1.306 1.306s1.307-.578 1.307-1.306-.579-1.307-1.307-1.307"/></svg>',
        'linkedin'  => '<svg xmlns="http://www.w3.org/2000/svg" width="23" height="22" fill="none" aria-hidden="true" focusable="false"><path fill="currentColor" d="M5.49 2.616C5.49 1.18 4.25 0 2.745 0S0 1.18 0 2.616C0 4.05 1.215 5.232 2.745 5.232S5.49 4.074 5.49 2.616m-.365 19.098V7.223H.39v14.491zm17.708-7.94c0-3.912-.899-6.922-5.66-6.922-2.307 0-3.838 1.204-4.47 2.338h-.072V7.2H8.089V21.69h4.736v-7.176c0-1.899.389-3.727 2.842-3.727s2.454 2.153 2.454 3.842v7.061h4.736v-7.94z"/></svg>',
        'facebook'  => '<svg xmlns="http://www.w3.org/2000/svg" width="12" height="22" fill="none" aria-hidden="true" focusable="false"><path fill="currentColor" d="M9.744 3.628h1.685V.267S9.899 0 8.439 0C5.386 0 3.391 1.89 3.391 5.314v3.009H0v3.948h3.392v9.443h4.175v-9.443h3.113l.592-3.948H7.567V5.76c0-1.08.518-2.133 2.177-2.133"/></svg>',
    ];

    // 1) Preferred: use the block attribute set by Gutenberg.
    $service = $block['attrs']['service'] ?? '';

    // 2) Fallback: sniff the class name (e.g. wp-social-link-instagram).
    if ( ! $service && preg_match( '/wp-social-link-([a-z0-9_-]+)/i', $block_content, $m ) ) {
        $service = strtolower( $m[1] );
    }

    // 3) Replace whatever SVG is inside this block with our custom icon.
    if ( $service && isset( $custom_social_icons[ $service ] ) ) {
        $block_content = preg_replace(
            '/<svg\b[^>]*>.*?<\/svg>/is',
            $custom_social_icons[ $service ],
            $block_content,
            1
        );
    }


      if ( 'core/button' === $block['blockName'] ) {
        $block_content = preg_replace('/(<a [^>]+>)/', '${1}<span class="wp-block-button__text">', $block_content);
        $block_content = str_replace('</a>', '</span></a>', $block_content);
      }

      // WP Query block modifications
      // if ( 'core/query' === $block['blockName'] ) {
      //   $query_id      = $block['attrs']['queryId'];
      //   $container_end = strpos( $block_content, '>' );
    
      //   $paged = absint( $_GET[ 'query-' . $query_id . '-page' ] ?? 1 );
    
      //   $block_content = substr_replace( $block_content, ' data-paged="' . esc_attr( $paged ) . '" data-attrs="' . esc_attr( json_encode( $block ) ) . '"', $container_end, 0 );
      // }

      // if ( 'core/query-pagination' === $block['blockName'] ) {
      //   $block_content = sprintf( '<div class="wp-block-buttons is-content-justification-center is-layout-flex wp-container-1">
      //   <div class="wp-block-button observing intersected intersecting"><a class="wp-block-button__link has-offwhite-color has-blue-background-color has-text-color has-background wp-element-button wp-block-button__link view-more-query button" href="#">%s</a></div></div>', esc_html__( 'Load more' ) );
      // }

      // Parallax Image container 
      // if ( 'core/image' === $block['blockName'] && strpos($block_content, 'is-style-landscape-parallax') ) {
      //   $block_content = preg_replace('/<img(.*?)>/', '<div class="parallax-inner"><img$1></div>', $block_content);
      // }
    }
  
    return $block_content;
  }

  public static function excerpt_length( $number  ) {

    return $number ;
  }

  public static function excerpt_more( $more  ) {

    $more = '&hellip;';
    return $more ;
  }

  public static function the_content( $content ) {

    // Silly workaround to enqueue button styles by rendering a phantom button block.
    render_block( [ 'blockName' => 'core/button' ] );

    return str_replace( '<p></p>', '', $content );
  }

  public static function wpseo_separator_options( $separators ) {

    return $separators; 
  }

  public static function render_block_core_post_content( $attributes, $content, $block ) {
    static $seen_ids = array();

    if ( ! isset( $block->context['postId'] ) ) {
      return '';
    }

    $post_id = $block->context['postId'];

    if ( isset( $seen_ids[ $post_id ] ) ) {
      // WP_DEBUG_DISPLAY must only be honored when WP_DEBUG. This precedent
      // is set in `wp_debug_mode()`.
      $is_debug = WP_DEBUG && WP_DEBUG_DISPLAY;

      return $is_debug ?
        // translators: Visible only in the front end, this warning takes the place of a faulty block.
        __( '[block rendering halted]' ) :
        '';
    }

    $seen_ids[ $post_id ] = true;

    // Check is needed for backward compatibility with third-party plugins
    // that might rely on the `in_the_loop` check; calling `the_post` sets it to true.
    if ( ! in_the_loop() && have_posts() ) {
      the_post();
    }

    // When inside the main loop, we want to use queried object
    // so that `the_preview` for the current post can apply.
    // We force this behavior by omitting the third argument (post ID) from the `get_the_content`.
    $content = get_the_content();
    // Check for nextpage to display page links for paginated posts.
    if ( has_block( 'core/nextpage' ) ) {
      $content .= wp_link_pages( array( 'echo' => 0 ) );
    }

    /** This filter is documented in wp-includes/post-template.php */
    $content = apply_filters( 'the_content', str_replace( ']]>', ']]&gt;', $content ) );
    unset( $seen_ids[ $post_id ] );

    if ( empty( $content ) ) {
      return '';
    }

    $wrapper_attributes = get_block_wrapper_attributes( array( 'class' => 'entry-content' ) );

    if ( isset( $attributes['className'] ) && false !== strpos( $attributes['className'], 'is-style-no-wrapper' ) ) {
      $output = $content;
    } else {
      $output = '<main ' . $wrapper_attributes . '>' . $content . '</main>';
    }

    return (
      $output
    );
  }

  // Formidable
  public static function frm_field_input_html( $field, $echo = true ) {

    if( 'date' === $field['type'] ) {
      $html = ' autocomplete="off"';
    }

    if( $echo ) print $html; 
    return $html;
  }

  public static function frm_field_div_classes( $classes, $field ) {

    $classes = rtrim( $classes ) . ' frm-field-' . esc_attr( $field['type'] );

    return $classes;
  }

  // REST
  public static function rest_api_init() {

    add_filter( 'rest_pre_echo_response', [ __CLASS__, 'rest_pre_echo_response' ], 10, 3 );
  }

  public static function rest_pre_echo_response( $result, $server, $request ) {

    if ( '/wp/v2/posts' === $request->get_route() ) {

      $result = array_map( function ( $post ) {

        $post_output = <<<EOHTML
EOHTML;

        return (object)[ 'card' => preg_replace( '/[\r\n]+/', '', $post_output ) ];

      }, $result );
  
    }
  
    return $result;
  }

  public static function custom_block_categories( $categories ) {
    return array_merge(
        array(
            array(
                'slug' => 'verbovancouver-blocks',
                'title' => __( 'Verbo', 'verbovancouver-blocks' ),
            ),
        ),
        $categories
    );
  }

  public static function custom_login_message( $message ) {
    if ( empty($message) ){
        if (strpos($_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'], 'verbovancouver' )) {
            return "<p style='padding: 26px 16px;text-align:left;color: #1f1f1f;border: 1px solid #f1c466;border-radius: 6px;background-color: rgb(249 244 191 / 90%);font-size:  14px;line-height: 1.5;'><strong>You are logging in to the Staging environment if you would like to access the live website make sure the correct URL is used.</strong></p>";
        }
    } else {
        return $message;
    }
}

}
VerboTheme::run();

/**
 * Get the Google Maps API key.
 *
 * Priority:
 *   1. ACF option on the Customize page (field: gmaps_api_key)
 *   2. VV_GMAPS_KEY constant in wp-config.php
 *   3. Empty string
 * To add the key to a script use: <?php echo vv_gmaps_key(); ?>
 * @return string
 */
function vv_gmaps_key() {
    static $key = null;
    if ( $key !== null ) return $key;

    // 1) ACF option (editable in wp-admin → Settings → Customize)
    $option = function_exists( 'get_field' )
        ? get_field( 'gmaps_api_key', 'option' )
        : '';

    // 2) Fallback to wp-config.php constant
    if ( empty( $option ) && defined( 'VV_GMAPS_KEY' ) ) {
        $option = VV_GMAPS_KEY;
    }

    $key = is_string( $option ) ? trim( $option ) : '';
    return $key;
}