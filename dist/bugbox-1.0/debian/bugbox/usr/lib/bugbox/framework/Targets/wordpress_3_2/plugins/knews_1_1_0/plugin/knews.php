<?php
/*
Plugin Name: K-news
Plugin URI: http://www.knewsplugin.com
Description: Finally, newsletters are multilingual, quick and professional.
Version: 1.1.0
Author: Carles Reverter
Author URI: http://www.carlesrever.com
License: GPLv2 or later
*/

/*
This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
*/

if (!class_exists("KnewsPlugin")) {
	class KnewsPlugin {
	
		var $adminOptionsName = "KnewsAdminOptions";
		var $knewsOptions = array();
		var $knewsLangs = array();
		var $initialized = false;
		var $initialized_textdomain = false;
		var $advice='';
		var $KNEWS_MAIN_BLOG_ID = 1;
		var $knews_admin_messages = '';

				
		/******************************************************************************************
		/*                                   INICIALITZAR
		******************************************************************************************/
		
		// Carregar opcions de la BBDD / inicialitzar
		function getAdminOptions() {

			$KnewsAdminOptions = array (
				'smtp_knews' => '0',
				'from_mail_knews' => get_bloginfo('admin_email'),
				'from_name_knews' => 'Knews robot',
				'smtp_host_knews' => 'smtp.knewsplugin.com',
				'smtp_port_knews' => '25',
				'smtp_user_knews' => '',
				'smtp_pass_knews' => '',
				'smtp_secure_knews' => '',
				'multilanguage_knews' => 'off',
				'no_warn_ml_knews' => 'no',
				'no_warn_cron_knews' => 'no',
				'config_knews' => 'no',
				'update_knews' => 'no',
				'write_logs' => 'no',
				'knews_cron' => 'cronwp',
				'update_pro' => 'no');

			$devOptions = get_option($this->adminOptionsName);
			if (!empty($devOptions)) {
				foreach ($devOptions as $key => $option)
					$KnewsAdminOptions[$key] = $option;
			} else {
				update_option($this->adminOptionsName, $KnewsAdminOptions);
			}

			define('KNEWS_MULTILANGUAGE', $this->check_multilanguage_plugin($KnewsAdminOptions['multilanguage_knews']));

			return $KnewsAdminOptions;
		}
	
		function creaSiNoExisteixDB () {
			if (!$this->tableExists(KNEWS_USERS)) require( KNEWS_DIR . "/includes/knews_installDB.php");
			if (version_compare(get_option('knews_version','0.0.0'), KNEWS_VERSION, '<')) require( KNEWS_DIR . "/includes/knews_updateDB.php");
		}
		
		function get_default_messages() {
			$KnewsDefaultMessages = array (
				array ( 'label'=>__('Text direction, Left To Right or Right To Left: put <span style="color:#e00">ltr</span> or <span style="color:#e00">rtl</span>','knews'), 'name'=>'text_direction'),
				array ( 'label'=>__('Widget title','knews'), 'name'=>'widget_title'),
				array ( 'label'=>__('Widget e-mail label form','knews'), 'name'=>'widget_label'),
				array ( 'label'=>__('Widget submit button','knews'), 'name'=>'widget_button'),
				array ( 'label'=>__('Wrong e-mail address, please check (AJAX message)','knews'), 'name'=>'ajax_wrong_email'),
				array ( 'label'=>__('We have sent you a confirmation e-mail (AJAX message)','knews'), 'name'=>'ajax_subscription'),
				array ( 'label'=>__('Subscription done, you were already subscribed (AJAX message)','knews'), 'name'=>'ajax_subscription_direct'),
				array ( 'label'=>__('You were already a subscriber (AJAX message)','knews'), 'name'=>'ajax_subscription_oops'),
				array ( 'label'=>__('Subscription error (AJAX message)','knews'), 'name'=>'ajax_subscription_error'),
				array ( 'label'=>__('Confirmation E-mail (subject)','knews'), 'name'=>'email_subscription_subject'),
				array ( 'label'=>__('Confirmation E-mail (body)','knews'), 'name'=>'email_subscription_body'),
				array ( 'label'=>__('E-mail on automatically import (title)','knews'), 'name'=>'email_importation_subject'),
				array ( 'label'=>__('E-mail on automatically import (body)','knews'), 'name'=>'email_importation_body'),
				array ( 'label'=>__('Subscription OK Dialog (Title)','knews'), 'name'=>'subscription_ok_title'),
				array ( 'label'=>__('Subscription OK Dialog (Message)','knews'), 'name'=>'subscription_ok_message'),
				array ( 'label'=>__('Subscription Error Dialog (Title)','knews'), 'name'=>'subscription_error_title'),
				array ( 'label'=>__('Subscription Error Dialog (Message)','knews'), 'name'=>'subscription_error_message'),
				array ( 'label'=>__('UnSubscribe Error Dialog (Title)','knews'), 'name'=>'subscription_stop_error_title'),
				array ( 'label'=>__('UnSubscribe Error Dialog (Message)','knews'), 'name'=>'subscription_stop_error_message'),
				array ( 'label'=>__('UnSubscribe OK Dialog (Title)','knews'), 'name'=>'subscription_stop_ok_title'),
				array ( 'label'=>__('UnSubscribe OK Dialog (Message)','knews'), 'name'=>'subscription_stop_ok_message'),
				array ( 'label'=>__('Close Button Caption','knews'), 'name'=>'dialogs_close_button'),
				array ( 'label'=>__('Default alignment (<span style="color:#e00">left</span> for left to right languages and <span style="color:#e00">right</span> for right to left languages)','knews'), 'name'=>'default_alignment'),
				array ( 'label'=>__('Inverse alignment (<span style="color:#e00">right</span> for left to right languages and <span style="color:#e00">left</span> for right to left languages)','knews'), 'name'=>'inverse_alignment'),
				array ( 'label'=>__('Cant read text 1','knews'), 'name'=>'cant_read_text_1'),
				array ( 'label'=>__('Cant read text link','knews'), 'name'=>'cant_read_text_link'),
				array ( 'label'=>__('Cant read text 2','knews'), 'name'=>'cant_read_text_2'),
				array ( 'label'=>__('Unsubscribe text 1','knews'), 'name'=>'unsubscribe_text_1'),
				array ( 'label'=>__('Unsubscribe text link','knews'), 'name'=>'unsubscribe_text_link'),
				array ( 'label'=>__('Unsubscribe text 2','knews'), 'name'=>'unsubscribe_text_2'),
				array ( 'label'=>__('The read more text link','knews'), 'name'=>'read_more_link'),
			);
			return $KnewsDefaultMessages;
		}

		function get_custom_text($name, $lang, $restore=false) {
			$lang = str_replace('-','_',$lang);
			$custom = get_option('knews_custom_' . $name . '_' . $lang,'');

			if ($custom == '' || $restore) {
				
				require_once (KNEWS_DIR . '/includes/mo_reader.php');

				if (!is_file(KNEWS_DIR . '/languages/knews-' . $lang . '.mo')) {
					$custom = mo_reader(KNEWS_DIR . '/languages/knews-en_US.mo', $name);
				} else {
					$custom = mo_reader(KNEWS_DIR . '/languages/knews-' . $lang . '.mo', $name);
				}
				
				$custom = str_replace('\"','"',$custom);
				update_option('knews_custom_' . $name . '_' . $lang, $custom);
			}
			return $custom;
		}
		
		function init($blog_id=0) {
			global $knewsOptions, $wpdb;
			
			if ($blog_id != 0) switch_to_blog($blog_id);
			
			define('KNEWS_USERS', $wpdb->prefix . 'knewsusers');	
			define('KNEWS_USERS_EXTRA', $wpdb->prefix . 'knewsusersextra');	
			define('KNEWS_EXTRA_FIELDS', $wpdb->prefix . 'knewsextrafields');	
			define('KNEWS_LISTS', $wpdb->prefix . 'knewslists');	
			define('KNEWS_USERS_PER_LISTS', $wpdb->prefix . 'knewsuserslists');	
			define('KNEWS_NEWSLETTERS', $wpdb->prefix . 'knewsletters');	
			define('KNEWS_NEWSLETTERS_SUBMITS_DETAILS', $wpdb->prefix . 'knewsubmitsdetails');
			define('KNEWS_STATS', $wpdb->prefix . 'knewstats');
			define('KNEWS_KEYS', $wpdb->prefix . 'knewskeys');

			define('KNEWS_NEWSLETTERS_SUBMITS', $wpdb->base_prefix . 'knewsubmits');

			define('KNEWS_DIR', dirname(__FILE__));
			
			$url = plugins_url();
			if ($blog_id != 0) $url = $this->get_right_blog_path($blog_id) . 'wp-content/plugins';
			define('KNEWS_URL', $url . '/knews');

			$this->knews_load_plugin_textdomain();

			$knewsOptions = $this->getAdminOptions();
			$this->creaSiNoExisteixDB();
			
			$this->knewsLangs = $this->getLangs();
			$this->initialized = true;
		}
		
		function get_right_blog_path($blog_id) {
			global $wpdb;

			$blog_found=array();
			
			if( is_multisite() ) {
				$query = "SELECT * FROM " . $wpdb->base_prefix . 'blogs' . " WHERE blog_id=" . $blog_id;
				$blog_found = $wpdb->get_results( $query );
			}
			
			if (count($blog_found)==0) return get_bloginfo('wpurl') . '/';
			$protocol = 'http://';
			if (substr(get_bloginfo('wpurl'),0,8)=='https://') $protocol = 'https://';
			return $protocol . $blog_found[0]->domain . $blog_found[0]->path;
		}
		
		function get_main_plugin_url() {
			
			$url = plugins_url();

			if( is_multisite() ) {
				$url = $this->get_right_blog_path($this->KNEWS_MAIN_BLOG_ID) . 'wp-content/plugins';
			}
			
			return $url;
		}

		function knews_load_plugin_textdomain() {
			global $initialized_textdomain;

			if ($initialized_textdomain) return;
			load_plugin_textdomain( 'knews', false, 'knews/languages');
			$initialized_textdomain=true;
		}
		
		function check_multilanguage_plugin($plugin='') {
			global $knewsOptions;

			if ($plugin=='') $plugin = $knewsOptions['multilanguage_knews'];

			$multilanguage_plugin = false;
			if ($plugin == 'wpml') $multilanguage_plugin = $this->have_wpml();
			if ($plugin == 'qt') $multilanguage_plugin = $this->have_qtranslate();

			return $multilanguage_plugin;
		}

		/******************************************************************************************
		/*                                 LOGICA DEL PLUGIN
		******************************************************************************************/
		function KnewsPlugin() {
			//Execucio tant a admin com a web
		}
	
		/******************************************************************************************
		/*                                  COMMON FUNCTIONS 
		******************************************************************************************/
		function get_last_cron_time () {
			
			$last_cron_time=0;
			
			if( is_multisite() ) {
				if ( get_current_blog_id() != $this->KNEWS_MAIN_BLOG_ID ) {
					switch_to_blog($this->KNEWS_MAIN_BLOG_ID);
					$last_cron_time = get_option('knews_cron_time',-1);
					restore_current_blog();
				}
			}
			
			if ($last_cron_time == 0) $last_cron_time = get_option('knews_cron_time',0);
			if ($last_cron_time == -1) $last_cron_time = 0;
			
			return $last_cron_time;
		}
		function get_mysql_date($when='now') {
			if ($when=='now') return current_time('mysql');
			return date("Y-m-d H:i:s", $when);
		}
		
		function sql2time($sqldate) {
			return mktime(substr($sqldate,11,2), substr($sqldate,14,2), substr($sqldate,17,2), substr($sqldate,5,2), substr($sqldate,8,2), substr($sqldate,0,4));
		}

		function humanize_dates ($date, $format) {
			
			if ($date == '0000-00-00 00:00:00') return '-';
			
			if ($format=='mysql') $date = $this->sql2time($date);

			//$gmt_offset = intval(get_option('gmt_offset')) * 60 * 60;
			//$date = $date + $gmt_offset;
			
			$day = 60*60*24;
			$today_start = mktime (0,0,0,date('n'),date('j'),date('Y')); // + $gmt_offset;

			$diference = $date - $today_start;

			$hour = intval(($date % $day) / (60*60));
			$minute = intval((($date % $day) - $hour * 60 * 60) / 60);
			if ($hour < 10) $hour = '0' . $hour;
			if ($minute < 10) $minute = '0' . $minute;
			$hour = $hour . ':' . $minute;
			
			$date_readable = date('d',$date) . '/' . date('m',$date) . '/' . date('Y',$date);

			if ($diference > 0) {
				//Future or today
				if ($diference < $day) return __('Today, at','knews') . ' ' . $hour;
				if ($diference < $day*2) return __('Tomorrow, at','knews') . ' ' . $hour;
				return $date_readable . ' ' . __('at','knews') . ' ' . $hour;
			} else {
				//Past
				$diference=$diference * -1;
				if ($diference < $day) return __('Yesterday, at','knews') . ' ' . $hour;
				return $date_readable . ' ' . __('at','knews') . ' ' . $hour;
			}
		}
		
		function tableExists($table){
			global $wpdb;
			return strcasecmp($wpdb->get_var("show tables like '$table'"), $table) == 0;
		}
		
		function get_safe($field, $un_set='') {
			if (!isset($_GET[$field])) return $un_set;
			return $_GET[$field];
		}

		function post_safe($field, $un_set='') {
			if (!isset($_POST[$field])) return $un_set;
			return $_POST[$field];
		}
	
		function get_user_lang($email){

			if (! $this->initialized) $this->init();

			global $wpdb;
			
			$query = "SELECT * FROM " . KNEWS_USERS . " WHERE email='" . $email . "'";
			$user_found = $wpdb->get_results( $query );
			return $user_found[0]->lang;
		}
		
		function security_for_direct_pages($kill=true) {
			$current_user = wp_get_current_user();
			if ($current_user->ID==0) {
				if ($kill) {
					die;
				} else {
					return false;
				}
			}
			return true;
		}
		function get_unique_id() {
			return substr(md5(uniqid()),-8);
		}
		function add_user_self(){

			if (! $this->initialized) $this->init();
			
			global $wpdb;
			//$name = mysql_real_escape_string($_POST['name']);
			$lang = mysql_real_escape_string($_POST['lang_user']);
			$lang_locale = mysql_real_escape_string($_POST['lang_locale_user']);
			$email = mysql_real_escape_string($_POST['email']);
			$date = $this->get_mysql_date();
			$confkey = $this->get_unique_id();
			$id_list_news = intval($_POST['user_knews_list']);

			if (!$this->validEmail($email)) {
				echo '<div class="response"><p>' . $this->get_custom_text('ajax_wrong_email', $lang_locale) . ' <a href="#" onclick="window.location.reload()">' . $this->get_custom_text('dialogs_close_button', $lang_locale) . '</a></p></div>';
				return false;
			}
			$submit_mail=true;
			
			$query = "SELECT * FROM " . KNEWS_USERS . " WHERE email='" . $email . "'";
			$user_found = $wpdb->get_results( $query );


			if (count($user_found)==0) {
				$query = "INSERT INTO " . KNEWS_USERS . " (email, lang, state, joined, confkey) VALUES ('" . $email . "','" . $lang . "', '1', '" . $date . "','" . $confkey . "');";
				$results = $wpdb->query( $query );

			} else if ($user_found[0]->state=='2') {
				$submit_mail=false;
				$results=true;

			} else {
				$query = "UPDATE " . KNEWS_USERS . " SET state='1', confkey='" . $confkey . "', lang='" . $lang . "' WHERE id=" . $user_found[0]->id;
				$results = $wpdb->query( $query );
			}
			
			if ($results) {
				if (count($user_found)==0) {

					$query = "INSERT INTO " . KNEWS_USERS_PER_LISTS . " (id_user, id_list) VALUES (LAST_INSERT_ID(), " . $id_list_news . ");";

				} else {

					$query = "SELECT * FROM " . KNEWS_USERS_PER_LISTS . " WHERE id_user=" . $user_found[0]->id . " AND id_list=" . $id_list_news;
					$subscription_found = $wpdb->get_results( $query );
					
					if (count($subscription_found)==0) {
						$query = "INSERT INTO " . KNEWS_USERS_PER_LISTS . " (id_user, id_list) VALUES (" . $user_found[0]->id . ", " . $id_list_news . ");";
					}
				}
				$results = $wpdb->query( $query );
				
				echo '<div class="response"><p>';
				
				if ($submit_mail) {
										
					if ($this->submit_confirmation ($email, $confkey, $lang_locale)) {
						echo $this->get_custom_text('ajax_subscription', $lang_locale);
					} else {
						echo $this->get_custom_text('ajax_subscription_error', $lang_locale);
					}
				} else {
					if (count($subscription_found)==0) {
						echo $this->get_custom_text('ajax_subscription_direct', $lang_locale);
					} else {
						echo $this->get_custom_text('ajax_subscription_oops', $lang_locale);
					}
				}
				
				echo '</p></div>';
			}
			
			return $results;
		}
		
		function submit_confirmation ($email, $confkey, $lang_locale) {

			global $knewsOptions;

			$mailHtml = $this->get_custom_text('email_subscription_body', $lang_locale);
			
			$url_confirm = KNEWS_URL . '/direct/knews_confirmuser.php?k=' . $confkey . '&e=' . $email;
			$mailHtml = str_replace('#url_confirm#', $url_confirm, $mailHtml);

			$mailText = str_replace('</p>', '</p>\r\n\r\n', $mailHtml);
			$mailText = str_replace('<br>', '<br>\r\n', $mailHtml);
			$mailText = str_replace('<br />', '<br />\r\n', $mailHtml);
			$mailText = strip_tags($mailText);

			$result=$this->sendMail( $email, $this->get_custom_text('email_subscription_subject', $lang_locale), $mailHtml, $mailText );
			return ($result['ok']==1);
		}

		function validEmail($email) {
			if (empty($email) || !is_email($email)) {
				return false;
			} else {
				return true;
			}
		}
		
		function localize_lang($langs_array, $lang, $not_found='en_US') {
			$lang_locale=$not_found;
			foreach ($langs_array as $search_lang) {
				if ($search_lang['language_code']==$lang) {
					if (isset($search_lang['localized_code'])) $lang_locale=$search_lang['localized_code'];
					break;
				}
			}
			return $lang_locale;
		}

		function have_wpml() {
			return (function_exists('icl_get_languages'));
		}
		
		function have_qtranslate() {
			return (function_exists( 'qtrans_init'));
		}

		/******************************************************************************************
		/*                                FUNCIONS FRONT END
		******************************************************************************************/

		function confirm_user_self() {
			
			if (! $this->initialized) $this->init();

			global $wpdb;
			
			$confkey = mysql_real_escape_string($_GET['k']);
			$email = mysql_real_escape_string($_GET['e']);
			$date = $this->get_mysql_date();
			
			if (!$this->validEmail($email)) return false;
			if ($confkey=='') return false;
			
			$query = "UPDATE ".KNEWS_USERS." SET state='2' WHERE email='" . $email . "' AND confkey='" . $confkey . "'";
			$results = $wpdb->query( $query );
			
			return $results;
		}
		
		function block_user_self() {
			
			if (! $this->initialized) $this->init();

			global $wpdb;
			
			$id_newsletter = intval($this->get_safe('n'));
			$confkey = mysql_real_escape_string($_GET['k']);
			$email = mysql_real_escape_string($_GET['e']);
			$date = $this->get_mysql_date();
			
			if (!$this->validEmail($email)) return false;
			if ($confkey=='') return false;
			
			$query = "SELECT id FROM " . KNEWS_USERS . " WHERE confkey='" . $confkey . "' AND email='" . $email . "'";
			$find_user = $wpdb->get_results( $query );
			
			if (count($find_user) != 1) return false;
	
			$query = "INSERT INTO " . KNEWS_STATS . " (what, user_id, submit_id, date) VALUES (3, " . $find_user[0]->id . ", " . $id_newsletter . ", '" . $date . "')";
			$result=$wpdb->query( $query );

			$query = "UPDATE ".KNEWS_USERS." SET state='3' WHERE id=" . $find_user[0]->id;
			$results = $wpdb->query( $query );
			
			return $results;
		}
		
		function getLangs($need_localized=false) {
			global $knewsOptions;

			if ((KNEWS_MULTILANGUAGE) && $knewsOptions['multilanguage_knews']=='wpml') {
				if (function_exists('icl_get_languages')) {
					$languages = icl_get_languages('skip_missing=0');
					if(!empty($languages)) {

						if ($need_localized) {
							foreach ($languages as $lang) {
								$lang['localized_code'] = $this->wpml_locale($lang['language_code']);
								$languages_localized[]=$lang;
							}
							$languages=$languages_localized;
						}						
						return $languages;
					}
				}
			}
			
			if ((KNEWS_MULTILANGUAGE) && $knewsOptions['multilanguage_knews']=='qt') {
				global $q_config;
				
				if (is_array($q_config)) {
					if (isset($q_config['enabled_languages'])) {
						
						$active_langs = $q_config['enabled_languages'];
						
						if (isset($q_config['language'])) {
							$q_def_lang = $q_config['language'];
						} else {
							$q_def_lang = substr(get_bloginfo('language'), 0, 2);
						}

						foreach ($active_langs as $lang) {
							
							$q_nat_lang = $lang; if (isset($q_config['language_name'][$lang])) $q_nat_lang = $q_config['language_name'][$lang];
							$q_trans_lang = $lang; if (isset($q_config['windows_locale'][$lang])) $q_trans_lang = $q_config['windows_locale'][$lang];
							$q_localized_lang = $lang; if (isset($q_config['locale'][$lang])) $q_localized_lang = $q_config['locale'][$lang];
	
							$wpml_style_langs[$lang] = array (
									'active' 			=> (($q_def_lang==$lang) ? 1 : 0),
									'native_name'		=> $q_nat_lang,
									'translated_name'	=> $q_trans_lang,
									'language_code'		=> $lang,
									'localized_code'	=> $q_localized_lang
								);
						}
						if (count($wpml_style_langs) > 0) return $wpml_style_langs;
					}
				}
			}
			
			$short_lang = substr(get_bloginfo('language'), 0, 2);
			return array (
				$short_lang => array (
					'active'=>1, 
					'native_name'=>__('Unique language','knews') . ' (' . $short_lang . ')', 
					'translated_name'=>__('Unique language','knews') . ' (' . $short_lang . ')', 
					'language_code'=>$short_lang, 
					'localized_code'=>get_bloginfo('language')
				)
			);
			
		}

		function pageLang() {
			foreach($this->knewsLangs as $l) {
				if($l['active']) break;
			}
			return $l;
		}

		function wpml_locale($lang) {
			global $wpdb;
			$default_locale = $wpdb->get_results("SELECT default_locale FROM " . $wpdb->prefix . "icl_languages WHERE code='" . $lang . "'");
			if ($default_locale) return $default_locale[0]->default_locale;
			return '';
		}
		
		function tellMeLists() {
		
			if (! $this->initialized) $this->init();
		
			global $wpdb;
			
			$active_lang=$this->pageLang();
			$lists=array();

			$query = "SELECT * FROM " . KNEWS_LISTS;

			if (is_user_logged_in()) {
				$query .= " WHERE open_registered='1'";
			} else {
				$query .= " WHERE open='1'";
			}

			$results = $wpdb->get_results( $query );

			foreach ($results as $list) {
				$valid=true;
				//Primer mirem si hem de descartar per idioma
				if ($active_lang['language_code'] != '' && KNEWS_MULTILANGUAGE) {
					if ($list->langs != '') {
						$lang_sniffer = explode(',', $list->langs);
						if (!in_array($active_lang['language_code'], $lang_sniffer) ) $valid=false;
					}
				}
				if ($valid) $lists[$list->id]=$list->name;
								
			}
			return $lists;
			
		}
		
		function printListsSelector($lists) {
			if (count($lists) > 1) {
				echo '<select name="user_knews_list" id="user_knews_list">';
				while ($list = current($lists)) {
					echo '<option value="' . key($lists) . '">' . $list . '</option>';
					next($lists);
				}
				echo '</select>';
			} else if (count($lists) == 1) {
				echo '<input type="hidden" name="user_knews_list" id="user_knews_list" value="' . key($lists) . '" />';
			} else {
				echo '<input type="hidden" name="user_knews_list" id="user_knews_list" value="-" />';			
			}
		}

		function printAddUserUrl() {
			return KNEWS_URL . '/direct/knews_adduser.php';
		}
		
		function printLangHidden() {
			global $knewsOptions;
			
			$lang = $this->pageLang();
			
			if ((KNEWS_MULTILANGUAGE) && $knewsOptions['multilanguage_knews']=='wpml') $lang['localized_code'] = $this->wpml_locale($lang['language_code']);

			echo '<input type="hidden" name="lang_user" id="lang_user" value="' . $lang['language_code'] . '" />';
			echo '<input type="hidden" name="lang_locale_user" id="lang_locale_user" value="' . $lang['localized_code'] . '" />';
		}
		
		function printAjaxScript($container) {
			?>
			<script type="text/javascript">
				jQuery(document).ready(function() {
					jQuery('<?php echo $container; ?> form').submit( function() {
						jQuery.post(jQuery(this).attr('action'), jQuery(this).serialize(), function (data) { 
							jQuery('<?php echo $container; ?>').html(data);
						});
						return false;
					});
				})
			</script>
			<?php
		}
		
		function printWidget($args) {

			global $knewsOptions;

			if (! $this->initialized) $this->init();
			
			$knews_lists = $this->tellMeLists();

			if (count($knews_lists) > 0) {

				$this->printAjaxScript('div.knews_add_user');

				$lang = $this->pageLang();

				if ((KNEWS_MULTILANGUAGE) && $knewsOptions['multilanguage_knews']=='wpml') $lang['localized_code'] = $this->wpml_locale($lang['language_code']);

				if (is_array($args)) echo $args['before_widget'] . $args['before_title'] . $this->get_custom_text('widget_title', $lang['localized_code']) . $args['after_title'];
			?>
				<div class="knews_add_user">
					<form action="<?php echo $this->printAddUserUrl(); ?>" method="post">
						<label for="email"><?php echo $this->get_custom_text('widget_label', $lang['localized_code']); ?></label>
						<input type="text" id="email" name="email" value="" />
						<?php $this->printListsSelector($knews_lists); ?>
						<?php $this->printLangHidden(); ?>
						<input type="submit" value="<?php echo $this->get_custom_text('widget_button', $lang['localized_code']); ?>" />
					</form>
				</div>
			<?php
				if (is_array($args)) echo $args['after_widget'];
			}
		}

		function register_widget(){
			wp_register_sidebar_widget('knews_sidebar_widget', 'K-news Widget', array($this, 'printWidget'));
			wp_register_widget_control('knews_sidebar_widget', 'K-news Widget ', array($this, 'control_widget'));
		}

		function control_widget(){
			echo '<a href="admin.php?page=knews_config&tab=custom">' . __('Customize widget messages','knews') . '</a>';
		}
		
		function htmlentities_corrected($str_in) {
			$list = get_html_translation_table(HTML_ENTITIES);
			unset($list['"']);
			unset($list['<']);
			unset($list['>']);
			unset($list['&']);
		
			$search = array_keys($list);
			$values = array_values($list);
		
			$search = array_map('utf8_encode', $search);
			$str_in = str_replace($search, $values, $str_in);
			
			return $str_in;
		}


		function sendMail($recipients, $theSubject, $theHtml, $theText='', $test_array='', $fp=false) {

			$test_smtp=is_array($test_array);
			
			if (!is_array($recipients)) {
				$myobject = new stdClass;
				$myobject->email = $recipients;
				$recipients = array($myobject);
			}
			
			global $knewsOptions, $wpdb;

			if ($knewsOptions['smtp_knews']=='0' && !$test_smtp) {
				
				$headers = 'From: ' . $knewsOptions['from_name_knews'] . ' <' . $knewsOptions['from_mail_knews'] . '>' . "\r\n";
				if ($theHtml != '') add_filter('wp_mail_content_type',create_function('', 'return "text/html";'));

			} else {
				
				include_once (KNEWS_DIR . '/includes/class-phpmailer.php');
				include_once (KNEWS_DIR . '/includes/class-smtp.php');
			
				$mail=new PHPMailer();
				$mail->IsSMTP();
				$mail->CharSet='UTF-8';
	
				$mail->Subject=$theSubject;
				
				if (!$test_smtp) {

					$mail->From = $knewsOptions['from_mail_knews'];
					$mail->FromName = $knewsOptions['from_name_knews'];
				
					$mail->Host = $knewsOptions['smtp_host_knews'];
					$mail->Port = $knewsOptions['smtp_port_knews'];
					$mail->Timeout = 30;
	
					if ($knewsOptions['smtp_user_knews']!='' || $knewsOptions['smtp_pass_knews'] != '') {
		
						$mail->SMTPAuth=true;
						$mail->Username = $knewsOptions['smtp_user_knews'];
						$mail->Password = $knewsOptions['smtp_pass_knews'];
						if ($knewsOptions['smtp_secure_knews'] != '') $mail->SMTPSecure = $knewsOptions['smtp_secure_knews'];
					}

				} else {

					$mail->From = $test_array['from_mail_knews'];
					$mail->FromName = $test_array['from_name_knews'];

					$mail->Host = $test_array['smtp_host_knews'];
					$mail->Port = $test_array['smtp_port_knews'];
					$mail->Timeout = 30;
	
					if ($test_array['smtp_user_knews']!='' || $test_array['smtp_pass_knews'] != '') {
		
						$mail->SMTPAuth=true;
						$mail->Username = $test_array['smtp_user_knews'];
						$mail->Password = $test_array['smtp_pass_knews'];
						if ($test_array['smtp_secure_knews'] != '') $mail->SMTPSecure = $test_array['smtp_secure_knews'];
					}
					
				}
				
				if (count($recipients) > 1) $mail->SMTPKeepAlive = true;
			}

			$submit_error=0;
			$submit_ok=0;
			$error_info=array();

			foreach ($recipients as $recipient) {
				$customHtml = $theHtml; $customText = $theText;

				if (isset($recipient->confirm)) {
					$customHtml=str_replace('#url_confirm#', $recipient->confirm, $customHtml);
					$customText=str_replace('#url_confirm#', $recipient->confirm, $customText);
				}
				if (isset($recipient->unsubscribe)) {
					$customHtml=str_replace('%unsubscribe_href%', $recipient->unsubscribe, $customHtml);
					$customText=str_replace('%unsubscribe_href%', $recipient->unsubscribe, $customText);
				}

				if (isset($recipient->cant_read)) {
					$customHtml=str_replace('%cant_read_href%', $recipient->cant_read, $customHtml);
					$customText=str_replace('%cant_read_href%', $recipient->cant_read, $customText);
				}

				if (isset($recipient->tokens)) {
					foreach ($recipient->tokens as $token) {
						$customHtml=str_replace($token['token'], $token['value'], $customHtml);
						$customText=str_replace($token['token'], $token['value'], $customText);
					}
				}

				$customHtml = str_replace('#blog_name#', get_bloginfo('name'), $customHtml);
				$customText = str_replace('#blog_name#', get_bloginfo('name'), $customText);

				if (isset($recipient->confkey)) {
					$customHtml = str_replace('%confkey%', $recipient->confkey, $customHtml);
					$customText = str_replace('%confkey%', $recipient->confkey, $customText);
				}

				$customHtml = $this->htmlentities_corrected($customHtml); $customText = $this->htmlentities_corrected($customText);

				if ($knewsOptions['smtp_knews']=='0' && !$test_smtp) {

					$message = (($theHtml!='') ? $customHtml : $customText);
					
					if (strpos($recipient->email , '@knewstest.com') === false) {
						$mail_recipient = $recipient->email;
					} else {
						$mail_recipient = get_option('admin_email');
					}

					if (wp_mail($mail_recipient, $theSubject, $message, $headers)) {
						$submit_ok++;
						$error_info[]='submit ok [wp_mail()]';
						$status_submit=1;
					} else {
						$submit_error++;
						$error_info[]='wp_mail() error';
						$status_submit=2;
					}

				} else {

					if (strpos($recipient->email , '@knewstest.com') === false) {
						$mail_recipient = $recipient->email;
					} else {
						$mail_recipient = get_option('admin_email');
					}

					$mail->AddAddress($mail_recipient);

					//if ($theHtml != '') $mail->Body=utf8_encode($customHtml);
					//if ($theText != '') $mail->AltBody=utf8_encode($customText);
					if ($theHtml != '') $mail->Body=$customHtml;
					if ($theText != '') $mail->AltBody=$customText;
					if ($theHtml != '') $mail->IsHTML(true);

					if ($mail->Send()) {
						$submit_ok++;
						$error_info[]='submit ok [smtp]';
						$status_submit=1;
					} else {
						$submit_error++;
						$error_info[]=$mail->ErrorInfo . ' [smtp]';
						$status_submit=2;
					}
						
					$mail->ClearAddresses();
					$mail->ClearAttachments();

				}

				if (count($recipients) > 1) {
					set_time_limit(25);
					echo ' ';
				}

				if (isset($recipient->unique_submit)) {
					$query = "UPDATE " . KNEWS_NEWSLETTERS_SUBMITS_DETAILS . " SET status=" . $status_submit . " WHERE id=" .$recipient->unique_submit;
					$result = $wpdb->query( $query );
				}
				
				if ($fp) {
					$hour = date('H:i:s', current_time('timestamp'));
					fwrite($fp, '  ' . $hour . ' | ' . $recipient->email . ' | ' . $error_info[count($error_info)-1] . "<br>\r\n");
				}
				
				if ($submit_error != 0) {
					for ($i = $submit_ok+1; $i < count($recipients); $i++) {
						if (isset($recipients[$i]->unique_submit)) {
							$query = "UPDATE " . KNEWS_NEWSLETTERS_SUBMITS_DETAILS . " SET status=0 WHERE id=" .$recipients[$i]->unique_submit;
							$unlock = $wpdb->query( $query );
						}
					}
					break;
				}
			}
		
			if (count($recipients) > 1 && ($knewsOptions['smtp_knews']!='0') || $test_smtp) $mail->SmtpClose();
			
			return array('ok'=>$submit_ok, 'error'=>$submit_error, 'error_info'=>$error_info);
			
		}

		function im_pro() { return false; }
		
		function read_advice() {
			global $advice;
			if ($advice !='') return $advice;
			
			$last_advice_time = get_option('knews_advice_time',0);
			$now_time = time();
			if ($now_time - $last_advice_time > 86400) {

				$response = wp_remote_get( 'http://www.knewsplugin.com/read_advice.php?v=' . KNEWS_VERSION . '&l=' . WPLANG );

			} else {
				$response = get_option('knews_advice_response', '0');
				return $response;
			}

			if( is_wp_error( $response ) ) {
				$advice='0';

			} else {
				if (isset($response['body'])) {
					$advice=$response['body'];
					if (substr($advice, 0, 7) == 'advice*') {
						if (substr($advice, 7, 1)=='0') {
							$advice='0';
						} else {
							$advice = substr($advice, 7);
						}
					} else {
						$advice = '0';
					}
				} else {
					$advice='0';
				}
			}
			//Save cache
			$advice_time = time();
			update_option('knews_advice_time', $advice_time);
			update_option('knews_advice_response', $advice);
			
			return $advice;
		}

		/******************************************************************************************
		/*                                   PANELS ADMIN
		******************************************************************************************/
		
		function KnewsAdminNews() {
			if (! $this->initialized) $this->init();
			require( KNEWS_DIR . "/admin/knews_admin_news.php");
		}
		function KnewsAdminLists() {
			if (! $this->initialized) $this->init();
			require( KNEWS_DIR . "/admin/knews_admin_lists.php");
		}
		function KnewsAdminUsers() {
			if (! $this->initialized) $this->init();
			require( KNEWS_DIR . "/admin/knews_admin_users.php");
		}
		function KnewsAdminSubmit() {
			if (! $this->initialized) $this->init();
			require( KNEWS_DIR . "/admin/knews_admin_submits.php");
		}
		function KnewsAdminImport() {
			if (! $this->initialized) $this->init();
			require( KNEWS_DIR . "/admin/knews_admin_import.php");
		}
		function KnewsAdminExport() {
			if (! $this->initialized) $this->init();
			require( KNEWS_DIR . "/admin/knews_admin_export.php");
		}
		function KnewsAdminStats() {
			if (! $this->initialized) $this->init();
			require( KNEWS_DIR . "/admin/knews_admin_stats.php");
		}
		function KnewsAdminAuto() {
			if (! $this->initialized) $this->init();
			require( KNEWS_DIR . "/admin/knews_admin_auto.php");
		}
		function KnewsAdminConfig() {
			if (! $this->initialized) $this->init();
			require( KNEWS_DIR . "/admin/knews_admin_config.php");
		}
		
		function knews_dashboard_widget(){
			include_once KNEWS_DIR . '/includes/dashboard-widget.php';
		}
		function dashboard_widget_setup(){
			if ($this->read_advice() != '0') {
				if (current_user_can('manage_options')) {
					$dashboard_widgets_order = (array)get_user_option( "meta-box-order_dashboard" );
					$all_widgets = array();
					foreach($dashboard_widgets_order as $k=>$v){
						$all_widgets = array_merge($all_widgets, explode(',', $v));
					}
					if(!in_array('knews_dash_advice', $all_widgets)){
						$install = true;
					} else {
						$install = false;
					}
					wp_add_dashboard_widget('knews_dash_advice', 'Knews Plugin Message', array($this, 'knews_dashboard_widget'), null);	
					if($install){
						$dashboard_widgets_order['side'] = 'knews_dash_advice' . ',' . @strval($dashboard_widgets_order['side']);
						$user = wp_get_current_user();
						update_user_option($user->ID, 'meta-box-order_dashboard', $dashboard_widgets_order, false);
						$dashboard_widgets_order = (array)get_user_option( "meta-box-order_dashboard" );
					}
				}
			}
		}
	}
}

