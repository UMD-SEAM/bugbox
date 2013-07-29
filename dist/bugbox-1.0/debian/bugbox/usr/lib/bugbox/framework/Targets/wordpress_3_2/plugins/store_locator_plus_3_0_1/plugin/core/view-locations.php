<?php
/****************************************************************************
 ** file: view-locations.php
 **
 ** Manage the view locations admin panel action.
 ***************************************************************************/

// Setup the view link
//
 $view_link="| <a href='".SLPLUS_ADMINPAGE."view-locations.php'>".
    __("Manage Locations", SLPLUS_PREFIX)."</a>"; 
 
// Save all values except a few for subsequent form processing
//
$hidden='';
foreach($_REQUEST as $key=>$val) {
	if ($key!="q" && $key!="o" && $key!="sortorder" && $key!="start" && $key!="act" && $key!='sl_tags' && $key!='sl_id') {
		$hidden.="<input type='hidden' value='$val' name='$key'>\n"; 
	}
}

// Header Text
//
print "<div class='wrap'>
            <div id='icon-edit-locations' class='icon32'><br/></div>
            <h2>".
            __('Store Locator Plus - Manage Locations', SLPLUS_PREFIX).
            "</h2>";

            
//-------------------------
// Navbar Section
//-------------------------    
print '<div id="slplus_navbar">';
print get_string_from_phpexec(SLPLUS_COREDIR.'/templates/navbar.php');
print '</div>';            

