<?php
/*
These are the functions to mantain compatibility with legacy versions prior to Schreikasten 0.13
*/

/* Copyright 2010 Juan Sebastián Echeverry (email : sebaxtian@gawab.com)

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

/**
* Function to update sk into the new widget.
*/
function skLegacy_updateWidget() {

	$options = get_option('widget_sk');
	$add=1;
	
	$data = array('title'=> $options['title'], 'items'=>$options['items'], 'rss'=>$options['rss']);
	
	//Update the widget data
	update_option('sk_options', $options);
	
	// old format, conver if single widget
	$settings = wp_convert_widget_settings('sk', 'widget_sk', $options);
	//Update the widgets in the sidebar
	$pos = 0; //The position where the widget is
	$sidebars_widgets = get_option('sidebars_widgets');
	foreach ( (array) $sidebars_widgets as $index => $sidebar ) {
		if ( is_array($sidebar) ) {
			$count = 0; //New counter
			foreach ( $sidebar as $i => $name ) {
				//Check if the widget has the name from the old one
				if ( $name == 'schreikasten') {
					//We found something, set the data
					$sb_pos = $index;
					$pos = $index;
					$changed = true;
				}
				$count++;
			}
			//If we found the widget, move all the widgets after the one where we are 
			//searching, then add the new widgets
			if($changed) {
				$sidebar = $sidebars_widgets[$sb_pos];
				//How many widgets do we have in this sidebar?
				$size = count($sidebar);
				
				//Add from end to begin
				$aux=0;
				$sidebar_aux = array();
				for($i=0; $i<$size; $i++) {
					if($i == $pos) {
						for($j=2;$j<$add+2;$j++) {
							$sidebar_aux[$count] = "sk-$j"; 
							$count++;
						}
					} else {
						$sidebar_aux[$count] = $sidebar[$i];
						$count++;
					}
				}
				
				//Update the sidebars_widgets
				$sidebars_widgets[$sb_pos]=$sidebar_aux;
				update_option('sidebars_widgets', $sidebars_widgets);
				$changed = false;
			}
		}
	}
}

function skLegacy_content($content) {
	//The chat box
	$search = "/(?:<p>)*\s*\[sk-shoutbox\]\s*(?:<\/p>)*/i";
	if(preg_match ( $search , $content)) {
		$options = get_option('sk_options');
		$args = array();
		$args['items'] = $options['items'];
		$content = preg_replace($search, sk_codeShoutbox($args), $content);
	}
	
	//The feed icon
	$search = "/\[sk-feed-icon\]/i";
	$img_url = get_bloginfo('wpurl')."/wp-includes/images/rss.png";
	$feed_url = sk_plugin_url('/ajax/feed.php');
	$replace = "<a class='rsswidget' href='$feed_url' title='" . __('Subscribe' , 'sk')."'><img src='$img_url' alt='RSS' border='0' /></a>";
	$content = preg_replace($search, $replace, $content);
	
	//Thed feed link
	$search = "/\[sk-feed\]([^\[]+)\[\/sk-feed\]/i";
	$replace = "<a href='$feed_url'>$1</a>";
	$content = preg_replace($search, $replace, $content);
	return $content;
}

?>
