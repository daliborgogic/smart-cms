<?php
  function cors () {
    remove_filter('rest_pre_serve_request', 'rest_send_cors_headers');
    add_filter('rest_pre_serve_request', function ($value) {
      header('Access-Control-Allow-Origin: *');
      header('Access-Control-Allow-Methods: GET');
      header('Access-Control-Allow-Credentials: true');
      return $value;
    });
  }
  add_action('rest_api_init', 'cors', 15);

  function setup () {
    add_theme_support('post-thumbnails');
    // add_image_size( 'w360', 360, 9999, false );
  }
  add_action('after_setup_theme', 'setup');

  function remove_menus () {
    // remove_menu_page('edit.php');
    remove_menu_page('edit-comments.php');
    // remove_menu_page('plugins.php');
    remove_menu_page('users.php');
  }
  add_action('admin_menu', 'remove_menus');

  // Enable ACF 5 early access.  Requires at least version ACF 4.4.12 to work
  define('ACF_EARLY_ACCESS', '5');

  // Custom Post Types
  require_once(__DIR__ . '/post-types/jobs.php');

  // Allow SVG
  add_filter( 'wp_check_filetype_and_ext', function($data, $file, $filename, $mimes) {

    global $wp_version;
    if ( $wp_version !== '4.7.1' ) {
       return $data;
    }

    $filetype = wp_check_filetype( $filename, $mimes );

    return [
        'ext'             => $filetype['ext'],
        'type'            => $filetype['type'],
        'proper_filename' => $data['proper_filename']
    ];

  }, 10, 4 );

  function cc_mime_types( $mimes ){
    $mimes['svg'] = 'image/svg+xml';
    return $mimes;
  }
  add_filter( 'upload_mimes', 'cc_mime_types' );

  function fix_svg() {
    echo '<style type="text/css">
          .attachment-266x266, .thumbnail img {
               width: 100% !important;
               height: auto !important;
          }
          </style>';
  }
  add_action( 'admin_head', 'fix_svg' );
