<?php
/*
Plugin Name: Schreikasten
Plugin URI: http://www.sebaxtian.com/acerca-de/schreikasten
Description: A shoutbox using ajax and akismet.
Version: 0.14.13
Author: Juan Sebastián Echeverry
Author URI: http://www.sebaxtian.com
*/

/* Copyright 2008-2010 Sebaxtian (email : sebaxtian@gawab.com)

	This program is free software; you can redistribute it and/or modify
	it under the terms of the GNU General Public License as published by
	the Free Software Foundation; either version 2 of the License, or
	(at your option) any later version.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with this program; if not, write to the Free Software
	Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA 02110-1301 USA
*/

define ("SK_NOT_FILTERED", 0);
define ("SK_HAM", 1);
define ("SK_SPAM", 2);
define ("SK_MOOT", 3);
define ("SK_BLACK", 4);
define ("SK_BLOCKED", -1);

define ("SK_MODERATION_CONFIG", 0);
define ("SK_MODERATION_YES", 1);
define ("SK_MODERATION_NO", 2);

define ("SK_REQUIREMAIL_CONFIG", 0);
define ("SK_REQUIREMAIL_YES", 1);
define ("SK_REQUIREMAIL_NO", 2);

define ("SK_ANNOUNCE_CONFIG", 1);
define ("SK_ANNOUNCE_YES", 2);
define ("SK_ANNOUNCE_NO", 3);

define ("SK_LAYOUT_SHOUTBOX", 1);
define ("SK_LAYOUT_BOARD", 2);
define ("SK_LAYOUT_CHAT", 3);
define ("SK_LAYOUT_QA", 4);

define ("SK_DB_VERSION", 4);
define ("SK_HEADER_V", 1.21);

define ("SK_CAP", 'moderate_schreikasten');


require_once("legacy.php");

$db_version=get_option('sk_db_version');
$sk_user_agent = "WordPress/$wp_version | Schreikasten/0.1";

add_action('init', 'sk_text_domain');
add_action('init', 'sk_cookie_id');
add_action('wp_head', 'sk_header');
add_action('admin_head', 'sk_adminHeader');
add_action('admin_menu', 'sk_menus');
add_filter('the_content', 'sk_content');
add_filter('the_excerpt', 'sk_content');
add_action('wp_ajax_sk_ajax', 'sk_ajax');
add_action('wp_ajax_nopriv_sk_ajax', 'sk_ajax');
add_action('wp_ajax_sk_ajax_add', 'sk_ajax_add');
add_action('wp_ajax_nopriv_sk_ajax_add', 'sk_ajax_add');
add_action('wp_ajax_sk_ajax_action', 'sk_ajax_action');
add_action('right_now_discussion_table_end', 'sk_dashboard');
register_activation_hook(__FILE__, 'sk_activate');
register_deactivation_hook(__FILE__, 'sk_deactivate');
add_action('sk_cron', 'sk_delete_old_comments');

require_once('libs/SimpleRSSFeedCreator.php');

/**
* Function to delete odl comments.
* This function should be called by the cron action.
*
* @access public
*/
function sk_delete_old_comments() {
	global $wpdb;
	$options = get_option('sk_options');
	if(is_numeric($options['delete_num']) && $options['delete_num']>0) {
		$type = 'DAY';
		switch($options['delete_type']) {
			case 2:
				$type = 'WEEK';
				break;
			case 3:
				$type = 'MONTH';
				break;
			default:
				$type = 'DAY';
				break;
		}
		$num = $options['delete_num'];
		$table_name = $wpdb->prefix . "schreikasten";
		$sql="DELETE FROM $table_name WHERE date < DATE_SUB(NOW(), INTERVAL $num $type);";
		$wpdb->query($sql);
	}
}


/**
* Function to add the required data to the header in the site.
* This function should be called by an action.
*
* @access public
*/
function sk_adminHeader() {
	$css_register = sk_plugin_url("/css/admin.css");
	//Declare style
	wp_register_style('schreikasten_admin', $css_register, false, SK_HEADER_V);
	wp_enqueue_style('schreikasten_admin');
	wp_print_styles( array( 'schreikasten_admin' ));
}

/**
* Function to add the required data to the header in the site.
* This function should be called by an action.
*
* @access public
*/
function sk_header() {

	//Local URL
	$url = get_bloginfo( 'wpurl' );
	$local_url = parse_url( $url );
	$aux_url   = parse_url(wp_guess_url());
	$url = str_replace($local_url['host'], $aux_url['host'], $url);
	
	// Define custom JavaScript function
	echo "
	<script type='text/javascript'>
	/* <![CDATA[ */
	sk_i18n_error = '".__("Can\'t read Schreikasten Feed", 'sk')."';
	sk_url = '$url';
	sk_hasFocus = false;
	sk_old_title = document.title;
	sk_title_message = '".__('New message from', 'sk')." ';
	sk_text_del = '".__('Are you sure you want to delete this comment?', 'sk')."';
	sk_text_blk = '".__('Are you sure you want to mark this comment as blacklisted?', 'sk')."';
	sk_text_spm = '".__('Are you sure you want to mark this comment as SPAM?', 'sk')."';
	/* ]]> */
	</script>
	";
	
	//Declare javascript
	wp_register_script('htmlEncoder', $url.'/wp-content/plugins/schreikasten/libs/htmlEncoder.js', false, SK_HEADER_V);
	wp_enqueue_script('htmlEncoder');
	wp_register_script('soundmanager2', $url.'/wp-content/plugins/schreikasten/libs/soundmanager2.js', false, SK_HEADER_V);
	wp_enqueue_script('soundmanager2');
	wp_register_script('bax-universal', $url.'/wp-content/plugins/schreikasten/libs/universal.js', false, SK_HEADER_V);
	wp_enqueue_script('bax-universal');
	wp_register_script('schreikasten', $url.'/wp-content/plugins/schreikasten/schreikasten.js', array('soundmanager2', 'bax-universal', 'sack', 'htmlEncoder'), SK_HEADER_V);
	wp_enqueue_script('schreikasten');
	
	
	//Define custom CSS URI
	$css = get_theme_root()."/".get_template()."/schreikasten.css";
	if(file_exists($css)) {
		$css_register = get_bloginfo('template_directory')."/schreikasten.css";
	} else {
		$css_register = sk_plugin_url("/css/schreikasten.css");
	}
	//Declare style
	wp_register_style('schreikasten', $css_register, false, SK_HEADER_V);
	wp_enqueue_style('schreikasten');
	
	// Declare we use JavaScript SACK library for Ajax
	wp_print_scripts( array( 'schreikasten' ));
	wp_print_styles( array( 'schreikasten' ));
}
/**
* Information to be displayed in the widget right-now 
*
* @access public
*/
function sk_dashboard() {
	global $wpdb;
	global $current_user;
	get_currentuserinfo();
	if(current_user_can(SK_CAP)) {
		//Comments and spam number
		$total = sk_count();
		$approved = sk_count(SK_HAM);
		$pending = sk_count(SK_MOOT);
		$spam = sk_count(SK_SPAM);
		$blacklisted = sk_count(SK_BLACK) + sk_count(SK_BLOCKED);
		echo "\n\t</table>\n\t</div>";
		echo '<div class="table table_schreikasten" align="center">
	<p class="sub">'.__('Schreikasten', 'sk').'</p>
	<table>
	<tbody>
		<tr class="first">
			<td class="b b-comments"><a href="edit-comments.php?page=skmanage"><span class="total-count">'.$total.'</span></a></td><td class="last t comments"><a href="edit-comments.php?page=skmanage">'._n("Comment", "Comments", $total, 'sk').'</a></td>
			<td class="b b_approved"><a href="edit-comments.php?page=skmanage&filter=ham"><span class="approved-count">'.$approved.'</span></a></td><td class="last t"><a class="approved" href="edit-comments.php?page=skmanage&filter=ham">'._n("Approved", "Approved", $approved, 'sk').'</a></td>
		</tr>
		<tr>
			<td class="b b-waiting"><a href="edit-comments.php?page=skmanage&filter=moot"><span class="pending-count">'.$pending.'</span></a></td><td class="last t"><a class="waiting" href="edit-comments.php?page=skmanage&filter=moot">'._n("Pending", "Pending", $pending, 'sk').'</a></a></td>
			<td class="b b-spam"><a href="edit-comments.php?page=skmanage&filter=spam"><span class="spam-count">'.$spam.'</span></a></td><td class="last t"><a class="spam" href="edit-comments.php?page=skmanage&filter=spam">'._n("Spam", "Spam", $spam, 'sk').'</a></td>
		</tr>';
		if($blacklisted>0) echo '<tr>
			<td colspan="2"></td>
			<td class="b b_blacklisted"><a href="edit-comments.php?page=skmanage&filter=black"><span class="approved-count">'.$blacklisted.'</span></a></td><td class="last t"><a class="blacklisted" href="edit-comments.php?page=skmanage&filter=black">'._n("Rejected", "Rejected", $blacklisted, 'sk').'</a></td>
		</tr>';
	echo '</tbody>';
	}
}

/**
* Does this message exists? Or have been deleted?
*
* @param int id Comment id
* @access public
*/
function sk_commentExists($id) {
	global $wpdb;
	$answer = false;
	//Getcomment data
	$table_name = $wpdb->prefix . "schreikasten";
	$sql="SELECT count(*) FROM $table_name WHERE id=$id";
	$count = $wpdb->get_var($sql);
	if($count>0) $answer = true;
	return $answer;
}

/**
* Function called when a comment has been accepted as HAM
*
* @param int id Comment id
* @access public
*/
function sk_actionAdd($id) {
	global $wpdb;
	//Getcomment data
	$table_name = $wpdb->prefix . "schreikasten";
	$sql="SELECT * FROM $table_name WHERE id=$id";
	$comments = $wpdb->get_results($sql);
	if(count($comments)>0) {
		foreach($comments as $comment) {
			if(function_exists('cp_alterPoints') && $comment->user_id>=0) {
				cp_alterPoints($comment->user_id, get_option('cp_comment_points'));
				cp_log('schreikasten',$comment->user_id,get_option('cp_comment_points'),$comment->id);
			}
		}
	}
}

/**
* Function called when a comment has been deleted or marked as SPAM
*
* @param int id Comment id
* @access public
*/
function sk_actionDelete($id) {
	global $wpdb;
	//Getcomment data
	$table_name = $wpdb->prefix . "schreikasten";
	$sql="SELECT * FROM $table_name WHERE id=$id";
	$comments = $wpdb->get_results($sql);
	if(count($comments)>0) {
		foreach($comments as $comment) {
			if(function_exists('cp_alterPoints') && $comment->user_id>=0) {
				cp_alterPoints($comment->user_id, -get_option('cp_del_comment_points'));
				cp_log('schreikasten',$comment->user_id,-get_option('cp_del_comment_points'),$comment->id);
			}
		}
	}
}

/**
* Function to answer the ajax call.
* This function should be called by an action.
*
* @access public
*/
function sk_ajax() {
	//Get the data from the post call
	$rand  = $_POST['rand'];
	$page  = $_POST['page'];
	$size  = $_POST['size'];
	$count = $_POST['count'];
	$force = $_POST['force'];
	if(!is_numeric($size) || $size<1) $size = 5;
	
	if($force=='true') $force = true; else $force = false;
	
	//Get the new data.
	$results = "none";
	if($force || $count!=sk_count(SK_HAM))
		$results = sk_show_comments($size, $page, false, $rand).sk_page_selector($size, $page,$rand); 
	// Compose JavaScript for return
	die( $results );
}

/**
* Function to answer the ajax call.
* This function should be called by an action.
*
* @access public
*/
function sk_ajax_add() {
	//Get the data from the post call
	$alias= html_entity_decode($_POST['alias'], ENT_QUOTES, 'UTF-8');
	$email= html_entity_decode($_POST['email'], ENT_QUOTES, 'UTF-8');
	$text = html_entity_decode($_POST['text'], ENT_QUOTES, 'UTF-8');
	$for  = $_POST['skfor'];
	$rand = $_POST['rand'];
	$size = $_POST['size'];
	if(!is_numeric($size) || $size<1) $size = 5;
	$results = false;
	
	$alias = wptexturize($alias);
	$email = wptexturize($email);
	$text = wptexturize($text);
	
	//Get environment data
	if (isset($_SERVER['HTTP_X_FORWARD_FOR']) && $_SERVER['HTTP_X_FORWARD_FOR']) {
		$ip = $_SERVER['HTTP_X_FORWARD_FOR'];
	} else {
		$ip = $_SERVER['REMOTE_ADDR'];
	}
	
	setcookie('comment_author_' . COOKIEHASH, $alias, time() + 30000000, COOKIEPATH, COOKIE_DOMAIN);
	setcookie('comment_author_email_' . COOKIEHASH, $email, time() + 30000000, COOKIEPATH, COOKIE_DOMAIN);
	
	$id=sk_add_comment($alias, $email, $text, $ip, $for);
	
	//Get the new data.
	if(sk_cookie_id()==0) {
		$results = "<p id='skwarning'>".__('We cannot accept messages<br>from this PC', 'sk').".</p>";
	}
	
	$results.= sk_show_comments($size, 1,$id,$rand).sk_page_selector($size, 1,$rand); 

	// Compose JavaScript for return
	die( $results );
}

