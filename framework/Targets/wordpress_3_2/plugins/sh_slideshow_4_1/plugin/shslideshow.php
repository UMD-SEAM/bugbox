<?php
/*
Plugin Name: SH Slideshow
Plugin URI: 
Description: Slideshow banner with different effects which is using jQuery Cycle Plugin. Simply for normal users and advanced users.
Version: 3.1.4
Author: Sam Hoe
Author URI: 
*/

/*  Copyright 2011  Sam Hoe  (email : SH Slideshow Sam Hoe samhoamt@gmail.com)

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

// Add Actions and Filters
add_action('admin_menu','shslideshow_menu');
add_action('wp_head','shslideshow_script');
add_action('admin_init','shslideshow_admin_script');
add_action('init','shslideshow_init');
register_activation_hook(__FILE__,'set_shslideshow_options');
register_deactivation_hook(__FILE__,'unset_shslideshow_options');

function shslideshow_admin_script(){
	wp_enqueue_script('jquery-ui-sortable');
}

function shslideshow_init(){
	// Add jQuery Script
	wp_enqueue_script('jquery');
	// Create table in database
	if(isset($_GET['activate']) && $_GET['activate'] == 'true'):
		global $wpdb;
		$result = mysql_list_tables(DB_NAME);
		$current_tables = array();
		while($row = mysql_fetch_array($result)):
			$current_tables[] = $row[0];
		endwhile;
		// create slideshow table
		if(!in_array('sh_slideshow',$current_tables)):
			$result = mysql_query('CREATE TABLE sh_slideshow (id INT NOT NULL AUTO_INCREMENT PRIMARY KEY, name VARCHAR(255), transition INT, timeout INT, pause INT, auto INT, effect VARCHAR(255), random INT, target VARCHAR(255), width INT, height INT, bgcolor VARCHAR(255), nav_transition INT, navigation INT, nav_type INT, nav_pos INT, css INT, next_text VARCHAR(255), prev_text VARCHAR(255), nav_spacing INT, nav_top INT, nav_left INT, nav_link_color VARCHAR(255), nav_link_hover_color VARCHAR(255), nav_link_underline INT)');
		endif;
		// create slides table
		if(!in_array('sh_slides',$current_tables)):
			$result = mysql_query('CREATE TABLE sh_slides (id INT NOT NULL AUTO_INCREMENT PRIMARY KEY, slideshow INT, slide VARCHAR(255), link_url VARCHAR(255), custom_url VARCHAR(255), weight INT)');
		endif;
	endif;
}

// initial settings
function set_shslideshow_options(){
	// Slide Settings
	add_option('sh_ss_transition', 1); // transition speed
	add_option('sh_ss_timeout', 5); // stop time
	add_option('sh_ss_pause', 0); // 1=stop when mouseover
	add_option('sh_ss_atuo', 1); // 1=auto slideshow; 0=manually
	add_option('sh_ss_effect', 'fade'); // Animation effect
	add_option('sh_ss_random', 0); // Random effect
	add_option('sh_ss_target','_self');
	add_option('sh_ss_width', 640);
	add_option('sh_ss_height', 480);
	add_option('sh_ss_bgcolor','transparent');
	add_option('sh_ss_nav_transition', 0); // transition speed when triggered manually
	add_option('sh_ss_navigation', 1); // display navigation
	add_option('sh_ss_navtype', 1); // Navigation type. 1='pager'; 2='pre-next'
	add_option('sh_ss_navpos', 1); // Navigation position. 0=outside the slide; 1=inside the slide
	
	// Slides
	add_option('sh_ss_custom','');
	add_option('sh_ss_slideno',5); // how many slides
	add_option('sh_ss_recent_posts',0); // use recent posts
	
	// Style
	add_option('sh_ss_css',1); // Add shslideshow css
	add_option('sh_ss_next_text','Next');// Navigation Next text
	add_option('sh_ss_prev_text','Prev');// Navigation Prev text
	add_option('sh_ss_nav_spacing',10);// Navigation Spacing
	add_option('sh_ss_nav_top',10);// Navigation Spacing from top
	add_option('sh_ss_nav_left',0);// Navigation Spacing from left
	add_option('sh_ss_nav_link_color','#000');// Navigation Link Color
	add_option('sh_ss_nav_link_hover_color','#000');// Navigation Link hover Color
	add_option('sh_ss_nav_link_underline',0);// Navigation Link underline
	
}

// Unset settings
function unset_shslideshow_options(){
	// Slide Settings
	delete_option('sh_ss_transition'); // transition speed
	delete_option('sh_ss_timeout'); // stop time
	delete_option('sh_ss_pause'); // 1=stop when mouseover
	delete_option('sh_ss_atuo'); // 1=auto slideshow; 0=manually
	delete_option('sh_ss_effect'); // Animation effect
	delete_option('sh_ss_random'); // Random effect
	delete_option('sh_ss_target');
	delete_option('sh_ss_width');
	delete_option('sh_ss_height');
	delete_option('sh_ss_bgcolor');
	delete_option('sh_ss_nav_transition'); // transition speed when triggered manually
	delete_option('sh_ss_navigation'); // display navigation
	delete_option('sh_ss_navtype'); // Navigation type. 1='pager'; 2='pre-next'
	delete_option('sh_ss_navpos'); // Navigation position. 0=outside the slide; 1=inside the slide
	
	// Slides
	delete_option('sh_ss_custom');
	delete_option('sh_ss_slideno'); // how many slides
	delete_option('sh_ss_recent_posts'); // use recent posts
	
	// Style
	delete_option('sh_ss_css'); // Add shslideshow css
	delete_option('sh_ss_next_text');// Navigation Next text
	delete_option('sh_ss_prev_text');// Navigation Prev text
	delete_option('sh_ss_nav_spacing');// Navigation Spacing
	delete_option('sh_ss_nav_top');// Navigation Spacing from top
	delete_option('sh_ss_nav_left');// Navigation Spacing from left
	delete_option('sh_ss_nav_link_color');// Navigation Link Color
	delete_option('sh_ss_nav_link_hover_color');// Navigation Link hover Color
	delete_option('sh_ss_nav_link_underline');// Navigation Link underline
}

// Admin Menu
function shslideshow_menu(){
	$tmp = basename(dirname(__FILE__)); // Plugin folder
	add_menu_page('SH Slideshow Options','SH Slideshow',8,$tmp.'/options.php');
	add_submenu_page($tmp.'/options.php','SH Slideshow Options','Options',8,$tmp.'/options.php');
	add_submenu_page($tmp.'/options.php','SH Slideshow Appearance','Appearance',8,$tmp.'/style.php');
	add_submenu_page($tmp.'/options.php','SH Slideshow Mini Slideshow','Mini Slideshows',8,$tmp.'/mini-slideshow.php');
	add_submenu_page($tmp.'/options.php','SH Slideshow User Guide','User Guide',8,$tmp.'/guide.php');
}

// Using Wordpress media uploader
function shslideshow_admin_scripts() {
	$tmp = basename(dirname(__FILE__)); // Plugin folder
	wp_enqueue_script('media-upload');
	wp_enqueue_script('thickbox');
	wp_register_script('my-upload', WP_PLUGIN_URL.'/sh-slideshow/uploader.js', array('jquery','media-upload','thickbox'));
	wp_enqueue_script('my-upload');
}

function shslideshow_admin_styles() {
	wp_enqueue_style('thickbox');
}

if (($_GET['page'] == 'sh-slideshow/options.php')||($_GET['page'] == 'sh-slideshow/mini-slideshow.php')) {
add_action('admin_print_scripts', 'shslideshow_admin_scripts');
add_action('admin_print_styles', 'shslideshow_admin_styles');
}

// Slideshow Function
// short code
function shslideshow_shortcode( $atts, $content = null ) {
	$slide_no = get_option('sh_ss_slideno');
	$slide_width = get_option('sh_ss_width');
	$slide_height = get_option('sh_ss_height');
	$slide = 0;
	$shslideshow = '<div id="shslideshow"><div id="slide">';
if(get_option('sh_ss_recent_posts')):
	query_posts('post_type=post&post_status=publish&orderby=date&order=DESC&showposts='.$slide_no);
	if(have_posts()):
		while(have_posts()): the_post();
			$images = get_slides(get_the_ID(),$slide_width,$slide_height);
			$shslideshow .= '<a href="'.get_permalink(get_the_ID()).'" title="'.get_the_title(get_the_ID()).'" target="'.get_option('sh_ss_target').'">';
			$shslideshow .= $images[0]; 
			$shslideshow .= '</a>';
		endwhile;
		$slide = $slide_no;
	endif;
	//Reset Query
	wp_reset_query();
else:
	for($i=1;$i<=$slide_no;$i++):
		if(get_option('sh_ss_slide'.$i.'_img')!=''):
			$slide++;
			if(!get_option('sh_ss_slide'.$i)):
				$shslideshow .= '<img src="'.get_option('sh_ss_slide'.$i.'_img').'" title="" />';
			elseif(get_option('sh_ss_slide'.$i)=='manual'):
				$shslideshow .= '<a href="'.get_option('sh_ss_link'.$i).'" title="" target="'.get_option('sh_ss_target').'">';
				$shslideshow .= '<img src="'.get_option('sh_ss_slide'.$i.'_img').'" title="" />';
				$shslideshow .= '</a>';
			else:
				$permalink = get_permalink(get_option('sh_ss_slide'.$i));
				$title = get_the_title(get_option('sh_ss_slide'.$i));
				$shslideshow .= '<a href="'.$permalink.'" title="'.$title.'" target="'.get_option('sh_ss_target').'">';
				$shslideshow .= '<img src="'.get_option('sh_ss_slide'.$i.'_img').'" title="'.$title.'" />';
				$shslideshow .= '</a>';
			endif;
		endif;
	endfor;
endif;
        $shslideshow .= '</div>';
		if(get_option('sh_ss_navigation' ) && ($slide_no > 1) && ($slide > 1)):
            $shslideshow .= '<div id="shslideshow_nav">';
        	if(get_option('sh_ss_navtype')==2):
			$shslideshow .= '
            	<div id="shslideshow_nav_pre">'.get_option('sh_ss_prev_text').'</div>
            	<div id="shslideshow_nav_next">'.get_option('sh_ss_next_text').'</div>
			';
            endif;
            $shslideshow .= '</div>';
            endif;
            $shslideshow .= '
           	<div style="clear:both;"></div>
        </div>
	';
   	return $shslideshow;
}
add_shortcode('shslideshow', 'shslideshow_shortcode');

// PHP code
function shslideshow(){
	$slide_no = get_option('sh_ss_slideno');
	$slide_width = get_option('sh_ss_width');
	$slide_height = get_option('sh_ss_height');
	$slide = 0;
	echo '
		<div id="shslideshow">
        	<div id="slide">';
if(get_option('sh_ss_recent_posts')):
	query_posts('post_type=post&post_status=publish&orderby=date&order=DESC&showposts='.$slide_no);
	if(have_posts()):
		while(have_posts()): the_post();
			$images = get_slides(get_the_ID(),$slide_width,$slide_height);
			echo '<a href="'.get_permalink(get_the_ID()).'" title="" target="'.get_option('sh_ss_target').'">';
			echo $images[0]; 
			echo '</a>';
		endwhile;
		$slide = $slide_no;
	endif;
	//Reset Query
	wp_reset_query();
else:
	for($i=1;$i<=$slide_no;$i++):
		if(get_option('sh_ss_slide'.$i.'_img')!=''):
			$slide++;
			if(!get_option('sh_ss_slide'.$i)):
				echo '<img src="'.get_option('sh_ss_slide'.$i.'_img').'" title="" />';
			elseif(get_option('sh_ss_slide'.$i)=='manual'):
				echo '<a href="'.get_option('sh_ss_link'.$i).'" title="" target="'.get_option('sh_ss_target').'">';
				echo '<img src="'.get_option('sh_ss_slide'.$i.'_img').'" title="" />';
				echo '</a>';
			else:
				$permalink = get_permalink(get_option('sh_ss_slide'.$i));
				$title = get_the_title(get_option('sh_ss_slide'.$i));
				echo '<a href="'.$permalink.'" title="'.$title.'" target="'.get_option('sh_ss_target').'">';
				echo '<img src="'.get_option('sh_ss_slide'.$i.'_img').'" title="'.$title.'" />';
				echo '</a>';
			endif;
		endif;
	endfor;
endif;
        echo '</div>';
		if(get_option('sh_ss_navigation' ) && ($slide_no > 1) && ($slide > 1)):
            echo '<div id="shslideshow_nav">';
        	if(get_option('sh_ss_navtype')==2):
			echo '
            	<div id="shslideshow_nav_pre">'.get_option('sh_ss_prev_text').'</div>
            	<div id="shslideshow_nav_next">'.get_option('sh_ss_next_text').'</div>
			';
            endif;
            echo '</div>';
            endif;
            echo '
           	<div style="clear:both;"></div>
        </div>
	';
}

// Mini slideshow PHP code
function shminislideshow($id){
	global $wpdb;
	$slideshow = $wpdb->get_row('select * from sh_slideshow where id='.$id);
	$slides = $wpdb->get_results('select * from sh_slides where slideshow='.$id.' order by weight');
	$total_slides = count($slides);
	$mini_slideshow = '';
	if($slideshow->css):
	// Style
	$mini_slideshow .= '<style type="text/css">
div#shslideshow_'.$id.'{
	width:'.$slideshow->width.'px;
	background-color:'.$slideshow->bgcolor.';
	margin:auto;
}
div#shslideshow_'.$id.' div.slides{
	position:relative;
	width:100%;
	height:'.$slideshow->height.'px;
	z-index:1;
}
div#shslideshow_'.$id.' div.slides img{
	width:'.$slideshow->width.'px;
	height:'.$slideshow->height.'px;
}
div#shslideshow_nav_'.$id.'{
	margin-left:'.$slideshow->nav_left.'px;
}
div#shslideshow_nav_pre_'.$id.',div#shslideshow_nav_next_'.$id.'{
	display:block;
	float:left;
}
div#shslideshow_nav_pre_'.$id.':hover,div#shslideshow_nav_next_'.$id.':hover{
	cursor:pointer;
}
div#shslideshow_nav_'.$id.' a,div#shslideshow_nav_pre_'.$id.',div#shslideshow_nav_next_'.$id.'{
	margin-right: '.$slideshow->nav_spacing.'px;
	color:'.$slideshow->nav_link_color.';
}
div#shslideshow_nav_'.$id.' a:hover,div#shslideshow_nav_'.$id.' a.activeSlide,div#shslideshow_nav_pre_'.$id.':hover,div#shslideshow_nav_next_'.$id.':hover{
	color:'.$slideshow->nav_link_hover_color.';
}';
if($slideshow->nav_pos):
	$mini_slideshow .= 'div#shslideshow_nav_'.$id.'{ position:absolute;';
	if($slideshow->nav_top < 0):
		$mini_slideshow .= 'margin-top:'.$slideshow->nav_top.'px;';
	else:
		$mini_slideshow .= 'margin-top:-'.$slideshow->nav_top.'px;';
	endif;
	$mini_slideshow .= 'z-index:5; }';
else:
	$mini_slideshow .= 'div#shslideshow_nav_'.$id.'{ padding-top:'.$slideshow->nav_top.'px; }';
endif;
if($slideshow->nav_link_underline):
$mini_slideshow .= 'div#shslideshow_nav_'.$id.' a,div#shslideshow_nav_pre_'.$id.',div#shslideshow_nav_next_'.$id.'{
	text-decoration:underline;
}';
else:
$mini_slideshow .= 'div#shslideshow_nav_'.$id.' a,div#shslideshow_nav_pre_'.$id.',div#shslideshow_nav_next_'.$id.'{
	text-decoration:none;
}';
endif;
$mini_slideshow .= '</style>';
endif;
	$mini_slideshow .= '<div id="shslideshow_'.$id.'" class="shminislideshow">';
	$mini_slideshow .= '<div class="slides">';
	foreach($slides as $slide):
		if($slide->link_url == 'manual'):
			if($slide->custom_url == ''):
				$mini_slideshow .= '<img src="'.$slide->slide.'" alt="" />';
			else:
				$mini_slideshow .= '<a href="'.$slide->custom_url.'" title="" target="'.$slideshow->target.'"><img src="'.$slide->slide.'" alt="" /></a>';
			endif;
		elseif($slide->link_url == 0):
			$mini_slideshow .= '<img src="'.$slide->slide.'" alt="" />';
		else:
			$permalink = get_permalink($slide->link_url);
			$title = get_the_title($slide->link_url);
			$mini_slideshow .= '<a href="'.$permalink.'" title="'.$title.'" target="'.$slideshow->target.'"><img src="'.$slide->slide.'" alt="" /></a>';
		endif;
	endforeach;
	$mini_slideshow .= '</div>';
	if(($slideshow->navigation == 1)&&($total_slides >1)):
		$mini_slideshow .= '<div id="shslideshow_nav_'.$id.'" class="shslideshow_nav">';
		if($slideshow->nav_type == 2):
			$mini_slideshow .= '<div id="shslideshow_nav_pre_'.$id.'" class="shslideshow_nav_pre">'.$slideshow->prev_text.'</div>';
			$mini_slideshow .= '<div id="shslideshow_nav_next_'.$id.'" class="shslideshow_nav_next">'.$slideshow->next_text.'</div>';
		endif;
		$mini_slideshow .= '</div>';
	endif;
	$mini_slideshow .= '</div>';
	
	// Script
	$mini_slideshow .= '<script language="javascript">
	jQuery(document).ready(function(){
		jQuery("#shslideshow_'.$id.' .slides").cycle({
			fx: "'.$slideshow->effect.'",
			pause:'.$slideshow->pause.',
			randomizeEffects:'.$slideshow->random.',
			fastOnEvent:'.($slideshow->nav_transition*1000).',
			fit:1,
			speed: '.($slideshow->transition*1000).',';
if($slideshow->navigation):
	if($slideshow->nav_type == 1):
		$mini_slideshow .= 'pager: "#shslideshow_nav_'.$id.'",';
	elseif($slideshow->nav_type == 2):
		$mini_slideshow .= 'next: "#shslideshow_nav_next_'.$id.'", prev: "#shslideshow_nav_pre_'.$id.'",';
	endif;
endif;
if($slideshow->auto == 0):
	$mini_slideshow .= 'timeout: 0,';
elseif($slideshow->auto == 2):
	$mini_slideshow .= 'autostop: 1,';
	$mini_slideshow .= 'timeout: '.($slideshow->timeout*1000);
elseif($slideshow->auto == 3):
	$mini_slideshow .= 'autostop: 1,';
	$mini_slideshow .= 'autostopCount: '.($total_slides+1).',';
	$mini_slideshow .= 'timeout: '.($slideshow->timeout*1000);
else:
	$mini_slideshow .= 'timeout: '.($slideshow->timeout*1000);
endif;		
$mini_slideshow .= '});
	});
</script> 
	';
	echo $mini_slideshow;
}
add_shortcode('shminislideshow', 'shminislideshow_shortcode');

// Mini slideshow Shortcode
function shminislideshow_shortcode($atts,$content = null){
	global $wpdb;
	$slideshow = $wpdb->get_row('select * from sh_slideshow where id='.$atts['id']);
	$slides = $wpdb->get_results('select * from sh_slides where slideshow='.$atts['id'].' order by weight');
	$total_slides = count($slides);
	$mini_slideshow = '';
	if($slideshow->css):
	// Style
	$mini_slideshow .= '<style type="text/css">
div#shslideshow_'.$atts['id'].'{
	width:'.$slideshow->width.'px;
	background-color:'.$slideshow->bgcolor.';
	margin:auto;
}
div#shslideshow_'.$atts['id'].' div.slides{
	position:relative;
	width:100%;
	height:'.$slideshow->height.'px;
	z-index:1;
}
div#shslideshow_'.$atts['id'].' div.slides img{
	width:'.$slideshow->width.'px;
	height:'.$slideshow->height.'px;
}
div#shslideshow_nav_'.$atts['id'].'{
	margin-left:'.$slideshow->nav_left.'px;
}
div#shslideshow_nav_pre_'.$atts['id'].',div#shslideshow_nav_next_'.$atts['id'].'{
	display:block;
	float:left;
}
div#shslideshow_nav_pre_'.$atts['id'].':hover,div#shslideshow_nav_next_'.$atts['id'].':hover{
	cursor:pointer;
}
div#shslideshow_nav_'.$atts['id'].' a,div#shslideshow_nav_pre_'.$atts['id'].',div#shslideshow_nav_next_'.$atts['id'].'{
	margin-right: '.$slideshow->nav_spacing.'px;
	color:'.$slideshow->nav_link_color.';
}
div#shslideshow_nav_'.$atts['id'].' a:hover,div#shslideshow_nav_'.$atts['id'].' a.activeSlide,div#shslideshow_nav_pre_'.$atts['id'].':hover,div#shslideshow_nav_next_'.$atts['id'].':hover{
	color:'.$slideshow->nav_link_hover_color.';
}';
if($slideshow->nav_pos):
	$mini_slideshow .= 'div#shslideshow_nav_'.$atts['id'].'{ position:absolute;';
	if($slideshow->nav_top < 0):
		$mini_slideshow .= 'margin-top:'.$slideshow->nav_top.'px;';
	else:
		$mini_slideshow .= 'margin-top:-'.$slideshow->nav_top.'px;';
	endif;
	$mini_slideshow .= 'z-index:5; }';
else:
	$mini_slideshow .= 'div#shslideshow_nav_'.$atts['id'].'{ padding-top:'.$slideshow->nav_top.'px; }';
endif;
if($slideshow->nav_link_underline):
$mini_slideshow .= 'div#shslideshow_nav_'.$atts['id'].' a,div#shslideshow_nav_pre_'.$atts['id'].',div#shslideshow_nav_next_'.$atts['id'].'{
	text-decoration:underline;
}';
else:
$mini_slideshow .= 'div#shslideshow_nav_'.$atts['id'].' a,div#shslideshow_nav_pre_'.$atts['id'].',div#shslideshow_nav_next_'.$atts['id'].'{
	text-decoration:none;
}';
endif;
$mini_slideshow .= '</style>';
endif;
	$mini_slideshow .= '<div id="shslideshow_'.$atts['id'].'" class="shminislideshow">';
	$mini_slideshow .= '<div class="slides">';
	foreach($slides as $slide):
		if($slide->link_url == 'manual'):
			if($slide->custom_url == ''):
				$mini_slideshow .= '<img src="'.$slide->slide.'" alt="" />';
			else:
				$mini_slideshow .= '<a href="'.$slide->custom_url.'" title="" target="'.$slideshow->target.'"><img src="'.$slide->slide.'" alt="" /></a>';
			endif;
		elseif($slide->link_url == 0):
			$mini_slideshow .= '<img src="'.$slide->slide.'" alt="" />';
		else:
			$permalink = get_permalink($slide->link_url);
			$title = get_the_title($slide->link_url);
			$mini_slideshow .= '<a href="'.$permalink.'" title="'.$title.'" target="'.$slideshow->target.'"><img src="'.$slide->slide.'" alt="" /></a>';
		endif;
	endforeach;
	$mini_slideshow .= '</div>';
	if(($slideshow->navigation == 1)&&($total_slides >1)):
		$mini_slideshow .= '<div id="shslideshow_nav_'.$atts['id'].'" class="shslideshow_nav">';
		if($slideshow->nav_type == 2):
			$mini_slideshow .= '<div id="shslideshow_nav_pre_'.$atts['id'].'" class="shslideshow_nav_pre">'.$slideshow->prev_text.'</div>';
			$mini_slideshow .= '<div id="shslideshow_nav_next_'.$atts['id'].'" class="shslideshow_nav_next">'.$slideshow->next_text.'</div>';
		endif;
		$mini_slideshow .= '</div>';
	endif;
	$mini_slideshow .= '</div>';
	
	// Script
	$mini_slideshow .= '<script language="javascript">
	jQuery(document).ready(function(){
		jQuery("#shslideshow_'.$atts['id'].' .slides").cycle({
			fx: "'.$slideshow->effect.'",
			pause:'.$slideshow->pause.',
			randomizeEffects:'.$slideshow->random.',
			fastOnEvent:'.($slideshow->nav_transition*1000).',
			fit:1,
			speed: '.($slideshow->transition*1000).',';
if($slideshow->navigation):
	if($slideshow->nav_type == 1):
		$mini_slideshow .= 'pager: "#shslideshow_nav_'.$atts['id'].'",';
	elseif($slideshow->nav_type == 2):
		$mini_slideshow .= 'next: "#shslideshow_nav_next_'.$atts['id'].'", prev: "#shslideshow_nav_pre_'.$atts['id'].'",';
	endif;
endif;
if($slideshow->auto == 0):
	$mini_slideshow .= 'timeout: 0,';
elseif($slideshow->auto == 2):
	$mini_slideshow .= 'autostop: 1,';
	$mini_slideshow .= 'timeout: '.($slideshow->timeout*1000);
elseif($slideshow->auto == 3):
	$mini_slideshow .= 'autostop: 1,';
	$mini_slideshow .= 'autostopCount: '.($total_slides+1).',';
	$mini_slideshow .= 'timeout: '.($slideshow->timeout*1000);
else:
	$mini_slideshow .= 'timeout: '.($slideshow->timeout*1000);
endif;		
$mini_slideshow .= '});
	});
</script> 
	';
	return $mini_slideshow;
}

// Add Script
function shslideshow_script(){
	if(is_array(get_option('sh_ss_effect'))):
		$i = 0;
		foreach(get_option('sh_ss_effect') as $effect):
			if($i == 1):
				$effects .= ',';
			endif;
			$effects .= $effect;
			$i = 1;
		endforeach;
	else:
		$effects = get_option('sh_ss_effect');
	endif;
	// Javascript
	echo '
<!-- Add Cycle Script -->
<script language="javascript" type="text/javascript" src="'.WP_PLUGIN_URL.'/sh-slideshow/jquery.cycle.all.js"></script>
<script language="javascript">
	jQuery(document).ready(function($){
		$("#shslideshow #slide").cycle({
			fx: "'.$effects.'",';
		if(get_option('sh_ss_pause')):
			echo 'pause: 1,';
		endif;
		if(get_option('sh_ss_navigation')):
			if(get_option('sh_ss_navtype')==1):
				echo 'pager: "#shslideshow_nav",';
			elseif(get_option('sh_ss_navtype')==2):
				echo '
					next:   "#shslideshow_nav_next",
					prev:   "#shslideshow_nav_pre",
				';
			endif;
		endif;
		echo 'randomizeEffects:'.get_option('sh_ss_random').',';
		echo 'fastOnEvent:'.(get_option('sh_ss_nav_transition')*1000).',';
		echo '
			fit:1,
			speed: '.(get_option('sh_ss_transition')*1000).',';
		if(get_option('sh_ss_atuo')==0):
			echo 'timeout: 0';
		elseif(get_option('sh_ss_atuo')==2):
			echo 'autostop: 1,';
			echo 'timeout: '.(get_option('sh_ss_timeout')*1000);
		elseif(get_option('sh_ss_atuo')==3):
			echo 'autostop: 1,';
			echo 'autostopCount: '.(get_option('sh_ss_slideno')+1).',';
			echo 'timeout: '.(get_option('sh_ss_timeout')*1000);
		else:
			echo 'timeout: '.(get_option('sh_ss_timeout')*1000);
		endif;
		echo '});
	});
</script>
	';
	if(get_option('sh_ss_css')):
	// Css
	echo '
<!-- Add SH Slideshow Style -->
<style type="text/css">
	div#shslideshow{
		width:'.get_option('sh_ss_width').'px;
		background-color:'.get_option('sh_ss_bgcolor').';
		margin:auto;
	}
	div#shslideshow div#slide{
		position:relative;
		width:100%;
		height:'.get_option('sh_ss_height').'px;
		z-index:1;
	}
	div#shslideshow div#slide img{
		width:'.get_option('sh_ss_width').'px;
		height:'.get_option('sh_ss_height').'px;
	}
	div#shslideshow_nav{
		margin-left:'.get_option('sh_ss_nav_left').'px;
	}
	div#shslideshow_nav_pre,div#shslideshow_nav_next{
		display:block;
		float:left;
	}
	div#shslideshow_nav_pre:hover,div#shslideshow_nav_next:hover{
		cursor:pointer;
	}
	div#shslideshow_nav a,div#shslideshow_nav_pre,div#shslideshow_nav_next{
		margin-right: '.get_option('sh_ss_nav_spacing').'px;
		color:'.get_option('sh_ss_nav_link_color').';
	}
	div#shslideshow_nav a:hover,div#shslideshow_nav a.activeSlide,div#shslideshow_nav_pre:hover,div#shslideshow_nav_next:hover{
		color:'.get_option('sh_ss_nav_link_hover_color').';
	}
	';
	if(get_option('sh_ss_navpos')):
	echo '
	div#shslideshow_nav{
		position:absolute;';
	if(get_option('sh_ss_nav_top')<0):
		echo 'margin-top:'.get_option('sh_ss_nav_top').'px;';
	else:
		echo 'margin-top:-30px;';
	endif;
	echo '
		z-index:5;
	}';
	else:
	echo '
	div#shslideshow_nav{
		padding-top:'.get_option('sh_ss_nav_top').'px;
	}';
	endif;
	
	if(get_option('sh_ss_nav_link_underline')):
	echo '
	div#shslideshow_nav a,div#shslideshow_nav_pre,div#shslideshow_nav_next{
		text-decoration:underline;
	}
	';
	else:
	echo '
	div#shslideshow_nav a,div#shslideshow_nav_pre,div#shslideshow_nav_next{
		text-decoration:none;
	}
	';
	endif;
	echo '
</style>
';
	endif;
}
// get all of the images attached to the current post
/*
	Size of the image shown for an image attachment: either a string keyword (thumbnail, medium, large or full) or a 2-item array representing width and height in pixels, e.g. array(32,32). As of Version 2.5, this parameter does not affect the size of media icons, which are always shown at their original size.
*/
function get_slides($pid, $width=30, $height=30) {
	global $post;
	$scriptpath = WP_PLUGIN_URL.'/sh-slideshow';
	$photos = get_children( array('post_parent' => $pid, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => 'ASC', 'orderby' => 'menu_order ID') );

	$results = array();
	
	if ($photos) {
		foreach ($photos as $photo) {
			$img_src = wp_get_attachment_image_src($photo->ID, 'full');
			$img = '<img src="'.$scriptpath.'/timthumb.php?src='.$img_src[0].'&amp;w='.$width.'&amp;h='.$height.'&amp;zc=1" alt=""/>';
			$results[] = $img;
		}
	}

	return $results;
}
?>