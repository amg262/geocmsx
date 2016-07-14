 <?php // Get out!

defined( 'ABSPATH' ) or die( 'Plugin file cannot be accessed directly.' );



if ( !defined( 'WP_UNINSTALL_PLUGIN' ) ) {
    exit();
}


class GsxDataRemoval {

	public $trail_story_options;

	public function __construct() {

		$trail_story_options =  get_option('trail_story_options');

	}

	public function run() {
		$this->delete_posts();
		$this->delete_options();
		$this->delete_data();
	}

	public function delete_posts() {

		if ($this->$trail_story_options['delete_posts']):

			$posts = array();
			$count = 0;

			$args = array(

				'numberposts' => -1,

				'post_type' => 'geopost',

				'post_status' => 'any'

			);


			$posts = query_posts( $args );


				//if (is_array($posts)) {
			setup_postdata($posts);

			foreach ($posts as $post) {
				$count++;
			   wp_delete_post( $post->ID, true );

			}
			reset_postdata();
			return $count;
		endif;
	}





	/**

	 * Drop Geo Mashup database tables.

	 * 

	 * @since 1.3

	 * @access public

	 */

	 public function delete_data() {

		global $wpdb;

		if ($this->$trail_story_options['delete_data']):

			$tables = array( 'geo_mashup_administrative_names', 'geo_mashup_location_relationships', 'geo_mashup_locations' );

			foreach( $tables as $table ) {

				$wpdb->query( 'DROP TABLE IF EXISTS ' . $wpdb->prefix . $table );

			}
			endif;
		}




	//note in multisite looping through blogs to delete options on each blog does not scale. You'll just have to leave them.

	/*

	* Getting options groups

	*/

	public function delete_options() {

		if ($this->$trail_story_options['delete_options']):

			delete_option( 'trail_story_options' );

			delete_option( 'geo_mashup_temp_kml_url' );

			delete_option( 'geo_mashup_db_version' );

			delete_option( 'geo_mashup_activation_log' );

			delete_option( 'geo_mashup_options' );

			delete_option( 'geo_locations' );

			delete_site_option( 'geo_mashup_temp_kml_url' );

			delete_site_option( 'geo_mashup_db_version' );

			delete_site_option( 'geo_mashup_activation_log' );

			delete_site_option( 'geo_mashup_options' );

			delete_site_option( 'geo_locations' );
			
			delete_site_option( 'trail_story_options' );

		endif;

	}

}

$exe = new GsxDataRemoval();
$exe->run();


