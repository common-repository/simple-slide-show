<?php

/*
Plugin Name: Simple Slide Show
Plugin URI: http://wordpress.org/#
Description: Displays slideshow of images
Author: Alex Mansfield
Version: 1.1
Author URI: http://alexmansfield.com/
*/


/******************** slide show functions ********************/

// display slide show
function am_simpleslideshow($array) {
   $options = get_option('plugin_options');

   extract(shortcode_atts(array(
         'location' => 'http://localhost',
         'transition_effect' => $options['transition_effect'],
         'delay' => $options['delay'],
         'transition_time' => $options['transition_time'],
         'height' => $options['height'],
         'width' => $options['width']),
         $array));
   if(substr($location, 0, 1) != '/'){$location = '/' . $location;}
   if(substr($location, -1) != '/'){$location = $location . '/';}
   $location = $_SERVER['DOCUMENT_ROOT'] . $location;
   if(is_dir($location)){
      $html = '<div id="slider">' . "\n";
      foreach(glob($location . '{*.jpg,*.jpeg,*.JPG,*.JPEG,*.png,*.PNG}', GLOB_BRACE) as $image){
         $image = str_replace($_SERVER['DOCUMENT_ROOT'], '', $image);
         $html .= '<img src="' . WP_CONTENT_URL . '/plugins/simple-slide-show/timthumb.php?src=' . $image . '&h=' . $height . '&w=' . $width . '" alt="" title="" />' . "\n";
      }
      $html .= '</div>';
      $html.= '<script type="text/javascript">jQuery(window).load(function() {jQuery("#slider").nivoSlider({pauseTime:' . $delay . ',effect:"' . $transition_effect . '",animSpeed:' . $transition_time . ',directionNav:false,controlNav:false});});</script>';
   }else{
      $html = 'Error: The image folder does not exist or is unreadable';
   }
   return $html;
}

// import files (css and javascript)
wp_enqueue_script('jquery');
function am_simpleslideshow_header(){
   echo '<link rel="stylesheet" href="' . WP_CONTENT_URL . '/plugins/simple-slide-show/nivo/nivo-slider.css" type="text/css" media="screen" />' . "\n";
   echo '<script type="text/javascript" src="' . WP_CONTENT_URL . '/plugins/simple-slide-show/nivo/jquery.nivo.slider.pack.js"></script>' . "\n";
}

// add shortcodes and actions
add_shortcode('simpleslideshow', 'am_simpleslideshow');
add_action('wp_head', 'am_simpleslideshow_header');


/******************** options page functions ********************/

// add hooks and actions
register_activation_hook(__FILE__, 'am_default_values');
add_action('admin_init', 'am_register_settings' );
add_action('admin_menu', 'am_add_options_page');

// add options page to settings menu
function am_add_options_page() {
   add_options_page('Simple Slide Show Settings', 'Simple Slide Show', 'administrator', __FILE__, 'am_simpleslideshow_display_options');
}

// Register our settings. Add the settings section, and settings fields
function am_register_settings(){
   register_setting('plugin_options', 'plugin_options');
   add_settings_section('main_section', 'Default Settings', 'cb_main_section', __FILE__);
   
   add_settings_field('transition_effect',  'Transition Effect','cb_dropdown', __FILE__, 'main_section', $args = array('id' => 'transition_effect', 'options' => 'fade,fold,sliceDown,sliceDownLeft,sliceUp,sliceUpLeft,sliceUpDown,sliceUpDownLeft,random'));
   add_settings_field('delay',              'Delay',            'cb_textbox',  __FILE__, 'main_section', $args = array('id' => 'delay', 'description' => 'Time in milliseconds between transitions. (1000 = 1 second)'));
   add_settings_field('transition_time',    'Transition Time',  'cb_textbox',  __FILE__, 'main_section', $args = array('id' => 'transition_time', 'description' => 'Transition time in milliseconds. (1000 = 1 second)'));
   add_settings_field('width',              'Width',            'cb_textbox',  __FILE__, 'main_section', $args = array('id' => 'width', 'description' => 'Default slide show width in pixels.'));
   add_settings_field('height',             'Height',           'cb_textbox',  __FILE__, 'main_section', $args = array('id' => 'height', 'description' => 'Default slide show height in pixels.'));
}