/**
* Function to execute an ajax action. Only works with administrator rights.
* This function should be called by an action.
*
* @access public
*/
function sk_ajax_action() {
	//Get the data from the post call
	$sk_action = $_POST['sk_action'];
	$id   = $_POST['id'];
	$rand = $_POST['rand'];
	$page = $_POST['page'];
	$size = $_POST['size'];
	if(!is_numeric($size) || $size<1) $size = 5;
	
	global $current_user;
	
	get_currentuserinfo();
	
	//check if this user can perform an action
	if($current_user) {
		if(current_user_can( SK_CAP )) { //Yes, he is the man!
			switch($sk_action) {
				case 'set_black': //set as blacklisted
					sk_mark_as_black($id);
					break;
				case 'set_spam': //set as spam
					sk_mark_as_spam($id);
					break;
				case 'delete': //delete the comment
					sk_delete_comment($id);
					break;
			}
		}
	}
	
	//Get the new data.
	$results = sk_show_comments($size, $page, false, $rand).sk_page_selector($size, $page,$rand); 

	// Compose JavaScript for return
	die( $results );
}
 
/**
* Returns the ID of the PC who is makeing the call. If there isn't an assigned
* cookie create one.
* This function is public but an action call it too.
*
* @access public
*/
function sk_cookie_id() {
	$answer=0;
	
	// If there isn't a cookie, create one
	if(!$_COOKIE['sk-id']) {
		//Positive numbers are for logged users, negative for cookies
		@setcookie("sk-id", (mt_rand())*-1, time()+3600*24*365*10,"/","");
	} else {
		$answer=$_COOKIE['sk-id'];
	}
	
	return $answer;
}

/**
* Function to do a post request. This function is used by the antispam system, akismet.
*
* @access public
* @param string request The post request
* @param string host The server for whom we call
* @param string path The URL to ask in the server
* @param int port The port number. Optional, it uses the HTTP port by default
*/
function sk_http_post($request, $host, $path, $port = 80) {
	global $sk_user_agent;
	$http_request  = "POST $path HTTP/1.0\r\n";
	$http_request .= "Host: $host\r\n";
	$http_request .= "Content-Type: application/x-www-form-urlencoded; charset=" . get_settings('blog_charset') . "\r\n";
	$http_request .= "Content-Length: " . strlen($request) . "\r\n";
	$http_request .= "User-Agent: $sk_user_agent\r\n";
	$http_request .= "\r\n";
	$http_request .= $request;
	
	$response = '';
	if( false !== ( $fs = @fsockopen($host, $port, $errno, $errstr, 3) ) ) {
		fwrite($fs, $http_request);
		while ( !feof($fs) ) {
			$response .= fgets($fs, 1160); // One TCP-IP packet
		}
		fclose($fs);
		$response = explode("\r\n\r\n", $response, 2);
	}
	return $response;
}

/**
* Verify Akismet Key
*
* @return bool
* @access public
*/
function sk_verify_key( ) {
	$answer=false;
	
	//Ask if the key is asigned to this host
	$key=get_option('sk_api_key');
	$blog = urlencode( get_option('home') );
	if(strlen(get_option('sk_api_key'))!=0) {
		$response = sk_http_post("key=$key&blog=$blog", 'rest.akismet.com', '/1.1/verify-key');
		if ( 'valid' == $response[1] ) {
			$answer=true;
		}
	}
	return $answer;
}

/**
* To declare where are the mo files (i18n).
* This function should be called by an action.
*
* @access public
*/
function sk_text_domain() {
	load_plugin_textdomain('sk', false, 'schreikasten/lang');
}

/**
* Function to return the url of the plugin concatenated to a string. The idea is to
* use this function to get the entire URL for some file inside the plugin.
*
* @access public
* @param string str The string to concatenate
* @return The URL of the plugin concatenated with the string 
*/
function sk_plugin_url($str = '')
{

	$aux = '/wp-content/plugins/schreikasten/'.$str;
	$aux = str_replace('//', '/', $aux);
	$url = get_bloginfo('wpurl');
	return $url.$aux;
	
}

/**
* Indicates if require name and email.
*
* @return bool
* @access public
*/
function sk_require_name_and_email()
{
	$answer=false;
	$options = get_option('sk_options');
	$require = $options['requiremail'];
	$general = get_option('require_name_email');
	
	if(is_bool($require)) {
		if($require) {
			$require = 2; //The old 'true' is the new '2'. 
		} else {
			$require = 3; //The old 'false' is the new '3'.
		}
	}
	
	//If we use the general configuration and the configuration say 'yes', or
	//if we say 'yes'	
	if($require == 2 || ($require == 1 && $general) )
		$answer=true;
	return $answer;
}

/**
* Indicates if only registered user can add comments.
*
* @return bool
* @access public
*/
function sk_only_registered_users()
{
	$answer=false;
	$options = get_option('sk_options');
	$registered = $options['registered'];
	$general = get_option('comment_registration');
	
	if(is_bool($registered)) {
		if($registered) {
			$registered = 2; //The old 'true' is the new '2'. 
		} else {
			$registered = 3; //The old 'false' is the new '3'.
		}
	}
	
	//If we use the general configuration and the configuration say 'yes', or
	//if we say 'yes'	
	if($registered == 2 || ($registered == 1 && $general) )
		$answer=true;
	return $answer;
}

/**
* Returns the avatar using the comment id
*
* @access public
* @param int id Comment's id
* @param int size Size of the avatar image
* @return string The avatar image
*/
function sk_avatar($id, $size) {
	global $wpdb;
	$answer="";
	
	if(strlen($id)>0 && $id!=0 ) {
		//Get the email, user id and alias for the comment
		$table_name = $wpdb->prefix . "schreikasten";
		$data = $wpdb->get_row("select user_id, alias, email from $table_name where id=$id");
		$alias=$data->alias;
		$email=$data->email;
		$user_id=$data->user_id;
		
		if($user_id>0) { //If user id is greater than 0, it means this is a registered user
			$answer=get_avatar($user_id,$size);
		} else {
			if($email=="") { //If we don't have an email, use the alias
				$answer=get_avatar($alias,$size);
			} else {
				$answer=get_avatar($email,$size); //else, use the email
			}
		}
	} else {
		$answer=get_avatar("",$size); //The anonimous
	}
	return $answer;
}

/**
* Returns HTML for 'page selector' footer
*
* @param int group Which group are we showing?
* @return string
* @access public
*/
function sk_page_selector($size, $group=1,$rand=false) {
	global $wpdb;
	
	if(!$rand) $rand = mt_rand(111111,999999);
	
	if(!is_numeric($size) || $size<1) $size = 5;
	
	$uri_sk=sk_plugin_url('/content.php?page');
	$answer="";
	$total_groups=5; //We will show only 5 groups
	$style_actual_group="";
	$style_no_actual_group="";
	$first_item= "&#171;";
	$last_item= "&#187;";
	
	//Create nonce
	$nonce = wp_create_nonce('sk'.$rand);
	
	$options = get_option('sk_options');
	
	// Get the number of comments we have
	$table_name = $wpdb->prefix . "schreikasten";
	$sql="SELECT count(*) FROM $table_name WHERE status=".SK_HAM;
	if($options['layout']==SK_LAYOUT_QA) {
		$sql.=" AND reply=0";
	}
	
	$total = $wpdb->get_var($sql);
	
	//Get the number of groups we have
	$groups=ceil($total/$size);
	
	//By default we start with the first group and end with the number of groups
	//With this we define the interval to show
	$group_start=1;
	$group_end=$total_groups;
	
	//A number to determine thye groups to show
	$group_limit=ceil($total_groups/2)-1;

	//If the number of groups is lesser or equar than the number of groups to show
	if($groups<=$total_groups) {
		$group_end=$groups; //The start group is 1,and the end group is the number of groups
	} else {
		if($groups-$group<=$group_limit) {	// If the difference between the total groups 
														// to show and the group we are showing is 
														// lesser or equal to the group limit.
														// It means we are so close to the end so we have to 
														// show the total number of groups at the end and
														// calculate the begin 
			$group_start=$groups-$total_groups+1; //The start group is the groups minus the total groups to show pluss 1 
			$group_end=$groups; // The end group is the number of groups
		} else {
			if($group>$group_limit) { 	// If the group to show is greater than the group limit. 
												// It means we are far away from the begin so we can 
												// show calculate the list and set the group in the middle.
				$group_start=$group-$group_limit; //The start is the group to show minus the group limit
				$group_end=$group+$group_limit; //The end is the group to show plus the group limit
			}
		}
	}
	
	//The timer system (experimental)
	$timer="";
	if($options['refresh']>0) {		
		$timer="\nsk_timer$rand();";
	}

	//If the list doesn't start from 1, create a link to go to the benginig
	if($group_start!=1) {
		$answer.="<a class='sk-page-other' onclick=\"
				var aux = document.getElementById('throbber-page$rand');
				aux.setAttribute('class', 'throbber-page-on');
				aux.setAttribute('className', 'throbber-page-on'); //IE sucks
				document.getElementById('sk_page$rand').value=1;
				$timer
				sk_feed( 1, $rand, sk_semaphore$rand, true );\">$first_item</a> &#183; ";
	}
	
	//Create the page list and the links
	for($group_id=$group_start; $group_id<=$group_end; $group_id++) {
		$style=$style_no_actual_group;
		if($group_id==$group) {
			$answer.="<span class='sk-page-actual'>$group_id</span> &#183; ";
		} else {
			$answer.="<a class='sk-page-other' onclick=\"
				var aux = document.getElementById('throbber-page$rand');
				aux.setAttribute('class', 'throbber-page-on');
				aux.setAttribute('className', 'throbber-page-on'); //IE sucks
				document.getElementById('sk_page$rand').value=$group_id;
				$timer
				sk_feed( $group_id, $rand, sk_semaphore$rand, true );\">$group_id</a> &#183; ";
		}
	}

	//If the list doesn't finish with the last group, create a link to the end
	if($group_end!=$groups) {
	$answer.="<a class='sk-page-other'
			 onclick=\"
			
				var aux = document.getElementById('throbber-page$rand');
				aux.setAttribute('class', 'throbber-page-on');
				aux.setAttribute('className', 'throbber-page-on'); //IE sucks
				document.getElementById('sk_page$rand').value=$groups;
				$timer
				sk_feed( $groups, $rand, sk_semaphore$rand, true );\">$last_item</a> &#183; ";
	}

	//As every link ends with a line, delete the last one as we don't need it
	if ( function_exists( 'mb_substr' ) ) {
        $answer = mb_substr($answer,0,-8);
	} else {
        $answer = substr($answer,0,-8);
	}
	return "<br/><div id='throbber-page$rand' class='throbber-page-off'><small>$answer</small></div>";
}


