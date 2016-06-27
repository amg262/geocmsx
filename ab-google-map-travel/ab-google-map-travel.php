<?php
/*
  Plugin Name: AB Google Map Travel (AB-MAP)
  Plugin URI: http://wp.aboobacker.com
  Version: 4.1
  Author: Aboobacker P Ummer
  Author URI: http://aboobacker.com
  Description: If you are a taxi provider, make your customers allow to calculate their travel distance, fare with different charges for day and night travels.
  Tags: Google Maps, Distance Calculator, Google Maps, Calculate Distance, Driving Directions, Google Travel, AB Google Map Travel, Abooze Map Plugin
 */
global $wp_version;
// Wordppress Version Check
if (version_compare($wp_version, '3.0', '<')) {
    exit($exit_msg . " Please upgrade your wordpress.");
}
//Accept only numeric values
function admin_scripts() { 
if(isset($_GET['page']) && $_GET['page'] == 'ab_map_options' ) {
?>
<script type="text/javascript">
	jQuery(document).ready(function(e) {
		jQuery('#ab_sec_form input:text').not('#curr_format').keyup(function(){
			var this_field = jQuery(this).val();
			if (isNaN( this_field / 1) == true) {
				alert('Value should be numeric');
				jQuery(this).val('');
			}
		});
	});
</script>
<?php } }
add_action('admin_head','admin_scripts');
// Link javascript & css files
function abinclude_files($lang) {
    wp_enqueue_style('abdistance_calc_css', plugins_url('/css/styles.css', __FILE__));
    wp_enqueue_style('google_map_css', 'http://code.google.com/apis/maps/documentation/javascript/examples/default.css');
    wp_enqueue_script('google_map_js', 'http://maps.google.com/maps/api/js?sensor=false&language=' . get_option('language'));
    wp_enqueue_script('abdistance_calc_js', plugins_url('/js/ab-get-distance.js', __FILE__));
}
// Include files on page load
add_action('init', 'abinclude_files');
function map_onload($lat, $lng) {
    $output = '';
    $output .= '<script language="javascript" type="text/javascript">';
    $output .= 'window.onload=function(){initialize(' . $lat . ', ' . $lng . ');}';
    $output .= '</script>';
    echo $output;
}
function abdistance_calculator() {
    $lat = get_option('latitude');
    $lng = get_option('longitude');
    map_onload($lat, $lng);
    $result = "";
    $result .= '<script language="javascript">
 function get_distance(form){
     from = form.place_from.value;
     to = form.place_to.value;
     calcRoute(from,to);
 }
 
</script>
<div id="abgdc-wrap">
 <div id="map_canvas" style="position: relative;width:' . get_option('map_width') . 'px;height:' . get_option('map_height') . 'px;margin:0px auto;border:solid 5px #003;" ></div><!-- #map_canvas -->
<form action="" method="post" name="form1">
<table class="abgdc-table">
<tr>
<td>From: </td><td><input type="text" name="place_from" class="txt" /></td>
<td>To: </td><td><input type="text" name="place_to" class="txt" /></td>
<td>Travel Time: 
<input type="radio" id="day_time" name="travel_time" value="day" checked="checked" /> Day 
<input type="radio" id="night_time" name="travel_time" value="night" /> Night 
</td>
<br />
<td><input type="button" value="Get Quote" onclick="get_distance(this.form)"/>
<input type="hidden" value="'. get_option('zoom').'" id="map_zoom"/>
<input type="hidden" value="'. get_option('curr_format').'" id="curr_format"/>
</td>
</tr>
</table>
</form>
 <div id="distance"></div><!-- #distance -->
 <div id="steps"></div><!-- #steps -->
</div><!-- #abgdc-wrap -->';?>
<input type="hidden" value="<?php echo get_option('day_more_five_fare');?>" id="day_more_five" />
<input type="hidden" value="<?php echo get_option('day_less_five_fare');?>" id="day_less_five" />
<input type="hidden" value="<?php echo get_option('more_five_fare');?>" id="night_more_five" />
<input type="hidden" value="<?php echo get_option('less_five_fare');?>" id="night_less_five" />
<?php
    return $result;
}
function ab_shortcode($atts) {
    $result = abdistance_calculator();
    return $result;
}
// Add [AB-MAP] shortcode
add_shortcode("AB-MAP", "ab_shortcode");
/* ==============================================================================
 *
 *              Admin Section for the Plugin
 *
  ============================================================================== */
