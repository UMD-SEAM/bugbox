<?php

function gigpress_show_related_auto($content = '') {
	return gigpress_show_related(null, $content);
}

function gigpress_show_related($args = array(), $content = '') {
		
	global $is_excerpt, $wpdb, $gpo, $post;
	if( $is_excerpt == TRUE || !is_object($post) ) {
		$is_excerpt = FALSE;
		return $content;
	} else {
	
		extract(shortcode_atts(array(
			'scope' => 'all',
			'sort' => 'asc'
		), $args));
		
		// Date conditionals based on scope
		switch($scope) {
			case 'upcoming':
				$date_condition = ">= '" . GIGPRESS_NOW . "'";
				break;
			case 'past':
				$date_condition = "< '" . GIGPRESS_NOW . "'";
				break;
			case 'all':
				$date_condition = "!= ''";
		}		
		
		$shows = $wpdb->get_results(
			$wpdb->prepare("SELECT * FROM " . GIGPRESS_ARTISTS . " AS a, " . GIGPRESS_VENUES . " as v, " . GIGPRESS_SHOWS ." AS s LEFT JOIN  " . GIGPRESS_TOURS . " AS t ON s.show_tour_id = t.tour_id WHERE show_related = %d AND show_expire " . $date_condition . " AND show_status != 'deleted' AND s.show_artist_id = a.artist_id AND s.show_venue_id = v.venue_id ORDER BY show_date " . $sort . ",show_expire " . $sort . ",show_time " . $sort, $post->ID)
		);
	
		if($shows != FALSE) {
			
			ob_start();
				
				$count = 1;
				$total_shows = count($shows);
				foreach ($shows as $show) {
					$showdata = gigpress_prepare($show, 'related');						
					include gigpress_template('related');
					$count++;
				}
			
			$giginfo = ob_get_clean();
			
			if ( $gpo['related_position'] == "before" ) {
				$output = $giginfo . $content;
			} else {
				$output = $content . $giginfo;
			}
			
			return $output;
							
		} else {
		
			return $content;
		}
	}
}