/**
* Function to create the database and to add options into WordPress.
* This function should be called by an action.
*
* @access public
*/
function sk_activate() {
	global $wpdb;
	global $db_version;
	
	//Create new capabilitie
	$role1 = get_role( 'administrator' );
	$role2 = get_role( 'editor' );
	$role3 = get_role( 'author' );
	// add SK_CAP to the roles if the administrator doesn't have the capabilitie.
	if(!$role1->has_cap( SK_CAP )) {
		$role1->add_cap( SK_CAP );
		$role2->add_cap( SK_CAP );
		$role3->add_cap( SK_CAP );
	}
	
	$table_name = $wpdb->prefix . "schreikasten";
	$blacklist_name = $wpdb->prefix . "schreikasten_blacklist";
	$db_version=get_option('sk_db_version');
	switch($db_version) {
	case 1: //SQL code to update from SK1 to SK4
		$sql="ALTER TABLE $table_name ADD user_id int NOT NULL"; 
		$wpdb->query($sql);
		$sql="ALTER TABLE $table_name ADD email tinytext NOT NULL";
		$wpdb->query($sql);
		$sql="ALTER TABLE $table_name CONVERT TO CHARACTER SET utf8"; 
		$wpdb->query($sql);
		$sql = "CREATE TABLE $blacklist_name(
		id bigint(1) NOT NULL AUTO_INCREMENT,
		pc bigint(1) NOT NULL,
		date datetime NOT NULL,
		forever tinyint(4) NOT NULL,
		PRIMARY KEY (id)
		) CHARSET=utf8;";
		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		dbDelta($sql);
		update_option('sk_db_version', SK_DB_VERSION);
		break;
	case 2: //SQL code to update from SK2 to SK4
		$sql="ALTER TABLE $table_name ADD reply int NOT NULL"; 
		$wpdb->query($sql);
		$sql="ALTER TABLE $table_name CONVERT TO CHARACTER SET utf8"; 
		$wpdb->query($sql);
		$sql="ALTER TABLE $blacklist_name CONVERT TO CHARACTER SET utf8"; 
		$wpdb->query($sql);
		update_option('sk_db_version', SK_DB_VERSION);
		break;
	case 3: //SQL code to update from SK3 to SK4
		$sql="ALTER TABLE $table_name CONVERT TO CHARACTER SET utf8"; 
		$wpdb->query($sql);
		$sql="ALTER TABLE $blacklist_name CONVERT TO CHARACTER SET utf8"; 
		$wpdb->query($sql);
		update_option('sk_db_version', SK_DB_VERSION);
		break;
	case 4: //We are in SK3, so theres nothing we have to do
		break;
	default: //It's a fresh installation, create the table.
		if($wpdb->get_var("show tables like '$table_name'") != $table_name)
		{
			$sql = "CREATE TABLE $table_name(
				id bigint(1) NOT NULL AUTO_INCREMENT,
				alias tinytext NOT NULL,
				text text NOT NULL,
				date datetime NOT NULL,
				ip char(32) NOT NULL,
				status int NOT NULL,
				user_id int NOT NULL,
				email tinytext NOT NULL,
				reply int NOT NULL,
				PRIMARY KEY (id)
			) CHARSET=utf8;";
			require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
			dbDelta($sql);
		}
		
		if($wpdb->get_var("show tables like '$blacklist_name'") != $blacklist_name)
		{
			$sql = "CREATE TABLE $blacklist_name(
				id bigint(1) NOT NULL AUTO_INCREMENT,
				pc bigint(1) NOT NULL,
				date datetime NOT NULL,
				forever tinyint(4) NOT NULL,
				PRIMARY KEY (id)
				) CHARSET=utf8;";
			require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
			dbDelta($sql);
		}
		
		//Widget options
		$options = array('title'=>__('Schreikasten', 'sk'), 'registered'=>false, 'avatar'=>true, 'layout'=>SK_LAYOUT_SHOUTBOX, 'alert_about_emails'=>true, 'items'=>'5', 'refresh'=>0, 'bl_days'=>'7', 'bl_maxpending'=>'2', 'announce'=>'1', 'requiremail'=>'1', 'maxchars'=>'225', 'rss'=>false, 'moderation'=>SK_MODERATION_CONFIG, 'date_format'=>'l, M j. Y h:i A');
		add_option('sk_options', $options);
		
		add_option('sk_db_version', SK_DB_VERSION);
		add_option('sk_api_key', '');
		add_option('sk_api_key_accepted', false);
		sk_verify_key( ); //if we have an old sk_api_key, verify it;
		
		break;
	}
	
	//Widget options
	$options = array('title'=>__('Schreikasten', 'sk'), 'registered'=>false, 'avatar'=>true, 'layout'=>SK_LAYOUT_SHOUTBOX, 'alert_about_emails'=>true, 'items'=>'5', 'refresh'=>0, 'bl_days'=>'7', 'bl_maxpending'=>'2', 'announce'=>'1', 'requiremail'=>'1', 'maxchars'=>'225', 'rss'=>false, 'moderation'=>SK_MODERATION_CONFIG, 'delete_type'=>1, 'delete_num'=>0, 'maxperday'=>0);
	add_option('sk_options', $options);
	
	//Have we updated or no?
	//Is this widget a multiwidget?
	$options = get_option('widget_sk');
	if ( is_array($options) && !array_key_exists('_multiwidget', $options) ) {
		//Update the widget into multiple widgets
		skLegacy_updateWidget();
	}
	
	//Update layout configuration
	$options = get_option('sk_options');
	if(isset($options['replies'])) {
		if($options['replies']) $options['layout'] = SK_LAYOUT_BOARD;
		else $options['layout'] = SK_LAYOUT_SHOUTBOX;
		unset($options['replies']);
		update_option('sk_options', $options);
	}
	if(!isset($options['date_format'])) {
		 $options['date_format'] = 'l, M j. Y g:i A';
		 update_option('sk_options', $options);
	}
	if(!isset($options['delete_type'])) {
		 $options['delete_type'] = 1;
		 $options['delete_num'] = 0;
		 update_option('sk_options', $options);
	}
	if(!isset($options['maxperday'])) {
		 $options['maxperday'] = 0;
		 update_option('sk_options', $options);
	}
	
	//The cron function
	wp_clear_scheduled_hook('sk_cron');
	wp_schedule_event(time(), 'hourly', 'sk_cron');
	
}

function sk_deactivate() {
	wp_clear_scheduled_hook('sk_cron');
}

/**
* Function to delete a Comment
*
* @param int id Comment's id
* @return bool
* @access public
*/
function sk_delete_comment($id) {
	global $wpdb;
	
	//Delete the comment
	$table_name = $wpdb->prefix . "schreikasten";
	$query = "DELETE FROM " . $table_name ." WHERE id=" . $id;
	sk_actionDelete($id); //Call any action we have.
	$answer=$wpdb->query( $query );
	return $answer;
}

/**
* Sends an email to the author of a comment it has a reply.
* Return true if the email was send.
*
* @param int id Comment's id
* @return bool
* @access public
*/
function sk_reply($id) {
	global $wpdb;
	$answer=false;
	
	//Get the 'from' data
	$table_name = $wpdb->prefix . "schreikasten";
	$query="SELECT * FROM " . $table_name ." WHERE id=".$id;
	$from = $wpdb->get_row($query);
	
	//If we have the 'from' data and it is a reply
	if($from->reply!=0) {
		//Get the 'for' data
		$query="SELECT * FROM " . $table_name ." WHERE id=".(-1*$from->reply);
		$for = $wpdb->get_row($query);
		
		$website=get_option('blogname');
		$url=get_option('home');
		
		//Create the mail and send it
		if($for->email!="" && $from->status!=SK_SPAM) {
			if($for->email!=get_option('admin_email')) {
			
				$email=$for->email;
				$notify_message = sprintf(__('There is a reply to your comment on %s from %s', 'sk'), $website, $from->alias) . "\r\n\r\n";
				$notify_message .= sprintf(__('Your comment : %s', 'sk'), html_entity_decode($for->text, ENT_QUOTES, 'UTF-8') ) . "\r\n\r\n";
				$notify_message .= sprintf(__('Reply comment: %s', 'sk'), html_entity_decode($from->text, ENT_QUOTES, 'UTF-8') ). "\r\n\r\n";
				
				$notify_message .= $url;
				
				@wp_mail($email, sprintf(__('An answer to your comment on %s', 'sk'), $website), $notify_message);
			} 
			//To not resend the reply, clear the reply column
			$query="UPDATE $table_name SET reply=reply*-1 WHERE id=".$id;
			$wpdb->query($query);
			
			$answer=true;
		}
	}
	return $answer;
}

/**
* Sends an email to the admin, indicating there is a new comment and if it needs to be moderated.
* Return true if the email was send.
*
* @param int id Comment's id
* @return bool
* @access public
*/
function sk_inform($id) {
	global $wpdb;
	
	//Get the comment data
	$table_name = $wpdb->prefix . "schreikasten";
	$query="SELECT * FROM " . $table_name ." WHERE id=".$id;
	$comment = $wpdb->get_row($query);
	
	$options = get_option('sk_options');
	$announce = $options['announce'];
	if(!$announce) $announce = SK_ANNOUNCE_CONFIG;
	
	//If it was accepted create the information email
	if($comment->status==SK_HAM) {
		if($announce == SK_ANNOUNCE_YES || ($announce == SK_ANNOUNCE_CONFIG && get_option('comments_notify'))) {
			$admin_email = get_option('admin_email');
			$notify_message=__('There is a new comment on Schreikasten', 'sk') . "\r\n";
			$notify_message.= sprintf( '%s', admin_url("edit-comments.php?page=skmanage&paged=1&mode=edit&id=$id") ) . "\r\n\r\n";
			$notify_message .= sprintf( __('Author : %1$s (IP: %2$s)', 'sk'), html_entity_decode($comment->alias, ENT_QUOTES, 'UTF-8'), $comment->ip ) . "\r\n";
			if($comment->email!="") $notify_message .= sprintf( __('E-mail : %s', 'sk'), $comment->email ) . "\r\n";
			$notify_message .= sprintf( __('Whois  : http://ws.arin.net/cgi-bin/whois.pl?queryinput=%s'), $comment->ip ) . "\r\n";
			$notify_message .= sprintf( __('Comment: %s', 'sk'), html_entity_decode($comment->text, ENT_QUOTES, 'UTF-8') ) . "\r\n\r\n";							
			$notify_message .= sprintf( __('Delete it: %s', 'sk'), admin_url("edit-comments.php?page=skmanage&paged=1&mode=delete&id=$id") ) . "\r\n";
			$notify_message .= sprintf( __('Reject it: %s', 'sk'), admin_url("edit-comments.php?page=skmanage&paged=1&mode=set_black&id=$id") ) . "\r\n";
			$notify_message .= sprintf( __('Spam it: %s', 'sk'), admin_url("edit-comments.php?page=skmanage&paged=1&mode=set_spam&id=$id") );
								
			@wp_mail($admin_email, __('New comment on Schreikasten', 'sk'), $notify_message);
		}
	}
	
	//If it waits for moderation, create the information email
	if($comment->status==SK_MOOT) {
		if($announce == SK_ANNOUNCE_YES || ($announce == SK_ANNOUNCE_CONFIG && get_option('moderation_notify'))) {
			$admin_email = get_option('admin_email');
			$notify_message=__('A new comment on Schreikasten is waiting for your approval', 'sk') . "\r\n";
			$notify_message.= sprintf( '%s', admin_url("edit-comments.php?page=skmanage&paged=1&mode=edit&id=$id") ) . "\r\n\r\n";
			$notify_message .= sprintf( __('Author : %1$s (IP: %2$s)', 'sk'), html_entity_decode($comment->text, ENT_QUOTES, 'UTF-8'), $comment->ip ) . "\r\n";
			if($comment->email!="") $notify_message .= sprintf( __('E-mail : %s', 'sk'), $comment->email ) . "\r\n";
			$notify_message .= sprintf( __('Whois  : http://ws.arin.net/cgi-bin/whois.pl?queryinput=%s'), $comment->ip ) . "\r\n";
			$notify_message .= sprintf( __('Comment: %s', 'sk'), html_entity_decode($comment->text, ENT_QUOTES, 'UTF-8') ) . "\r\n\r\n";
			$notify_message .= sprintf( __('Approve it: %s', 'sk'), admin_url("edit-comments.php?page=skmanage&paged=1&mode=set_ham&id=$id") ) . "\r\n";
			$notify_message .= sprintf( __('Delete it: %s', 'sk'), admin_url("edit-comments.php?page=skmanage&paged=1&mode=delete&id=$id") ) . "\r\n";
			$notify_message .= sprintf( __('Reject it: %s', 'sk'), admin_url("edit-comments.php?page=skmanage&paged=1&mode=set_black&id=$id") ) . "\r\n";
			$notify_message .= sprintf( __('Spam it: %s', 'sk'), admin_url("edit-comments.php?page=skmanage&paged=1&mode=set_spam&id=$id") );
								
			@wp_mail($admin_email, __('Schreikasten comment to moderate', 'sk'), $notify_message);
		}
	}
	
}