// display the admin options page
function am_simpleslideshow_display_options() {
?>
   <div class="wrap">
      <div class="icon32" id="icon-options-general"><br></div>
      <h2>Simple Slide Show Options</h2>
      <form action="options.php" method="post">
      <?php settings_fields('plugin_options'); ?>
      <?php do_settings_sections(__FILE__); ?>
      <p class="submit">
         <input name="Submit" type="submit" class="button-primary" value="<?php esc_attr_e('Save Changes'); ?>" />
      </p>
      </form>
   </div>
<?php
}

// set default options (only on first activation)
function am_default_values() {
   $tmp = get_option('plugin_options');
   if(!is_array($tmp)){
      $arr = array('transition_effect' => 'fade', 'delay' => '5000', 'transition_time' => '500', 'height' => '200', 'width' => '300');
      update_option('plugin_options', $arr);
   }
}


/******************** callback functions ********************/

// html for main_section
function  cb_main_section() {
   echo '<p>These settings will be used if they are not set in the shortcode.</p>';
}

// textbox
function cb_textbox($args) {
   $options = get_option('plugin_options');
   echo '<input id="' . $args['id'] . '" name="plugin_options[' . $args['id'] . ']" size="40" type="text" value="' . $options[$args['id']] . '" class="regular-text" />' . "\n";
   if($args['description']){
      echo '<span class="description">' . $args['description'] . '</span>';
   }
}

// textarea
function cb_textarea($args) {
   $options = get_option('plugin_options');
   if($args['description']){
      echo '<span class="description">' . $args['description'] . '</span><br />' . "\n";
   }
   echo '<textarea id="' . $args['id'] . '" name="plugin_options[' . $args['id'] . ']" rows="7" cols="50" type="textarea">' . $options[$args['id']] . '</textarea>' . "\n";
}

// dropdown
function  cb_dropdown($args) {
   $options = get_option('plugin_options');
   $choices = explode(',', $args['options']);
   echo '<select id="' . $args['id'] . '" name="plugin_options[' . $args['id'] . ']">' . "\n";
   foreach($choices as $choice) {
      $selected = ($options[$args['id']]==$choice) ? 'selected="selected"' : '';
      echo '<option value="' . $choice . '" ' . $selected . '>' . $choice . '</option>' . "\n";
   }
   echo '</select>' . "\n";
   if($args['description']){
      echo '<span class="description">' . $args['description'] . '</span>' . "\n";
   }
}

// radio buttons
function cb_radio($args) {
   $options = get_option('plugin_options');
   $choices = explode(',', $args['options']);
   if($args['description']){
      echo '<span class="description">' . $args['description'] . '</span><br />' . "\n";
   }
   foreach($choices as $choice) {
      $checked = ($options[$args['id']]==$choice) ? ' checked="checked" ' : '';
      echo '<label><input ' . $checked . ' value="' . $choice . '" name="plugin_options[' . $args['id'] . ']" type="radio" /> ' . $choice . '</label><br />' . "\n";
   }
}

function cb_checkbox($args) {
   $options = get_option('plugin_options');
   if($options[$args['id']]) { $checked = ' checked="checked" '; }
   echo '<label><input ' . $checked . ' id="' . $args['id'] . '" name="plugin_options[' . $args['id'] . ']" type="checkbox" /> ' . $args['description'] . '</label>' . "\n";
}

function cb_password($args) {
   $options = get_option('plugin_options');
   echo '<input id="' . $args['id'] . '" name="plugin_options[' . $args['id'] . ']" size="40" type="password" value="' . $options[$args['id']] . '" class="regular-text" />' . "\n";
   if($args['description']){
      echo '<span class="description">' . $args['description'] . '</span>';
   }
}




?>