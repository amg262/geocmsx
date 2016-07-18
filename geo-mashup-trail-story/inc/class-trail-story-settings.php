<?php
defined( 'ABSPATH' ) or die( 'Plugin file cannot be accessed directly.' );

interface iTrailStorySettings {
    public function __construct();
    public function add_trail_story_menu_page();
    public function create_trail_story_menu_page();
    public function page_init();
    public function sanitize( $input );
    public function print_option_info();
    public function trail_story_option_callback();

}
/**
* PLUGIN SETTINGS PAGE
*/
class TrailStorySettings {
    /**
     * Holds the values to be used in the fields callbacks
     */
    public $trail_story_options;
    /**
     * Start up
     */
    public function __construct()
    {
        add_action( 'admin_menu', array( $this, 'add_trail_story_menu_page' ) );
        add_action( 'admin_init', array( $this, 'page_init' ) );
    }
    /**
     * Add options page
     */
    public function add_trail_story_menu_page() {
        $count = wp_count_posts('geopost');
        $pending_count = $count->pending;
        // This page will be under "Settings"add_submenu_page( 'tools.php', 'SEO Image Tags', 'SEO Image Tags', 'manage_options', 'seo_image_tags', 'seo_image_tags_options_page' );
       
        add_submenu_page(
            'edit.php?post_type=geopost',
            'User Settings',
            'User Settings',
            'manage_options',
            'map-settings',
            array( $this, 'create_trail_story_menu_page' ));
        
     
        ///add_filter( 'add_menu_classes', array( $this, 'add_target_to_links') );
        add_filter( 'add_menu_classes', array( $this, 'show_pending_geocms') );

       // add_filter( 'add_menu_classes', array( $this, 'show_pending_number_trail_segment') );
        
        
    }
    
    public function add_target_to_links($menu) {
        
        // loop through $menu items, find match, add indicator
        foreach( $menu as $menu_key => $menu_data ) {
            $menu[$menu_key][0] .= "<span class='geo-trail-map-menu'>&nbsp;</span>";
        }
        return $menu;
    }
    /**
    * Shows pending for trail stories
    */
    public function show_pending_geocms( $menu ) {
        $type = "geopost";
        $status = "pending";
        $num_posts = wp_count_posts( $type, 'readable' );
        $pending_count = 0;
        if ( !empty($num_posts->$status) )
            $pending_count = $num_posts->$status;
        // build string to match in $menu array
        if ($type == 'post') {
            $menu_str = 'edit.php';
        } else {
            $menu_str = 'edit.php?post_type=' . $type;
        }
        // loop through $menu items, find match, add indicator
        foreach( $menu as $menu_key => $menu_data ) {
            if( $menu_str != $menu_data[2] )
                continue;
            $menu[$menu_key][0] .= " <span class='awaiting-mod count-$pending_count'><span class='pending-count'>" . number_format_i18n($pending_count) . '</span></span>';
        }
        return $menu;
    }


    /**
     * Options page callback
     */
    public function create_trail_story_menu_page() {
        // Set class property
        $this->trail_story_options = get_option( 'trail_story_option' );
        ?>
        <div class="wrap">
            <h1>GeoCMSx Map Maker</h1>
            <div>
            <p>
            <form method="post" action="options.php">

            <?php

                // This prints out all hidden setting fields
                settings_fields( 'trail_story_options_group' );
                do_settings_sections( 'trail-story-options-admin' );
                submit_button('Save Options');
            ?>
            </form>
            </p>
            </div>
        </div>
        <?php
    }

    /**
     * Register and add settings
     */
    public function page_init() {
        global $geo_mashup_options;
        register_setting(
            'trail_story_options_group', // Option group
            'trail_story_options', // Option name
            array( $this, 'sanitize' ) // Sanitize
        );

        add_settings_section(
            'trail_story_options_section', // ID
            '', // Title
            array( $this, 'print_option_info' ), // Callback
            'trail-story-options-admin' // Page
        );

        add_settings_section(
            'trail_story_option', // ID
            '', // Title
            array( $this, 'trail_story_option_callback' ), // Callback
            'trail-story-options-admin', // Page
            'trail_story_options_section' // Section
        );
      
       
    }
    /**
     * Sanitize each setting field as needed
     *
     * @param array $input Contains all settings fields as array keys
     */
    public function sanitize( $input ) {
        $new_input = array();
    
        return $input;
    }
    /**
     * Print the Section text
     */