/**
* Function to add a Comment. If Akismet is working ask akismet, else mark as Ham.
*
* @param string alias Who comments?
* @param string text Comment's text
* @param string ip 
* @return bool
* @access public
*/
function sk_add_comment($alias, $email, $text, $ip, $for) {
	global $wpdb;
	global $current_user;
	$options = get_option('sk_options');
	get_currentuserinfo();
	
	//If the sender is logged use it's internal id
	$user_id=0; //Positive for logged users, negative for cookies, 0 for anonymous
	if($current_user->ID>0) {
		$user_id=$current_user->ID;
	} else { //else, use the cookie
		$user_id=sk_cookie_id();
	}
	
	$user_can_send = true;
	if(sk_only_registered_users() && $user_id<=0) $user_can_send = false;
	
	//Do we need moderation?
	$require_moderation = true;
	if($options['moderation'] == SK_MODERATION_CONFIG) {
		if(!get_option('comment_moderation')) {
			$require_moderation = false;
		}
	}
	if($options['moderation'] == SK_MODERATION_NO) {
		$require_moderation = false;
	}
	
	$answer=false;
	
	//Mail is ok and do we require it?
	$mailok = false;
	if( $options['requiremail'] == SK_REQUIREMAIL_YES || ($options['requiremail'] == SK_REQUIREMAIL_CONFIG && get_option('require_name_email')) ) {
		$search = "/^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i";
		if(preg_match ( $search , $email)) $mailok = true;
	} else {
		$mailok = true;
	}
	
	//Is this form sending to much data?
	$maxcharok = false;
	if ( function_exists( 'mb_strlen' ) ) {
		$aux_size = mb_strlen($text);
	} else {
		$aux_size = strlen($text);
	}
   
	if($aux_size<=$options['maxchars']) $maxcharok = true;
	
	//Is the data well formed? Maybe a spammer is sending data through an external form.
	if($maxcharok && $mailok && $user_can_send) {
		//If we can only accept messages for registered user and this is a registered user
		//or we can accept for not registered users
		//and in general this user can send more messages, accept the comment
		if( ( ( sk_only_registered_users() && $user_id>0 ) || !sk_only_registered_users() ) && !sk_can_not_accept_more_messages($user_id) ) {
			$time=current_time('mysql');
			
			//if someone logged or with a cooki sends it
			if($user_id!=0) {
				if(strlen($alias)>0 && strlen($text)>0) { //If we have a name and a text
					$table_name = $wpdb->prefix . "schreikasten";
					$insert = "INSERT INTO " . $table_name .
						" (alias, text, date, ip, status, user_id, email, reply) " .
						"VALUES ('%s', '%s', '%s', '%s', %d, %d, '%s', %d)";
					//Add the comment, mark it to moderate
					$insert = $wpdb->prepare( $insert, $alias, $text, $time, $ip, SK_MOOT, $user_id, $email, (-1*$for) );
					if($answer = $wpdb->query( $insert ) ) { //If the comment was accepted
						$id = $wpdb->get_var("select last_insert_id()");
						$answer=$id;
						$spam=false; 
						//if(sk_verify_key()) {
						if(get_option('sk_api_key_accepted')) { //If we have a verified key
							$spam=sk_is_spam($id); //Check if this comment is spam
						}
						
						if(!$spam) { //If it is not spam
							//If this is the administrator
							//OR
							//the owner is not in the blacklist 
							//and we do not require to moderate
							//and it is not an anonymous,
							//accept the message
							if(current_user_can('install_plugins') || (!sk_is_blacklisted() && 1 != $require_moderation && $user_id != 0 )) {
								//sk_mark_as_ham($id); //accept the message
								$query="UPDATE " . $table_name ." SET status='".SK_HAM."' WHERE id=".$id;
								$wpdb->query( $query );
								sk_reply($id); //send the reply, if it has one
								sk_actionAdd($id); //Call external functions.
							}
							sk_inform($id); //Inform the administrator
						}
					}
				}
			}
		}
	}
	return $answer;
}

/**
* Update blacklist. Release PC listed with more than the max days accepted.
*
* @access public
*/
function sk_blacklist_update() {
	global $wpdb;
	$options = get_option('sk_options');
	$days=0;
	
	//get the max number of days to be blacklisted
	if(is_array($options)) {
		$days=$options['bl_days'];
	}
	if($days!=0) {
		$table_name = $wpdb->prefix . "schreikasten_blacklist";
		//Delete any pc blacklisted wich aren't marked to be blacklisted forever 
		//and with a mark older than the days accepted
		$query="DELETE FROM " . $table_name ." WHERE forever=0 AND date <= NOW() - INTERVAL ".$days." DAY";
		$wpdb->query( $query );
	}
}

/**
* Return the id of the block, or false if PC is not blacklisted. Without 
* parameter checks cookies
*
* @param int pc PC to check. Withoput parameter use cookie
* @return int
* @access public
*/
function sk_is_blacklisted($pc=false) {
	global $wpdb;
	global $current_user;
	$answer=false;
	
	//use the current pc id (or user id) if the function didn't get the variable
	if(!$pc) {
		get_currentuserinfo();
		$pc=0;
		if($current_user->ID>0) {
			$pc=$current_user->ID;
		} else {
			$pc=sk_cookie_id();
		}
	}

	//Check if the PC is in the blacklist
	$table_name = $wpdb->prefix . "schreikasten_blacklist";
	$query="SELECT COUNT(*) FROM " . $table_name ." WHERE pc = ".$pc;
	$total=$wpdb->get_var( $query );
	if($total>0) {
			$query="SELECT id FROM " . $table_name ." WHERE pc = ".$pc;
			$answer=$wpdb->get_var( $query );
	}
	return $answer;
}

/**
* Return the number of allowed messages a PC can send. -1 if is unlimited.
*
* @param int pc PC to check. Withoput parameter use cookie
* @return int 
* @access public
*/
function sk_allowed_messages($pc=false) {
	global $wpdb;
	global $current_user;
	get_currentuserinfo();
	$answer=-1;
	$options = get_option('sk_options');
	
	//If current user can administrate comments, it has unlimited messages allowed
	//else, check the limit
	if(!current_user_can( SK_CAP )) {
		//use the current pc id (or user id) if the function didn't get the variable
		if(!$pc) {
			$pc=0;
			if($current_user->ID>0) {
				$pc=$current_user->ID;
			} else {
				$pc=sk_cookie_id();
			}
		}
		
		$allowed_blocked = -1;
		$allowed_perday = -1;
		
		//Is it blacklisted?
		if(sk_is_blacklisted($pc)) {
			
			//max number of messages a blacklisted pc can send
			$max=$options['bl_maxpending'];
			//get the numbr of messages a pc (or user) have pending from moderation
			$table_name = $wpdb->prefix . "schreikasten";
			$query="SELECT COUNT(*) FROM " . $table_name ." WHERE status = ".SK_MOOT." AND user_id = ".$pc;
			$total=$wpdb->get_var( $query );
			
			$allowed_blocked=$max-$total;
		}
		
		//max number of messages a blacklisted pc can send
		$max=$options['maxperday'];
		//Messages per day
		if($max>0) {
			
			//get the numbr of messages a pc (or user) have pending from moderation
			$table_name = $wpdb->prefix . "schreikasten";
			$query="SELECT COUNT(*) FROM " . $table_name ." WHERE status != ".SK_BLACK." AND DATE(date) = DATE(NOW()) AND user_id = ".$pc;
			$total=$wpdb->get_var( $query );
			
			//if it has more or equal, inform this user can't send more comments
			$allowed_perday=$max-$total;
		}
		
		//Do we have a limmit?
		if($allowed_perday != -1 || $allowed_blocked != -1) {
			if($allowed_perday == -1) $answer = $allowed_blocked;
			if($allowed_blocked == -1) $answer = $allowed_perday;
			
		}
		if($allowed_perday != -1 && $allowed_blocked != -1) {
			if($allowed_perday > $allowed_blocked) $answer = $allowed_blocked;
			else $answer = $allowed_perday;
		}
	}
	
	return $answer;
}

/**
* Return true if this PC can't send more comments to be accepted
*
* @param int pc PC to check. Withoput parameter use cookie
* @return bool
* @access public
*/
function sk_can_not_accept_more_messages($pc=false) {
	$answer = false;
	if(sk_allowed_messages($pc)==0) $answer = true;
	return $answer;
}

/**
* Marks comment as spam and send message to Akismet
*
* @param int id Comment's id
* @return bool
* @access public
*/
function sk_mark_as_spam($id) {
	global $wpdb;
	$table_name = $wpdb->prefix . "schreikasten";
	$query="UPDATE " . $table_name ." SET status='".SK_SPAM."' WHERE id=".$id;
	$answer1=$wpdb->query( $query );
	sk_actionDelete($id); //Call any action we have.
	
	//if(sk_verify_key()) {
	if(get_option('sk_api_key_accepted')) { //if we have an accepted key
		global $sk_user_agent;
		//Get the data for akismet
		$key=get_option('sk_api_key');
		$blog = urlencode( get_option('home') );
		$query="SELECT * FROM " . $table_name ." WHERE id=".$id;
		$comment = $wpdb->get_row($query);
		$user_agent=$comment->user_agent;
		$ip=$comment->ip;
		$comment_author=$comment->alias;
		$comment_content=$comment->text;
		
		//create the post request
		$path = "blog=$blog&user_ip=$ip&user_agent=$sk_user_agent&comment_author=$comment_author&comment_content=$comment_content";
		//do we have an email?
		if($comment->email!="") {
			$path.="&comment_author_email=".$comment->email;
		}
		//ask and wait for the answer
		$response = sk_http_post($path, $key.'.rest.akismet.com', '/1.1/submit-spam');
	}
	
	return $answer1;
}

/**
* Marks comment as blacklisted
*
* @param int id Comment's id
* @return bool
* @access public
*/
function sk_mark_as_black($id) {
	global $wpdb;
	$answer2=true;
	$table_name = $wpdb->prefix . "schreikasten";
	$blacklist_name = $wpdb->prefix . "schreikasten_blacklist";
	$query="UPDATE " . $table_name ." SET status='".SK_BLACK."' WHERE id=".$id;
	$answer1=$wpdb->query( $query );
	sk_actionDelete($id); //Call any action we have.
	
	//Get PC
	$query="SELECT user_id FROM " . $table_name ." WHERE id=".$id;
	$pc=$wpdb->get_var($query);
	
	//If there is a reference about the PC, do something
	if($pc!=0) {
		$query="SELECT date FROM " . $table_name ." WHERE id=".$id;
		$date=$wpdb->get_var($query);
		//Is pc blacklisted?
		if(sk_is_blacklisted($pc)) {
			//Update the PC so the new blacklist date is the actual date if the blacklist date is older than
			//the one in the list.
			$sql="UPDATE $blacklist_name SET date='$date' WHERE pc=$pc AND date<'$date'";
			$wpdb->query( $sql );
		} else {
			//Add the PC to the blacklist
			$insert = "INSERT INTO " . $blacklist_name .
				" (pc, date, forever) " .
				"VALUES ('$pc', '$date', 0)";
			$answer2 = $wpdb->query( $insert );
		}
	}
	
	return $answer1&&$answer2;
}

/**
* Marks comment as ham and send message to Akismet
*
* @param int id Comment's id
* @return bool
* @access public
*/
function sk_mark_as_ham($id) {
	global $wpdb;
	$table_name = $wpdb->prefix . "schreikasten";
	$status = sk_status($id);
	//Change the status
	$query="UPDATE " . $table_name ." SET status='".SK_HAM."' WHERE id=".$id;
	$answer1=$wpdb->query( $query );
	//Send the reply
	sk_reply($id);
	sk_actionAdd($id); //Call any action we have.
	
	// Send HAM mark to Akismet if there is an API key and
	// the comment was marked as SPAM
	if(get_option('sk_api_key_accepted') && $status==SK_SPAM) {
		global $sk_user_agent;
		//Get the data to send to akismet
		$key=get_option('sk_api_key');
		$blog = urlencode( get_option('home') );
		$query="SELECT * FROM " . $table_name ." WHERE id=".$id;
		$comment = $wpdb->get_row($query);
		$user_agent=$comment->user_agent;
		$ip=$comment->ip;
		$comment_author=$comment->alias;
		$comment_content=$comment->text;

		//Create the post request
		$path = "blog=$blog&user_ip=$ip&user_agent=$sk_user_agent&comment_author=$comment_author&comment_content=$comment_content";
		//do we have an email?
		if($comment->email!="") {
			$path.="&comment_author_email=".$comment->email;
		}
		
		//ask and wait for the anser
		$response = sk_http_post($path, $key.'.rest.akismet.com', '/1.1/submit-ham');
	}
	
	return $answer1;
}

/**
* Return comment's status
*
* @param int id Comment's id
* @return bool
* @access public
*/
function sk_status($id) {
	global $wpdb;
	global $sk_user_agent;
	$status=0;
	$table_name = $wpdb->prefix . "schreikasten";
	$query="SELECT * FROM " . $table_name ." WHERE id=".$id;
	$comment = $wpdb->get_row($query);
	if($comment) {
		$status=$comment->status;
	}
	return $status;
}

