<?php 
/**
* Defined contants
*/
defined( 'ABSPATH' ) or die( 'Plugin file cannot be accessed directly.' );
define('TRAIL_STORY_DIR_PATH', dirname( __FILE__ ));
define('TRAIL_STORY_PLUGIN_FILE', plugin_basename( __FILE__ ) );
define('TRAIL_STORY_GEO_MASHUP_DIRECTORY', dirname( GEO_MASHUP_PLUGIN_NAME ) );
define('TRAIL_STORY_URL_PATH', trim( plugin_dir_url( __FILE__ ), '/' ) );

/**
* Including files in other directories
*/
include_once('classes/class-gsx-settings.php');
include_once('classes/trail-story-frontend-form.php');
include_once('classes/class-gsx-cpt.php');
include_once('classes/class-gsx-helper.php');
//include_once('classes/class-gsx-database.php');
//include_once('inc/cpt-trail-story.php');
//include_once('inc/cpt-trail-condition.php');
//include_once('inc/cpt-trail-segment.php');

/**
* Flushing permalinks for CPTs on DEACTIVATE
*/



/**
 * Shortcode for display frontend user trail story input
 */
add_shortcode( 'frontend_trail_story_map', 'trail_story_post_form_handler' );
//add_action( 'wp_footer', 'trail_story_post_g_form_handler', 10 );


function trail_story_post_form_handler(){

    GeoMashupPostUIFrontend::get_instance()->GeoMashupPostUIFrontend();

    trail_story_post_form();

}
/**
 * Function to print the front end user post form
 */
function trail_story_post_form() {
    // Create form
    GeoMashupPostUIFrontend::get_instance()->print_form();

}


/**
* Enqueue the plugins JS and CSS files
*/
add_action( 'init', 'register_admin_trail_story_scripts' );

function register_admin_trail_story_scripts() {
    wp_register_script( 'admin_trail_story_js', plugins_url( 'inc/admin-trail-story.js', __FILE__ ), array('jquery'));
    wp_register_style( 'admin_trail_story_css', plugins_url( 'inc/admin-trail-story.css', __FILE__ ));
    wp_enqueue_script( 'admin_trail_story_js' );
    wp_enqueue_style( 'admin_trail_story_css' );
}

/**
* Filter for user displayed map
*/
add_filter( 'geo_mashup_load_user_editor', 'trail_story_filter_geo_mashup_load_user_editor' );

function trail_story_filter_geo_mashup_load_user_editor( $enabled ) {
    
    // Get access to the current WordPress object instance
    global $wp;

    // Get the base URL
    $current_url = home_url(add_query_arg(array(),$wp->request));

    // Add WP's redirect URL string
    $current_url = $current_url . $_SERVER['REDIRECT_URL'];

    // Retrieve the current post's ID based on its URL
    $id = url_to_postid($current_url);

    $post_thing = get_post($id);
    
    $enabled = has_shortcode( $post_thing->post_content, 'frontend_trail_story_map') ? true : false;
    return $enabled;
    
}

/**
 * Save post metadata when a post is saved.
 *
 * @param int $post_id The post ID.
 * @param post $post The post object.
 * @param bool $update Whether this is an existing post being updated or not.
 */
function save_geopost_meta( $post_id, $post, $update ) {

    /*
     * In production code, $slug should be set only once in the plugin,
     * preferably as a class property, rather than in each function that needs it.
     */
    $slug = 'geopost';

    // If this isn't a 'book' post, don't update it.
    if ( $slug != $post->post_type ) {
        return;
    }
    $tax = 'geopost-category';
    $geoposts = 'geoposts';
    $term = get_term_by('slug', $geoposts, $tax);

    $obj_terms = wp_get_object_terms( $post_id, $tax );
    //var_dump($post);
    //var_dump($term);
    //wp_set_post_categories( $post_ID, $post_categories, $append );
    //wp_set_object_terms( $post_id, $term, $tax);
    // - Update the post's metadata.
    //var_dump($term);
    if ( isset( $_REQUEST['post_ID'] ) && ($obj_terms == null) ) {
       //wp_set_post_terms( $post_id, $term, $tax);
        wp_set_object_terms( $_REQUEST['post_ID'], $term->name, $tax);
    }


}
add_action( 'save_post', 'save_geopost_meta', 10, 3 );
