<?php
/*
Plugin Name: Twitter Plugin
Plugin URI:  http://bestwebsoft.com/plugin/
Description: Plugin to add a link to the page author to twitter.
Author: BestWebSoft
Version: 2.02
Author URI: http://bestwebsoft.com/
License: GPLv2 or later
*/

/*
	 Copyright 2011  BestWebSoft  ( admin@bestwebsoft.com )
	
	This program is free software; you can redistribute it and/or modify
	it under the terms of the GNU General Public License, version 2, as 
	published by the Free Software Foundation.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with this program; if not, write to the Free Software
	Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

//add settings links

if( ! function_exists( 'bws_add_menu_render' ) ) {
	function bws_add_menu_render() {
		global $title;
		?>
		<div class="wrap">
			<div class="icon32 icon32-bws" id="icon-options-general"></div>
			<h2><?php echo $title;?></h2>
			<p><a href="http://wordpress.org/extend/plugins/captcha/">Captcha</a></p>
			<p><a href="http://wordpress.org/extend/plugins/contact-form-plugin/">Contact Form</a></p>
			<p><a href="http://wordpress.org/extend/plugins/facebook-button-plugin/">Facebook Like Button Plugin</a></p>
			<p><a href="http://wordpress.org/extend/plugins/twitter-plugin/">Twitter Plugin</a></p>
			<p><a href="http://wordpress.org/extend/plugins/portfolio/">Portfolio</a></p>
			<span style="color: rgb(136, 136, 136); font-size: 10px;">If you have any questions, please contact us via plugin@bestwebsoft.com or fill in our contact form on our site <a href="http://bestwebsoft.com/contact/">http://bestwebsoft.com/contact/</a></span>
		</div>
		<?php
	}
}

if( ! function_exists( 'bws_plugin_header' ) ) {
	function bws_plugin_header() {
		global $post_type;
		?>
		<style>
		#adminmenu #toplevel_page_my_new_menu div.wp-menu-image
		{
			background: url("<?php echo get_bloginfo('url');?>/wp-content/plugins/twitter-plugin/images/icon_16.png") no-repeat scroll center center transparent;
		}
		#adminmenu #toplevel_page_my_new_menu:hover div.wp-menu-image,#adminmenu #toplevel_page_my_new_menu.wp-has-current-submenu div.wp-menu-image
		{
			background: url("<?php echo get_bloginfo('url');?>/wp-content/plugins/twitter-plugin/images/icon_16_c.png") no-repeat scroll center center transparent;
		}	
		.wrap #icon-options-general.icon32-bws
		{
			background: url("<?php echo get_bloginfo('url');?>/wp-content/plugins/twitter-plugin/images/icon_36.png") no-repeat scroll left top transparent;
		}
		</style>
		<?php
	}
}

add_action('admin_head', 'bws_plugin_header');

// Register settings for plugin
if( ! function_exists( 'twttr_settings' ) ) {
	function twttr_settings() {
		global $twttr_options_array;

		$twttr_options_array_defaults = array(
			'url_twitter' => 'admin',
			'position' => ''
		);

		if( ! get_option( 'twttr_options_array' ) )
			add_option( 'twttr_options_array', $twttr_options_array_defaults, '', 'yes' );

		$twttr_options_array = get_option( 'cntctfrm_options' );

		$twttr_options_array = array_merge( $twttr_options_array_defaults, $twttr_options_array );
	}
}

//add meny
if(!function_exists ( 'twttr_add_pages' ) ) {
	function twttr_add_pages() {
		//add_options_page ( 'Twitter', 'Twitter', 8, 'twitter', 'twitter_form' );
		add_menu_page(__('BWS Plugins'), __('BWS Plugins'), 'edit_themes', 'my_new_menu', 'bws_add_menu_render', " ", 90); 
		add_submenu_page('my_new_menu', 'Twitter Options', 'Twitter', 'edit_themes', "twitter.php", 'twttr_settings_page');

		//call register settings function
		add_action( 'admin_init', 'twttr_settings' );
	}
}
//add meny.End
		
//add.Form meny.
if (!function_exists ( 'twttr_settings_page' ) ) {
	function twttr_settings_page () {
		global $twttr_options_array;
		if ( isset ( $_REQUEST['twttr_position'] ) && isset ( $_REQUEST['twttr_url_twitter'] ) ) {
			$twttr_options_array['twttr_url_twitter'] = $_REQUEST['twttr_url_twitter'];
			$twttr_options_array['twttr_position'] = $_REQUEST['twttr_position'];
			update_option ( "twttr_options_array", $twttr_options_array );?>
			<div class='updated fade below-h2'>
				<p>
					<strong>Options saved.</strong>
				</p>
			</div>
		<?php } ?>
		<div class="wrap">
			<div class="icon32 icon32-bws" id="icon-options-general"></div>
			<h2>Twitter option</h2>
			<div>
				<form method='post' action=" admin.php?page=twitter.php">
					<table class="form-table">
						<tr valign="top">
							<th scope="row" colspan="2">Settings for the button "Follow Me":</th>
						</tr>					
						<tr valign="top">
							<th scope="row">
								Enter your username:
							</th>
							<td>
								<input name='twttr_url_twitter' type='text' value='<?php echo $twttr_options_array['twttr_url_twitter'] ?>'/><br />
								<span style="color: rgb(136, 136, 136); font-size: 10px;">If you do not have Twitter account yet you need to create it using this link <a target="_blank" href="https://twitter.com/signup">https://twitter.com/signup</a> .</span><br />
								<span style="color: rgb(136, 136, 136); font-size: 10px;">Put a shortcode [follow_me] on necessary page or post to use "Follow Me" button.</span><br />
								<span style="color: rgb(136, 136, 136); font-size: 10px;">If you would like to utilize this button in another place, please put strings below into the template source code 	&#60;?php if ( function_exists( 'follow_me' ) ) echo follow_me(); ?&#62;</span>
							</td>
						</tr>
						<tr valign="top">
							<th scope="row" colspan="2">Settings for the button "Twitter":</th>
						</tr>					
						<tr>
							<th>
								Choose a position for an icon "Twitter":
							</th>
							<td>
								<input style="margin-top:5px;" type="radio" name="twttr_position" value="1" <?php if ( $twttr_options_array['twttr_position'] == 1 ) echo 'checked="checked"'?> /><label for="twttr_position">Top position</label><br />
								<input style="margin-top:5px;" type="radio" name="twttr_position" value="1" <?php if ( $twttr_options_array['twttr_position'] == 0 ) echo 'checked="checked"'?> /><label for="twttr_position">Bottom position</label><br />
								<span style="color: rgb(136, 136, 136); font-size: 10px;">When clicking this sign a user adds to their twitter page article that they liked along with a link to it.</span><br />
							</td>
						</tr>
						<tr>
						<td colspan="2">
							<input type="submit" value="Save Changes" class="button-primary">
						</td>
					</tr>
					</table>
				</form>
			</div>
		</div>
	<?php
	}		
}
//add.Form meny.End.	
	
// score code[follow_me]
if (!function_exists('twttr_follow_me')){
	function twttr_follow_me() {
		return '<a href="http://twitter.com/'.get_option("url_twitter").'" target="_blank" title="Follow me">
				 <img src="'.get_option('home').'/wp-content/plugins/twitter-plugin/images/twitter-follow.gif" alt="Follow me" />
			  </a>';
	}
}

	
//Positioning in the page	
if(!function_exists( 'twttr_twit' ) ) {
	function twttr_twit( $content ) {
	global $post;
	$permalink_post = get_permalink($post_ID);
	$title_post = $post->post_title;
	
		$position = get_option( 'position' );
		$str = '<div style="clear:both;margin-bottom:5px;">
				<a href="http://twitter.com/share?url='.$permalink_post.'&text='.$title_post.'" target="_blank" title="Click here if you liked this article">
					<img src="'.get_option('home').'/wp-content/plugins/twitter-plugin/images/twitt.gif" alt="Twitt" />
				</a>
			</div>';
		if ( $position ){
			return $qw.$str.$content;
		}
		else{
			return $content.$str;
		}
	}
}
//Positioning in the page.End.


function twttr_action_links( $links, $file ) {
		//Static so we don't call plugin_basename on every plugin row.
	static $this_plugin;
	if ( ! $this_plugin ) $this_plugin = plugin_basename(__FILE__);

	if ( $file == $this_plugin ){
			 $settings_link = '<a href="admin.php?page=twitter.php">' . __('Settings', 'twitter-plugin') . '</a>';
			 array_unshift( $links, $settings_link );
		}
	return $links;
} // end function fcbk_bttn_plgn_action_links

function twttr_links($links, $file) {
	$base = plugin_basename(__FILE__);
	if ($file == $base) {
		$links[] = '<a href="admin.php?page=twitter.php">' . __('Settings','twitter-plugin') . '</a>';
		$links[] = '<a href="http://wordpress.org/extend/plugins/twitter-plugin/faq/" target="_blank">' . __('FAQ','twitter-plugin') . '</a>';
		$links[] = '<a href="Mailto:plugin@bestwebsoft.com">' . __('Support','twitter-plugin') . '</a>';
	}
	return $links;
}

// adds "Settings" link to the plugin action page
add_filter( 'plugin_action_links', 'twttr_action_links',10,2);

//Additional links on the plugin page
add_filter( 'plugin_row_meta', 'twttr_links',10,2);

add_filter( "the_content", "twttr_twit" );

add_action ( 'admin_menu', 'twttr_add_pages' );

add_shortcode( 'follow_me', 'twttr_follow_me' );

?>