/**
* Ask Akismet if the comment is spam
*
* @param int id Comment's id
* @return bool
* @access public
*/
function sk_is_spam($id) {
	global $wpdb;
	global $sk_user_agent;
	
	//Get the data for akismet
	$key=get_option('sk_api_key');
	$blog = urlencode( get_option('home') );
	$table_name = $wpdb->prefix . "schreikasten";
	$query="SELECT * FROM " . $table_name ." WHERE id=".$id;
	$comment = $wpdb->get_row($query);
	$user_agent=$comment->user_agent;
	$ip=$comment->ip;
	$comment_author=$comment->alias;
	$comment_content=$comment->text;
	
	//Create the post request
	$path = "blog=$blog&user_ip=$ip&user_agent=$sk_user_agent&comment_author=$comment_author&comment_content=$comment_content";
	//do we have an email?
	if($comment->email!="") {
		$path.="&comment_author_email=".$comment->email;
	}
	
	//ask and wait for the answer
	$response = sk_http_post($path, $key.'.rest.akismet.com', '/1.1/comment-check');
	$answer=true;

	//If the comment isn't spam, all righty then
	if ( 'false' == $response[1] ) {
		$answer=false;
	} else { //SPAM!!!!! Mark the comment as spam now!!!!!!
		$query="UPDATE " . $table_name ." SET status=".SK_SPAM." WHERE id=".$id;
		$wpdb->query( $query );
	}
	return $answer;
}

/**
* Deletes all comments marked as spam.
*
* @access public
*/
function sk_delete_spam() {
	global $wpdb;
	$table_name = $wpdb->prefix . "schreikasten";
	$sql = "DELETE FROM " . $table_name ." WHERE status=" . SK_SPAM;
	$wpdb->query( $sql );
}

/**
* How many elements in list? Without argument return total number of comments. You can use
* SK_HAM for accpeted, SK_SPAM for spam, and SK_MOOT for comments to be moderated.
*
* @param int status
* @return int
* @access public
*/
function sk_count($status=false) {
	global $wpdb;
	$table_name = $wpdb->prefix . "schreikasten";
	$sql="SELECT count(*) FROM " . $table_name; 
	if($status)
		$sql.=" WHERE status=$status";
	$total = $wpdb->get_var($sql);
	return $total;
}

/**
* User who sended the last comment marked as SK_HAM.
*
* @return string
* @access public
*/
function sk_last() {
	global $wpdb;
	$table_name = $wpdb->prefix . "schreikasten";
	$sql="SELECT alias FROM $table_name WHERE status = ".SK_HAM." ORDER BY date DESC LIMIT 1"; 
	$alias = $wpdb->get_var($sql);
	return $alias;
}

/**
* Edit comment
*
* @param int id Comment's id
* @param string alias Who send the comment
* @param string text Comment's content
* @access public
*/
function sk_edit_comment($id, $alias, $email, $text) {
	global $wpdb;
	$table_name = $wpdb->prefix . "schreikasten";
	$query="UPDATE " . $table_name ." SET alias='".$alias."', email= '".$email."', text='".$text."' WHERE id=".$id;
	$wpdb->query($query);
}

/**
* Lock a PC forever
*
* @param int id block's id
* @access public
*/
function sk_lock_forever($id) {
	global $wpdb;
	$table_name = $wpdb->prefix . "schreikasten_blacklist";
	$query="UPDATE " . $table_name ." SET forever=1 WHERE id=".$id;
	return $wpdb->query($query);
}

/**
* Unlock a PC
*
* @param int id block's id
* @access public
*/
function sk_unlock($id) {	
	global $wpdb;
	$table_name = $wpdb->prefix . "schreikasten_blacklist";
	$query="DELETE FROM " . $table_name ." WHERE id=".$id;
	return $wpdb->query($query);
}

/**
* Format a comment to be displayed in the shoutbox
*
* @access public
* @param objetc comment The comment
* @param sending Is this the comment we are sending?
*/
function sk_canReply() {
	global $current_user;
	
	$answer = false;
	$sk_canmannage=false;
	if(current_user_can( SK_CAP )) {
		$sk_canmannage=true;
	}
	
	$options = get_option('sk_options');
	
	if($options['layout'] == SK_LAYOUT_BOARD || $options['layout'] == SK_LAYOUT_CHAT || ($options['layout'] == SK_LAYOUT_QA && $sk_canmannage)) $answer = true;
	
	return $answer;
}

/**
* Format the replies to be displayed below a comment (Q&A)
*
* @access public
* @param objetc comment The comment
* @param sending Is this the comment we are sending?
*/
function sk_format_replies($id,$sk_canmannage=false,$rand=false) {
	global $wpdb;
	$answer = false;
	$options = get_option('sk_options');
	$table_name = $wpdb->prefix . "schreikasten";
	$sql="SELECT id, text FROM $table_name WHERE reply = $id AND status=".SK_HAM." ORDER BY date ASC";
	$comments = $wpdb->get_results($sql);
	
	foreach($comments as $comment) {
		$mannage = "";
		//If the user can mannage, set the mannage system inside the comment
		$edit = "";
		if($sk_canmannage) {
			$id = $comment->id;
			$edit="<a href='javascript:sk_replyDelete$rand($id,\"".__('Are you sure you want to delete this comment?', 'sk')."\");'>" . __('[delete]' , 'sk') . "</a> | <a href='".htmlspecialchars(admin_url("edit-comments.php?page=skmanage&paged=1&mode=edit&id=$id"))."'>" . __('[edit]' , 'sk') . "</a>";
			$edit = "<div align='right'>$edit</div>";
		}
		$answer.="<div class='sk-reply' id='sk-$rand-$id'>{$comment->text}$edit</div>";
	}
	
	return $answer;
}

/**
* Format a comment to be displayed in the shoutbox
*
* @access public
* @param objetc comment The comment
* @param sending Is this the comment we are sending?
*/
function sk_format_comment($comment,$sending=false,$rand=false,$hide=false) {
	global $current_user;
	
	if(!$rand) $rand = mt_rand(111111,999999);
	
	$answer = "";
	$for="";
	$av_size=32;
	
	get_currentuserinfo();
	
	//check if this user can administrate
	$sk_canmannage=false;
	if(current_user_can( SK_CAP )) {
		$sk_canmannage=true;
	}
	
	$options = get_option('sk_options');
	
	//If we can reply a message, create the reply system 
	if(sk_canReply()) {
		$for=" ";
		if(!$sending) {
			if($comment->email!="") {
				$for.="<a href='#sk_top' onclick='javascript:for_set$rand(".$comment->id.", \"".$comment->alias."\");'> ".__("[reply]","sk")."</a>";
			} else {
				$for.="<span class='sk-for'>".__("[no sender]", "sk")."</span>";
			}
		}
	}
	
	//The classes for coloring the comments (used only if we are sending this comment)
	$class="sk-userdata-user";
	$divClass='sk-comment';
	if($comment && $comment->status==SK_SPAM) {
		$divClass.='-spam';
		$class.="-spam";
	}
	
	if($comment && $comment->status==SK_MOOT) {
		$divClass.='-moot';
		$class.="-moot";
	}
		
	$mannage = "";
	//If the user can mannage, set the mannage system inside the comment
	if($sk_canmannage) {
		$class="sk-userdata-admin";
		if($comment->status==SK_SPAM) {
			$class.="-spam";
		}
		if($comment->status==SK_MOOT) {
			$class.="-moot";
		}
		$av_size=41;
		$id=$comment->id;
		$edit="<a href='".htmlspecialchars(admin_url("edit-comments.php?page=skmanage&paged=1&mode=edit&id=$id"))."'>" . __('[edit]' , 'sk') . "</a>";
		$mannage.="<br/><a style='cursor: pointer;' onclick='sk_confirm_action(\"delete\", $rand, $id, sk_semaphore$rand);'>" . __('Delete' , 'sk') . "</a> | ";
		if($comment->user_id!=0) {
			$mannage.="<a style='cursor: pointer;' onclick='sk_confirm_action(\"set_black\", $rand, $id, sk_semaphore$rand);'>". __('Reject', 'sk') . "</a> | ";
		}
		$mannage.="<a style='cursor: pointer;' onclick='sk_confirm_action(\"set_spam\", $rand, $id, sk_semaphore$rand);'>". __('Spam', 'sk') . "</a><br/>";
		if($sending) {
			$mannage="<br/>";
			$edit = "[ ".__('Sending', 'sk')." ]";
		}
	}
	
	$avatar="";
	$item="";
	
	//Get the avatar using the specific size
	if($options['avatar']) {
		$avatar=sk_avatar($comment->id,$av_size);
	}
	
	$comment_text=sk_format_text($comment->text);
	$comment_text=str_replace("<p>", "", $comment_text);
	$comment_text=str_replace("</p>", "", $comment_text);
	
	//If we have to hide this comment it's because this is the
	//section that would display the message while we ask if it's spam.
	//Then we have to set the comment part as the section that would have
	//the message and the alias.
	$th_alias_id = $th_text_id = "";
	if($hide) {
		$th_alias_id  = " id='th_sk_alias$rand'";
		$th_text_id  = " id='th_sk_text$rand'"; 
	}
	
	//Create the comment text
	$item.="<div style='min-height: ".$av_size."px;' class='$class'>".
			$avatar.
			"<strong$th_alias_id>".$comment->alias."</strong>
			<br/><div class='sk-little'>(".$comment->date.")$mannage</div>
		</div>
		<div class='sk-widgettext'>
			<div class='skwidget-comment'$th_text_id>".$comment_text."</div>
			<div class='skwidget-edit'>$for $edit</div>
			<div style='clear: both;'></div>
		</div>";
		
	if($options['layout']==SK_LAYOUT_QA) {
		if($replies = sk_format_replies($comment->id,$sk_canmannage,$rand)) $item.=$replies;
	}
	
	//if we show avatars, use the images
	$id = $comment->id;
	
	$user_id = $comment->user_id;
	//Set the comment owner type
	$usertype = 'sk-user-user';
	if($user_id>0) {
		$comment_user = get_userdata($user_id);
		$capabilities = $comment_user->wp_capabilities; //->administrator;
		if(isset($capabilities['author']) && $capabilities['author']==1) $usertype = 'sk-user-author';
		if(isset($capabilities['editor']) && $capabilities['editor']==1) $usertype = 'sk-user-editor';
		if(isset($capabilities['administrator']) && $capabilities['administrator']==1) $usertype = 'sk-user-admin';
	}
	
	$sk_id = "id='sk-$rand-$id'";
	$class = "class='$divClass $usertype'";
	if($hide) {
		$class = "class='throbber-img-off' style='visibility: hidden;'";
		$sk_id = "id='throbber-img$rand'";
	}
	
	if($options['avatar']) {
		$answer.="\n<div $class $sk_id><a name='sk-comment-id$id'></a>
		$item
		</div>";
	} else { //else, it's a list item
		$answer.="\n<li $class $sk_id><a name='sk-comment-id$id'></a>
				$item
				</li>";
	}
	return $answer;
}

/**
* Returns the name of the owner of the comment
*
* @param int id The comment id 
* @return string The name of the owner, false on error
* @access public
*/

function sk_name_by_id($id)
{
	global $wpdb;
	$answer = false;
			
	//Get the comments to show
	$table_name = $wpdb->prefix . "schreikasten";
	$sql="SELECT alias FROM $table_name WHERE id=$id";
	$comments = $wpdb->get_results($sql);
	if(is_array($comments)) {
		foreach($comments as $comment) {
			$answer = $comment->alias;
		}
	}
		
	return $answer;
}

/**
* Returns the page where this comment is
*
* @param int id The comment id 
* @return string The page number, false on error
* @access public
*/

function sk_page_by_id($id)
{
	global $wpdb;
	$answer = 1;
	
	$options = get_option('sk_options');
	$size=$options['items'];
	
	//Get the comments to show
	$table_name = $wpdb->prefix . "schreikasten";
	$sql="SELECT count(*) FROM $table_name WHERE id>=$id AND status=".SK_HAM;
	if($comments = $wpdb->get_var($sql)) {
		$answer = ceil($comments/$size);
	}
		
	return $answer;
}

/**
* Returns HTML for contents to show
*
* @param int page Which group are we showing?
* @param int id If this comment is marked as SPAM show it 
* @return string
* @access public
*/

