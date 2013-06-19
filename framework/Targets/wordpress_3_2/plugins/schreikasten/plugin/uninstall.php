<?php if( !defined( 'ABSPATH') && !defined('WP_UNINSTALL_PLUGIN') )
	exit();
	
	define ("SK_CAP", 'moderate_schreikasten');

	global $wpdb;
	global $db_version;
	global $wp_roles;
	$table_name = $wpdb->prefix . "schreikasten";
	$wpdb->query("DROP TABLE $table_name;");
	$blacklist_name = $wpdb->prefix . "schreikasten_blacklist";
	$wpdb->query("DROP TABLE $blacklist_name;");
	delete_option('sk_db_version');
	delete_option('sk_api_key');
	delete_option('sk_api_key_accepted');
	delete_option('sk_options');
	delete_option('widget_sk');
	
	//Delete cappabilitie
	foreach(array_keys($wp_roles->get_names()) as $role_name) {
		$role = get_role( $role_name );
		$role->remove_cap( SK_CAP );
	}

?>
