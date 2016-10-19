<?php // Get out!


// Make sure this is a legitimate uninstall request
if( ! defined( 'ABSPATH') or ! defined('WP_UNINSTALL_PLUGIN') or ! current_user_can( 'delete_plugins' ) )
    exit();


/**
class GeoCmsUninstall {
    /
    public function __construct()
    {
        global $trail_story_settings;
        $trail_story_settings = get_option('trail_story_option');
    }

    public function delete_posts() {

        $posts = array();
        $count = 0;

        $args = array(
            'numberposts' => -1,
            'post_type' => 'geopost'
        );

        $posts = get_posts( $args );

        foreach ($posts as $post) {
            print $count++;
            //wp_delete_post( $post->ID, true );
        }
        //reset_postdata();
        wp_reset_query();

        return $count;

    }

    public function delete_terms() {

        $tax = 'geopost-category';
        $terms = get_terms( array(
            'taxonomy' => $tax,
            'hide_empty' => false,
        ) );

        foreach ($terms as $term) {
            echo $term->term_id;
        }
        //wp_delete_term( $term_id, $taxonomy, $args )
    }


    public function delete_database() {

        global $wpdb;
        global $trail_story_settings;
        global $geocms_options;

        if ($trail_story_settings['delete_data']) {

            $tables = array(
                'geo_mashup_administrative_names',
                'geo_mashup_location_relationships',
                'geo_mashup_locations', 'geocms');

            foreach ($tables as $table) {

                $wpdb->query('DROP TABLE IF EXISTS ' . $wpdb->prefix . $table);

            }
        }
    }





    public function delete_options() {

        delete_option( 'trail_story_option' );
        delete_option( 'geocms_options' );
        delete_option( 'geo_mashup_temp_kml_url' );
        delete_option( 'geo_mashup_db_version' );
        delete_option( 'geo_mashup_activation_log' );
        delete_option( 'geo_mashup_options' );
        delete_option( 'geo_locations' );

        /*delete_site_option( 'geo_mashup_temp_kml_url' );

        delete_site_option( 'geo_mashup_db_version' );

        delete_site_option( 'geo_mashup_activation_log' );

        delete_site_option( 'geo_mashup_options' );

        delete_site_option( 'geo_locations' );

        delete_site_option( 'geocms_options' );
    }
}


