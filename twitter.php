<?php
/*
Plugin Name: Twitter Plugin
Plugin URI:  http://bestwebsoft.com/plugin/
Description: Plugin to add a link to the page author to twitter.
Author: BestWebSoft
Version: 2.27
Author URI: http://bestwebsoft.com/
License: GPLv2 or later
*/

/*
	 Copyright 2011  BestWebSoft  ( http://support.bestwebsoft.com )
	
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

require_once( dirname( __FILE__ ) . '/bws_menu/bws_menu.php' );

//add meny
if ( !function_exists ( 'twttr_add_pages' ) ) {
	function twttr_add_pages() {
		add_menu_page( 'BWS Plugins', 'BWS Plugins', 'manage_options', 'bws_plugins', 'bws_add_menu_render', plugins_url( 'images/px.png', __FILE__ ), 1001); 
		add_submenu_page('bws_plugins', __( 'Twitter Settings', 'twitter' ), __( 'Twitter', 'twitter' ), 'manage_options', 'twitter.php', 'twttr_settings_page');

		//call register settings function
		add_action( 'admin_init', 'twttr_settings' );
	}
}
//add meny.End

// Register settings for plugin
if ( ! function_exists( 'twttr_settings' ) ) {
	function twttr_settings() {
		global $twttr_options_array;

		$twttr_options_array_defaults = array(
			'twttr_url_twitter' 	=> 'admin',
			'twttr_display_option' 	=> 'custom',
			'twttr_count_icon' 		=> 1,
			'twttr_img_link' 		=>  plugins_url( "images/twitter-follow.gif", __FILE__ ),
			'twttr_position' 		=> '',
			'twttr_disable' 		=> '0'
		);

		if ( ! get_option( 'twttr_options_array' ) )
			add_option( 'twttr_options_array', $twttr_options_array_defaults, '', 'yes' );

		$twttr_options_array = get_option( 'twttr_options_array' );
		$twttr_options_array = array_merge( $twttr_options_array_defaults, $twttr_options_array );
	}
}
		
//add.Form meny.
if ( !function_exists ( 'twttr_settings_page' ) ) {
	function twttr_settings_page () {
		global $twttr_options_array;
		$copy = false;
		
		if ( @copy( plugin_dir_path( __FILE__ )."images/twitter-follow.jpg", plugin_dir_path( __FILE__ )."images/twitter-follow1.jpg" ) !== false )
			$copy = true;

		$message = "";
		$error = "";
		if ( isset( $_REQUEST['twttr_form_submit'] ) && check_admin_referer( plugin_basename(__FILE__), 'twttr_nonce_name' ) ) {
			$twttr_options_array['twttr_url_twitter'] = $_REQUEST['twttr_url_twitter'];
			$twttr_options_array['twttr_display_option' ] =	$_REQUEST['twttr_display_option'];
			$twttr_options_array['twttr_position'] = $_REQUEST['twttr_position'];
			$twttr_options_array['twttr_disable'] = isset( $_REQUEST["twttr_disable"] ) ? 1 : 0;
			if ( isset( $_FILES['upload_file']['tmp_name'] ) &&  $_FILES['upload_file']['tmp_name'] != "" ) {		
				$twttr_options_array['twttr_count_icon'] = $twttr_options_array['twttr_count_icon'] + 1;				
			}
			if ( $twttr_options_array['twttr_count_icon'] > 2 )
				$twttr_options_array['twttr_count_icon'] = 1;

			update_option( 'twttr_options_array', $twttr_options_array );
			$message = __( "Settings saved", 'twitter' );
			
			// Form options
			if ( isset ( $_FILES['upload_file']['tmp_name'] ) &&  $_FILES['upload_file']['tmp_name'] != "" ) {		
				$max_image_width	=	100;
				$max_image_height	=	100;
				$max_image_size		=	32 * 1024;
				$valid_types 		=	array( 'jpg', 'jpeg' );
				// Construction to rename downloading file
				$new_name			=	'twitter-follow'.$twttr_options_array['twttr_count_icon']; 
				$new_ext			=	'.jpg';
				$namefile			=	$new_name.$new_ext;
				$uploaddir			=	$_REQUEST['home'] . 'wp-content/plugins/twitter-plugin/images/'; // The directory in which we will take the file:
				$uploadfile			=	$uploaddir.$namefile; 

				//checks is file download initiated by user
				if ( isset ( $_FILES['upload_file'] ) && $_REQUEST['twttr_display_option'] == 'custom' )	{		
					//Checking is allowed download file given parameters
					if ( is_uploaded_file( $_FILES['upload_file']['tmp_name'] ) ) {	
						$filename	=	$_FILES['upload_file']['tmp_name'];
						$ext		=	substr( $_FILES['upload_file']['name'], 1 + strrpos( $_FILES['upload_file']['name'], '.' ) );		
						if ( filesize ( $filename ) > $max_image_size ) {
							$error = __( "Error: File size > 32K", 'twitter' );
						} elseif ( ! in_array ( $ext, $valid_types ) ) { 
							$error = __( "Error: Invalid file type", 'twitter' );
						} else {
							$size = GetImageSize( $filename );
							if ( ( $size ) && ( $size[0] <= $max_image_width ) && ( $size[1] <= $max_image_height ) ) {
								//If file satisfies requirements, we will move them from temp to your plugin folder and rename to 'twitter_ico.jpg'
								if ( move_uploaded_file ( $_FILES['upload_file']['tmp_name'], $uploadfile ) ) { 
									$message .= '. ' ."Upload successful.";
								} else { 
									$error = __( "Error: moving file failed", 'twitter' );
								}
							} else { 
								$error = __( "Error: check image width or height", 'twitter' );
							}
						}
					} else { 
						$error = __( "Uploading Error: check image properties", 'twitter' );
					}	
				}
			}			
		} 
		twttr_update_option(); ?>
		<div class="wrap">
			<div class="icon32 icon32-bws" id="icon-options-general"></div>
			<h2><?php echo __( "Twitter Settings", 'twitter' ); ?></h2>
			<div class="updated fade" <?php if( empty( $message ) || $error != "" ) echo "style=\"display:none\""; ?>><p><strong><?php echo $message; ?></strong></p></div>
			<div class="error" <?php if( "" == $error ) echo "style=\"display:none\""; ?>><p><strong><?php echo $error; ?></strong></p></div>
			<div>
				<form method='post' action="admin.php?page=twitter.php" enctype="multipart/form-data">
					<table class="form-table">
						<tr valign="top">
							<th scope="row" colspan="2"><?php echo __( 'Settings for the button "Follow Me":', 'twitter' ); ?></th>
						</tr>					
						<tr valign="top">
							<th scope="row">
								<?php echo __( "Enter your username:", 'twitter' ); ?>
							</th>
							<td>
								<input name='twttr_url_twitter' type='text' value='<?php echo $twttr_options_array['twttr_url_twitter'] ?>'/><br />
								<span style="color: rgb(136, 136, 136); font-size: 10px;"><?php echo __( 'If you do not have Twitter account yet, you should create it using this link', 'twitter' ); ?> <a target="_blank" href="https://twitter.com/signup">https://twitter.com/signup</a> .</span><br />
								<span style="color: rgb(136, 136, 136); font-size: 10px;"><?php echo __( 'Paste the shortcode [follow_me] into the necessary page or post to use the "Follow Me" button.', 'twitter' ); ?></span><br />
								<span style="color: rgb(136, 136, 136); font-size: 10px;"><?php echo __( 'If you would like to use this button in some other place, please paste this line into the template source code', 'twitter' ); ?>	&#60;?php if ( function_exists( 'follow_me' ) ) echo follow_me(); ?&#62;</span>
							</td>
						</tr>						
						<tr valign="top">
							<th scope="row">
								<?php echo __( "Choose display settings:", 'twitter' ); ?>
							</th>
							<td>
								<select name="twttr_display_option" onchange="if ( this . value == 'custom' ) { getElementById ( 'twttr_display_option_custom' ) . style.display = 'block'; } else { getElementById ( 'twttr_display_option_custom' ) . style.display = 'none'; }">
									<option <?php if ( $twttr_options_array['twttr_display_option'] == 'standart' ) echo 'selected="selected"'; ?> value="standart"><?php echo __( "Standard button", 'twitter' ); ?></option>
									<?php if( $copy || $twttr_options_array['twttr_display_option'] == 'custom' ) { ?>
									<option <?php if ( $twttr_options_array['twttr_display_option'] == 'custom' ) echo 'selected="selected"'; ?> value="custom"><?php echo __( "Custom button", 'twitter' ); ?></option>
									<?php } ?>
								</select>
							</td>
						</tr>
						<tr>
							<td colspan="2">
								<div id="twttr_display_option_custom" <?php if ( $twttr_options_array['twttr_display_option'] == 'custom' ) { echo ( 'style="display:block"' ); } else {echo ( 'style="display:none"' ); }?>>
									<table>
										<th style="padding-left:0px;font-size:13px;">
											<?php echo __( "Current image:", 'twitter' ); ?>
										</th>
										<td>
											<img src="<?php echo $twttr_options_array['twttr_img_link']; ?>" />
										</td>
									</table>											
									<table>
										<th style="padding-left:0px;font-size:13px;">											
											<?php echo __( "\"Follow Me\" image:", 'twitter' ); ?>
										</th>
										<td>
											<input type="hidden" name="MAX_FILE_SIZE" value="64000"/>
											<input type="hidden" name="home" value="<?php echo ABSPATH ; ?>"/>
											<input type="file" name="upload_file" style="width:196px;" /><br />
											<span style="color: rgb(136, 136, 136); font-size: 10px;"><?php echo __( 'Image properties: max image width:100px; max image height:100px; max image size:32Kb; image types:"jpg", "jpeg".', 'twitter' ); ?></span>	
										</td>
									</table>											
								</div>
							</td>
						</tr>
						<tr valign="top">
							<th scope="row" colspan="2"><?php echo __( '"Twitter" button settings:', 'twitter' ); ?></th>
						</tr>					
						<tr>
							<th><?php echo __( 'Disable the "Twitter" button:', 'twitter' ); ?></th>							
							<td>
								<input type="checkbox" name="twttr_disable" value="1" <?php if( 1 == $twttr_options_array["twttr_disable"] ) echo "checked=\"checked\""; ?> /><br />
								<span style="color: rgb(136, 136, 136); font-size: 10px;"><?php echo __( 'The button "T" will not be displayed. Just the shortcode [follow_me] will work.', 'twitter' ); ?></span><br />
							</td>
						</tr>
						<tr>
							<th>
								<?php echo __( 'Choose the "Twitter" icon position:', 'twitter' ); ?>
							</th>
							<td>
								<input style="margin-top:3px;" type="radio" name="twttr_position" value="1" <?php if ( $twttr_options_array['twttr_position'] == 1 ) echo 'checked="checked"'?> /> <label for="twttr_position"><?php echo __( 'Top position', 'twitter' ); ?></label><br />
								<input style="margin-top:3px;" type="radio" name="twttr_position" value="0" <?php if ( $twttr_options_array['twttr_position'] == 0 ) echo 'checked="checked"'?> /> <label for="twttr_position"><?php echo __( 'Bottom position', 'twitter' ); ?></label><br />
								<span style="color: rgb(136, 136, 136); font-size: 10px;"><?php echo __( 'By clicking this icon a user can add the article he/she likes to his/her Twitter page.', 'twitter' ); ?></span><br />
							</td>
						</tr>
						<tr>
							<td colspan="2">
								<input type="hidden" name="twttr_form_submit" value="submit" />
								<input type="submit" class="button-primary" value="<?php _e( 'Save Changes' ) ?>" />
							</td>
						</tr>
					</table>
					<?php wp_nonce_field( plugin_basename(__FILE__), 'twttr_nonce_name' ); ?>
				</form>
			</div>
		</div>
	<?php
	}		
}
//add.Form meny.End.

//Function 'twitter_twttr_display_option' reacts to changes type of picture (Standard or Custom) and generates link to image, link transferred to array 'twttr_options_array'
if ( ! function_exists( 'twttr_update_option' ) ) {
	function twttr_update_option () {
		global $twttr_options_array;
		if ( $twttr_options_array [ 'twttr_display_option' ] == 'standart' ){
			$twttr_img_link	=	plugins_url( 'images/twitter-follow.jpg', __FILE__ );
		} else if ( $twttr_options_array['twttr_display_option'] == 'custom') {
			$twttr_img_link	= plugins_url( 'images/twitter-follow'.$twttr_options_array['twttr_count_icon'].'.jpg', __FILE__ );
		}
		$twttr_options_array['twttr_img_link'] = $twttr_img_link;
		update_option( "twttr_options_array", $twttr_options_array );
	}
}	
	
// score code[follow_me]
if ( !function_exists( 'twttr_follow_me' ) ){
	function twttr_follow_me() {
		global $twttr_options_array;

		if ( $twttr_options_array [ 'twttr_display_option' ] == 'standart' ){
			return '<div class="twttr_follow">
			    <a href="https://twitter.com/'.$twttr_options_array["twttr_url_twitter"].'" class="twitter-follow-button" data-show-count="true">Follow me</a>
			    <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
				</div>';
		} else {
			return '<div class="twttr_follow"><a href="http://twitter.com/'.$twttr_options_array["twttr_url_twitter"].'" target="_blank" title="Follow me">
				 <img src="'.$twttr_options_array['twttr_img_link'].'" alt="Follow me" />
			  </a></div>';
		}		
	}
}
	
//Positioning in the page	
if ( !function_exists( 'twttr_twit' ) ) {
	function twttr_twit( $content ) {
		global $post, $twttr_options_array;
		$permalink_post = get_permalink($post->ID);
		$title_post = $post->post_title;
		if ( $title_post == 'your-post-page-title' )
			return $content;

		if ( 0 == $twttr_options_array['twttr_disable'] ) {
			$position = $twttr_options_array['twttr_position'];
			$str = '<div class="twttr_button">
					<a href="http://twitter.com/share?url='.$permalink_post.'&text='.$title_post.'" target="_blank" title="'.__( 'Click here if you like this article.', 'twitter' ).'">
						<img src="'.plugins_url('images/twitt.gif', __FILE__).'" alt="Twitt" />
					</a>
				</div>';
			if ( $position ) {
				return $str.$content;
			} else {
				return $content.$str;
			}
		} else {
			return $content;
		}
	}
}
//Positioning in the page.End.
if ( !function_exists( 'twttr_action_links' ) ) {
	function twttr_action_links( $links, $file ) {
		//Static so we don't call plugin_basename on every plugin row.
		static $this_plugin;
		if ( ! $this_plugin ) $this_plugin = plugin_basename(__FILE__);

		if ( $file == $this_plugin ){
				 $settings_link = '<a href="admin.php?page=twitter.php">' . __( 'Settings', 'twitter' ) . '</a>';
				 array_unshift( $links, $settings_link );
			}
		return $links;
	}
} // end function twttr_bttn_plgn_action_links

if ( !function_exists( 'twttr_links' ) ) {
	function twttr_links( $links, $file ) {
		$base = plugin_basename(__FILE__);
		if ( $file == $base ) {
			$links[] = '<a href="admin.php?page=twitter.php">' . __( 'Settings','twitter' ) . '</a>';
			$links[] = '<a href="http://wordpress.org/extend/plugins/twitter-plugin/faq/" target="_blank">' . __( 'FAQ','twitter' ) . '</a>';
			$links[] = '<a href="http://support.bestwebsoft.com">' . __( 'Support','twitter' ) . '</a>';
		}
		return $links;
	}
}

//Function '_plugin_init' are using to add language files.
if ( ! function_exists ( 'twttr_plugin_init' ) ) {
	function twttr_plugin_init() {
		load_plugin_textdomain( 'twitter', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' ); 
		load_plugin_textdomain( 'bestwebsoft', false, dirname( plugin_basename( __FILE__ ) ) . '/bws_menu/languages/' ); 
	}
} // end function twttr_plugin_init


if ( ! function_exists ( 'twttr_admin_head' ) ) {
	function twttr_admin_head() {
		wp_register_style( 'twttrStylesheet', plugins_url( 'css/style.css', __FILE__ ) );
		wp_enqueue_style( 'twttrStylesheet' );

		if ( isset( $_GET['page'] ) && $_GET['page'] == "bws_plugins" )
			wp_enqueue_script( 'bws_menu_script', plugins_url( 'js/bws_menu.js' , __FILE__ ) );
	}
}

// Function for delete options 
if ( ! function_exists ( 'twttr_delete_options' ) ) {
	function twttr_delete_options() {
		global $wpdb;
		delete_option( 'twttr_options_array' );
	}
}

add_action( 'init', 'twttr_plugin_init' );
add_action( 'init', 'twttr_settings' );

add_action( 'admin_enqueue_scripts', 'twttr_admin_head' );
add_action( 'wp_enqueue_scripts', 'twttr_admin_head' );

// adds "Settings" link to the plugin action page
add_filter( 'plugin_action_links', 'twttr_action_links',10,2);

//Additional links on the plugin page
add_filter( 'plugin_row_meta', 'twttr_links',10,2);

add_filter( "the_content", "twttr_twit" );

add_action ( 'admin_menu', 'twttr_add_pages' );

add_shortcode( 'follow_me', 'twttr_follow_me' );

register_uninstall_hook( __FILE__, 'twttr_delete_options' );
?>