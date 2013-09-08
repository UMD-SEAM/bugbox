<?php
include_once('../../../../wp-config.php');
include_once('../../../../wp-includes/wp-db.php');
//-------------------------------------------------------
// Perform crawling a batch of post urls. Batch size is 
// provided in 'batch_size'. Batch starting id is provided
// in 'from_id'.
//-------------------------------------------------------
	crawl_submit();
	function crawl_submit( )
	{
		global $wpdb;
		$post_type_in = array();

		if( isset( $_POST['page'] ) )
		{
		    $post_type_in[] = '"page"';
		}
		if( isset( $_POST['post'] ) )
		{
		    $post_type_in[] = '"post"';
		}
		$post_type_in = '(' . implode(',', $post_type_in) . ')';
		$id_from = mysql_real_escape_string( $_POST['from_id'] );
		$batch_size = mysql_real_escape_string( $_POST['batch_size'] );

		$post_recs = $wpdb->get_results( $wpdb->prepare( "SELECT ID,GUID FROM $wpdb->posts WHERE post_status='publish' AND post_type IN $post_type_in AND ID > $id_from order by ID asc LIMIT $batch_size" ) );
		$post_count = $wpdb->num_rows;

		echo '<tl_count>', $post_count , '</tl_count>';

		$last_id = -1;

		$permalinks = array();

		$post_recs = (object) $post_recs;

		foreach( $post_recs as $post_rec )
		{
		    (object)$post_rec = $post_rec;
		    if( $post_rec->GUID != null )
		    { 
			$postdata = array(
				'url'			=>  $post_rec->GUID,
				'depth'			=> 0,
				'annotation'	=> (object) array( 'wp_post_id' => (int)$post_rec->ID ),
			);
			$permalinks['urls'][] = $postdata;
		    }
		    $last_id = $post_rec->ID;
		}

		$json_object = json_encode($permalinks);
		if( is_wp_error( $json_object ) )
			return false;

		if( $post_recs )
		{
			$json_out = do_post( $json_object );			
		}

		if( $post_count >= $batch_size )
			echo '<tl_last>', $last_id, '</tl_last>';
		return $last_id;	
	}

	function do_post( $postdata )
	{
		$yolink_config = get_option('yolink_config');
		
		$api_url = 'http://index.yolink.com/index/crawl?o=JSON&ak=' . $yolink_config['yolink_apikey'];
		$request = new WP_Http;
		$args = array(
			'headers'		=> array( 'Content-Type' => 'application/json; charset=utf-8'),
			'body'			=> $postdata,
		);
		$out = $request->post( $api_url, $args );
	}
	
?>