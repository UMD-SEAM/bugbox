<?php
ob_start();
if (!function_exists('add_action')) {
	$path='./';
	for ($x=1; $x<6; $x++) {
		$path .= '../';
		if (@file_exists($path . 'wp-config.php')) {
		    require_once($path . "wp-config.php");
			break;
		}
	}
}
ob_end_clean();

if ($Knews_plugin) {
	$Knews_plugin->security_for_direct_pages();

	if (! $Knews_plugin->initialized) $Knews_plugin->init();

	$ajaxid = intval($Knews_plugin->get_safe('ajaxid'));
	if ($ajaxid != 0) {
		global $post;
		$post = get_post($ajaxid);
		setup_postdata($post);

		$text = get_the_content();
		$text = strip_shortcodes( $text );
		$text = apply_filters('the_content', $text);
		$text = str_replace(']]>', ']]>', $text);
		$text = strip_tags($text);
		$excerpt_length = apply_filters('excerpt_length', 55);
		$words = explode(' ', $text, $excerpt_length + 1);
		if (count($words) > $excerpt_length) {
			array_pop($words);
			//array_push($words, '[...]');
			$text = implode(' ', $words);
		}
		$text = nl2br($text);

		$jsondata['permalink'] = get_permalink($ajaxid);
 	    $jsondata['title'] = get_the_title();
 	    $jsondata['excerpt'] = $text;
 	    $jsondata['content'] = get_the_content();

 		echo json_encode($jsondata);
		
	} else {
		$languages=$Knews_plugin->getLangs();
		$lang = $Knews_plugin->get_safe('lang');
		$s = $Knews_plugin->get_safe('s');
		$type = $Knews_plugin->get_safe('type','post');
		$cat = intval($Knews_plugin->get_safe('cat'));
		$orderbt = $Knews_plugin->get_safe('orderby');
		$order = $Knews_plugin->get_safe('order', 'asc');
		
		$url_base =  KNEWS_URL . '/direct/select_post.php';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Select Post</title>
<style type="text/css">
	html,body{ width:100%; height:100%;}
	body, div, dl, dt, dd, ul, ol, li, h1, h2, h3, h4, h5, h6, pre, form, fieldset, input, textarea, p, blockquote, th, td, input, hr { 
		margin:0px; 
		padding:0px; 
		border:none;
		font-family:Verdana, Geneva, sans-serif;
		font-size:12px;
		line-height:100%;
	}
	a {
		text-decoration:none;
		color:#000;
	}
	a:hover {
		color:#d54e21;
	}
	div.content {
		padding:10px 20px 0 20px;
	}
	div.pestanyes {
		background:#fff;
		padding-left:15px;
		display:block;
		height:25px;
	}
	div.pestanyes a {
		border-top-left-radius:3px;
		border-top-right-radius:3px;
		color:#aaa;
		display:inline-block;
		height:20px;
		padding:4px 14px 0 14px;
		border:#dfdfdf 1px solid;
		text-decoration:none;
		margin-left:5px;
		font-family:Georgia,"Times New Roman","Bitstream Charter",Times,serif;
		font-size:14px;
	}
	div.pestanyes a:hover {
		color:#d54e21;
	}
	div.pestanyes a.on {
		color:#000;
		background:#f9f9f9;
		cursor:default;
		border-bottom:#f9f9f9 1px solid;
	}

	p.langs_selector a {
		color:#21759B;
	}
	p.langs_selector a:hover {
		color:#d54e21;
	}
	p {
		padding-bottom:10px;
	}
	div.filters {
		border-top:#dfdfdf 1px solid;
		border-bottom:#dfdfdf 1px solid;
		padding:10px 10px 0 10px;
		height:30px;
		background:#f9f9f9;
		background-image:-moz-linear-gradient(center top , #F9F9F9, #ECECEC);
		margin-bottom:20px;
	}
	input.button {
		border:#888 1px solid;
		background:#fff;
		border-radius:11px;
		cursor:pointer;
		padding:3px 11px;
	}
	input.button:hover {
		border-color:#000;
	}
	input.texte {
		padding:3px;
		border:#DFDFDF 1px solid;
		border-radius:3px;
		margin-right:5px;
	}
	div.left_side {
		width:290px;
		position:absolute;
	}
	div.right_side {
		float:right;
	}
	select {
		border:#DFDFDF 1px solid;
		padding:1px;
	}
</style>

<script type="text/javascript">
function select_post(n, lang) {
	parent.CallBackPost(n, lang);
}
</script>
</head>

<body>
<div class="content">
	<p><strong><?php _e('Select the post to insert in the newsletter','knews'); ?>:</strong></p>
	<?php
		foreach ($languages as $l) {
			if ($l['active']==1 && $lang=='') $lang = $l['language_code'];
		}
		
		//Languages
		if (count($languages) > 1) {
			echo '<p class="langs_selector">';
			$first=true;
			foreach ($languages as $l) {
				if (!$first) echo ' | ';
				$first=false;
				if ($lang==$l['language_code']) echo '<strong>';
				echo '<a href="' . $url_base . '?lang=' . $l['language_code'] . '&type=' . $type  . '">' . $l['native_name'] . '</a>';
				if ($lang==$l['language_code']) echo '</strong>';
			}
			echo '</p>';
		}
		$url_base .= '?lang=' . $lang;
		
		//Posts / Pages
		echo '<div class="pestanyes">';
		echo (($type=='post') ? '<a class="on"' : '<a') . ' href="' . $url_base . '&type=post' . '">' . __('Posts','knews') . '</a>' . (($type=='post') ? '</strong>' : '');
		echo (($type=='page') ? '<a class="on"' : '<a') . ' href="' . $url_base . '&type=page' . '">' . __('Pages','knews') . '</a>' . (($type=='page') ? '</strong>' : '') . '</div>';
		
		echo '<div class="filters">';
		//Filters
		if ($type=='post') {
			echo '<div class="left_side">';
			$cats = get_categories(array('hide_empty'=>0));
			if (count($cats)>1) {
				echo '<form action="' . $url_base . '" method="get">';
				echo '<input type="hidden" name="lang" value="' . $lang . '">';
				echo '<input type="hidden" name="type" value="' . $type . '">';
				echo '<select name="cat" id="cat">';
				echo '<option value="0">' . __('All categories','knews') . '</option>';
				foreach ($cats as $c) {
					echo '<option value="' . $c->cat_ID . '"' . (($c->cat_ID==$cat) ? ' selected="selected"' : '') . '>' . $c->name . '</option>';
				}
				echo '</select> <input type="submit" value="' . __('Filter','knews') . '" class="button">';
				echo '</form>';
			}
			echo '</div>';
		}
		
		//Search
		echo '<div class="right_side">';
		echo '<form action="' . $url_base . '" method="get">';
		echo '<input type="hidden" name="lang" value="' . $lang . '">';
		echo '<input type="hidden" name="type" value="' . $type . '">';
		echo '<input type="text" name="s" value="" class="texte">';
		echo '<input type="submit" value="' . __('Search','knews') . '" class="button">';
		echo '</form>';
		echo '</div>';
		
		echo '</div>';
		/*function new_excerpt_more($more) {
			return '[...]';
		}
		add_filter('excerpt_more', 'new_excerpt_more');*/
	
		$args = array('posts_per_page' => -1, 'post_type' => $type);
	
		if ($cat != 0) $args['cat'] = $cat;
		if ($s != '') $args['s'] = $s;
	
		$myposts = query_posts($args);
		
		//print_r($myposts);
		
		foreach($myposts as $post) {
			setup_postdata($post);
			echo '<p><a href="#" onclick="select_post(' . $post->ID . ',\'' . $lang . '\')"><strong>';
			the_title();
			echo '</strong></a><br>';
			echo get_the_excerpt();
			echo '</p>';
		}
	 ?>
	 </div>
</body>
</html>
<?php 
	}
}
?>