function sk_show_comments($size, $page=1,$id=false,$rand=false)
{
	global $wpdb;
	
	if(!$rand) $rand = mt_rand(111111,999999);
	
	$options = get_option('sk_options');
	$first=(($page-1)*$size);
	$table_name = $wpdb->prefix . "schreikasten";
	
	//Get the comments to show
	if($options['layout']!=SK_LAYOUT_QA) {
		$sql="SELECT id, alias, text, date, user_id, email, status FROM $table_name WHERE status=".SK_HAM." ORDER BY id desc LIMIT $first, $size";
	} else {
		$sql="SELECT id, alias, text, date, user_id, email, status FROM $table_name WHERE status=".SK_HAM." AND reply = 0 ORDER BY id desc LIMIT $first, $size";
	}
	$comments = $wpdb->get_results($sql);
	
	if($options['layout']==SK_LAYOUT_CHAT) {
		$comments = array_reverse($comments);
	}

	//if we don't show avatars, then it's a list	
	if(!$options['avatar']) {
		$answer="<ul>";
	}
	
	$av_size=32;
	
	//The throbber div
	
	//Create the throbber div
	$aux = "";
	$aux->alias = "";
	$aux->text = "";
	$aux->date = "&nbsp;".__('Sending', 'sk')."...&nbsp;";
	$aux->status = SK_HAM;
	$aux->id = 0;
	$aux->user_id = 0;
	
	$answer.= sk_format_comment($aux,true,$rand,true);

	//If there is and id, it means we have to show it, so, get the comment
	if($id) {
		$sql="SELECT id, alias, text, date, user_id, email, status FROM $table_name WHERE id=$id";
		$idComments = $wpdb->get_results($sql);
		foreach($idComments as $idComment) {
			//Set the data the page format
			$idComment->date = mysql2date($options['date_format'], $idComment->date);
			//If it's spam add it at the begginning.
			if($idComment->status==SK_SPAM || $idComment->status==SK_MOOT) {
				array_unshift($comments, $idComment);
			}
		}
	}

	//The comments list
	foreach($comments as $comment) {
		//Set the data the page format
		$comment->date = mysql2date($options['date_format'], $comment->date);
		$answer.=sk_format_comment($comment,false,$rand);
	}
	
	//If we don't show avatars, it's a list 
	if(!$options['avatar']) $answer.="</ul>";
	$answer.="\n";
	
	$sk_count = sk_count(SK_HAM);
	$sk_last = "";
	if($sk_count>0) $sk_last = sk_last();
	$sk_blocked = 0;
	if(sk_is_blacklisted()) $sk_blocked = 1;
	
	//Message to display
	$message = 'none';
	$sk_allowed = sk_allowed_messages();
	if($sk_allowed != 0)
		$sk_enabled = 1;
	else
		$sk_enabled = 0;
	
	if($sk_allowed == 0) {
		if($sk_blocked == 1) {
			$message = __('This PC was blacklisted, and you have reached your comments quota limit.', 'sk');
		} else {
			$message = __('You have reached your daily comments quota limit.', 'sk');
		}
	}
	
	$submit = __('Submit', 'sk');
	
	return "<input type='hidden' id='sk_allowed$rand' name='sk_allowed$rand' value='$sk_allowed' /><input type='hidden' id='sk_enabled$rand' name='sk_enabled$rand' value='".$sk_enabled."' /><input type='hidden' id='sk_submit_text$rand' name='sk_submit_text$rand' value='".$submit."' /><input type='hidden' id='sk_message$rand' name='sk_message$rand' value='".$message."' /><input type='hidden' id='sk_blocked$rand' name='sk_blocked$rand' value='".$sk_blocked."' /><input type='hidden' id='sk_int_count$rand' name='sk_int_count$rand' value='$sk_count' /><input type='hidden' id='sk_int_last$rand' name='sk_int_last$rand' value='$sk_last' />".$answer;
}

/**
* Enable menus.
* This function should be called by an action.
*
* @access public
*/
function sk_menus()
{
	global $submenu;
	add_submenu_page('edit-comments.php', __('Schreikasten', 'sk'), __('Schreikasten', 'sk'), SK_CAP, 'skmanage', 'sk_manage' );
	add_submenu_page('options-general.php', __('Schreikasten', 'sk'), __('Schreikasten', 'sk'), 'manage_options', 'skconfig', 'sk_config');
}

/**
* Configuration page
* This function should be called by an action.
*
* @access public
*/
function sk_config() {
	$messages=array();
	if(isset($_POST['sk-submit']) && $_POST['sk-submit']) {
		$api_key=$_POST["sk_api_key"];
		update_option('sk_api_key', $api_key);
		$mode='done';
	}

	$sk_api_key=get_option('sk_api_key');
	
	
	//In case we don't have activated it yet
	$db_version=get_option('sk_db_version');
	if(!$db_version || $db_version<SK_DB_VERSION) sk_activate();
	
	// Get our options and see if we're handling a form submission.
	$options = get_option('sk_options');
	
	//Max characters
	if(!isset($options['maxchars'])) $options['maxchars']=255;

	if (isset($_POST['sk-submit']) && $_POST['sk-submit'] ) {
		// Remember to sanitize and format use input appropriately.
		$options['title'] = strip_tags(stripslashes($_POST['sk_title']));
		
		$options['maxchars'] = $_POST['sk_maxchars'];
		if(!is_numeric($options['maxchars'])) $options['maxchars'] = 225;
		
		$options['items'] = $_POST['sk_items'];
		if(!is_numeric($options['items'])) $options['items'] = 5;
		
		$options['avatar'] = false;
		if($_POST['sk_avatar'])
			$options['avatar'] = true;
			
		$options['rss'] = false;
		if($_POST['sk_rss'])
			$options['rss'] = true;
			
		$options['date_format'] = 'l, M j. Y g:i A';
		if($_POST['date_format'])
			$options['date_format'] = $_POST['date_format'];
		
		$options['alert_about_emails'] = false;
		if($_POST['sk_alert_about_emails'])
			$options['alert_about_emails'] = true;
				
		$options['refresh'] = $_POST['sk_refresh'];
		$options['bl_days'] = $_POST['sk_bl_days'];
		$options['bl_maxpending'] = $_POST['sk_bl_maxpending'];
		
		$options['maxperday'] = $_POST['sk_maxperday'];
		
		$options['registered'] = $_POST['sk_registered'];
		$options['announce'] = $_POST['sk_announce'];
		$options['requiremail'] = $_POST['sk_requiremail'];
		$options['layout'] = $_POST['sk_layout'];
		
		$options['delete_type'] = $_POST['sk_delete_type'];
		$options['delete_num'] = abs($_POST['sk_delete_num']);
		
		$options['moderation'] = $_POST['sk_moderation'];
		
		update_option('sk_options', $options);
		
		// Put an 'options updated' message on the screen
		array_push($messages,__( 'Options saved', 'sk' ));
	}
	// Be sure you format your options to be valid HTML attributes.
	$title = htmlspecialchars($options['title'], ENT_QUOTES);
		// Here is our little form segment. Notice that we don't need a
	// complete form. This will be embedded into the existing form.
	$items=$options['items'];
	$maxchars=$options['maxchars'];

	$status=$options['avatar'];
	$registered=$options['registered'];
	$layout=$options['layout'];
	$alert_about_emails=$options['alert_about_emails'];
	$rss=$options['rss'];
	
	$refresh="selectedrefresh".$options['refresh'];
	$$refresh=' selected="selected"';
	
	$days="selecteddays".$options['bl_days'];
	$$days=' selected="selected"';
	
	$maxpending="selectedmaxpending".$options['bl_maxpending'];
	$$maxpending=' selected="selected"';
	
	$maxperday="selectedmaxperday".$options['maxperday'];
	$$maxperday=' selected="selected"';
	
	$registered="registered".$options['registered'];
	$$registered=' selected="selected"';
	
	$require="require".$options['requiremail'];
	$$require=' selected="selected"';
	
	$moderation="moderation".$options['moderation'];
	$$moderation=' selected="selected"';
	
	$announce="announce".$options['announce'];
	$$announce=' selected="selected"';
	
	$layout="layout".$options['layout'];
	$$layout=' selected="selected"';
	
	$deletetype="deletetype".$options['delete_type'];
	$$deletetype=' selected="selected"';
	
	//Do we have messages to show?
	if(count($messages)>0) {
		echo "<div class='updated'>";
		foreach($messages as $message) echo "<p><strong>$message</strong></p>";
		echo "</div>";
	}
	
	// Now display the options 
	include('templates/sk_config.php');

}

/**
* Manage page
*
* @access private
*/
function sk_manage() {
	global $wpdb;
	$select=SK_NOT_FILTERED;
	if(isset($_GET['filter'])) {
		if($_GET['filter']=='spam') $select=SK_SPAM;
		if($_GET['filter']=='ham') $select=SK_HAM;
		if($_GET['filter']=='moot') $select=SK_MOOT;
		if($_GET['filter']=='black') $select=SK_BLACK;
		if($_GET['filter']=='blocked') $select=SK_BLOCKED;
	}
	$table_name = $wpdb->prefix . "schreikasten";
	$table_list = $wpdb->prefix . "schreikasten_blacklist";
	$messages=array();
	
	$mode = $mode_x = false;

	if(isset($_POST['mode_x'])) $mode_x=$_POST['mode_x'];
	if(!$mode_x)
		if(isset($_GET['mode_x'])) $mode_x=$_GET['mode_x'];
	if(isset($_GET['mode'])) $mode=$_GET['mode'];
	
	//if pressed deletespam, delete all spam
	if(isset($_POST['deletespam']) && $_POST['deletespam']) {
		$mode_x='deletespam_x';
	}
	
	// Assume we don't have to do any action, but ask if we have
	$doaction=false;
	if(isset($_POST['doaction']) && $_POST['doaction']!="") $doaction=$_POST['action'];
	if(isset($_POST['doaction2']) && $_POST['doaction2']!="") $doaction=$_POST['action2'];
	
	//In case we have to do something previous
	if($doaction)
	{
		if(is_array($_POST['checked_comments'])) {
			switch($doaction)
			{
				case 'approve': //approve the list of checked comments
					foreach($_POST['checked_comments'] as $checked_id) {
						sk_mark_as_ham($checked_id);
					}
					break;
				case 'markspam': //mark as spam the list of checked comments
					foreach($_POST['checked_comments'] as $checked_id) {
						sk_mark_as_spam($checked_id);
					}
					break;
				case 'markblack': //mark as black the list of checked comments
					foreach($_POST['checked_comments'] as $checked_id) {
						sk_mark_as_black($checked_id);
					}
					break;
				case 'delete': //delete the list of checked comments
					foreach($_POST['checked_comments'] as $checked_id) {
						sk_delete_comment($checked_id);
					}
					break;
			}
		}
		
		if(is_array($_POST['checked_pcs'])) {
			switch($doaction)
			{
				case 'forever': //mark to be blocked forever the list of checked comments	
					foreach($_POST['checked_pcs'] as $checked_id) {
						sk_lock_forever($checked_id);
					}
					break;
				case 'unlock': //mark to be unblocked the list of checked comments
					foreach($_POST['checked_pcs'] as $checked_id) {
						sk_unlock($checked_id);
					}
					break;
			}
		}
	}
	
	$sk_api_key=get_option('sk_api_key');
	$sk_manage_page='skmanage';
	switch($select) {
	case SK_MOOT:
		$sk_manage_page='skmanagemoot';
		break;
	case SK_HAM:
		$sk_manage_page='skmanageham';
		break;
	case SK_SPAM:
		$sk_manage_page='skmanagespam';
		break;
	case SK_BLACK:
		$sk_manage_page='skmanageblack';
		break;
	case SK_BLOCKED:
		$sk_manage_page='skmanageblocked';
		break;
	}

	//if we are going to execute a command previous to show the form
	switch($mode_x) {
		case 'edit_x': //edit the comment
		case 'tedit_x': //edit a tracked comment
			$mode='done';
			if($mode_x=='tedit_x') {
					$mode="tracking";
			}
			if(isset($_POST['submit']) && $_POST['submit']) {
				//get the data from the form
				$id=$_POST['sk_id'];
				$alias=$_POST['sk_alias'];
				$email=$_POST['sk_email'];
				$comment=$_POST['sk_comment'];
				
				$comment = str_replace(array("\"", "\'"), array("&quot;", "&#39;"), $comment);
				
				//edit the comment
				sk_edit_comment($id, $alias, $email, $comment);
				
				$newstatus=$_POST['comment_status'];
				$actstatus=sk_status($id);
				
				//If we change the status
				if($newstatus!=$actstatus) {
					switch($newstatus) {
						case SK_HAM:
							sk_mark_as_ham($id);
							break;
						case SK_SPAM:
							sk_mark_as_spam($id);
							break;
						case SK_BLACK:
							sk_mark_as_black($id);
							break;
					}
					//Message to indicate we change the status
					array_push($messages, __("Status changed",'sk'));
				}
				//Message to indicate we edited a comment
				array_push($messages, __( 'Comment modified', 'sk' ));
			}
			break;
		case 'set_ham_x': //set as ham
			$id=$_GET['id'];
			if(sk_mark_as_ham($id)) array_push($messages, __("Status changed",'sk'));
			break;
		case 'set_black_x': //set as blacklisted
			$id=$_GET['id'];
			if(sk_mark_as_black($id)) array_push($messages, __("Status changed",'sk'));
			break;
		case 'set_spam_x': //set as spam
			$id=$_GET['id'];
			if(sk_mark_as_spam($id)) array_push($messages, __("Status changed",'sk'));
			break;
		case 'delete_x': //delete the comment
			$id=$_GET['id'];
			if(sk_delete_comment($id)) array_push($messages, __("Comment deleted",'sk'));
			break;
		case 'forever_lock_x': //block forever
			$id=$_GET['id'];
			if(sk_lock_forever($id)) array_push($messages, __("PC locked forever",'sk'));
			break;
		case 'unlock_x': //unlock the PC
			$id=$_GET['id'];
			if(sk_unlock($id)) array_push($messages, __("PC unlocked",'sk'));
			break;
		case 'lock_x': //lock the PC
			$id=$_GET['id'];
			sk_mark_as_black($id);
			array_push($messages, __("PC locked",'sk'));
			break;
		case 'deletespam_x': //delete all the spam
			sk_delete_spam();
			break;
	}
	
	//show the form
	switch($mode) {
		case 'tedit': //edit a comment or a tracking comment
		case 'edit':
			$id=$_GET['id'];
			$table_name = $wpdb->prefix . "schreikasten";
			$data = $wpdb->get_row("select alias, text, status, date, email from $table_name where id=$id");
			if($data) { //get data
				$alias=$data->alias;
				$email=$data->email;
				$comment=$data->text;
				$status=$data->status;
				$date=$data->date;
				//show form
				include('templates/sk_comment.php');
			} else {
				$mode='done';
			}
			break;
		case 'tracking':
			$tid=$_GET['tid'];
			$table_name = $wpdb->prefix . "schreikasten";
			$data = $wpdb->get_row("select * from $table_name where id=$tid");
			//get the tracking data
			if($data->id) {
				//show form
				include('templates/sk_tracking.php');
			} else {
				$mode='done';
			}
			break;
		case 'delete':
		case 'set_ham':
		case 'set_black':
		case 'set_spam': //show the form to manage the comments
			$id=$_GET['id'];
			$table_name = $wpdb->prefix . "schreikasten";
			$data = $wpdb->get_row("select alias, text, status, date, email from $table_name where id=$id");
			if($data) {
				$alias=$data->alias;
				$email=$data->email;
				$comment=$data->text;
				$status=$data->status;
				$date=$data->date;
				include('templates/sk_confirm.php');
			} else {
				$mode='done';
			}
			break;
		default:
			if(count($messages)>0) 
			{
				echo "<div class='updated'>";
				foreach($messages as $message) echo "<p><strong>$message</strong></p>";
				echo "</div>";
			}
			
			if($select==SK_BLOCKED) {
				include('templates/sk_manageblack.php');
			} else {
				include('templates/sk_manage.php');
			}
			break;
	}
	
}