// Check Google API Key
// Not present : who cares
//
$slak=$slplus_plugin->driver_args['api_key'];
    
    // Initialize Variables
    //
    initialize_variables();  

	// If delete link is clicked
	if (isset($_GET['delete']) && ($_GET['delete']!='')) {
		$wpdb->query("DELETE FROM ".$wpdb->prefix."store_locator ".
		    "WHERE sl_id='".$_GET['delete']."'");
	}

    // Edit, any form
    //
	if ($_POST                                                  && 
	    (isset($_GET['edit']) && $_GET['edit'])                 &&
	    (!isset($_POST['act']) || (isset($_POST['act']) && ($_POST['act']!="delete"))) 
	    ) {
		$field_value_str = '';
		foreach ($_POST as $key=>$value) {
			if (ereg("\-$_GET[edit]", $key)) {
			    $slpFieldName = ereg_replace("\-$_GET[edit]", "", $key); 
			    if (!$slplus_plugin->license->packages['Pro Pack']->isenabled) {
			        if ( ($slpFieldName == 'latitude') || ($slpFieldName == 'longitude')) {
			            continue;
			        }
                }			         
				$field_value_str.="sl_".$slpFieldName."='".trim(comma($value))."', ";
				$_POST[$slpFieldName]=$value; 
			}
		}
		$field_value_str=substr($field_value_str, 0, strlen($field_value_str)-2);
		$edit=$_GET['edit']; 
		extract($_POST);
		$the_address="$address $address2, $city, $state $zip";
		
		$old_address=$wpdb->get_results("SELECT * FROM ".$wpdb->prefix."store_locator WHERE sl_id=$_GET[edit]", ARRAY_A);
		$wpdb->query("UPDATE ".$wpdb->prefix."store_locator SET $field_value_str WHERE sl_id=$_GET[edit]");
		
        if (!isset($old_address[0]['sl_address']))  { $old_address[0]['sl_address'] = '';   } 
        if (!isset($old_address[0]['sl_address2'])) { $old_address[0]['sl_address2'] = '';  } 
        if (!isset($old_address[0]['sl_city'])) 	{ $old_address[0]['sl_city'] = ''; 	    } 
        if (!isset($old_address[0]['sl_state'])) 	{ $old_address[0]['sl_state'] = ''; 	} 
        if (!isset($old_address[0]['sl_zip'])) 	    { $old_address[0]['sl_zip'] = ''; 		} 
                
        // RE-geocode if the address changed
        // or if the lat/long is not set
        //
		if (   ($the_address!=
		        $old_address[0]['sl_address'].' '.$old_address[0]['sl_address2'].', '.$old_address[0]['sl_city'].', '.
		        $old_address[0]['sl_state'].' '.$old_address[0]['sl_zip']
		        ) ||
		        ($old_address[0]['sl_latitude']=="" || $old_address[0]['sl_longitude']=="")
            	) {        
			do_geocoding($the_address,$_GET['edit']);
		}
		
		print "<script>location.replace('".ereg_replace("&edit=$_GET[edit]", "", 
                    $_SERVER['REQUEST_URI'])."');</script>";
	}
	
	// ACTION HANDLER
    //If post action is set
    //
	if (isset($_REQUEST['act'])) {

        // Delete Action	    
        if ($_REQUEST['act']=="delete") {
            if ($_POST) {extract($_POST);}
            if (isset($sl_id)) {
                if (is_array($sl_id)==1) {
                    $id_string="";
                    foreach ($sl_id as $value) {
                        $id_string.="$value,";
                    }
                    $id_string=substr($id_string, 0, strlen($id_string)-1);
                } else {
                    $id_string=$sl_id;
                }
                
                if ($id_string != '') {
                    $wpdb->query("DELETE FROM ".$wpdb->prefix."store_locator WHERE sl_id IN ($id_string)");
                }
            }
            
        // Tagging Action
        }  elseif (eregi("tag", $_REQUEST['act'])) {
            
            //adding or removing tags for specified a locations
            if ($_POST) {extract($_POST);}
            
            if (isset($sl_id)) {
                if (is_array($sl_id)) {
                    $id_string='';
                    foreach ($sl_id as $value) {
                        $id_string.="$value,";
                    }
                    $id_string=substr($id_string, 0, strlen($id_string)-1);
                } else {
                    $id_string=$sl_id;
                }
                
                // If we have some store IDs
                //
                if ($id_string != '') {
                    //adding tags
                    if ($act=="add_tag") {
                        $wpdb->query("UPDATE ".$wpdb->prefix."store_locator SET sl_tags=CONCAT(sl_tags, '".strtolower($sl_tags).", ') WHERE sl_id IN ($id_string)");
                        
                    //removing tags
                    } elseif ($act=="remove_tag") {
                        if (empty($sl_tags)) {
                            //if no tag is specified, all tags will be removed from selected locations
                            $wpdb->query("UPDATE ".$wpdb->prefix."store_locator SET sl_tags='' WHERE sl_id IN ($id_string)");
                        } else {
                            $wpdb->query("UPDATE ".$wpdb->prefix."store_locator SET sl_tags=REPLACE(sl_tags, '$sl_tags,', '') WHERE sl_id IN ($id_string)");
                        }                        
                    }
                }                    
            }          
            
        // Locations Per Page Action
        } elseif ($_REQUEST['act']=="locationsPerPage") {
            //If bulk delete is used
            update_option('sl_admin_locations_per_page', $_REQUEST['sl_admin_locations_per_page']);
            extract($_REQUEST);
            
        // Change View Action
        //
        } elseif ($_REQUEST['act']=='changeview') {
            if (get_option('sl_location_table_view') == 'Expanded') {
                update_option('sl_location_table_view', 'Normal');
            } else {
                update_option('sl_location_table_view', 'Expanded');
            }
            
        // Recode The Address
        //
        } elseif ($_REQUEST['act']=='recode') {
            if (isset($_REQUEST['sl_id'])) {
                if (!is_array($_REQUEST['sl_id'])) {
                    $theLocations = array($_REQUEST['sl_id']);
                } else {
                    $theLocations = $_REQUEST['sl_id'];
                }
                
                // Process SL_ID Array
                //
                foreach ($theLocations as $thisLocation) {
                        $address=$wpdb->get_row("SELECT * FROM ".$wpdb->prefix."store_locator WHERE sl_id=$thisLocation", ARRAY_A);
                        
                        if (!isset($address['sl_address'])) { $address['sl_address'] = '';  print 'BLANK<br/>';	} 
                        if (!isset($address['sl_address2'])){ $address['sl_address2'] = ''; } 
                        if (!isset($address['sl_city'])) 	{ $address['sl_city'] = ''; 	} 
                        if (!isset($address['sl_state'])) 	{ $address['sl_state'] = ''; 	} 
                        if (!isset($address['sl_zip'])) 	{ $address['sl_zip'] = ''; 		}
                        
                        do_geocoding("$address[sl_address] $address[sl_address2], $address[sl_city], $address[sl_state] $address[sl_zip]",$thisLocation);
                }                
            }
            
        // Create Store Page(s)
        //
        } elseif ($_REQUEST['act'] == 'createpage') {
            if (isset($_REQUEST['sl_id'])) {
                if (!is_array($_REQUEST['sl_id'])) {
                    $theLocations = array($_REQUEST['sl_id']);
                } else {
                    $theLocations = $_REQUEST['sl_id'];
                }
                foreach ($theLocations as $thisLocation) {    
                    $slpNewPostID = call_user_func(array('SLPlus_AdminUI','slpCreatePage'),$thisLocation);
                    if ($slpNewPostID >= 0) {
                        $slpNewPostURL = get_permalink($slpNewPostID);
                        $wpdb->query("UPDATE ".$wpdb->prefix."store_locator ".
                                        "SET sl_linked_postid=$slpNewPostID, ".
                                        "sl_pages_url='$slpNewPostURL' ".
                                        "WHERE sl_id=$thisLocation"
                                        );                        
                        print "<div class='updated settings-error'>" .
                                ( (isset($_REQUEST['slp_pageid']) && ($slpNewPostID != $_REQUEST['slp_pageid']))?'Created new ':'Updated ').
                                " store page #<a href='$slpNewPostURL'>$slpNewPostID</a>" .
                                " for location # $thisLocation" .
                                "</div>\n";
                    } else {
                        print "<div class='updated settings-error'>Could NOT create page" .
                                " for location # $thisLocation" .
                                "</div>\n";
                    }
                }
            }
        }
        
    }
    
    
	// Changing Updater
	//
	if (isset($_GET['changeUpdater']) && ($_GET['changeUpdater']==1)) {
		if (get_option('sl_location_updater_type')=="Tagging") {
			update_option('sl_location_updater_type', 'Multiple Fields');
			$updaterTypeText="Multiple Fields";
		} else {
			update_option('sl_location_updater_type', 'Tagging');
			$updaterTypeText="Tagging";
		}
		$_SERVER['REQUEST_URI']=ereg_replace("&changeUpdater=1", "", $_SERVER['REQUEST_URI']);
		print "<script>location.replace('".$_SERVER['REQUEST_URI']."');</script>";
	}


    //-------------------------
    // Actionbar Section
    //-------------------------    
    print '<div id="slplus_actionbar">';
    print get_string_from_phpexec(SLPLUS_COREDIR.'/templates/managelocations_actionbar.php');
    print '</div>';   

	$qry = isset($_REQUEST['q']) ? $_REQUEST['q'] : '';
	$where=($qry!='')? 
	        " WHERE ".
	        "sl_store    LIKE '%$qry%' OR ".
	        "sl_address  LIKE '%$qry%' OR ".
	        "sl_address2 LIKE '%$qry%' OR ".
	        "sl_city     LIKE '%$qry%' OR ".
	        "sl_state    LIKE '%$qry%' OR ".
	        "sl_zip      LIKE '%$qry%' OR ".
	        "sl_tags     LIKE '%$qry%' " 
	        : 
	        '' ;
    
    /* Uncoded items */
    if (isset($_REQUEST['act'])) {
        if ($_REQUEST['act'] == 'show_uncoded') {
            if ($where == '') { $where = 'WHERE '; }
            $where .= ' sl_latitude IS NULL or sl_longitude IS NULL';
        }
    }

    //for search links
    $numMembers=$wpdb->get_results(
        "SELECT sl_id FROM " . $wpdb->prefix . "store_locator $where");
    $numMembers2=count($numMembers); 
    $start=(isset($_GET['start'])&&(trim($_GET['start'])!=''))?$_GET['start']:0;
    //edit this to determine how many locations to view per page of 'Manage Locations' page
    $num_per_page=$sl_admin_locations_per_page; 
    if ($numMembers2!=0) {include(SLPLUS_COREDIR.'search-links.php');}