//Initialize the admin panel
if (!function_exists("Knews_plugin_ap")) {

	if (class_exists("KnewsPlugin")) {
		$Knews_plugin = new KnewsPlugin();
		define('KNEWS_VERSION', '1.1.0');

		function Knews_plugin_ap() {
			global $Knews_plugin;
			if (!isset($Knews_plugin)) return;
	
			if (is_admin()) $Knews_plugin->knews_load_plugin_textdomain();
	
			add_menu_page( 'K-news', 'K-news', 'edit_posts', 'knews_news', array(&$Knews_plugin, 'KnewsAdminNews'), plugins_url() . '/knews/images/icon16.png');
			add_submenu_page( 'knews_news', __('Newsletters','knews'), __('Newsletters','knews'), 'edit_posts', 'knews_news', array(&$Knews_plugin, 'KnewsAdminNews'), '');
			add_submenu_page( 'knews_news', __('Mailing lists','knews'), __('Mailing lists','knews'), 'edit_posts', 'knews_lists', array(&$Knews_plugin, 'KnewsAdminLists'), '');
			add_submenu_page( 'knews_news', __('Subscribers','knews'), __('Subscribers','knews'), 'edit_posts', 'knews_users', array(&$Knews_plugin, 'KnewsAdminUsers'), '');
			add_submenu_page( 'knews_news', __('Submits','knews'), __('Submits','knews'), 'edit_posts', 'knews_submit', array(&$Knews_plugin, 'KnewsAdminSubmit'), '');
			add_submenu_page( 'knews_news', __('Import CSV','knews'), __('Import CSV','knews'), 'edit_posts', 'knews_import', array(&$Knews_plugin, 'KnewsAdminImport'), '');
			add_submenu_page( 'knews_news', __('Export CSV','knews'), __('Export CSV','knews'), 'edit_posts', 'knews_export', array(&$Knews_plugin, 'KnewsAdminExport'), '');
			add_submenu_page( 'knews_news', __('Stats','knews'), __('Stats','knews'), 'edit_posts', 'knews_stats', array(&$Knews_plugin, 'KnewsAdminStats'), '');
			add_submenu_page( 'knews_news', __('Configuration','knews'), __('Configuration','knews'), 'edit_posts', 'knews_config', array(&$Knews_plugin, 'KnewsAdminConfig'), '');

	        add_action('wp_dashboard_setup', array(&$Knews_plugin, 'dashboard_widget_setup'));

		}

		//WP Cron :: http://blog.slaven.net.au/2007/02/01/timing-is-everything-scheduling-in-wordpress/
		function knews_wpcron_function() {require('direct/knews_cron_do.php'); }

		function knews_more_reccurences($schedules) {
			$schedules['knewstime'] = array('interval' => 600, 'display' => 'Knews 10 minutes wpcron submit');
			return $schedules;
		}
		add_filter('cron_schedules', 'knews_more_reccurences');
		add_action( 'knews_wpcron_function_hook', 'knews_wpcron_function' );

		function knews_deactivate() {
			if (wp_next_scheduled('knews_wpcron_function_hook')) wp_clear_scheduled_hook('knews_wpcron_function_hook');
		}
		register_deactivation_hook(__FILE__, 'knews_deactivate');

		function knews_activate() {
			$look_options = get_option('KnewsAdminOptions');
			if (isset($look_options['knews_cron'])) {
				if ($look_options['knews_cron']!='cronwp') return;
			}
			if (!wp_next_scheduled('knews_wpcron_function_hook')) wp_schedule_event( time(), 'knewstime', 'knews_wpcron_function_hook' );
		}
		register_activation_hook(__FILE__, 'knews_activate');

	}

	if (isset($Knews_plugin)) {
		add_action(basename(__FILE__), array(&$Knews_plugin, 'init'));
		add_action('admin_menu', 'Knews_plugin_ap');
		add_action("widgets_init", array(&$Knews_plugin, 'register_widget'));
	}

	function knews_load_jquery() {
		if (!is_admin()) wp_enqueue_script( 'jquery' );
	}    
	add_action('init', 'knews_load_jquery');
	
	function knews_admin_enqueue() {
		if (isset($_GET['page'])) {
			if ($_GET['page']=='knews_news' || $_GET['page']=='knews_submit') {
				add_thickbox();
			}
		}
		//wp_enqueue_script('thickbox',null,array('jquery'));
		//wp_enqueue_style('thickbox.css', '/'.WPINC.'/js/thickbox/thickbox.css', null, '1.0');
	}
	add_action('admin_enqueue_scripts', 'knews_admin_enqueue');
	
	function knews_popup() {
		if (isset($_GET['subscription']) || isset($_GET['unsubscribe'])) {
			global $Knews_plugin;
			if (! $Knews_plugin->initialized) $Knews_plugin->init();
			require( KNEWS_DIR . '/includes/dialogs.php');
		}
	}
	add_action('wp_footer', 'knews_popup');
	
	function knews_admin_notice() {
	
		$div='<div style="background-color:#FFFBCC; border:#E6DB55 1px solid; color:#555555; border-radius:3px; padding:5px 10px; margin:20px 15px 10px 0; text-align:left">';
		$div_error='<div style="background-color:#FFEBE8; border:#CC0000 1px solid; color:#555555; border-radius:3px; padding:5px 10px; margin:20px 15px 10px 0; text-align:left">';
		
		global $Knews_plugin, $knewsOptions;
		if (! $Knews_plugin->initialized) $Knews_plugin->init();

		if ($Knews_plugin->knews_admin_messages != '') {
			echo $div . $Knews_plugin->knews_admin_messages . '</div>';
		} else {
			if (version_compare( KNEWS_VERSION, get_option('knews_version' )) < 0 || get_option('knews_pro') == 'yes') {
				if ($knewsOptions['update_knews'] == 'no' && version_compare( KNEWS_VERSION, get_option('knews_version' )) < 0) {
					echo $div . __('You are downgraded the version of Knews, you can lose data, please update quickly','knews');
					echo ' <a href="' . KNEWS_URL . '/direct/off_warn.php?w=update_knews&b=' . urlencode('http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']) . '" style="float:right">' . __("Don't show this message again [x]",'knews') . '</a></div>';
				} else {
					if (get_option('knews_pro') == 'yes') {
						if ($knewsOptions['update_pro'] == 'no') {
							echo $div;
							printf( __('You are downgraded to the free version of Knews, you can lose data, please update quickly! You can get the professional version %s here','knews'), '<a href="http://www.knewsplugin.com" target="_blank">');
							echo '</a>';
							echo ' <a href="' . KNEWS_URL . '/direct/off_warn.php?w=update_pro&b=' . urlencode('http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']) . '" style="float:right">' . __("Don't show this message again [x]",'knews') . '</a></div>';
						}
					}
				}
			}
		
			if (strpos($_SERVER['REQUEST_URI'],'knews_config') === false) {
				if ($knewsOptions['config_knews'] == 'no') {
					
					printf($div . __('Welcome to Knews.','knews') . ' ' . __('Please, go to %s configuration page','knews') . "</a>", 
						'<a href="' . get_admin_url() . 'admin.php?page=knews_config">');
					echo ' <a href="' . KNEWS_URL . '/direct/off_warn.php?w=config_knews&b=' . urlencode('http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']) . '" style="float:right">' . __("Don't show this message again [x]",'knews') . '</a></div>';
			
				} else {
		
					if (!$Knews_plugin->check_multilanguage_plugin() && $knewsOptions['multilanguage_knews'] != 'off' && $knewsOptions['no_warn_ml_knews'] == 'no') {
		
						printf($div_error . __('The multilanguage plugin has stopped working.','knews') . ' ' . __('Please, go to %s configuration page','knews') . "</a>", 
							'<a href="' . get_admin_url() . 'admin.php?page=knews_config">');
						echo ' <a href="' . KNEWS_URL . '/direct/off_warn.php?w=no_warn_ml_knews&b=' . urlencode('http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']) . '" style="float:right">' . __("Don't show this message again [x]",'knews') . '</a></div>';
		
					} elseif ($knewsOptions['knews_cron']=='cronjob') {
						$last_cron_time = $Knews_plugin->get_last_cron_time();
						$now_time = time();
						if ($now_time - $last_cron_time > 1000 && $last_cron_time != 0 && $knewsOptions['no_warn_cron_knews'] == 'no') {
		
							printf($div_error . __('CRON has stopped working.','knews') . ' ' . __('Please, go to %s configuration page','knews') . "</a>", 
								'<a href="' . get_admin_url() . 'admin.php?page=knews_config">');
							echo ' <a href="' . KNEWS_URL . '/direct/off_warn.php?w=no_warn_cron_knews&b=' . urlencode('http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']) . '" style="float:right">' . __("Don't show this message again [x]",'knews') . '</a></div>';
						}
					}
				}
			}
		}
	}
	add_action( 'admin_notices', 'knews_admin_notice' );
	
	function knews_plugin_form() {
		global $Knews_plugin; if (!isset($Knews_plugin)) return '';

		ob_start();
			$Knews_plugin->printWidget('');
			$result=ob_get_contents();
		ob_end_clean();

		return $result;
	}
	add_shortcode("knews_form", "knews_plugin_form");
}

?>