<?php 
/*
* Plugin Name: GeoCMSx Map Maker
* Plugin URI:  http://andrewgunn.xyz/geocmsx
* Version:     1.0.0
* Description: A fully loaded interactive geo map maker with functionality to allow users to submit geo located trail story and condition posts, require email to download trail itinerary documents, and custom pins and features tailored interactive trail maps.
* Author:      Andrew M. Gunn
* Author URI:  http://andrewgunn.xyz/
* License:     GNU General Public License (Version 2 - GPLv2)
* Text Domain: interactive-geo-trail-map
*/
if ( ! defined( 'ABSPATH' ) ) die( 'No way, jose!' );

/**
 * Defining constants
 */
define( 'IGTM_NAME', 'interactive-geo-trail-map' );
define( 'IGTM_DIR', dirname( __FILE__ ) );
define( 'IGTM_URL', plugins_url( __FILE__ ) );
define( 'GEO_MASHUP', 'geo-mashup' );
define( 'GEO_MASHUP_CUSTOM', 'geo-mashup-custom' );
define( 'GEO_MASHUP_ADDON', 'geo-mashup-trail-story' );
define( 'PHP', '.php' );


//require_once( 'inc/class-ui-pagecreator.php' );
require_once( 'geo-mashup-trail-story/geo-mashup-trail-story.php' );
require_once( 'geo-mashup-custom/geo-mashup-custom.php' );
require_once( 'geo-mashup/geo-mashup.php' );



//define( 'IGTM_PDF_URL', 'http://andrewgunn.xyz/wp-content/uploads/2015/10/Interactive-Geo-Trail-Map.pdf' ); 
/**
* Flushing permalinks for CPTs on DEACTIVATE
*/
//register_deactivation_hook( __FILE__, 'flush_permalinks' );

/**
* Flushing permalinks for CPTs ON ACTIVATE
*/
//register_activation_hook( __FILE__, 'setup_plugin_data' );
//register_activation_hook( __FILE__, 'create_misc_pages' );
//add_action( 'admin_init', 'flush_permalinks' );
/*
* Adding Settings link to plugin page
*/


interface iGsxInit {

}


/**
* PLUGIN SETTINGS PAGE
*/
class GsxInit {
    /**
     * Holds the values to be used in the fields callbacks
     */
    public $trail_story_options;
    /**
     * Start up
     */
    public function __construct()
    {
        //add_action( 'admin_menu', array( $this, 'add_trail_story_menu_page' ) );
      //  add_action( 'admin_init', array( $this, 'gsx_map_page' ) );
        //add_filter( 'plugin_action_links', array( $this, 'gsx_plugin_links'), 10, 5 );
        //register_activation_hook( __FILE__, array($this, 'gsx_map_page') );
    }
    public function gsx_setup() {
      //create map page
      //create fe page

      flush_rewrite_rules();
    }
    public function gsx_post_type() {

    }
    public function gsx_map_page() {

      global $post_ID;
      global $user_ID;
      //if ( $_POST['geo_mashup_add_location'] ) {
      $post = array(
        'post_author'   => $user_ID, //The user ID number of the author.
        //'post_category'   => $trail_story_post_category, //Add some categories. Apparently doesn't work
        'post_content'    => '[geo_mashup_map]', //The full text of the post.
        'post_date'     => date_i18n( 'Y-m-d H:i:s' ), //The time post was made.
        //'post_date_gmt' => Y-m-d H:i:s, //The time post was made, in GMT.
        'post_status'     => 'publish', //Set the status of the new post. Pode ser acertada via Admin
        'post_title'    => 'GeoPost Map', //The title of your post.
        'post_type'     => 'page' //Sometimes you want to post a page.
        //'tags_input'    => [  ], //For tags.
       // 'tax_input'       => array( 'trail-story-category' => 'trail-stories' ) //For taxonomies.
      );
        
        //include_once( 'class-trail-story-db.php' );
        // Insert the post into the database
        $id = wp_insert_post( $post );
        wp_set_post_terms( $id );
        var_dump($id);
    }

    public function gsx_fe_page() {

    }

    public function gsx_plugin_links( $actions, $plugin_file ) {
      static $plugin;

      if (!isset($plugin))
        $plugin = plugin_basename(__FILE__);

        if ($plugin == $plugin_file) {

          $settings = array(

                    //'map' => '<a href="admin.php?page=interactive-geo-trail-map%2Fgeo-mashup%2Fgeo-mashup.php">' . __('Map', 'General') . '</a>',
                    'settings' => '<a href="admin.php?page=geo-trail-map">' . __('Settings', 'General') . '</a>',

                    'support' => '<a target="_blank" href="http://andrewgunn.xyz/support?id=geocmsx">' . __('Support', 'General') . '</a>'

                    );

              $actions = array_merge($settings, $actions);
        }

        return $actions;
    }
}

$gsx = new GsxInit();