$opt= (isset($_GET['o']) && (trim($_GET['o']) != ''))
    ? $_GET['o'] : "sl_store";
$dir= (isset($_GET['sortorder']) && (trim($_GET['sortorder'])=='DESC')) 
    ? 'DESC' : 'ASC';

// Get the sort order and direction out of our URL
//
$slpCleanURL = str_replace("&o=$opt&sortorder=$dir", '', $_SERVER['REQUEST_URI']);    
    
// Flip the direction
//
$altdir= (($dir=='DESC') ? 'ASC':'DESC');

print "<br>
<table class='slplus widefat' cellspacing=0>
    <thead>
    <tr >
        <th colspan='1'><input type='checkbox' onclick='checkAll(this,document.forms[\"locationForm\"])' class='button'></th>
        <th colspan='1'>".__("Actions", SLPLUS_PREFIX)."</th>".
        slpCreateColumnHeader($slpCleanURL,'sl_id'      ,__('ID'       ,SLPLUS_PREFIX),$opt,$dir) .
        slpCreateColumnHeader($slpCleanURL,'sl_store'   ,__('Name'     ,SLPLUS_PREFIX),$opt,$dir) .
        slpCreateColumnHeader($slpCleanURL,'sl_address' ,__('Street'   ,SLPLUS_PREFIX),$opt,$dir) .        
        slpCreateColumnHeader($slpCleanURL,'sl_address2',__('Street2'  ,SLPLUS_PREFIX),$opt,$dir) .        
        slpCreateColumnHeader($slpCleanURL,'sl_city'    ,__('City'     ,SLPLUS_PREFIX),$opt,$dir) .        
        slpCreateColumnHeader($slpCleanURL,'sl_state'   ,__('State'    ,SLPLUS_PREFIX),$opt,$dir) .        
        slpCreateColumnHeader($slpCleanURL,'sl_zip'     ,__('Zip'      ,SLPLUS_PREFIX),$opt,$dir) .        
        slpCreateColumnHeader($slpCleanURL,'sl_tags'    ,__('Tags'     ,SLPLUS_PREFIX),$opt,$dir)                
        ;

      
