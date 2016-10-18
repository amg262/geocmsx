<?php
defined( 'ABSPATH' ) or die( 'Plugin file cannot be accessed directly.' );

class GsxPostType {
    /*
    * Custom Post Type for GeoPost
    */
    public function __construct()
    {
        //add_action( 'admin_menu', array( $this, 'add_trail_story_menu_page' ) );
        $trail_story_options = get_option( 'trail_story_option' );
        //  add_action( 'admin_init', array( $this, 'gsx_map_page' ) );
        add_action( 'init', array($this, 'register_cpt_geocms_pro' ));
        add_action( 'init', array($this, 'register_txn_geocms_pro' ));



    }
    public function register_cpt_geocms_pro() {

        $count = wp_count_posts('geopost');
        $pending_count = $count->pending;

        $labels = array(
            'name' => _x( 'GeoPost', 'geopost' ),
            'name_admin_bar' => _x( 'GeoPost' ,'geopost' ),
            'singular_name' => _x( 'GeoPost', 'geopost' ),
            'add_new' => _x( 'Add New', 'geopost' ),
            'add_new_item' => _x( 'Add New GeoPost', 'geopost' ),
            'edit_item' => _x( 'Edit GeoPost', 'geopost' ),
            'new_item' => _x( 'New GeoPost', 'geopost' ),
            'view_item' => _x( 'View GeoPosts', 'geopost' ),
            'all_items'          => __( 'All Posts', 'trail-condition' ),
            'search_items' => _x( 'Search GeoPosts', 'geopost' ),
            'not_found' => _x( 'No GeoPosts found', 'geopost' ),
            'not_found_in_trash' => _x( 'No GeoPosts found in Trash', 'geopost' ),
            'parent_item_colon' => _x( 'Parent GeoPosts:', 'geopost' ),
            'menu_name' => _x( 'GeoCMSx', 'geopost' ),
        );

        $args = array(
            'labels' => $labels,
            'hierarchical' => true,
            'supports' => array( 'title', 'editor', 'thumbnail' , 'custom-fields', 'post-formats' ),// 'excerpt'
            'taxonomies' => array(  'geopost-category' ), //, 'post_tag' ),
            'public' => true,
            'show_ui' => true,
            'show_in_menu' => true,
            'show_in_nav_menus' => true,
            'show_in_admin_bar' => true,
            'show_in_rest'=> true,
            //'register_meta_box_cb' => 'add_location_metabox',
            //'menu_position' => 5001,
            //'menu_icon' => 'dashicons-format-status',//'dashicons-controls-volumeon', //'dashicons-media-audio',
            // 'menu_icon' => 'dashicons-format-status',//'dashicons-controls-volumeon', //'dashicons-media-audio',
            //'menu_icon' => plugins_url( 'geocms-pro/geocms-core/assets/icon-20x20.png' ),
            'has_archive' => true,
            'publicly_queryable' => true,
            'exclude_from_search' => false,
            'has_archive' => true,
            'query_var' => true,
            'can_export' => true,
            //'rewrite' => true,
            'rewrite' => array('slug'=>'geopost'),
            'capability_type' => 'post'
        );

        register_post_type( 'geopost', $args );

    }


    /**
     * Taxonomy for GeoPost CPT categories
     */

    public function register_txn_geocms_pro() {

        $labels = array(
            'name' => _x( 'GeoPost Category', 'geopost-category' ),
            'singular_name' => _x( 'GeoPost Category', 'geopost-category' ),
            'search_items' => _x( 'Search GeoCMS Categories', 'geopost-category' ),
            'popular_items' => _x( 'Popular GeoCMS Categories', 'geopost-category' ),
            'all_items' => _x( 'All GeoCMS Categories', 'geopost-category' ),
            'parent_item' => _x( 'Parent GeoPost Category', 'geopost-category' ),
            'parent_item_colon' => _x( 'Parent GeoPost Category:', 'geopost-category' ),
            'edit_item' => _x( 'Edit GeoPost Category', 'geopost-category' ),
            'update_item' => _x( 'Update GeoPost Category', 'geopost-category' ),
            'add_new_item' => _x( 'Add New', 'geopost-category' ),
            'new_item_name' => _x( 'New GeoPost Category', 'geopost-category' ),
            'separate_items_with_commas' => _x( 'Separate GeoCMS Categories with commas', 'geopost-category' ),
            'add_or_remove_items' => _x( 'Add or remove GeoCMS Categories', 'geopost-category' ),
            'choose_from_most_used' => _x( 'Choose from the most used GeoPost Category', 'geopost-category' ),
            'menu_name' => _x( 'Categories', 'geopost-category' ),
        );

        $args = array(
            'labels' => $labels,
            'public' => true,
            'show_in_nav_menus' => true,
            'show_ui' => true,
            'show_in_menu' => true,
            'show_tagcloud' => true,
            'show_admin_column' => true,
            'hierarchical' => true,
            'rewrite' => true,//( 'slug' => 'category' ),
            'query_var' => true
        );

        register_taxonomy( 'geopost-category', array('geopost'), $args );

        if (! has_term( 'geoposts', 'geopost-category' )) {
            wp_insert_term( 'GeoPosts','geopost-category', array('GeoPosts', 'geoposts') );
        }
    }

}

$post_type = new GsxPostType();

