<?php

require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

if (version_compare(get_option('knews_version','0.0.0'), '1.1.0') < 0) {
	//The 1.1.0 added fields & tables

	global $wpdb;
	
	$sql =	"ALTER TABLE " .KNEWS_NEWSLETTERS . " ADD COLUMN lang varchar(3) NOT NULL DEFAULT ''";
	$wpdb->query($sql);
	$sql =	"ALTER TABLE " .KNEWS_NEWSLETTERS . " ADD COLUMN automated varchar(1) NOT NULL DEFAULT 0";
	$wpdb->query($sql);

	if (!$this->tableExists(KNEWS_NEWSLETTERS_SUBMITS)) {
	
		$sql =	"CREATE TABLE " . KNEWS_NEWSLETTERS_SUBMITS . " (
				id bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
				blog_id bigint(20) UNSIGNED NOT NULL DEFAULT " . $this->KNEWS_MAIN_BLOG_ID . ",
				newsletter int(11) NOT NULL,
				finished tinyint(1) NOT NULL,
				paused tinyint(1) NOT NULL,
				start_time timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
				end_time timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
				users_total int(11) NOT NULL,
				users_ok int(11) NOT NULL,
				users_error int(11) NOT NULL,
				priority tinyint(4) NOT NULL,
				strict_control varchar(100) NOT NULL,
				emails_at_once int(11) NOT NULL,
				special varchar(32) NOT NULL,
				UNIQUE KEY id (id)
			   )$charset_collate;";
		
		dbDelta($sql);

	} else {

		$sql = "SHOW COLUMNS FROM " . KNEWS_NEWSLETTERS_SUBMITS . " LIKE 'blog_id'";
		$exists = $wpdb->get_results($sql);
		if (count($exists)==0) {
	
			$sql =	"ALTER TABLE " .KNEWS_NEWSLETTERS_SUBMITS . " ADD COLUMN blog_id bigint(20) UNSIGNED NOT NULL DEFAULT " . $this->KNEWS_MAIN_BLOG_ID;
			$wpdb->query($sql);
	
		}
	}
	
	$this->knews_admin_messages = sprintf("Knews updated the database successfully. Welcome to %s version.", KNEWS_VERSION);

	if ($wpdb->prefix != $wpdb->base_prefix) {
		if ($this->tableExists($wpdb->prefix . 'knewsubmits')) {
	
			$query = "SELECT * FROM " . $wpdb->prefix . "knewsubmits";
			$submit_pend = $wpdb->get_results( $query );
			
			foreach ($submit_pend as $sp) {
				
				$query = 'INSERT INTO ' . KNEWS_NEWSLETTERS_SUBMITS . ' (blog_id, newsletter, finished, paused, start_time, users_total, users_ok, users_error, priority, strict_control, emails_at_once, special, end_time) VALUES (' . get_current_blog_id() . ', ' . $sp->newsletter . ', ' . $sp->finished . ', ' . $sp->paused . ', \'' . $sp->start_time . '\', ' . $sp->users_total . ', ' . $sp->users_ok . ', ' . $sp->users_error . ', ' . $sp->priority . ', \'' . $sp->strict_control . '\', ' . $sp->emails_at_once . ', \'' . $sp->special . '\', \'' . $sp->end_time . '\')';
				$results = $wpdb->query( $query );
				$submit_confirmation_id=$wpdb->insert_id; $submit_confirmation_id2=mysql_insert_id(); if ($submit_confirmation_id==0) $submit_confirmation_id=$submit_confirmation_id2;

				if ($submit_confirmation_id != 0) {
					$query  = "UPDATE " . KNEWS_NEWSLETTERS_SUBMITS_DETAILS . " SET submit=" . $submit_confirmation_id . " WHERE submit=" . $sp->id;
					$results = $wpdb->query( $query );
				}

			}
		}
	}
	
}

update_option('knews_version', KNEWS_VERSION);
?>
