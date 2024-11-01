<?php
/*
	Plugin Name: WP-SrvStatus
	Plugin URI: http://www.martin-fredrich.de/wordpress/wordpress-plugin-wp-srvstatus/
	Description: Displays offline/online status of defined (game)servers in your blogs sidebar
	Version: 0.3
	Author: Martin S. Fredrich
	Author URI: http://www.martin-fredrich.de

	Copyright 2010, Martin S. Fredrich

	This program is free software: you can redistribute it and/or modify
	it under the terms of the GNU General Public License as published by
	the Free Software Foundation, either version 3 of the License, or
	(at your option) any later version.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/


// ======== ONLY MAKE CHANGES IN LINE 29  ========= //
// define how many servers you want to add
define("SRVCNT","2");


// DO NOT MAKE ANY CHANGES BELOW THIS LINE!!!
// =============================================== //


// check for WP context
if ( !defined('ABSPATH') ){ die(); }


require_once('classes/tmfcolorparser.inc.php');
$cp = new TMFColorParser();

//initially set the options
function wp_srvStatus_install () {
  $newoptions = get_option('wpsrvstatus_widget');
  $newoptions['wgtitle'] = 'Serverstatus';
  for ($i = 1; $i <= SRVCNT; $i++) {
    $newoptions['srvLink' . $i] = '';
    $newoptions['serveraddress' . $i] = '';
    $newoptions['port'.$i] = '';
    $newoptions['dspname' .$i] = '';
    $newoptions['dspstyle'] = 'both';
    add_option('wpsrvstatus_widget', $newoptions);
    $atts = "page_item";
  }
}

// add the admin page
function wp_srvStatus_add_pages() {
	add_options_page('WP SrvStatus', 'WP SrvStatus', 8, __FILE__, 'wp_srvStatus_options');
}

function wp_srvStatus_init($content){
	if( strpos($content, '[WP-ServerStatus]') === false ){
		return $content;
	} else {
		$code = wp_srvStatus_createstatuscode(false);
		$content = str_replace( '[WP-ServerStatus]', $code, $content );
		return $content;
	}
}

// template function
function wp_srvStatus_insert( $atts=NULL ){
	echo wp_srvStatus_createstatuscode( false, $atts );
}

// shortcode function
function wp_srvStatus_shortcode( $atts=NULL ){
	return wp_srvStatus_createstatuscode( false, $atts );
}

function wp_srvStatus_createstatuscode( $widget=false, $atts=NULL ){

	// get the options
	if( $widget == true ){
		$options = get_option('wpsrvstatus_widget');
		$soname = "widget_so";
		$divname = "wpsrvstatuscontent";
		// get compatibility mode variable from the main options
		$mainoptions = get_option('wpsrvstatus_widget');
	}

	// add random seeds to so name to avoid collisions and force reloading (needed for IE)
	$soname .= rand(0,9999999);
	$divname .= rand(0,9999999);
    echo esc_attr($id_format);
	if( $options['compmode']!='true' ){
      for ($i = 1; $i <= SRVCNT; $i++) {
        $output .= wp_getStatus($options, $i);
        }
	}
	return $output;
}

function wp_getStatus($options, $i){

global $cp;

    //first, take a look if a server defined
    if($options['serveraddress'.$i] == '')
    {
      $txtOut = 'No server defined';
      return $txtOut;
    }

    $txtOut = '';

    if( $options['dspstyle'] == "both"){
       $textOn='<img src='.WP_CONTENT_URL.'/plugins/wp-srvstatus/img/online.png alt="Server online"  />&nbsp;online';
       $textOff='<img src='.WP_CONTENT_URL.'/plugins/wp-srvstatus/img/offline.png alt="Server offline"  />&nbsp;offline';
    }
    if($options['dspstyle'] == 'txt'){
      $textOn="<span style=\"color:green\">online</span>";
      $textOff="<span style=\"color:red\">offline</span>";
    }
    if($options['dspstyle'] == 'pic') {
      $textOn='<img src='.WP_CONTENT_URL.'/plugins/wp-srvstatus/img/online.png alt="Server online"  />';
      $textOff='<img src='.WP_CONTENT_URL.'/plugins/wp-srvstatus/img/offline.png alt="Server offline"   />';
    }


    $ziel=$options['serveraddress'.$i];
    $port=$options['port'.$i];

    if($options['srvLink'.$i] != '')
    {
      $link=$options['srvLink'.$i];
    }
    else{
      $link="#";
    }

    $abfrage = @fsockopen ($ziel, $port, $errno, $errstr, 1);

    if (!$abfrage)
    {
        $txtOut = '<li>' . $cp->toHTML($options['dspname'.$i]). '&nbsp;' .$textOff . '</li>';
    }
    else
    {
        $txtOut = '<li><a href="'.$link.'">' . $cp->toHTML($options['dspname'.$i]). '&nbsp;' .$textOn . '</a></li>';

    }

  return $txtOut;
}

// options page
function wp_srvStatus_options() {
	$options = $newoptions = get_option('wpsrvstatus_widget');
	// if submitted, process results
	if ( $_POST["wpsrvstatus_submit"] ) {
      for ($i = 1; $i <= SRVCNT; $i++) {
	    $newoptions['wgtitle'] = strip_tags(stripslashes($_POST['wgtitle']));
  	    $newoptions['srvLink'.$i] = strip_tags(stripslashes($_POST['srvLink'.$i]));
  		$newoptions['serveraddress'.$i] = strip_tags(stripslashes($_POST["serveraddress".$i]));
  		$newoptions['port'.$i] = strip_tags(stripslashes($_POST["port".$i]));
  		$newoptions['dspname'.$i] = strip_tags(stripslashes($_POST["dspname".$i]));
		$newoptions['dspstyle'] = strip_tags(stripslashes($_POST["dspstyle"]));
        }

	// any changes? save!
	if ( $options != $newoptions ) {
		$options = $newoptions;
		update_option('wpsrvstatus_widget', $options);
	}

}


 ?>
<table class="widefat" style="margin-top: .5em">
  <thead>
    <tr valign="top">
      <th colspan="2">WP-SrvStatus </th>
    </tr>
  </thead>
  <tbody>
    <tr valign="top">
      <td valign="top"><p>WP-Srvstatus is a plugin that shows you the online status of defined servers. This idea was born in case of setting up dedicated trackmania servers and I want to show the actually stats for the blog visitors / clan members.</p>
      The plugin is easy to configure and easy to use:
      <ul>
        <li style="list-style-type: disc">Fill in the server data in the fields below</li>
        <li class="wp-has-submenu">choose the appaereance</li>
        <li>save options </li>
        <li>and insert the widget into your sidebar</li>
     </ul>
     finish!
     <p>This plugin works not only on game servers, but also on every Server who is reachable from the internet.</p>

      </td>
      <td valign="top" width="40%"><h3>Donate</h3>
        <p><em>If you like this plugin and find it useful, help keep this plugin free and actively developed by clicking the <a href="https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=blaphase%40gmx%2ede&item_name=wp%20srvStatus&item_number=Support%20Open%20Source&no_shipping=0&no_note=1&tax=0&currency_code=EUR&lc=DE&bn=PP%2dDonationsBF&charset=UTF%2d8" target="_blank"><strong>donate</strong></a> button or send me a Trackmania coppers (login: bartschatten).  <br />
        Also, don't forget to follow me on <a href="http://twitter.com/bartschatten/" target="_blank"><strong>Twitter</strong></a>.</em>              </p>
        <p>
        <a target="_blank" title="Follow me on Twitter" href="http://twitter.com/bartschatten/">
            <img src="<?php echo WP_CONTENT_URL; ?>/plugins/wp-srvstatus/img/twitter.png" alt="Follow Us on Twitter" />	</a>
        <a target="_blank" title="Donate" href="https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=blaphase%40gmx%2ede&item_name=wp%20srvStatus&item_number=Support%20Open%20Source&no_shipping=0&no_note=1&tax=0&currency_code=EUR&lc=DE&bn=PP%2dDonationsBF&charset=UTF%2d8">      <img src="<?php echo WP_CONTENT_URL; ?>/plugins/wp-srvstatus/img/donate.png" alt="Donate with Paypal" />	</a>
       </p></td>
    </tr>
  </tbody>
</table>


 <?php

	// options form 1
	echo '<form method="post">';
	echo '<table class="widefat" style="margin-top: .5em">';
    echo '<thead><tr valign="top"><th colspan="2">Server status options - Server 1</th></tr></thead>';
   // echo '<table>';
    echo '<tbody>';
    // Widget title
	echo '<tr valign="top"><th scope="row">Widget title</th>';
	echo '<td><input type="text" name="wgtitle" value="'.$options['wgtitle'].'" size="30"></input><br />Can be empty. Default value: "Serverstatus"</td></tr>';
    for ($i = 1; $i <= SRVCNT; $i++) {
      if($i != 1){
        echo '<thead><tr valign="top"><th colspan="2">Server status options - Server ' . $i . '</th></tr></thead>';
        }
        // Server Address
    	echo '<tr valign="top"><th scope="row">Server address</th>';
    	echo '<td><input type="text" name="serveraddress'.$i.'" value="'.$options['serveraddress' . $i].'" size="30"></input><br />IP-adress like: 000.000.000.000 or URL without http://</td></tr>';
    	// Server Link
    	echo '<tr valign="top"><th scope="row">Server link</th>';
    	echo '<td><input type="text" name="srvLink'.$i.'" value="'.$options['srvLink' . $i].'" size="30"></input><br />Invoke server/app directly</td></tr>';
        // Port number
    	echo '<tr valign="top"><th scope="row">Port number</th>';
    	echo '<td><input type="text" name="port'.$i.'" value="'.$options['port' . $i].'" size="5"></input><br />For Trackmania normaly 2351</td></tr>';
    	// Server name
    	echo '<tr valign="top"><th scope="row">Name of the displayed Server</th>';
    	echo '<td><input type="text" name="dspname'.$i.'" value="'.$options['dspname' . $i].'" size="30"></input>Possible with tmf color code $000 -> $fff</td></tr>';
    //Display style
    if($i == 1){
        echo '<tr valign="top"><th scope="row">Display style</th>';
    	echo '<td><input type="radio" name="dspstyle" value="pic"';
    	if( $options['dspstyle'] == 'pic' ){ echo ' checked="checked" '; }
    	echo '></input> Image<br /><input type="radio" name="dspstyle" value="txt"';
    	if( $options['dspstyle'] == 'txt' ){ echo ' checked="checked" '; }
    	echo '></input> Text<br /><input type="radio" name="dspstyle" value="both"';
    	if( $options['dspstyle'] == 'both' ){ echo ' checked="checked" '; }
    	echo '></input> Both Image and online/offline text';
    }
	// end table
    }
    echo '</tbody>';
	echo '</table>';
	// close stuff
	echo '<input type="hidden" name="wpsrvstatus_submit" value="true"></input>';
	echo '</table>';
	echo '<p class="submit"><input type="submit" value="Update Options &raquo;"></input></p>';
	echo "</div>";
	echo '</form>';
}

//uninstall all options
function wp_srvStatus_uninstall () {
	delete_option('srvstatus_options');
	delete_option('srvstatus_widget');
}


// widget
function widget_init_wp_srvStatus_widget() {
	// Check for required functions
	if (!function_exists('register_sidebar_widget'))
		return;

	function wp_srvStatus_widget($args){
	    extract($args);
		$options = get_option('wpsrvstatus_widget');
		?>
	        <?php echo $before_widget; ?>
			<?php if( !empty($options['wgtitle']) ): ?>
				<?php echo $before_title . $options['wgtitle'] . $after_title; ?>
			<?php endif; ?>
			<?php
				if( !stristr( $_SERVER['PHP_SELF'], 'widgets.php' ) ){
					echo wp_srvStatus_createstatuscode(true);
				}
			?>
	        <?php echo $after_widget; ?>
        <?php
	}

	function wp_srvStatus_widget_control()
    {
       // echo '<p><label for="wpsrvstatus_widget_title">'. _e('Configure this under Settings in Main menu');
        echo '<p>'.sprintf(__('Options are found <a href="%s">here</a>', 'wp-srvstatus'), 'options-general.php?page=wp-srvstatus/wp-srvstatus').'<br /><small>'.__('Save your other widget settings first!', 'wp-srvstatus').'</small></p>';
	}

	register_sidebar_widget( "WP-ServerStatus", wp_srvStatus_widget );
	register_widget_control( "WP-ServerStatus", "wp_srvStatus_widget_control" );
}

// Delay plugin execution until sidebar is loaded
add_action('widgets_init', 'widget_init_wp_srvStatus_widget');
add_filter('plugin_action_links', 'plugin_action', -10, 2);

// add the actions
add_action('admin_menu', 'wp_srvStatus_add_pages');
register_activation_hook( __FILE__, 'wp_srvStatus_install' );
register_deactivation_hook( __FILE__, 'wp_srvStatus_uninstall' );
if( function_exists('add_shortcode') ){
	add_shortcode('WP-ServerStatus', 'wp_srvStatus_shortcode');
	add_shortcode('WP-ServerStatus', 'wp_srvStatus_shortcode');
} else {
	add_filter('the_content','wp_srvStatus_init');
}

function plugin_action($links, $file) {
	if ($file == plugin_basename(dirname(__FILE__).'/wp-srvstatus.php'))
		$links[] = "<a href='options-general.php?page=wp-srvstatus/wp-srvstatus.php'>" . __('Settings', 'wp-srvstatus', 'wp-srvstatus') . "</a>";
	return $links;
}

































?>
