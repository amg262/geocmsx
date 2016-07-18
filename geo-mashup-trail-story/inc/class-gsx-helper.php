<?php

defined( 'ABSPATH' ) or die( 'Plugin file cannot be accessed directly.' );


/*class GsxHelper {

	public function __construct() {
		add_action('media_buttons', array($this, 'thickbox_shortcodes', 11));
		add_action('admin_footer', array($this, 'thickbox_content'));

	}
public function thickbox_shortcodes() {
    ?>
<a id="thickbox_shortcode_button" title="Available Shortcodes" class="thickbox" href="#TB_inline?width=670&inlineId=thickbox_shortcode_window">
        <img src="<?php echo bloginfo('url'); ?>/wp-admin/images/media-button-other.gif" alt="" width="18px" height="18px" />
    </a>
<?php  }

public function thickbox_content(){  ?></pre>
<div id="thickbox_shortcode_window" style="display: none;">
	<table cellspacing="0" cellpadding="5">
<tbody>
<tr>
<th>Shortcode</th>
<th>Description</th>
</tr>
<tr>
<td><a id="test1" href="javascript:void(0);">Test shortcode</a></td>
<td>Click to insert the test shortcode</td>
</tr>
<tr>
<td><a id="test2" href="javascript:void(0);">Test2 shortcode</a></td>
<td>Click to insert the test2 shortcode</td>
</tr>
<tr>
<td><a id="test3" href="javascript:void(0);">Test3 shortcode</a></td>
<td>Click to insert the test3 shortcode</td>
</tr>
</tbody>
</table>
<pre>
<script type="text/javascript">// <![CDATA[
 jQuery(function($){
 //javascript code here
 	$('.insert_shortcode').unbind('click').bind("click",function() {
 //prepare shortcode variabel
 var shortcode = '';
 
 //get the id of the clicked element and load the correct shortcode
 switch(this.id){
 case 'test1':
 shortcode = '[test1 param="tester"]Content for shortcode test1[/test1]';
 break;
 case 'test2':
 shortcode = '[test2 param="tester"]Content for shortcode test2[/test2]';
 break;
 case 'test3':
 shortcode = '[test3 param="tester"]Content for shortcode test3[/test3]';
 break;
 }
 
 //check if visual editor is active or not
 is_tinyMCE_active = false;
 if (typeof(tinyMCE) != "undefined") {
 if (tinyMCE.activeEditor != null) {
 is_tinyMCE_active = true;
 }
 
 }
 
//append shortcode to the content of the editor
 if ( is_tinyMCE_active ) {
 tinymce.execCommand('mceInsertContent', false, shortcode);
 } else {
 var wpEditor = $('.wp-editor-area');
 wpEditor.append(shortcode);
 }
 return false;
 });

 });
 
// ]]></script>

</div>
<pre>
 <?php }

}

$helper = new GsxHelper();*/
add_action('media_buttons','add_sc_select',11);

add_action('admin_enqueue_scripts', 'gsx_fa');

function gsx_fa() {
	wp_register_style( 'gsx_fa', plugins_url( 'font-awesome-4.4.0/css/font-awesome.css', __FILE__ ));
    wp_enqueue_style( 'gsx_fa' );
}
function add_sc_select(){
    //global $shortcode_tags;
	$shortcode_tags = array('geo_mashup_map', 'geo_mashup_category_legend', 
		'geo_mashup_term_legend', 'frontend_trail_story_map');
     /* ------------------------------------- */
     /* enter names of shortcode to exclude bellow */
     /* ------------------------------------- */
    $exclude = array("wp_caption", "embed");
    echo '&nbsp;<a href="#" class="button delete-secondary" id="add_map_btn"><i class="fa fa-map-o" aria-hidden="true">&nbsp;</i>
Add Map</a><select id="sc_select"><option>Shortcode</option>';
    foreach ($shortcode_tags as $key ){
            if(!in_array($key,$exclude)){
            $shortcodes_list .= '<option value="['.$key.']">'.$key.'</option>';
            }
        }
     echo $shortcodes_list;
     echo '</select>';
}
add_action('admin_head', 'button_js');
function button_js() {
        echo '<script type="text/javascript">
        jQuery(document).ready(function(){
        	jQuery("#")
           jQuery("#sc_select").change(function() {
                          send_to_editor(jQuery("#sc_select :selected").val());
                          return false;
                });
        });
        </script>';
}