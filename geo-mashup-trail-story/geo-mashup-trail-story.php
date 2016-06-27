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
include_once('inc/class-trail-story-settings.php');
include_once('inc/trail-story-frontend-form.php');
include_once('inc/cpt-geocms.php');
//include_once('inc/cpt-trail-story.php');
//include_once('inc/cpt-trail-condition.php');
//include_once('inc/cpt-trail-segment.php');

/**
* Flushing permalinks for CPTs on DEACTIVATE
*/




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

    //var_dump($post);
    //var_dump($term);
    //wp_set_post_categories( $post_ID, $post_categories, $append );
    //wp_set_object_terms( $post_id, $term, $tax);
    // - Update the post's metadata.
    //var_dump($term);
    if ( isset( $_REQUEST['post_ID'] ) ) {
       // wp_set_post_terms( $post_id, $term, $tax);
        wp_set_object_terms( $_REQUEST['post_ID'], $term->name, $tax);
    }


}
add_action( 'save_post', 'save_geopost_meta', 10, 3 );

add_action('save_post', 'wpq_acf_gmap');
function wpq_acf_gmap($post_id) {
   if(defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return $post_id;
   if( !isset($_POST['acf_nonce'], $_POST['fields']) || !wp_verify_nonce($_POST['acf_nonce'], 'input') ) return $post_id;
   
   if(get_post_status( $post_id ) <> 'publish' )  return $post_id;
      
   $location   = (array) ( maybe_unserialize(get_post_meta($post_id, 'RAE_0254', true)) ); //change location as your acf field name
   if( count($location) >= 3 ) {
      $geo_address = $location['address'];
      $geo_latitude = $location['lat'];
      $geo_longitude = $location['lng'];
      $geo_location = ''.$geo_latitude.','.$geo_longitude.'';
      update_post_meta( $post_id, 'geo_address', $geo_address );
      update_post_meta( $post_id, 'geo_latitude', $geo_latitude );
      update_post_meta( $post_id, 'geo_longitude', $geo_longitude );
      update_post_meta( $post_id, 'geo_location', $geo_location );
   }
}