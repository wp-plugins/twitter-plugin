<?php
/*
Plugin Name: Twitter Plugin
Plugin URI:  http://bestwebsoft.com/plugin/
Description: Plugin to add a link to the page author to twitter.
Author: BestWebSoft
Version: 2.05
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
		$active_plugins = get_option('active_plugins');
		$all_plugins = get_plugins();

		$array_activate = array();
		$array_install = array();
		$array_recomend = array();
		$count_activate = $count_install = $count_recomend = 0;
		$array_plugins = array(
			array( 'captcha\/captcha.php', 'Captcha', 'http://wordpress.org/extend/plugins/captcha/', 'http://bestwebsoft.com/plugin/captcha-plugin/', '/wp-admin/update.php?action=install-plugin&plugin=captcha&_wpnonce=e66502ec9a' ), 
			array( 'contact-form-plugin\/contact_form.php', 'Contact Form', 'http://wordpress.org/extend/plugins/contact-form-plugin/', 'http://bestwebsoft.com/plugin/contact-form/', '/wp-admin/update.php?action=install-plugin&plugin=contact-form-plugin&_wpnonce=47757d936f' ), 
			array( 'facebook-button-plugin\/facebook-button-plugin.php', 'Facebook Like Button Plugin', 'http://wordpress.org/extend/plugins/facebook-button-plugin/', 'http://bestwebsoft.com/plugin/facebook-like-button-plugin/', '/wp-admin/update.php?action=install-plugin&plugin=facebook-button-plugin&_wpnonce=6eb654de19' ), 
			array( 'twitter-plugin\/twitter.php', 'Twitter Plugin', 'http://wordpress.org/extend/plugins/twitter-plugin/', 'http://bestwebsoft.com/plugin/twitter-plugin/', '/wp-admin/update.php?action=install-plugin&plugin=twitter-plugin&_wpnonce=1612c998a5' ), 
			array( 'portfolio\/portfolio.php', 'Portfolio', 'http://wordpress.org/extend/plugins/portfolio/', 'http://bestwebsoft.com/plugin/portfolio-plugin/', '/wp-admin/update.php?action=install-plugin&plugin=portfolio&_wpnonce=488af7391d' )
		);
		foreach($array_plugins as $plugins)
		{
			if( 0 < count( preg_grep( "/".$plugins[0]."/", $active_plugins ) ) )
			{
				$array_activate[$count_activate]['title'] = $plugins[1];
				$array_activate[$count_activate]['link'] = $plugins[2];
				$array_activate[$count_activate]['href'] = $plugins[3];
				$count_activate++;
			}
			else if( array_key_exists(str_replace("\\", "", $plugins[0]), $all_plugins) )
			{
				$array_install[$count_install]['title'] = $plugins[1];
				$array_install[$count_install]['link'] = $plugins[2];
				$array_install[$count_install]['href'] = $plugins[3];
				$count_install++;
			}
			else
			{
				$array_recomend[$count_recomend]['title'] = $plugins[1];
				$array_recomend[$count_recomend]['link'] = $plugins[2];
				$array_recomend[$count_recomend]['href'] = $plugins[3];
				$array_recomend[$count_recomend]['slug'] = $plugins[4];
				$count_recomend++;
			}
		}
		?>
		<div class="wrap">
			<div class="icon32 icon32-bws" id="icon-options-general"></div>
			<h2><?php echo $title;?></h2>
			<?php if($count_activate > 0) { ?>
			<div>
				<h3>Activated plugins</h3>
				<?php foreach($array_activate as $activate_plugin) { ?>
				<div style="float:left; width:200px;"><?php echo $activate_plugin['title']; ?></div> <p><a href="<?php echo $activate_plugin['link']; ?>">Read more</a></p>
				<?php } ?>
			</div>
			<?php } ?>
			<?php if($count_install > 0) { ?>
			<div>
				<h3>Installed plugins</h3>
				<?php foreach($array_install as $install_plugin) { ?>
				<div style="float:left; width:200px;"><?php echo $install_plugin['title']; ?></div> <p><a href="<?php echo $install_plugin['link']; ?>">Read more</a></p>
				<?php } ?>
			</div>
			<?php } ?>
			<?php if($count_recomend > 0) { ?>
			<div>
				<h3>Recommended plugins</h3>
				<?php foreach($array_recomend as $recomend_plugin) { ?>
				<div style="float:left; width:200px;"><?php echo $recomend_plugin['title']; ?></div> <p><a href="<?php echo $recomend_plugin['link']; ?>">Read more</a> <a href="<?php echo $recomend_plugin['href']; ?>">Download</a> <a class="install-now" href="<?php echo get_bloginfo("url") . $recomend_plugin['slug']; ?>" title="<?php esc_attr( sprintf( __( 'Install %s' ), $recomend_plugin['title'] ) ) ?>"><?php echo __( 'Install Now' ) ?></a></p>
				<?php } ?>
				<span style="color: rgb(136, 136, 136); font-size: 10px;">If you have any questions, please contact us via plugin@bestwebsoft.com or fill in our contact form on our site <a href="http://bestwebsoft.com/contact/">http://bestwebsoft.com/contact/</a></span>
			</div>
			<?php } ?>
		</div>
		<?php
	}
}

if( ! function_exists( 'bws_plugin_header' ) ) {
	function bws_plugin_header() {
		global $post_type;
		?>
		<style>
		#adminmenu #toplevel_page_bws_plugins div.wp-menu-image
		{
			background: url("<?php echo get_bloginfo('url');?>/wp-content/plugins/twitter-plugin/images/icon_16.png") no-repeat scroll center center transparent;
		}
		#adminmenu #toplevel_page_bws_plugins:hover div.wp-menu-image,#adminmenu #toplevel_page_bws_plugins.wp-has-current-submenu div.wp-menu-image
		{
			background: url("<?php echo get_bloginfo('url');?>/wp-content/plugins/twitter-plugin/images/icon_16_c.png") no-repeat scroll center center transparent;
		}	
		.wrap #icon-options-general.icon32-bws
		{
			background: url("<?php echo get_bloginfo('url');?>/wp-content/plugins/twitter-plugin/images/icon_36.png") no-repeat scroll left top transparent;
		}
		#toplevel_page_bws_plugins .wp-submenu .wp-first-item
		{
			display:none;
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

		$twttr_options_array = get_option( 'twttr_options_array' );

		$twttr_options_array = array_merge( $twttr_options_array_defaults, $twttr_options_array );
	}
}

//add meny
if(!function_exists ( 'twttr_add_pages' ) ) {
	function twttr_add_pages() {
		add_menu_page(__('BWS Plugins'), __('BWS Plugins'), 'manage_options', 'bws_plugins', 'bws_add_menu_render', WP_CONTENT_URL."/plugins/twitter-plugin/images/px.png", 101); 
		add_submenu_page('bws_plugins', 'Twitter Options', 'Twitter', 'manage_options', __FILE__, 'twttr_settings_page');

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
				<form method='post' action=" admin.php?page=twitter-plugin/twitter.php">
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
		if( $title_post  == 'your-post-page-title' )
			return $content;

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
			 $settings_link = '<a href="admin.php?page=twitter-plugin/twitter.php">' . __('Settings', 'twitter-plugin') . '</a>';
			 array_unshift( $links, $settings_link );
		}
	return $links;
} // end function fcbk_bttn_plgn_action_links

function twttr_links($links, $file) {
	$base = plugin_basename(__FILE__);
	if ($file == $base) {
		$links[] = '<a href="admin.php?page=twitter-plugin/twitter.php">' . __('Settings','twitter-plugin') . '</a>';
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
