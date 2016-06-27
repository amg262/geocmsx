<?php // Get out!

defined( 'ABSPATH' ) or die( 'Plugin file cannot be accessed directly.' );



//if uninstall not called from WordPress exit

if ( !defined( 'WP_UNINSTALL_PLUGIN' ) ) 

    exit();


if( is_admin() ) {



    	$trail_story_options = get_option( 'trail_story_options' );

    	$geo_mashup_options = get_option( 'geo_mashup_options' );


	 function delete_posts() {





		$args = array(

			'numberposts' => -1,

			'post_type' => 'geopost',

			'post_status' => 'any'

		);



		$posts = get_posts( $args );

		$i = 0;




			//if (is_array($posts)) {



			   foreach ($posts as $post) {



			       wp_delete_post( $post );

			       $i++;

			   }

			//}

		} else {

		}



	



	}



	 function delete_terms() {



		$tax = get_term_by( 'slug', 'geopost-category' );

		$terms = get_terms( array(

		    'taxonomy' => 'geopost-category',

		    'hide_empty' => false,

		) );

		$i = 0;



			if ( ! is_null($terms) ) {



				foreach ($terms as $term) {

					$id = $term->term_id;

					wp_delete_term( $id );

					$i++;

				}



			} else {

			}

		

		

	}





	/**

	 * Drop Geo Mashup database tables.

	 * 

	 * @since 1.3

	 * @access public

	 */

	 function geo_mashup_uninstall_db() {

		global $wpdb;

		

		if (isset($trail_story_options["delete_dbo"])) {



			$tables = array( 'geo_mashup_administrative_names', 'geo_mashup_location_relationships', 'geo_mashup_locations' );

			foreach( $tables as $table ) {

				$wpdb->query( 'DROP TABLE IF EXISTS ' . $wpdb->prefix . $table );

			}



		} else {

		}

	}



	/**

	 * Delete Geo Mashup saved options.

	 * 

	 * @since 1.3

	 * @access public

	 */

 function geo_mashup_uninstall_options() {

		delete_option( 'geo_mashup_temp_kml_url' );

		delete_option( 'geo_mashup_db_version' );

		delete_option( 'geo_mashup_activation_log' );

		delete_option( 'geo_mashup_options' );

		delete_option( 'geo_locations' );

		// Leave the google_api_key option 

		// Still belongs to this site, and may be used by other plugins

	}

	



	//note in multisite looping through blogs to delete options on each blog does not scale. You'll just have to leave them.

	/*

	* Getting options groups

	*/

 function delete_plugin_options() {



		delete_option( $trail_story_options );

		delete_site_option( $trail_story_options );



		delete_option( $geo_mashup_options );

		delete_site_option( $geo_mashup_options );

	}



 function execute_uninstallion() {

		delete_posts();

		geo_mashup_uninstall_options();

delete_plugin_options();

geo_mashup_uninstall_db();

	}

execute_uninstallion();



}
