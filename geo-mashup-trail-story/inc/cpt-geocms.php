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
    add_action( 'add_meta_boxes', array( $this, 'add_meta_box' ) );
    add_action( 'save_post',      array( $this, 'save'         ) );


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
        'supports' => array( 'title', 'editor', 'thumbnail', 'comments', 'revisions', 'page-attributes', 'post-formats' ),// 'excerpt' 
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
        'menu_icon' => plugins_url( 'assets/icon-20x20.png', dirname( __FILE__)  ),
        'has_archive' => true,
        'publicly_queryable' => true,
        'exclude_from_search' => false,
        'has_archive' => true,
        'query_var' => true,
        'can_export' => true,
        'rewrite' => true,
        //'rewrite' => array('slug'=>'geopost'),
        'capability_type' => 'post'
    );

    register_post_type( 'geopost', $args );
    flush_rewrite_rules();
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

/**
     * Adds the meta box container.
     */
    public function add_meta_box( $post_type ) {
        // Limit meta box to certain post types.
        $post_types = array( 'geopost' );
 
        if ( in_array( $post_type, $post_types ) ) {
            add_meta_box(
                '_geopost_location',
                __( 'Location', 'textdomain' ),
                array( $this, 'render_meta_box_content' ),
                $post_type,
                'advanced',
                'high'
            );
        }
    }
 
    /**
     * Save the meta when the post is saved.
     *
     * @param int $post_id The ID of the post being saved.
     */
    public function save( $post_id ) {
 
        /*
         * We need to verify this came from the our screen and with proper authorization,
         * because save_post can be triggered at other times.
         */
 
        // Check if our nonce is set.
        if ( ! isset( $_POST['geopost_location_cb_nonce'] ) ) {
            return $post_id;
        }
 
        $nonce = $_POST['geopost_location_cb_nonce'];
 
        // Verify that the nonce is valid.
        if ( ! wp_verify_nonce( $nonce, 'geopost_location_cb' ) ) {
            return $post_id;
        }
 
        /*
         * If this is an autosave, our form has not been submitted,
         * so we don't want to do anything.
         */
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
            return $post_id;
        }
 
        // Check the user's permissions.
        if ( 'page' == $_POST['post_type'] ) {
            if ( ! current_user_can( 'edit_page', $post_id ) ) {
                return $post_id;
            }
        } else {
            if ( ! current_user_can( 'edit_post', $post_id ) ) {
                return $post_id;
            }
        }
 
        /* OK, it's safe for us to save the data now. */
 
        // Sanitize the user input.
        $mydata = sanitize_text_field( $_POST['new_geopost_location'] );
 
        // Update the meta field.
        update_post_meta( $post_id, '_geopost_location', $mydata );
    }
 
 
    /**
     * Render Meta Box content.
     *
     * @param WP_Post $post The post object.
     */
    public function render_meta_box_content( $post ) {
 
        // Add an nonce field so we can check for it later.
        wp_nonce_field( 'geopost_location_cb', 'geopost_location_cb_nonce' );
 
        // Use get_post_meta to retrieve an existing value from the database.
        $value = get_post_meta( $post->ID, '_geopost_location', true );
 
        // Display the form, using the current value.
        ?>
        <label for="new_geopost_location">
            <?php _e( 'Description for this field', 'textdomain' ); ?>
        </label>
        <input type="text" id="new_geopost_location" name="new_geopost_location" value="<?php echo esc_attr( $value ); ?>" size="25" />
        <?php
    }

}

$post_type = new GsxPostType();

