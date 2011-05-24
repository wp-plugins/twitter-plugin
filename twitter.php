<?php
/*
Plugin Name: Twitter Plugin
Plugin URI:  http://bestwebsoft.com/plugin/
Description: Plugin to add a link to the page author to twitter.
Author: BestWebSoft
Version: 1.03
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
add_filter( 'plugin_row_meta', 'twitter_settings', 10, 2 );
add_filter( "the_content", "twitter_twit" );

add_action ( 'admin_menu', 'twitter_mt_add_pages' );

add_shortcode( 'follow_me', 'follow_me' );

if( !function_exists( twitter_settings ) ) {
	function twitter_settings( $links, $file ) {
		$base = plugin_basename( __FILE__ );
		if ( $file == $base ) {
			$links[] = '<a href="admin.php?page=twitter">' . __( 'Settings', 'Settings' ) . '</a>';
		}
		return $links;
	}
}
//add settings links.End.

//add meny
if(!function_exists ( 'twitter_mt_add_pages' ) ) {
	function twitter_mt_add_pages() {
		add_options_page ( 'Twitter', 'Twitter', 8, 'twitter', 'twitter_form' );
	}
}
//add meny.End
		
//add.Form meny.
if (!function_exists ( 'twitter_form' ) ) {
	function twitter_form () { ?>
		<div class="wrap">
			<div id="icon-options-general" class="icon32">
			</div>
			<h2>Twitter option</h2>
		<?php if ( isset ( $_REQUEST['position'] ) && isset ( $_REQUEST['url_twitter'] ) ) {
			$url_twitter = $_REQUEST['url_twitter'];
			update_option ( "url_twitter" , $url_twitter );
			$position = $_REQUEST['position'];
			update_option ( "position", $position );?>
			<div class='updated fade below-h2'>
				<p>
					<strong>Options saved.</strong>
				</p>
			</div>
		<?php }?>
			<div style='margin:50px 0 0 50px;'>
				<form method='post' action=" admin.php?page=twitter ">
					<div style="font-size:16px;border-bottom:1px dashed;padding-bottom:10px;margin-bottom:15px;">Settings for the button "Follow Me":</div>
					<div style="width:150px;float:left;">Enter your username:</div>
					<input name='url_twitter' type='text' value='<?php echo get_option("url_twitter") ?>'/>
					<div style="color: rgb(136, 136, 136); font-size: 10px;clear:both;">If you do not have Twitter account yet you need to create it using this link <a target="_blank" href="https://twitter.com/signup">https://twitter.com/signup</a> .</div>
					<div style="color: rgb(136, 136, 136); font-size: 10px;clear:both;">Put a shortcode [follow_me] on necessary page or post to use "Follow Me" button.</div>
					<div style="color: rgb(136, 136, 136); font-size: 10px;clear:both;">If you would like to utilize this button in another place, please put strings below into the template source code 	&#60;?php if ( function_exists( 'follow_me' ) ) echo follow_me(); ?&#62;</div>
					<div style="font-size:16px;border-bottom:1px dashed;padding:10px 0 10px 0;">Settings for the button "twitter":</div>
					<div style="clear:both;margin:15px 0 2px 0;">Choose a position for an icon "twitter":</div>
					<div style="width:150px;float:left;">Top position</div><input style="margin-top:5px;" type="radio" name="position" value="1" <?php if ( get_option( "position", $position ) == 1 ) echo 'checked="checked"'?> />
					<div style="clear:both;"></div>
					<div style="width:150px;float:left;">Bottom position</div><input style="margin-top:5px;" type="radio" name="position" value="0" <?php if ( get_option( "position", $position ) == 0 ) echo 'checked="checked"'?> />
					<div style="color: rgb(136, 136, 136); font-size: 10px;clear:both;">When clicking this sign a user adds to their twitter page article that they liked along with a link to it.</div>
					<input class="button-primary" type="submit" value="Save Changes" />
				</form>
			</div>
		</div>
	<?php
	}		
}
//add.Form meny.End.	
	
// score code[follow_me]
if (!function_exists('follow_me')){
	function follow_me() {
		return '<a href="http://twitter.com/'.get_option("url_twitter").'" target="_blank" title="Follow me">
				 <img src="'.get_option('home').'/wp-content/plugins/twitter-plugin/images/twitter-follow.gif" alt="Follow me" />
			  </a>'.$content;
	}
}

	
//Positioning in the page	
if(!function_exists( 'twitter_twit' ) ) {
	function twitter_twit( $content ) {
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
?>