// Expanded View
//
if (get_option('sl_location_table_view')!="Normal") {
    print 
        slpCreateColumnHeader($slpCleanURL,'sl_description' ,__('Description'  ,SLPLUS_PREFIX),$opt,$dir) .
        slpCreateColumnHeader($slpCleanURL,'sl_url'         ,__('URL'          ,SLPLUS_PREFIX),$opt,$dir);

    // Store Pages URLs
    //
    if ($slplus_plugin->license->packages['Store Pages']->isenabled) {            
        print slpCreateColumnHeader($slpCleanURL,
                    'sl_pages_url'   ,
                    __('Pages URL'    ,SLPLUS_PREFIX),
                    $opt,$dir
                    );
    }
        
    print 
        slpCreateColumnHeader($slpCleanURL,'sl_email'       ,__('Email'        ,SLPLUS_PREFIX),$opt,$dir) .
        slpCreateColumnHeader($slpCleanURL,'sl_hours'       ,__('Hours'        ,SLPLUS_PREFIX),$opt,$dir) .
        slpCreateColumnHeader($slpCleanURL,'sl_phone'       ,__('Phone'        ,SLPLUS_PREFIX),$opt,$dir) .
        slpCreateColumnHeader($slpCleanURL,'sl_image'       ,__('Image'        ,SLPLUS_PREFIX),$opt,$dir)
        ;    
}

print '<th>Lat</th><th>Lon</th></tr></thead>';