      /**
     * Print the Section text
     */
    public function print_option_info() { ?>
        <div id="plugin-info-header" class="plugin-info header">
            <div class="plugin-info content">
                
                <p><h3>Plugin developed by <a target="_blank" href="http://andrewgunn.xyz/">Andrew Gunn</a>. For Support please <a href="http://andrewgunn.xyz/support/" target="_blank">follow this link.</a></h3>
                </p>
                <p><h3>You can also <a href="mailto:andrewmgunn26@gmail.com" target="_blank">email me</a> to to in touch faster, any feedback is welcomed!</h3>
                </p>
                <br>
                <h3><div class="mapsmarker">Find more Map icons at <a href="https://mapicons.mapsmarker.com/" target="_blank">Maps Marker's</a> website</div></h3>
                        </p>
                <br>
            </div>

        </div>
    <?php }

    /**
     * Get the settings option array and print one of its values
     */
    public function trail_story_option_callback() {
        //Get plugin options
        global $trail_story_options, $geo_mashup_options;
        // Enqueue Media Library Use
        wp_enqueue_media();
        
        // Get trail story options
        $trail_story_options = (array) get_option( 'trail_story_options' ); 

        //var_dump($trail_story_options);?>
        <div>
            <div>
                <hr>
                <table class="form-table">
                    <tbody>
                        <tr>

                            <?php //$key = 'delete_data'; ?>
                            <th scope="row">
                                GeoCMSx License Key
                            </th>
                            <td>
       
                                <fieldset><?php $key = 'gsx_license'; 
                                    //var_dump($trail_story_options[$key]);?>
                                    <label for="trail_story_options[<?php echo $key; ?>]">
                                        <input style="width:320px;"id='trail_story_options[<?php echo $key; ?>]' name="trail_story_options[<?php echo $key; ?>]" type="text" value="<?php echo $trail_story_options[$key]; ?>" />
                                    
                                    </label>
                            
                            </td>
                        </tr>

                        <tr>
                            <?php //$key = 'delete_data'; ?>
                            <th scope="row">
                                Delete Posts
                            </th>
                            <td>
       
                                <fieldset><?php $key = 'delete_posts'; 
                                    //var_dump($trail_story_options[$key]);?>
                                    <label for="trail_story_options[<?php echo $key; ?>]">
                                        <input id='trail_story_options[<?php echo $key; ?>]' name="trail_story_options[<?php echo $key; ?>]" type="checkbox" value="1" <?php checked(1, $trail_story_options[$key], true ); ?> />
                                        Delete all posts, attachments, and plugin settings when uninstalled.
                                    </label>
                               
                                
                            </td>
                        </tr>

                        <tr>
                            <?php //$key = 'delete_data'; ?>
                            <th scope="row">
                                Delete Options
                            </th>
                            <td>
       
                                <fieldset><?php $key = 'delete_options'; 
                                    //var_dump($trail_story_options[$key]);?>
                                    <label for="trail_story_options[<?php echo $key; ?>]">
                                        <input id='trail_story_options[<?php echo $key; ?>]' name="trail_story_options[<?php echo $key; ?>]" type="checkbox" value="1" <?php checked(1, $trail_story_options[$key], true ); ?> />
                                        Delete all plugin options.
                                    </label>
                                
                                
                            </td>
                        </tr>
                       
                        <tr>
                            <th scope="row">
                                Delete All Data
                            </th>
                            <td>
                                <fieldset><?php $key = 'delete_data'; 
                                    //var_dump(isset($trail_story_options["delete_dbo"])); ?>

                                    <label for="trail_story_options[<?php echo $key; ?>]">
                                        <input id='trail_story_options[<?php echo $key; ?>]' name="trail_story_options[<?php echo $key; ?>]" type="checkbox" value="1" <?php checked(1, $trail_story_options[$key], true ); ?> />
                                        Drop all database tables and plugin data when uninstalled.
                                    </label>
                                <p class="description"><span class="icon warn">&nbsp;</span>
                                ON UNINSTALL. Use at your own risk. A backup is recommended beforehand.</p>
                                </fieldset>
                                
                            </td>
                        </tr>
                    </tbody>
                </table>
                    
      
                <?php submit_button('Save Options'); ?>
            </div>
            <p><hr></p>
            <div id="">
                <h1><strong>Map Customization and Data Layers</strong></h1>
                <p>
                <table class="form-table">
                    <tbody>
                         <tr>
                            <?php //$key = 'delete_data'; ?>
                            <th scope="row">
                                Traffic Layer
                            </th>
                            <td>
       
                                <fieldset><?php $key = 'traffic_layer'; 
                                    //var_dump($trail_story_options[$key]);?>
                                    <label for="trail_story_options[<?php echo $key; ?>]">
                                        <input id='trail_story_options[<?php echo $key; ?>]' name="trail_story_options[<?php echo $key; ?>]" type="checkbox" value="1" <?php checked(1, $trail_story_options[$key], true ); ?> />
                                    </label>
                                <p class="description"></p>
                                
                            </td>
                        </tr>
                       
                        <tr>
                            <?php //$key = 'delete_data'; ?>
                            <th scope="row">
                                Transit Layer
                            </th>
                            <td>
       
                                <fieldset><?php $key = 'transit_layer'; 
                                    //var_dump($trail_story_options[$key]);?>
                                    <label for="trail_story_options[<?php echo $key; ?>]">
                                        <input id='trail_story_options[<?php echo $key; ?>]' name="trail_story_options[<?php echo $key; ?>]" type="checkbox" value="1" <?php checked(1, $trail_story_options[$key], true ); ?> />
                                    </label>
                                <p class="description"></p>
                                
                            </td>
                        </tr>
                        <tr>
                            <?php //$key = 'delete_data'; ?>
                            <th scope="row">
                                Bicicyle Layer
                            </th>
                            <td>
       
                                <fieldset><?php $key = 'bike_layer'; 
                                    //var_dump($trail_story_options[$key]);?>
                                    <label for="trail_story_options[<?php echo $key; ?>]">
                                        <input id='trail_story_options[<?php echo $key; ?>]' name="trail_story_options[<?php echo $key; ?>]" type="checkbox" value="1" <?php checked(1, $trail_story_options[$key], true ); ?> />
                                    </label>
                                <p class="description"></p>
                                
                            </td>
                        </tr>
                          <tr>
                            <?php //$key = 'delete_data'; ?>
                            <th scope="row">
                               Fusion Heat Layer
                            </th>
                            <td>
       
                                <fieldset><?php $key = 'fusion_heat_layer'; 
                                    //var_dump($trail_story_options[$key]);?>
                                    <label for="trail_story_options[<?php echo $key; ?>]">
                                        <input id='trail_story_options[<?php echo $key; ?>]' name="trail_story_options[<?php echo $key; ?>]" type="checkbox" value="1" <?php checked(1, $trail_story_options[$key], true ); ?> />
                                    </label>
                                <p class="description"></p>
                                
                            </td>
                        </tr>
                        <hr>
                         <tr>
                            <?php //$key = 'delete_data'; ?>
                            <th scope="row">
                               Map Type
                            </th>
                            <td>
       
                                <fieldset>
                                <?php $key = 'enable_maptype'; 
                                    //var_dump($trail_story_options[$key]);?>
                                        <label for="trail_story_options[<?php echo $key; ?>]">Enable</label>
                                        <input id='trail_story_options[<?php echo $key; ?>]' name="trail_story_options[<?php echo $key; ?>]" type="checkbox" value="1" <?php checked(1, $trail_story_options[$key], true ); ?> />
                                <?php $key = 'map_type'; 
                                    //var_dump($trail_story_options[$key]);?>
                                    <label for="trail_story_options[<?php echo $key; ?>]">

                                        <select id='trail_story_options[<?php echo $key; ?>]' name="trail_story_options[<?php echo $key; ?>]" type="checkbox" value="trail_story_options[<?php echo $key; ?>]" >
                                            <option value="Subtle Grayscale" <?php selected( $trail_story_options[$key], 'Subtle Grayscale' ); ?>>Subtle Grayscale</option>
                                            <option value="Unsaturated Browns" <?php selected( $trail_story_options[$key], 'Unsaturated Browns' ); ?>>Unsaturated Browns</option>
                                            <option value="Paper" <?php selected( $trail_story_options[$key], 'Paper' ); ?>>Paper</option>
                                            <option value="Pale Dawn" <?php selected( $trail_story_options[$key], 'Pale Dawn' ); ?>>Pale Dawn</option>
                                            <option value="Midnight Commander" <?php selected( $trail_story_options[$key], 'Midnight Commander' ); ?>>Midnight Commander</option>
                                            <option value="Retro" <?php selected( $trail_story_options[$key], 'Retro' ); ?>>Retro</option>
                                            <option value="Bright" <?php selected( $trail_story_options[$key], 'Bright' ); ?>>Bright</option>
                                            <option value="Avocado" <?php selected( $trail_story_options[$key], 'Avocado' ); ?>>Avocado</option>
                                            <option value="Hopper" <?php selected( $trail_story_options[$key], 'Hopper' ); ?>>Hopper</option>
                                            </select>
                                    </label>
                                <p class="description"></p>
                                
                            </td>
                        </tr>
                        <tr>
                            <?php  //$key = 'delete_data'; ?>
                            <th scope="row">
                                # of Data Layers
                            </th>
                            <td>
                                <fieldset><?php $key = 'count_data_layers';
                                if ($trail_story_options[$key]==null):
                                    $trail_story_options[$key]='1';
                                endif;
                                                                            //var_dump($trail_story_options[$key]); ?>

                                    
                                    <input type="text"  style="width:50px;" name="trail_story_options[<?php echo $key; ?>]" id="trail_story_options[<?php echo $key; ?>]" value="<?php echo $trail_story_options[$key]; ?>" placeholder="1" value="<?php if ($trail_story_options[$key] == null) { echo '1';$trail_story_options[$key]='1'; } else { echo $trail_story_options[$key]; } ?>" />
                                    <input type="submit" value="Add Layer" />
                                    <br>
                                                                    <?php  ?>
                            </td>
                        </tr>

                        <?php if ($trail_story_options['count_data_layers']) {
                            $count = intval($trail_story_options['count_data_layers']);
                           // var_dump($count);
                            for ($i=1;$i<=$count;$i++) { ?>

                                <tr>
                                    <?php //$key = 'delete_data'; ?>
                                    <th scope="row">
                                        Layer <?php echo '<strong>'.$i.'</strong>'; ?>
                                    </th>
                                    <td>
                                    <?php $str = 'data-layer-'.$i; ?>
                                        <fieldset><?php $key = 'trail-story-add-icon-text-box-'.$str?>

                                            
                                           
                                        <?php 
                                        $local_layers = array();
                                        $var = 'data_layer_'.$i;
                                        $post_type = $var;?>
                           <div class="icon-wrapper">
                             <div class="icon-header">
                                        <div class="icon-content">
                                        <div class="<?php echo $post_type; ?>">
                                            <div class="icon-post-type">
                                                <h4><?php// _e( esc_html( $post_type ) ); ?></h4>
                                            </div>
                                            <div class="icon-image-buttons">

                                                <?php $key = 'trail_story_add_icon_text_box_' . $post_type; ?>

                                                <div class="trail-story-icon-button-container">
                                                    <?php // Holster for Image url (Hidden) ?>
                                                    <label for="trail-story-add-icon-button-<?php echo $post_type; ?>">
                                                    <?php _e(esc_html($trail_story_options['trail_story_add_icon_text_box_'. $post_type ]), 'geocmsx'); ?>
                                                    <input type="hidden" 
                                                           id="trail_story_add_icon_text_box_<?php echo $post_type; ?>" 
                                                           class="trail-story-icon-url" 
                                                           name="trail_story_options[trail_story_add_icon_text_box_<?php echo $post_type; ?>]" 
                                                           value="<?php echo $trail_story_options['trail_story_add_icon_text_box_'. $post_type ]; ?>"/>

                                                           <?php if ($trail_story_options['trail_story_add_icon_text_box_'. $post_type ]) {
                                                           }?>

                                                    <?php // Button to add Image icon ?>
                                                    <input type="button" 
                                                           id="trail-story-add-icon-button-<?php echo $post_type; ?>" 
                                                           class="button trail-story-add-icon-button <?php echo $trail_story_options[$key] ? 'hidden' : ''; ?>" 
                                                           name="trail-story-add-icon-button" 
                                                           value="<?php _e( 'Add File', 'geo-mashup-trail-story-add-on' ); ?>"/>

                                                    <?php // Button to remove Image icon ?>
                                                    <input type="button" 
                                                           id="trail-story-remove-icon-button-<?php echo $post_type; ?>" 
                                                           class="button trail-story-remove-icon-button <?php echo !$trail_story_options[$key] ? 'hidden' : ''; ?>" 
                                                           name="trail-story-add-icon-button" 
                                                           value="<?php _e( 'Remove File', 'geo-mashup-trail-story-add-on' ); ?>"/>

                                                           <div style="clear:both;height:0;"></div>
                                                </div>

                                                <div class="trail-story-icon-image-container">

                                                <?php if( $trail_story_options[ $key ] ){ ?>

                                                    <img src="<?php echo esc_attr( $trail_story_options[ $key ] ); ?>" alt="" style="max-width:100%;" />

                                                <?php } ?>

                                                </div>
                                                </label>
                                            </div>
                                            <div style="clear:both;height:0;"></div>
                                        </div></div></div>
                                    </td>
                                </tr>
                                <?php }
                            } ?>
                            <hr>
                        <?php //var_dump($trail_story_options); ?>
                            <hr>
                        <tr>
                            <?php //$key = 'delete_data'; ?>
                            <th scope="row">
                                Data Layer URLs
                            </th>
                            <td>
                                <fieldset>

                                    <?php $key = 'enable_kml'; 
                                    //var_dump($trail_story_options[$key]);?>
                                        <label for="trail_story_options[<?php echo $key; ?>]">Enable</label>

                                        <input id='trail_story_options[<?php echo $key; ?>]' name="trail_story_options[<?php echo $key; ?>]" type="checkbox" value="1" <?php checked(1, $trail_story_options[$key], true ); ?> />
                                <?php $key = 'custom_kml_layers';
                                                                            //var_dump($trail_story_options[$key]); ?>

                                    
                                    <input type="text"  style="width:700px;" name="trail_story_options[<?php echo $key; ?>]" id="trail_story_options[<?php echo $key; ?>]" value="<?php echo $trail_story_options[$key]; ?>" />
                                    <br>
                                    
                                <p class="description">DEFAULT IS COMMA DELIMITED</p>
                                
                            </td>
                        </tr>
                      <tr>
                            <?php //$key = 'delete_data'; ?>
                            <th scope="row">
                                Earthquake GeoRSS
                            </th>
                            <td>
                                <fieldset>
                                <?php $key = 'enable_eq'; 
                                    //var_dump($trail_story_options[$key]);?>
                                        <label for="trail_story_options[<?php echo $key; ?>]">Enable</label>

                                        <input id='trail_story_options[<?php echo $key; ?>]' name="trail_story_options[<?php echo $key; ?>]" type="checkbox" value="1" <?php checked(1, $trail_story_options[$key], true ); ?> />
                                <?php $key = 'eq_geo_rss';
                                                                            //var_dump($trail_story_options[$key]); ?>

                                    
                                    <input type="text"  style="width:700px;" name="trail_story_options[<?php echo $key; ?>]" id="trail_story_options[<?php echo $key; ?>]" value="<?php echo $trail_story_options[$key]; ?>" />
                                    <br>
                                    
                                <p class="description">DEFAULT IS COMMA DELIMITED</p>
                                
                            </td>
                        </tr>
                        
                        <tr>
                            <?php //$key = 'delete_data'; ?>
                            <th scope="row">
                                GeoJSON URLs
                            </th>
                            <td>
                                <fieldset>
                                <?php $key = 'enable_geoson'; 
                                    //var_dump($trail_story_options[$key]);?>
                                        <label for="trail_story_options[<?php echo $key; ?>]">Enable</label>

                                        <input id='trail_story_options[<?php echo $key; ?>]' name="trail_story_options[<?php echo $key; ?>]" type="checkbox" value="1" <?php checked(1, $trail_story_options[$key], true ); ?> />
                                <?php $key = 'geo_json';
                                                                            //var_dump($trail_story_options[$key]); ?>

                                    <label for="trail_story_options[<?php echo $key; ?>]">
                                    </label>
                                    <input type="text"  style="width:700px;" name="trail_story_options[<?php echo $key; ?>]" id="trail_story_options[<?php echo $key; ?>]" value="<?php echo $trail_story_options[$key]; ?>" />

                                    </fieldset>                                   
                                <p class="description">DEFAULT IS COMMA DELIMITED</p>
                                
                            </td>
                        
                        </tr>

                        
                    </tbody>
                </table></p>
                <?php submit_button('Save Options'); ?>
            </div>
            <p><hr></p>
            <div>
                <h1><strong>Map Marker Images</strong></h1>
            <div class="icon-wrapper">
                <div class="icon-header">

                    <div class="icon-header-post-type-title">
                        <h2><?php _e( 'Post Types', 'geo-mashup-trail-story-add-on' ); ?></h2>
                    </div>

                    <div class="icon-header-image-title">
                        <h2><?php _e('Image', 'geo-mashup-trail-story-add-on') ?></h2>
                    </div>

                </div>

                <h3><?php //_e('&nbsp;','geo-mashup-trail-story-add-on'); ?></h3>
                <?php $arr = array('Multi-Marker');$c=0;$i=1;//var_dump($arr);?>
        <?php foreach( $arr as $post_type ) : ?>
            <?php //if ( in_array( $post_type->name, $geo_mashup_options->get( 'overall', 'located_post_types' ) ) ) { ?>

                <div class="icon-content">
                    <div class="<?php echo $post_type; ?>">
                        <div class="icon-post-type">
                            <h4><?php _e( esc_html( $post_type ) ); ?></h4>
                        </div>
                        <div class="icon-image-buttons">

                            <?php $key = 'trail-story-add-icon-text-box-' . $post_type; ?>

                            <div class="trail-story-icon-button-container">
                                <?php // Holster for Image url (Hidden) ?>
                                <input type="hidden" 
                                       id="trail-story-add-icon-text-box-<?php echo $post_type; ?>" 
                                       class="trail-story-icon-url" 
                                       name="trail_story_options[trail-story-add-icon-text-box-<?php echo $post_type; ?>]" 
                                       value="<?php echo $trail_story_options['trail-story-add-icon-text-box-'. $post_type ]; ?>"/>

                                <?php // Button to add Image icon ?>
                                <input type="button" 
                                       id="trail-story-add-icon-button-<?php echo $post_type; ?>" 
                                       class="button trail-story-add-icon-button <?php echo $trail_story_options[$key] ? 'hidden' : ''; ?>" 
                                       name="trail-story-add-icon-button" 
                                       value="<?php _e( 'Add Image', 'geo-mashup-trail-story-add-on' ); ?>"/>

                                <?php // Button to remove Image icon ?>
                                <input type="button" 
                                       id="trail-story-remove-icon-button-<?php echo $post_type; ?>" 
                                       class="button trail-story-remove-icon-button <?php echo !$trail_story_options[$key] ? 'hidden' : ''; ?>" 
                                       name="trail-story-add-icon-button" 
                                       value="<?php _e( 'Remove Image', 'geo-mashup-trail-story-add-on' ); ?>"/>

                                       <div style="clear:both;height:0;"></div>
                            </div>

                            <div class="trail-story-icon-image-container">

                            <?php if( $trail_story_options[ $key ] ){ ?>

                                <img src="<?php echo esc_attr( $trail_story_options[ $key ] ); ?>" alt="" style="max-width:100%;" />
                                <?php $trail_story_options['custom_icons'] = 'true'; ?>

                            <?php } ?>

                            </div>

                        </div>
                        <div style="clear:both;height:0;"></div>
                    </div>
                </div>
            <?php //} ?>
        <?php endforeach; // post types ?>

        <?php foreach( get_post_types( array( 'show_ui' => true ), 'objects' ) as $post_type ) : ?>
            <?php if ( in_array( $post_type->name, $geo_mashup_options->get( 'overall', 'located_post_types' ) ) ) { ?>

                <div class="icon-content">
                    <div class="<?php echo $post_type->name; ?>">
                        <div class="icon-post-type">
                            <h4><?php _e( esc_html( $post_type->labels->name ) ); ?></h4>
                        </div>
                        <div class="icon-image-buttons">

                            <?php $key = 'trail-story-add-icon-text-box-' . $post_type->name; ?>

                            <div class="trail-story-icon-button-container">
                                <?php // Holster for Image url (Hidden) ?>
                                <input type="hidden" 
                                       id="trail-story-add-icon-text-box-<?php echo $post_type->name; ?>" 
                                       class="trail-story-icon-url" 
                                       name="trail_story_options[trail-story-add-icon-text-box-<?php echo $post_type->name; ?>]" 
                                       value="<?php echo $trail_story_options['trail-story-add-icon-text-box-'. $post_type->name ]; ?>"/>

                                <?php // Button to add Image icon ?>
                                <input type="button" 
                                       id="trail-story-add-icon-button-<?php echo $post_type->name; ?>" 
                                       class="button trail-story-add-icon-button <?php echo $trail_story_options[$key] ? 'hidden' : ''; ?>" 
                                       name="trail-story-add-icon-button" 
                                       value="<?php _e( 'Add Image', 'geo-mashup-trail-story-add-on' ); ?>"/>

                                <?php // Button to remove Image icon ?>
                                <input type="button" 
                                       id="trail-story-remove-icon-button-<?php echo $post_type->name; ?>" 
                                       class="button trail-story-remove-icon-button <?php echo !$trail_story_options[$key] ? 'hidden' : ''; ?>" 
                                       name="trail-story-add-icon-button" 
                                       value="<?php _e( 'Remove Image', 'geo-mashup-trail-story-add-on' ); ?>"/>

                                       <div style="clear:both;height:0;"></div>
                            </div>

                            <div class="trail-story-icon-image-container">

                            <?php if( $trail_story_options[ $key ] ){ ?>

                                <img src="<?php echo esc_attr( $trail_story_options[ $key ] ); ?>" alt="" style="max-width:100%;" />

                            <?php } ?>

                            </div>

                        </div>
                        <div style="clear:both;height:0;"></div>
                    </div>
                </div>
            <?php } ?>
        <?php endforeach; // post types ?>
        <p><hr></p>
        <?php $include_taxonomies = $geo_mashup_options->get( 'overall', 'include_taxonomies' ); ?>

        <h1><strong><?php _e('Categories','geo-mashup-trail-story-add-on'); ?></strong></h1>

        <?php if ( !empty( $include_taxonomies ) and !defined( 'GEO_MASHUP_DISABLE_CATEGORIES' ) ) : ?>
            <?php foreach( $include_taxonomies as $include_taxonomy ) : ?>

                <?php $taxonomy_object = get_taxonomy( $include_taxonomy ); ?>
                <?php $taxonomy_options = $geo_mashup_options->get( 'global_map', 'term_options', $include_taxonomy ); ?>
                <?php $terms = get_terms( $include_taxonomy, array( 'hide_empty' => false ) ); ?>

                <h3 style="font-weight:bold;"><?php echo $taxonomy_object->label; ?></h3>

                <?php if ( is_array($terms) ) : ?>
                    <?php foreach( $terms as $term ) : ?>
                        <div class="icon-content">
                            <div class="<?php echo $term->slug; ?>">
                                <div class="icon-post-type">
                                    <h4><?php _e( esc_html( $term->name ) ); ?></h4>
                                </div>
                                <div class="icon-image-buttons">
                            
                                    <?php $key = 'trail-story-add-icon-text-box-' . $term->slug; ?>

                                    <div class="trail-story-icon-button-container">
                                        <?php // Holster for Image url (Hidden) ?>
                                        <input type="hidden" 
                                               id="trail-story-add-icon-text-box-<?php echo $term->slug; ?>" 
                                               class="trail-story-icon-url" 
                                               name="trail_story_options[trail-story-add-icon-text-box-<?php echo $term->slug; ?>]" 
                                               value="<?php echo $trail_story_options['trail-story-add-icon-text-box-'. $term->slug ]; ?>"/>

                                        <?php // Button to add Image icon ?>
                                        <input type="button" 
                                               id="trail-story-add-icon-button-<?php echo $term->slug; ?>" 
                                               class="button trail-story-add-icon-button <?php echo $trail_story_options[$key] ? 'hidden' : ''; ?>" 
                                               name="trail-story-add-icon-button" 
                                               value="<?php _e( 'Add Image', 'geo-mashup-trail-story-add-on' ); ?>"/>

                                        <?php // Button to remove Image icon ?>
                                        <input type="button" 
                                               id="trail-story-remove-icon-button-<?php echo $term->slug; ?>" 
                                               class="button trail-story-remove-icon-button <?php echo !$trail_story_options[$key] ? 'hidden' : ''; ?>" 
                                               name="trail-story-add-icon-button" 
                                               value="<?php _e( 'Remove Image', 'geo-mashup-trail-story-add-on' ); ?>"/>

                                               <div style="clear:both;height:0;"></div>
                                    </div>

                                    <div class="trail-story-icon-image-container">

                                    <?php if( $trail_story_options[ $key ] ){ ?>

                                        <img src="<?php echo esc_attr( $trail_story_options[ $key ] ); ?>" alt="" style="max-width:100%;" />

                                    <?php } ?>

                                    </div>

                                </div>
                                <div style="clear:both;height:0;"></div>
                            </div>
                        </div>

                    <?php endforeach; // terms as term ?>   
                <?php endif; ?>
            
            <?php endforeach; // include taxonomies as included taxonomy ?>
        <?php endif; ?>
            <div style="clear:both;height:0;"></div>
        </div>
       <br><br>

    <?php }
    // TODO: Input fields for KMLs
    /**
     * Get the settings option array and print one of its values
     */
    /*public function trail_story_setting_callback() {
        //Get plugin options
        global $trail_story_options, $geo_mashup_options;
        $trail_story_options = (array) get_option( 'trail_story_settings' );
        if( isset( $trail_story_options['trail_story_option'] ) ) { ?>
            <input type="checkbox" id="trail_story_settings" name="trail_story_settings[trail_story_setting]" value="1" <?php checked( 1, $trail_story_options['trail_story_setting'], false ); ?> />
        
        <?php } else { ?>
            <input type="checkbox" id="trail_story_settings" name="trail_story_settings[trail_story_setting]" value="1" <?php checked( 1, $trail_story_options['trail_story_setting'], false ); ?> />
       
        <?php }
        echo $html;
    }*/
}
if( is_admin() )
    $trail_story = new TrailStorySettings();