/**
* Return the list of messages the logged user has created 
*
* @access public
* @param string content The content to change.
* @return string The content with the changes the plugin have to do.
*/
function sk_userMessages() {
	global $wpdb;
	global $current_user;
	get_currentuserinfo();
	$options = get_option('sk_options');
	if($current_user->ID>0) {
		$table_name = $wpdb->prefix . "schreikasten";
		$sql="SELECT id, alias, text, date, user_id, email, status FROM $table_name WHERE status=".SK_HAM." AND user_id = {$current_user->ID} ORDER BY id ";
		if($options['layout'] == SK_LAYOUT_CHAT) {
			$sql.= "DESC";
			$count = 1;
			$sum = 1;
		} else {
			$sql.= "ASC";
			$sum = -1;
		}
		$comments = $wpdb->get_results($sql);
		$comments = array_reverse($comments);
		if($sum == -1) $count = count($comments);
		$answer = "<ul class='sku-list'>";
		
		$back = true;
		foreach($comments as $comment) {
			$class = "sku-item";
			if($back) $class.=" sku-back";
			$answer.= "<li class='$class'><div class='sku-count'>$count</div><div class='sku-content'><span class='sku-date'>({$comment->date})</span> <span class='sku-text'>{$comment->text}</span></div></li>";
			$count = $count+$sum;
			$back = !$back;
		}
		$answer.= '</ul>';
	} else {
		$answer = sprintf( __('To show your messages list you have to <a href="%s">log in</a>.', 'sk'), wp_login_url(get_permalink()) );
	}	
	return $answer;
}