if ($locales=$wpdb->get_results("SELECT * FROM " . $wpdb->prefix . 
        "store_locator  $where ORDER BY $opt $dir LIMIT $start,$num_per_page", ARRAY_A)) {
		
        $bgcol = '#eee';
		foreach ($locales as $value) {
		    $locID = $value['sl_id'];
			$bgcol=($bgcol=="#eee")?"#fff":"#eee";			
			$bgcol=($value['sl_latitude']=="" || $value['sl_longitude']=="")? "salmon" : $bgcol;			
			$value=array_map("trim",$value);
			
			// EDIT MODE
			//
			if (isset($_GET['edit']) && ($locID==$_GET['edit'])) {
				print "<tr style='background-color:$bgcol'>";
	            $colspan=(get_option('sl_location_table_view')!="Normal")? 	12 : 18;	
                if ($slplus_plugin->license->packages['Store Pages']->isenabled) { $colspan++; }	            
				
                print "<td colspan='$colspan'><form name='manualAddForm' method=post>
                <a name='a".$locID."'></a>
                <table cellpadding='0' class='manual_update_table'>
                <!--thead><tr><td>".__("Type&nbsp;Address", SLPLUS_PREFIX)."</td></tr></thead-->
                <tr>
                    <td valign='top'>";
                
                execute_and_output_template('edit_location_address.php');
                
                // Store Pages URLs
                //
                if (
                    ($slplus_plugin->license->packages['Store Pages']->isenabled) &&
                    ($value['sl_pages_url'] != '')
                    ){
                    $shortSPurl = preg_replace('/^.*?store_page=/','',$value['sl_pages_url']);
                    print "<label for='store_page'>Store Page</label><a href='$value[sl_pages_url]' target='cybersprocket'>$shortSPurl</a><br/>";
                }
                
                print "<br>
                        <nobr><input type='submit' value='".__("Update", SLPLUS_PREFIX)."' class='button-primary'><input type='button' class='button' value='".__("Cancel", SLPLUS_PREFIX)."' onclick='location.href=\"".ereg_replace("&edit=$_GET[edit]", "",$_SERVER['REQUEST_URI'])."\"'></nobr>
                    </td><td>
                        <b>".__("Additional Information", SLPLUS_PREFIX)."</b><br>
                        <textarea name='description-$locID' rows='5' cols='17'>$value[sl_description]</textarea>&nbsp;<small>".__("Description", SLPLUS_PREFIX)."</small><br>
                        <input name='tags-$locID' value='$value[sl_tags]'>&nbsp;<small>".__("Tags (seperate with commas)", SLPLUS_PREFIX)."</small><br>		
                        <input name='url-$locID' value='$value[sl_url]'>&nbsp;<small>".__("URL", SLPLUS_PREFIX)."</small><br>
                        <input name='email-$locID' value='$value[sl_email]'>&nbsp;<small>".__("Email", SLPLUS_PREFIX)."</small><br>
                        <input name='hours-$locID' value='$value[sl_hours]'>&nbsp;<small>".__("Hours", SLPLUS_PREFIX)."</small><br>
                        <input name='phone-$locID' value='$value[sl_phone]'>&nbsp;<small>".__("Phone", SLPLUS_PREFIX)."</small><br>
                        <input name='image-$locID' value='$value[sl_image]'>&nbsp;<small>".__("Image URL (shown with location)", SLPLUS_PREFIX)."</small><br><br>
                    </td>
                        </tr>
                    </table>
                </form></td>
                </tr>";
                
			// DISPLAY MODE
			//
			} else {
                $value['sl_url']=(!url_test($value['sl_url']) && trim($value['sl_url'])!="")? 
                    "http://".$value['sl_url'] : 
                    $value['sl_url'] ;
                $value['sl_url']=($value['sl_url']!="")? 
                    "<a href='$value[sl_url]' target='blank'>".__("View", SLPLUS_PREFIX)."</a>" : 
                    "" ;
                $value['sl_email']=($value['sl_email']!="")? 
                    "<a href='mailto:$value[sl_email]' target='blank'>".__("Email", SLPLUS_PREFIX)."</a>" : 
                    "" ;
                $value['sl_image']=($value['sl_image']!="")? 
                    "<a href='$value[sl_image]' target='blank'>".__("View", SLPLUS_PREFIX)."</a>" : 
                    "" ;
                $value['sl_description']=($value['sl_description']!="")? 
                    "<a onclick='alert(\"".comma($value['sl_description'])."\")' href='#'>".
                    __("View", SLPLUS_PREFIX)."</a>" : 
                    "" ;
                
                print "<tr style='background-color:$bgcol'>
                <th><input type='checkbox' name='sl_id[]' value='$locID'></th>
                <th class='thnowrap'>".
                    "<a class='action_icon edit_icon' alt='".__('edit',SLPLUS_PREFIX)."' title='".__('edit',SLPLUS_PREFIX)."' 
                        href='".ereg_replace("&edit=".(isset($_GET['edit'])?$_GET['edit']:''), "",$_SERVER['REQUEST_URI']).
                    "&edit=" . $locID ."#a$locID'></a>".
                    "&nbsp;" . 
                    "<a class='action_icon delete_icon' alt='".__('delete',SLPLUS_PREFIX)."' title='".__('delete',SLPLUS_PREFIX)."' 
                        href='".$_SERVER['REQUEST_URI']."&delete=$locID' " .
                        "onclick=\"confirmClick('".sprintf(__('Delete %s?',SLPLUS_PREFIX),$value['sl_store'])."', this.href); return false;\"></a>";

                // Store Pages Active?
                // Show the create page button
                //
                if ($slplus_plugin->license->packages['Store Pages']->isenabled) {
                    call_user_func_array(array('SLPlus_AdminUI','slpRenderCreatePageButton'),array($locID,$value['sl_linked_postid']));
                }

        print "</th>
                <th>$locID</th>
                <td>$value[sl_store]</td>
                <td>$value[sl_address]</td>
                <td>$value[sl_address2]</td>
                <td>$value[sl_city]</td>
                <td>$value[sl_state]</td>
                <td>$value[sl_zip]</td>
                <td>$value[sl_tags]</td>";
                
                if (get_option('sl_location_table_view')!="Normal") {
                    print "<td>$value[sl_description]</td>
                            <td>$value[sl_url]</td>
                            ";
                    // Store Pages URLs
                    //
                    if ($slplus_plugin->license->packages['Store Pages']->isenabled) {
                        $shortSPurl = preg_replace('/^.*?store_page=/','',$value['sl_pages_url']);
                        print "<td><a href='$value[sl_pages_url]' target='cybersprocket'>$shortSPurl</a></td>";
                    }                    
                    print "<td>$value[sl_email]</td>
                            <td>$value[sl_hours]</td>
                            <td>$value[sl_phone]</td>
                            <td>$value[sl_image]</td>";
                }                
                print "<td>".$value['sl_latitude']."</td>";
                print "<td>".$value['sl_longitude']."</td>";
                print "</tr>";
			}
		}
} else {
		$notice=( isset($_GET['q']) && ($_GET['q']!="") )? 
                __("No Locations Showing for this Search of ", SLPLUS_PREFIX).
                    "<b>\"$_GET[q]\"</b>. $view_link" : 
                __("No Locations Currently in Database", SLPLUS_PREFIX);
		print "<tr><td colspan='5'>$notice | <a href='admin.php?page=$sl_dir/core/add-locations.php'>".
            __("Add Locations", SLPLUS_PREFIX)."</a></td></tr>";
	}
	print "</table>
	<input name='act' type='hidden'><br>";
if ($numMembers2!=0) {include(SLPLUS_COREDIR.'/search-links.php');}

print "</form>";
	

print "</div>";

/*****************************
 * function: url_test()
 *
 */
function url_test($url) {
	return (strtolower(substr($url,0,7))=="http://");
}

/*****************************
* function: slpCreateColumnHeader()
*
* Create the column headers for sorting the table.
*
*/
function slpCreateColumnHeader($theURL,$fldID='sl_store',$fldLabel='ID',$opt='sl_store',$dir='ASC') {
    if ($opt == $fldID) {
        $curDIR = (($dir=='ASC')?'DESC':'ASC');
    } else {
        $curDIR = $dir;
    }
    return "<th><a href='$theURL&o=$fldID&sortorder=$curDIR'>$fldLabel</a></th>";
}
