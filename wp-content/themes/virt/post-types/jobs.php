<?php
  add_action('init', 'department_taxonomy', 0);
  function department_taxonomy() {
    $labels = array(
      'name'                       => _x('Department', '', ''),
      'singular_name'              => _x('Department', '', ''),
      'menu_name'                  =>  __('Departments'),
      'all_items'                  =>  __('All Departments'),
      'new_item_name'              =>  __('New Department'),
      'add_new_item'               =>  __('Add New Department'),
      'edit_item'                  =>  __('Edit Department'),
      'update_item'                =>  __('Update Department'),
      'view_item'                  =>  __('View Department'),
      'separate_items_with_commas' =>  __('Separate Departments with commas'),
      'add_or_remove_items'        =>  __('Add or remove Departments'),
      'search_items'               =>  __('Search Departments'),
      'not_found'                  =>  __('Not Found'),
    );
    $args = array(
      'labels'                     => $labels,
      'hierarchical'               => true,
      'public'                     => true,
      'show_ui'                    => true,
      'show_admin_column'          => true,
      'show_in_nav_menus'          => true,
      'show_tagcloud'              => true
    );
    register_taxonomy( 'department_categories', array(''), $args );
  }

  add_action( 'init', 'office_taxonomy', 0);
  function office_taxonomy() {
    $labels = array(
      'name'                       => _x('Office', '', ''),
      'singular_name'              => _x('Office', '', ''),
      'menu_name'                  =>  __('Offices'),
      'all_items'                  =>  __('All Officess'),
      'new_item_name'              =>  __('New Office'),
      'add_new_item'               =>  __('Add New Office'),
      'edit_item'                  =>  __('Edit Office'),
      'update_item'                =>  __('Update Office'),
      'view_item'                  =>  __('View Office'),
      'separate_items_with_commas' =>  __('Separate Offices with commas'),
      'add_or_remove_items'        =>  __('Add or remove Offices'),
      'choose_from_most_used'      =>  __('Choose from the most used'),
      'popular_items'              =>  __('Popular Offices'),
      'search_items'               =>  __('Search Offices'),
      'not_found'                  =>  __('Not Found'),
    );
    $args = array(
      'labels'                     => $labels,
      'hierarchical'               => true,
      'public'                     => true,
      'show_ui'                    => true,
      'show_admin_column'          => true,
      'show_in_nav_menus'          => true,
      'show_tagcloud'              => true
    );
    register_taxonomy( 'office_categories', array(''), $args );
  }

  function jobs_post_type () {
    $labels = array(
      'name'                  => 'Jobs',
      'singular_name'         => 'Job',
      'menu_name'             => 'Jobs',
      'name_admin_bar'        => 'Job',
      'archives'              => 'Job Archives',
      'attributes'            => 'Job Attributes',
      'parent_item_colon'     => 'Parent Job:',
      'all_items'             => 'All Jobs',
      'add_new_item'          => 'Add New Job',
      'add_new'               => 'Add New',
      'new_item'              => 'New Job',
      'edit_item'             => 'Edit Job',
      'update_item'           => 'Update Job',
      'view_item'             => 'View Job',
      'view_items'            => 'View Jobs',
      'search_items'          => 'Search Job',
      'not_found'             => 'Not found',
      'not_found_in_trash'    => 'Not found in Trash',
      'featured_image'        => 'Featured Image',
      'set_featured_image'    => 'Set featured image',
      'remove_featured_image' => 'Remove featured image',
      'use_featured_image'    => 'Use as featured image',
      'insert_into_item'      => 'Insert into job',
      'uploaded_to_this_item' => 'Uploaded to this job',
      'items_list'            => 'jobs list',
      'items_list_navigation' => 'jobs list navigation',
      'filter_items_list'     => 'Filter jobs list',
    );
    $args = array (
      'label'                 => 'Job',
      'description'           => 'Job Description',
      'labels'                => $labels,
      'supports'              => array('title', 'editor'),
      'taxonomies'            => array('department_categories', 'office_categories'),
      'hierarchical'          => false,
      'public'                => true,
      'show_ui'               => true,
      'show_in_menu'          => true,
      'menu_position'         => 5,
      'show_in_admin_bar'     => true,
      'show_in_nav_menus'     => true,
      'can_export'            => true,
      'has_archive'           => false,
      'exclude_from_search'   => false,
      'publicly_queryable'    => true,
      'capability_type'       => 'post',
      'show_in_rest'          => true,
      'rest_base'             => 'jobs',
      'rest_controller_class' => 'WP_REST_Posts_Controller',
    );
    register_post_type ('jobs', $args);
  }

  add_action ('init', 'jobs_post_type', 0);

  // Add REST API support to an already registered taxonomy.
  add_action( 'init', 'department_taxonomy_rest_support', 25 );
  function department_taxonomy_rest_support() {
    global $wp_taxonomies;
    $taxonomy_name = 'department_categories';
    if ( isset( $wp_taxonomies[ $taxonomy_name ] ) ) {
      $wp_taxonomies[ $taxonomy_name ]->show_in_rest = true;
      $wp_taxonomies[ $taxonomy_name ]->rest_base = 'departments';
      $wp_taxonomies[ $taxonomy_name ]->rest_controller_class = 'WP_REST_Terms_Controller';
    }
  }
  add_action( 'init', 'office_taxonomy_rest_support', 25 );
  function office_taxonomy_rest_support() {
    global $wp_taxonomies;
    $taxonomy_name = 'office_categories';
    if ( isset( $wp_taxonomies[ $taxonomy_name ] ) ) {
      $wp_taxonomies[ $taxonomy_name ]->show_in_rest = true;
      $wp_taxonomies[ $taxonomy_name ]->rest_base = 'offices';
      $wp_taxonomies[ $taxonomy_name ]->rest_controller_class = 'WP_REST_Terms_Controller';
    }
  }

  function register_departments_field () {
    register_rest_field( 'jobs', 'department_categories',
      array(
      'get_callback'    => 'get_departments_names',
      'update_callback' => null,
      'schema'          => null,
      )
    );
  }

  add_action('rest_api_init', 'register_departments_field');

  function get_departments_names ($object, $field_name, $request) {
    $formatted_departments = array ();
    $departmentss =  get_the_terms ($object['id'], 'department_categories' );

    foreach ($departmentss as $department) {
      $formatted_departments[] = $department->name;
    }

    return $formatted_departments;
  }

  function register_offices_field () {
    register_rest_field( 'jobs', 'office_categories',
      array(
      'get_callback'    => 'get_offices_names',
      'update_callback' => null,
      'schema'          => null,
      )
    );
  }

  add_action('rest_api_init', 'register_offices_field');

  function get_offices_names($object, $field_name, $request) {
    $formatted_offices= array ();
    $offices =  get_the_terms ($object['id'], 'office_categories' );

    foreach ($offices as $office) {
      $formatted_offices[] = $office->name;
    }

    return $formatted_offices;
  }