/**
* Function to show the shoutbox. Can be used in the template files.
*
* @access private
*/
function sk_codeShoutbox($size=false) {
	global $wpdb, $current_user;
	
	$options = get_option('sk_options');
	
	//Size
	if(!$size) {
		$size = $options['items'];
	}
	
	if(isset($options['replies'])) sk_activate();
	
	if(!is_numeric($size) || $size<1) $size = 5;
	$sk_size = $size;
	
	//Our random number
	$rand = mt_rand(111111,999999);
	
	$answer = "";
	//Create the nonce
	$nonce = wp_create_nonce('sk'.$rand);
	//Get current user
	get_currentuserinfo();
	
	/************** This is huge *******************/
	$sk_page=1;
	$sk_for = false;
	$sk_id = $sk_for = false;
	if(isset($_GET['sk_id'])) $sk_id = $_GET['sk_id'];
	if($sk_id) $sk_page=sk_page_by_id($sk_id);
	if(isset($_GET['sk_for'])) $sk_for = $_GET['sk_for'];
	if($sk_for) $sk_page=sk_page_by_id($sk_for);
	$first_comments = sk_show_comments($size, $sk_page, false, $rand );
	$first_page_selector = sk_page_selector($size, $sk_page, $rand);
	
	$options = get_option('sk_options');
	$avatar = $options['avatar']; 
	$req = sk_require_name_and_email();
	
	//Get maxchars
	$maxchars = 255;
	if(isset($options['maxchars'])) $maxchars = $options['maxchars'];
	
	//Update blacklist dates
	sk_blacklist_update();
	
	//Set name and email on cookie name
	$alias="";
	$email="";
	
	if($_COOKIE['comment_author_' . COOKIEHASH]) {
		$alias=$_COOKIE['comment_author_' . COOKIEHASH];
		$email=$_COOKIE['comment_author_email_' . COOKIEHASH];
	}
	
	$anonymous_avatar=sk_plugin_url('/img/anonymous.jpg');
	$uri_sk=sk_plugin_url('/ajax/content.php');
	$uri_skadd=sk_plugin_url('/ajax/add_comment.php'); 
	$uri_img=sk_plugin_url('/img/loading.gif');
	$uri_out=wp_logout_url(get_permalink());
	if(is_home()) $uri_out=wp_logout_url(get_option('home'));
	
	$time=$options['refresh']*1000;
	
	$show_timer = "";
	if($options['refresh']>0) {		
		$show_timer = "\nsk_timer$rand();";
	}
	
	$ask_email = "";
	if ($req) { 
		/*desc: This is a javascript alert message, use \\n for new line and \\' for apostrophe. */
		$ask_email = "if(!check_email(email)) {
			alert('". __("E-mail is required", "sk") . "');
			return false;
		}";
	}
	
	$email_in_text = "";
	if($options['alert_about_emails']) {
		/*desc: This is a javascript alert message, use \\n for new line and \\' for apostrophe. */
		$email_in_text = "if(email_intext ( text ) ) {
			check=confirm('". __("To prevent identification theft, we recomend\\nthat you do not include e-mail adresses.\\nDo you want to continue?", "sk") ."');
			if(!check) {
				return false;
			}
		}";
	}
	
	$message = false;
	
	//Do we need moderation?
	$require_moderation = true;
	if($options['moderation'] == SK_MODERATION_CONFIG) {
		if(!get_option('comment_moderation')) {
			$require_moderation = false;
		}
	}
	if($options['moderation'] == SK_MODERATION_NO) {
		$require_moderation = false;
	}
	
	if(1 == $require_moderation && !current_user_can( SK_CAP )) {
		/*desc: This is a javascript alert message, use \\n for new line and \\' for apostrophe. */
		$message=__('Your message has been sent. Comments have\nto be approved before posted.', 'sk');
	}
	if(sk_is_blacklisted()) {
		/*desc: This is a javascript alert message, use \\n for new line and \\' for apostrophe. */
		$message=__('Your message has been sent but this PC was blacklisted.\nComments have to be approved before posted.', 'sk');
	}
	if($message) {
		$message = "alert(\"$message\");
		this.disabled=true;";
	}
	
	$form_button="";
	$form_table="<a name='sk_top'></a><table border='0' class='sk-table'>
	<tr><td width='20'></td><td width='100%'></td></tr>";
	$sk_count = sk_count(SK_HAM);
	
	$hidden = "<input type='hidden' id='sk_timer$rand' value=''/><input type='hidden' id='sk_count$rand' name='sk_count$rand' value='$sk_count' /><input type='hidden' id='sk_page$rand' name='sk_page$rand' value='$sk_page' /><input type='hidden' id='sk_size$rand' name='sk_size$rand' value='$sk_size' />";
	
	if(sk_only_registered_users() && $current_user->ID==0) {
		$url = (!empty($_SERVER['HTTPS'])) ? "https://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'] : "http://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
		$form_table.= "<tr>
			<td colspan='2' id='skwarning'>
				$hidden".sprintf( __('You must be <a href="%s">signed in</a> to post a comment', 'sk'), wp_login_url( $url ) )."
			</td>
		</tr>
	</table>";
	} else {
	
		if(sk_is_blacklisted()) {
			if(sk_can_not_accept_more_messages()) {
				$form_table.= "<tr>
					<td colspan='2'>". __("This PC was blacklisted. At this time comments cannot be posted.", "sk")."</td>
				<tr>";
			}
		}
	
		if($current_user->ID==0) {
			$form_table.="<tr class='sk-rowname'>
				<td nowrap='nowrap'>".__('Name', 'sk').":</td>
				<td>
					<input class='sk-text' type='text' id='sk_alias$rand' name='sk_alias$rand' value='$alias'/>
				</td>
			</tr>
			<tr class='sk-rowemail'>
				<td nowrap='nowrap'>".__('Email', 'sk').":</td>
				<td>
					<input class='sk-text' type='text' id='sk_email$rand' name='sk_email$rand' value='$email'/>
				</td>
			</tr>";
		}
		
		$form_table.="<tr class='sk-for-nai' id='sk_for_tr$rand'>
			<td nowrap='nowrap'>".__('For', 'sk').":</td>
			<td><span id='sk_for_name$rand'></span>&nbsp;<img src='".sk_plugin_url('/img/clear.png')."' align='top' border='0' alt='' onclick='for_delete$rand();' /><input id='sk_for_id$rand' name='sk_for_id$rand' type='hidden' size='5' value='0'/></td>
		</tr>
		<tr>
			<td colspan='2' align='right'><textarea rows='0' cols='0' class='sk-area' name='sk_text$rand' id='sk_text$rand' onkeypress='
				var key;
				if(window.event)
					key = window.event.keyCode; //IE
				else
					key = event.keyCode;
				if(this.value.length>$maxchars-1 &amp;&amp; !(key==8 || key==37 || key==38 || key==39 || key==40) )
				return false;'></textarea></td>
		</tr>
		</table>";
		
		$sk_allowed = sk_allowed_messages();
	
		$submit = __('Submit', 'sk');
		$for = __('For', 'sk');
		
		$button = "<div class='sk-box-text'>";
		if($current_user->ID==0) {
			$button.=__('Mail will not be published', 'sk')."<br/>";
			if ($req) {
				$button.=__("(but it's required)", "sk");
			} else {
				if($avatar) {
					$button.=__("(but it's used for avatar)", "sk"); 
				}
			} 
		} else {
			$button.=sprintf(__('Logged in as %s', 'sk'), $current_user->display_name);
			$button.="<br/><a href='$uri_out' title='".__('Log out', 'sk')."'>".__('Log out', 'sk')."</a>
				<input id='sk_alias$rand' name='sk_alias$rand' type='hidden' value='{$current_user->display_name}' />
				<input id='sk_email$rand' name='sk_email$rand' type='hidden' value='{$current_user->user_email}' />";
		}
		$button.="</div>";
		
		$form_button = "<table class='sk-table'>		
			<tr>
				<td colspan='2' class='sk-little'>
					<div class='sk-box-message'></div>
					<div class='sk-box-button'>
						$hidden<input type='button' class='sk-button sk-button-size' value='$submit' id='sk_button$rand' onclick='
						sk_enabled = document.getElementById(\"sk_enabled%rand%\").value;
						sk_allowed = document.getElementById(\"sk_allowed%rand%\").value;
						if(sk_enabled == 1 ) {
							this.disabled = true;
							sk_pressButton$rand();%message%
						} else {
							alert(document.getElementById(\"sk_message%rand%\").value);
						}
						'/>
					</div>
					$button
				</td>
			</tr>
			</table>";
			
	}

	$have_for = "";
	if($sk_for) { 
		$have_for = "for_set$rand($sk_for, '".sk_name_by_id($sk_for)."');";
	}
	
	$lenght = __("The lenght of the message is bigger than the allowed size.", "sk");
	
	$chat = "false";
	$qa = "false";
	$sk_general = "%form_table%%form_button%<div id='sk_content%rand%'>%first_comments%	%first_page_selector%</div>";
	switch($options['layout']) {
		case SK_LAYOUT_CHAT:
			$sk_general = "<div id='sk_content%rand%'>%first_comments%%first_page_selector%</div>%form_table%%form_button%";
			$chat = "true";
			break;
		case SK_LAYOUT_QA:
			$qa="true";
			break;
	}
	/******************* End of the hughe part where we are debuging now *************/
	
	$file = ABSPATH."wp-content/plugins/schreikasten/templates/sk_widget.php";
	if($fop = @fopen($file, 'r')) {
		$answer = null;
		while(!feof($fop))
			$answer .= fread($fop, 1024);
		fclose($fop);
	}
	
	$blacklisted_pc = 'false';
	if(sk_is_blacklisted()) $blacklisted_pc = 'true';
	
	$answer = str_replace('%allowed%', sk_allowed_messages(), $answer);
	$answer = str_replace('%blocked_pc%', $blacklisted_pc, $answer);
	$answer = str_replace('%sk_general%', $sk_general, $answer);
	$answer = str_replace('%nonce%', $nonce, $answer);
	$answer = str_replace('%chat%', $chat, $answer);
	$answer = str_replace('%qa%', $qa, $answer);
	$answer = str_replace('%answer%', __('Answer', 'sk'), $answer);
	$answer = str_replace('%sk_id%', $sk_id, $answer);
	$answer = str_replace('%sk_page%', $sk_page, $answer);
	$answer = str_replace('%sk_for%', $sk_for, $answer);
	$answer = str_replace('%first_comments%', $first_comments, $answer);
	$answer = str_replace('%first_page_selector%', $first_page_selector, $answer);
	$answer = str_replace('%maxchars%', $maxchars, $answer);
	$answer = str_replace('%alias%', $alias, $answer);
	$answer = str_replace('%email%', $email, $answer);
	$answer = str_replace('%uri_sk%', $uri_sk, $answer);
	$answer = str_replace('%uri_skadd%', $uri_skadd, $answer);
	$answer = str_replace('%uri_img%', $uri_img, $answer);
	$answer = str_replace('%time%', $time, $answer);
	$answer = str_replace('%show_timer%', $show_timer, $answer);
	$answer = str_replace('%ask_email%', $ask_email, $answer);
	$answer = str_replace('%email_in_text%', $email_in_text, $answer);
	$answer = str_replace('%lenght%', $lenght, $answer);

	$answer = str_replace('%form_table%', $form_table, $answer);
	$answer = str_replace('%form_button%', $form_button, $answer);
	$answer = str_replace('%message%', $message, $answer);
	$answer = str_replace('%submit%', $submit, $answer);
	$answer = str_replace('%button%', $button, $answer);
	$answer = str_replace('%have_for%', $have_for, $answer);
	$answer = str_replace('%for%', $for, $answer);
	
	$answer = str_replace('%rand%', $rand, $answer);
	
	return $answer;
	
}

/**
* Function to show the shoutbox. Can be used in the template files.
*
* @access private
*/
function sk_shoutbox($size = false) {
	if(!$size) {
		$options = get_option('sk_options');
		$size = $options['items'];
	}
	echo sk_codeShoutbox($size);
}

/**
* Function to show the shoutbox. Can be used in the template files.
*
* @access private
*/
function sk_format_text($comment_text) {
	remove_filter('comment_text', 'commentimage_comment_text');
	$comment_text=apply_filters('comment_text', $comment_text);
	if(function_exists('commentimage_comment_text')) add_filter('comment_text', 'commentimage_comment_text');
	if(function_exists('qtrans_useCurrentLanguageIfNotFoundUseDefaultLanguage')) $comment_text=qtrans_useCurrentLanguageIfNotFoundUseDefaultLanguage($comment_text);
	return $comment_text;
}


/**
* Function to get an RSS feed.
*
* @access public
* @return The feed string
*/
function sk_feed($max=20) {
	global $wpdb;	
	
	$options = get_option('sk_options');
	$title = $options['title'];
	if(strlen($title)==0) $title = "Schreikasten";
	
	$link = get_bloginfo('url');
	if(defined('SK_CHAT')) $link = SK_CHAT;
	
	$website=get_option('blogname');
	$offset = get_option('gmt_offset');
	if($offset==0) {
		$offset = "+0000";
	} else {
		$ceil = ceil($offset);
		$sign = $ceil/abs($ceil);
		$offset = abs($offset)*100 + 1;
		$offset = str_replace('51', '30', $offset);
		$offset = str_replace('01', '00', $offset);
		$offset = $sign * $offset;
		$offset = sprintf('%+05d', $offset);
	}
	
	$table_name = $wpdb->prefix . "schreikasten";
	$sql="SELECT id, alias, text, email, DATE_FORMAT(date, '%a, %d %b %Y %T $offset') as date_rss, user_id, email, status FROM $table_name WHERE status=".SK_HAM." ORDER BY id desc LIMIT $max";
	$comments = $wpdb->get_results($sql);
	$items = array();
	foreach($comments as $comment) {
		$for="";
		//If we can reply a message, create the reply system 
		if(sk_canReply()) {
			if($comment->email!="") {
				$for.="<a style='text-decoration: none;' href='{$link}?sk_for={$comment->id}#sk_top' onclick='javascript:for_set(".$comment->id.", \"".$comment->alias."\");'> ".__("[reply]","sk")."</a>";
			} else {
				$for.=__("[no sender]", "sk");
			}
			$for = "<br/>$for";
		}
		
		$comment_text = sk_format_text("{$comment->text}$for");
		
		$item = array(
				"link" => "{$link}?sk_id={$comment->id}#sk-comment-id{$comment->id}",
	     		"title" => sprintf(__("Comment by %s", 'sk'), $comment->alias ) ,
	     		"description" => $comment_text,
	     		"pubDate" => $comment->date_rss
	     	);
		array_push($items, $item);
	}
	
	$channel=array(
		"title" => sprintf(__('Shouts in %s', 'sk'), $website), 
		"description"=>sprintf(__('List of messages in %s', 'sk'), $title), 
		"link"=>$link,
		"items" => $items
   );
   
   $feed = new SimpleRSSFeedCreator($channel);
   
   return $feed->get_feed();

}

/**
* Filter to manage contents. Check for [sk-shoutbox] tags.
* This function should be called by a filter.
*
* @access public
* @param string content The content to change.
* @return string The content with the changes the plugin have to do.
*/
function sk_content($content) {
	$content = skLegacy_content($content);
	
	//The chat box
	$search = "/(?:<p>)*\s*\[schreikasten:([^,]+),(\d+),(true|false)\]\s*(?:<\/p>)*/i";
	if(preg_match_all($search, $content, $matches)) {
		if(is_array($matches)) {
			foreach($matches[1] as $key =>$title) {
				// Get the data from the tag
				$items=$matches[2][$key];
				$rss_icon=$matches[3][$key];
				if($rss_icon=='true') $rss_icon=true; else $rss_icon=false;
				
				$search = $matches[0][$key];
				// Create the script to show the feed
				$replace=sk_codeShoutbox($items);
				
				$img_url = get_bloginfo('wpurl')."/wp-includes/images/rss.png";
				$feed_url = sk_plugin_url('/feed.php');
				if(defined('SK_RSS')) $feed_url = SK_RSS;
				if($rss_icon) $title = "<a class='rsswidget' href='$feed_url' title='" . __('Subscribe' , 'sk')."'><img src='$img_url' alt='RSS' border='0' /></a> $title";
				
				$content = str_replace ($search, $title.$replace, $content);
			}
		}
	}
	
	//The chat box
	$search = "/(?:<p>)*\s*\[schreikasten:(\d+)\]\s*(?:<\/p>)*/i";
	if(preg_match_all($search, $content, $matches)) {
		if(is_array($matches)) {
			foreach($matches[1] as $key =>$items) {
				// Get the data from the tag
				$search = $matches[0][$key];
				// Create the script to show the feed
				$replace=sk_codeShoutbox($items);
				
				$content = str_replace ($search, $replace, $content);
			}
		}
	}
	
	//The chat box
	$search = "/(?:<p>)*\s*\[schreikasten\]\s*(?:<\/p>)*/i";
	$options = get_option('sk_options');
	$items = $options['items'];
	if(strpos($content, '[schreikasten]')!==false) {
		$replace=sk_codeShoutbox($items);
		$content = preg_replace($search, $replace, $content);
	}
	
	//User messages
	$search = "/(?:<p>)*\s*\[schreikasten-user\]\s*(?:<\/p>)*/i";
	$options = get_option('sk_options');
	$items = $options['items'];
	if(strpos($content, '[schreikasten-user]')!==false) {
		$replace=sk_userMessages();
		$content = preg_replace($search, $replace, $content);
	}
	
	return $content;
}


/**
* Schreikasten widget stuff (New MultiWidget )
*
*/
	
// check version. only 2.8 WP support class multi widget system
global $wp_version;
if((float)$wp_version >= 2.8) { //The new widget system
	
	class SkWidget extends WP_Widget {
	
	/**
		 * constructor
		 */	 
		function SkWidget() {
			$control_ops = array( 'width' => 420, 'height' => 280 );
			parent::WP_Widget('sk', 'Schreikasten', array('description' => __('Add a Schreikasten Shoutbox to the sidebar.', 'sk') ), $control_ops);
			
		}
		
		/**
		 * display widget
		 */	 
		function widget($args, $instance) {
			extract($args, EXTR_SKIP);
			
			$title = $instance['title'];
			$img_url = get_bloginfo('wpurl')."/wp-includes/images/rss.png";
			$feed_url = sk_plugin_url('/feed.php');
			if(defined('SK_RSS')) $feed_url = SK_RSS;
			if($instance['rss']) $title = "<a class='rsswidget' href='$feed_url' title='" . __('Subscribe' , 'sk')."'><img src='$img_url' alt='RSS' border='0' /></a> $title";
			
			echo $before_widget;
			if(strlen($instance['title']) > 0) echo $before_title . $title . $after_title;
			sk_shoutbox($instance['items']);
			echo $after_widget;
		
		}
		
		/**
		 *	update/save function
		 */	 	
		function update($new_instance, $old_instance) {
			
			$instance = $new_instance;
			if($new_instance['rss']) $instance['rss'] = 1; else $instance['rss'] = 0;

			return $instance;
		}
		
		/**
		 *	admin control form
		 */	 	
		function form($instance) {
			$default = 	array('title'=>'Schreikasten', 'items'=>'5','rss'=>'0');
			$instance = wp_parse_args( (array) $instance, $default );
			
			//Show the widget control.
			include('templates/sk_widgetconfig.php');
		}
	}

	/* register widget when loading the WP core */
	add_action('widgets_init', 'sk_register_widgets');

	function sk_register_widgets() {
		register_widget('SkWidget');
	}

}

/**
* XML Entity Mandatory Escape Characters
*
* @access public
* @param string string The string to change
* @return string The chabged string
*/
/*function sk_xmlentities($string) { 
	$string = (string) $string;
	$string = rawurldecode($string);
   return str_replace ( array ( '%', '&', '"', "'", '<', '>' ), array ( '%25', '&amp;' , '&quot;', '&#39;' , '&lt;' , '&gt;' ), $string ); 
}*/


?>