function ab_set_options() {
    add_option('latitude', '9.93123', 'Default Latitude');
    add_option('longitude', '76.26730', 'Default Longitude');
    add_option('language', 'en', 'Default Longitude');
    add_option('map_width', '500', 'Default Longitude');
    add_option('map_height', '300', 'Default Longitude');
    add_option('zoom', '7', 'Default Zoom');
    add_option('less_five_fare', '3', 'Less Five');
    add_option('more_five_fare', '2.5', 'More Five');
    add_option('day_less_five_fare', '2', 'Reg Less Five');
    add_option('day_more_five_fare', '1.5', 'Reg More Five');
	
	add_option('curr_format', '$', 'Currency format');
}
function ab_unset_options() {
    delete_option('latitude');
    delete_option('longitude');
    delete_option('language');
    delete_option('map_width');
    delete_option('map_height');
    delete_option('zoom');
    delete_option('less_five_fare');
    delete_option('more_five_fare');
    delete_option('day_less_five_fare');
    delete_option('day_more_five_fare');
	delete_option('curr_format');
}
register_activation_hook(__FILE__, 'ab_set_options');
register_deactivation_hook(__FILE__, 'ab_unset_options');
function ab_options_page() { ?>
<div class="wrap">
  <table class="widefat" style="width: 600px;" >
    <thead>
      <tr>
        <th colspan="2"><div class="icon32" id="icon-edit"></div>
          <h2>AB Google Map Travel Options</h2></th>
      </tr>
    </thead>
  </table>
  <br/>
  <?php
        if ($_REQUEST['submit']) {
            ab_update_options();
        }
        ab_print_options_form();
        ?>
</div>
<?php
}
function ab_update_options() {
    $lat = isset($_REQUEST['lat']) ? $_REQUEST['lat'] != "" ? $_REQUEST['lat'] : 9.93123  : 9.93123;
    update_option('latitude', $lat);
    $long = isset($_REQUEST['long']) ? $_REQUEST['long'] != "" ? $_REQUEST['long'] : 76.26730  : 76.26730;
    update_option('longitude', $long);
    $lang = isset($_REQUEST['lang']) ? $_REQUEST['lang'] != "" ? $_REQUEST['lang'] : 'en'  : 'en';
    update_option('language', $lang);
    $map_width = isset($_REQUEST['map_width']) ? $_REQUEST['map_width'] != "" ? $_REQUEST['map_width'] : 500  : 500;
    update_option('map_width', $map_width);
    $map_height = isset($_REQUEST['map_height']) ? $_REQUEST['map_height'] != "" ? $_REQUEST['map_height'] : 300  : 300;
    update_option('map_height', $map_height);
    
    $zoom = isset($_REQUEST['zoom']) ? $_REQUEST['zoom'] != "" ? $_REQUEST['zoom'] : 7  : 7;
    update_option('zoom', $zoom);
    
    $less_five_fare = isset($_REQUEST['less_five_fare']) ? $_REQUEST['less_five_fare'] != "" ? $_REQUEST['less_five_fare'] : 3  : 3;
    update_option('less_five_fare', $less_five_fare);
    
    $more_five_fare = isset($_REQUEST['more_five_fare']) ? $_REQUEST['more_five_fare'] != "" ? $_REQUEST['more_five_fare'] : 2.5  : 2.5;
    update_option('more_five_fare', $more_five_fare);
    $day_less_five_fare = isset($_REQUEST['day_less_five_fare']) ? $_REQUEST['day_less_five_fare'] != "" ? $_REQUEST['day_less_five_fare'] : 2  : 2;
    update_option('day_less_five_fare', $day_less_five_fare);
    
    $day_more_five_fare = isset($_REQUEST['day_more_five_fare']) ? $_REQUEST['day_more_five_fare'] != "" ? $_REQUEST['day_more_five_fare'] : 1.5  : 1.5;
    update_option('day_more_five_fare', $day_more_five_fare);
	$curr_format = isset($_REQUEST['curr_format']) ? $_REQUEST['curr_format'] != "" ? $_REQUEST['curr_format'] : '$'  : '$';
    update_option('curr_format', $curr_format);
    echo '<div id="message" class="updated fade"><p><strong>Options Saved...</strong></p></div>';
}
function ab_print_options_form() {
    $default_latitude = get_option('latitude');
    $default_longitude = get_option('longitude');
    $default_language = get_option('language');
    $default_map_width = get_option('map_width');
    $default_map_height = get_option('map_height');
    $default_zoom = get_option('zoom');
    $default_less_five_fare = get_option('less_five_fare');
    $default_more_five_fare = get_option('more_five_fare');
    $default_day_less_five_fare = get_option('day_less_five_fare');
    $default_day_more_five_fare = get_option('day_more_five_fare');
	$default_curr_format = get_option('curr_format');
    
    ?>
<form method="post" id="ab_sec_form">
  <table class="widefat" style="width: 600px;" >
    <thead>
      <tr>
        <th colspan="2">To configure the AB-MAP Plugin update the following values</th>
      </tr>
    </thead>
    <tfoot>
      <tr>
        <th colspan="2"> </th>
      </tr>
    </tfoot>
    <tbody>
      <tr>
        <td><label for="latitude">Latitude: </label></td>
        <td><input type="text" name="lat" size="30" id="map_lat" value="<?php echo strip_tags($default_latitude); ?>" />
          <small>Default: 9.93123</small></td>
      </tr>
      <tr>
        <td><label for="longitude">Longitude: </label></td>
        <td><input type="text" name="long" size="30" id="map_long" value="<?php echo strip_tags($default_longitude); ?>" />
          <small>Default: 76.26730</small></td>
      </tr>
      <tr>
        <td><label for="language">Language: </label></td>
        <td><select name="lang" value="<?php echo $default_language; ?>" >
            <option value="ar" <?php echo ($default_language == "ar") ? 'selected="selected"' : ''; ?> >Arabic</option>
            <option value="eu" <?php echo ($default_language == "eu") ? 'selected="selected"' : ''; ?> >Basque</option>
            <option value="bg" <?php echo ($default_language == "bg") ? 'selected="selected"' : ''; ?> >Bulgarian</option>
            <option value="ca" <?php echo ($default_language == "ca") ? 'selected="selected"' : ''; ?> >Catalan</option>
            <option value="cs" <?php echo ($default_language == "cs") ? 'selected="selected"' : ''; ?> >Czech</option>
            <option value="da" <?php echo ($default_language == "da") ? 'selected="selected"' : ''; ?> >Danish</option>
            <option value="en" <?php echo ($default_language == "en") ? 'selected="selected"' : ''; ?> >English</option>
            <option value="de" <?php echo ($default_language == "de") ? 'selected="selected"' : ''; ?> >German</option>
            <option value="el" <?php echo ($default_language == "el") ? 'selected="selected"' : ''; ?> >Greek</option>
            <option value="es" <?php echo ($default_language == "es") ? 'selected="selected"' : ''; ?> >Spanish</option>
            <option value="fa" <?php echo ($default_language == "fa") ? 'selected="selected"' : ''; ?> >Farsi</option>
            <option value="fi" <?php echo ($default_language == "fi") ? 'selected="selected"' : ''; ?> >Finnish</option>
            <option value="fil" <?php echo ($default_language == "fil") ? 'selected="selected"' : ''; ?>>Filipino</option>
            <option value="fr" <?php echo ($default_language == "fr") ? 'selected="selected"' : ''; ?> >French</option>
            <option value="gl" <?php echo ($default_language == "gl") ? 'selected="selected"' : ''; ?> >Galician</option>
            <option value="gu" <?php echo ($default_language == "gu") ? 'selected="selected"' : ''; ?> >Gujarati</option>
            <option value="hi" <?php echo ($default_language == "hi") ? 'selected="selected"' : ''; ?> >Hindi</option>
            <option value="hr" <?php echo ($default_language == "hr") ? 'selected="selected"' : ''; ?> >Croatian</option>
            <option value="hu" <?php echo ($default_language == "hu") ? 'selected="selected"' : ''; ?> >Hungarian</option>
            <option value="id" <?php echo ($default_language == "id") ? 'selected="selected"' : ''; ?> >Indonesian</option>
            <option value="iw" <?php echo ($default_language == "iw") ? 'selected="selected"' : ''; ?> >Hebrew</option>
            <option value="ja" <?php echo ($default_language == "ja") ? 'selected="selected"' : ''; ?> >Japanese</option>
            <option value="kn" <?php echo ($default_language == "kn") ? 'selected="selected"' : ''; ?> >Kannada</option>
            <option value="ko" <?php echo ($default_language == "ko") ? 'selected="selected"' : ''; ?> >Korean</option>
            <option value="lt" <?php echo ($default_language == "lt") ? 'selected="selected"' : ''; ?> >Lithuanian</option>
            <option value="lv" <?php echo ($default_language == "lv") ? 'selected="selected"' : ''; ?> >Latvian</option>
            <option value="ml" <?php echo ($default_language == "ml") ? 'selected="selected"' : ''; ?> >Malayalam</option>
            <option value="mr" <?php echo ($default_language == "mr") ? 'selected="selected"' : ''; ?> >Marathi</option>
            <option value="nl" <?php echo ($default_language == "nl") ? 'selected="selected"' : ''; ?> >Dutch</option>
            <option value="nn" <?php echo ($default_language == "nn") ? 'selected="selected"' : ''; ?> >Norwegian Nynorsk</option>
            <option value="no" <?php echo ($default_language == "no") ? 'selected="selected"' : ''; ?> >Norwegian</option>
            <option value="or" <?php echo ($default_language == "or") ? 'selected="selected"' : ''; ?> >Oriya</option>
            <option value="pl" <?php echo ($default_language == "pl") ? 'selected="selected"' : ''; ?> >Polish</option>
            <option value="pt" <?php echo ($default_language == "pt") ? 'selected="selected"' : ''; ?> >PortuguesE</option>
            <option value="pt-BR" <?php echo ($default_language == "pt-BR") ? 'selected="selected"' : ''; ?> >Portuguese(Brazil)</option>
            <option value="pt-PT" <?php echo ($default_language == "pt-PT") ? 'selected="selected"' : ''; ?> >Portuguese(Portugal)</option>
            <option value="rm" <?php echo ($default_language == "rm") ? 'selected="selected"' : ''; ?> >Romansch</option>
            <option value="ro" <?php echo ($default_language == "ro") ? 'selected="selected"' : ''; ?> >Romanian</option>
            <option value="ru" <?php echo ($default_language == "ru") ? 'selected="selected"' : ''; ?> >Russian</option>
            <option value="sk" <?php echo ($default_language == "sk") ? 'selected="selected"' : ''; ?> >Slovak</option>
            <option value="sr" <?php echo ($default_language == "sr") ? 'selected="selected"' : ''; ?> >Serbian</option>
            <option value="sv" <?php echo ($default_language == "sv") ? 'selected="selected"' : ''; ?> >Swedish</option>
            <option value="tl" <?php echo ($default_language == "tl") ? 'selected="selected"' : ''; ?> >Tagalog</option>
            <option value="ta" <?php echo ($default_language == "ta") ? 'selected="selected"' : ''; ?> >Tamil</option>
            <option value="te" <?php echo ($default_language == "te") ? 'selected="selected"' : ''; ?> >Telugu</option>
            <option value="th" <?php echo ($default_language == "th") ? 'selected="selected"' : ''; ?> >Thai</option>
            <option value="tr" <?php echo ($default_language == "tr") ? 'selected="selected"' : ''; ?> >Turkish</option>
            <option value="uk" <?php echo ($default_language == "uk") ? 'selected="selected"' : ''; ?> >Ukrainian</option>
            <option value="vi" <?php echo ($default_language == "vi") ? 'selected="selected"' : ''; ?> >Vietnamese</option>
            <option value="zh-CN" <?php echo ($default_language == "zh-CN") ? 'selected="selected"' : ''; ?> >Chinese (Simplified)</option>
            <option value="zh-TW" <?php echo ($default_language == "zh-TW") ? 'selected="selected"' : ''; ?> >Chinese (Traditional)</option>
          </select>
          <small>Default language for directions</small></td>
      </tr>
      <tr>
        <td><label for="map_width">Map Width: </label></td>
        <td><input type="text" name="map_width" size="30" id="map_width" value="<?php echo strip_tags($default_map_width); ?>" />
          <small>Default: 500</small></td>
      </tr>
      <tr>
        <td><label for="map_height">Map Height: </label></td>
        <td><input type="text" name="map_height" size="30" id="map_height" value="<?php echo strip_tags($default_map_height); ?>" />
          <small>Default: 300</small></td>
      </tr>
      <tr>
        <td><label for="zoom">Map Zoom: </label></td>
        <td><input type="text" name="zoom" size="30" id="map_zoom" value="<?php echo strip_tags($default_zoom); ?>" />
          <small>Default: 7</small></td>
      </tr>
    </tbody>
  </table>
  <br />
  <table class="widefat" style="width: 600px;" >
    <thead>
      <tr>
        <th colspan="2"><strong>Day Time Charge: </strong> To configure the travel charges update the following values</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td><label for="day_less_five_fare">Charge: <5 kms: </label></td>
        <td><input type="text" name="day_less_five_fare" size="30" value="<?php echo strip_tags($default_day_less_five_fare); ?>" />
          <small>Default: 2</small></td>
      </tr>
      <tr>
        <td><label for="day_more_five_fare">Charge: >5 kms: </label></td>
        <td><input type="text" name="day_more_five_fare" size="30" value="<?php echo strip_tags($default_day_more_five_fare); ?>" />
          <small>Default: 1.5</small></td>
      </tr>
    </tbody>
  </table>
  <table class="widefat" style="width: 600px;" >
    <thead>
      <tr>
        <th colspan="2"><strong>Night Time Charge: </strong> To configure the travel charges update the following values</th>
      </tr>
    </thead>
    <tfoot>
      <tr>
        <th colspan="2">Add <strong>[AB-MAP]</strong> shortcode to the post/page.</th>
      </tr>
    </tfoot>
    <tbody>
      <tr>
        <td><label for="less_five_fare">Charge: <5 kms: </label></td>
        <td><input type="text" name="less_five_fare" size="30" value="<?php echo strip_tags($default_less_five_fare); ?>" />
          <small>Default: 3</small></td>
      </tr>
      <tr>
        <td><label for="more_five_fare">Charge: >5 kms: </label></td>
        <td><input type="text" name="more_five_fare" size="30" value="<?php echo strip_tags($default_more_five_fare); ?>" />
          <small>Default: 2.5</small></td>
      </tr>
      <tr>
        <td></td>
        <td></td>
      </tr>
      <tr>
        <td><label for="curr_format">Currency format: </label></td>
        <td><input type="text" name="curr_format" id="curr_format" size="30" value="<?php echo strip_tags($default_curr_format); ?>" />
          <small>Default: $</small></td>
      </tr>
      <tr>
        <td></td>
        <td><input class="button-primary" type="submit" name="submit" value="Update Settings"/></td>
      </tr>
    </tbody>
  </table>
</form>
<?php
}
function add_menu_item() {
    add_menu_page('AB-MAP Options', 'Google Map Travel', 'add_users','ab_map_options', 'ab_options_page', plugins_url('/images/icon.png', __FILE__));
}
add_action('admin_menu', 'add_menu_item');
?>