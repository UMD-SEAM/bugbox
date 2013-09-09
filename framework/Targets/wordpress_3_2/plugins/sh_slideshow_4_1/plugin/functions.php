<?php
function sh_manage_slideshow(){
		global $wpdb;
?>
<script>
	function check_del(slideshow){
		return confirm('Are you sure to delete '+slideshow);
	}
</script>
	<h3>Slideshows <a class="button add-new-h2" href="#add">Add New</a></h3>
    <table class="widefat">
    	<thead>
        	<tr>
            	<th>ID</th>
                <th>Slideshow</th>
                <th>Shortcode</th>
                <th>PHP Code (Use only if editing theme file)</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
	<?php
		if($_REQUEST['submit']):
			$sql = 'insert into sh_slideshow (name,transition,timeout,pause,auto,effect,random,target,width,height,bgcolor,nav_transition,navigation,nav_type,nav_pos,css,next_text,prev_text,nav_spacing,nav_top,nav_left,nav_link_color,nav_link_hover_color,nav_link_underline) values ("'.$_REQUEST['sname'].'",1,5,0,1,"fade",0,"_self",640,480,"transparent",0,1,1,1,1,"Next","Prev",10,10,0,"#000","#000",0)';
			$result = $wpdb->query($sql);
			if($result):
				echo '<div id="message" class="updated fade">Added</div>';
			else:
				echo '<div id="message" class="error fade">Error appear.</div>';
			endif;
		endif;
		
		require_once('pager.php');
		$pagesize = 20;
		$linksize = 5;
		$page = new page();
		$page->sql = 'select id,name from sh_slideshow order by id ASC';
		$page->pagesize = $pagesize;
		$page->linksize = $linksize;
		$page->init();
		$start = $page->start;
		$current_page = $page->current_page;
		$url = $page->get_page_link($current_page);
		$slideshows = $wpdb->get_results($page->sql.' limit '.$start.','.$pagesize);
		if (empty($slideshows)):
			echo '<tr><td colspan="4">No Slideshows found.</td><tr>';
		else:
		foreach($slideshows as $slideshow):
	?>
        	<tr>
            	<td><?php echo $slideshow->id; ?></td>
                <td><?php echo $slideshow->name; ?></td>
                <td>[shminislideshow id="<?php echo $slideshow->id; ?>"]</td>
                <td>&lt;?php shminislideshow(<?php echo $slideshow->id; ?>); ?&gt;</td>
                <td><a href="<?php echo $url; ?>&action=settings&id=<?php echo $slideshow->id; ?>" title="Settings">Settings</a> / <a href="<?php echo $url; ?>&action=slides&id=<?php echo $slideshow->id; ?>" title="Slides">Slides</a> / <a href="<?php echo $url; ?>&action=delete&id=<?php echo $slideshow->id; ?>" title="Delete" onclick="return check_del('<?php echo $slideshow->name; ?>');">Delete</a></td>
            </tr>
	<?php
		endforeach;
		endif;
	?>
        </tbody>
    </table>
    <div class="sh_nav"><?php $page->show_pager(); ?></div>
    <h3 id="add">Add new mini slideshow</h3>
    <form method="post" action="?page=sh-slideshow/mini-slideshow.php">
    	<table class="form-table">
        	<tbody>
            	<tr>
                	<th scope="row"><label for="sname">Name</label></th>
                    <td><input type="text" name="sname" id="sname" class="regular-text" /></td>
                </tr>
            </tbody>
        </table>
        <p class="submit">
        	<input type="submit" name="submit" value="Add new" />
        </p>
    </form>
<?php
}
// Settings
function shslideshow_settings($id){
	$effects = array('fade','scrollUp','scrollDown','scrollLeft','scrollRight','scrollHorz','scrollVert','shuffle','blindX','blindY','blindZ','cover','curtainX','curtainY','fadeZoom','growX','growY','slideX','slideY','toss','turnUp','turnDown','turnLeft','turnRight','uncover','wipe','zoom');
	global $wpdb;
	if($_REQUEST['submit']):
		$es = $_REQUEST['effect'];
		$i = 0;
		foreach($es as $effect):
			if($i):
				$e .= ','.$effect;
			else:
				$e .= $effect;
			endif;
			$i++;
		endforeach;
		$sql = 'update sh_slideshow set width='.$_REQUEST['width'].', height='.$_REQUEST['height'].', bgcolor="'.$_REQUEST['bgcolor'].'", transition='.$_REQUEST['transition'].', timeout='.$_REQUEST['timeout'].', pause='.$_REQUEST['pause'].', auto='.$_REQUEST['auto'].', target="'.$_REQUEST['target'].'", effect="'.$e.'", random='.$_REQUEST['random'].', nav_transition='.$_REQUEST['nav_transition'].', navigation='.$_REQUEST['navigation'].', nav_type='.$_REQUEST['nav_type'].', nav_pos='.$_REQUEST['nav_pos'].', css='.$_REQUEST['css'].', next_text="'.$_REQUEST['next_text'].'", prev_text="'.$_REQUEST['prev_text'].'", nav_spacing='.$_REQUEST['nav_spacing'].', nav_top='.$_REQUEST['nav_top'].', nav_left='.$_REQUEST['nav_left'].', nav_link_color="'.$_REQUEST['nav_link_color'].'", nav_link_hover_color="'.$_REQUEST['nav_link_hover_color'].'", nav_link_underline='.$_REQUEST['nav_link_underline'].', width='.$_REQUEST['width'].' where id='.$_REQUEST['sid'];
		if($wpdb->query($sql)):
			echo '<div id="message" class="updated fade">Successfully updated </div>';
		else:
			echo '<div id="message" class="error fade">Error appear</div>';
		endif;
	endif;
	$sql = 'select * from sh_slideshow where id = '.$id;
	$options = $wpdb->get_results($sql);
	foreach($options as $option):
	$es = explode(',',$option->effect);
?>
	<form method="post">
    	<input type="hidden" name="sid" value="<?php echo $id; ?>" />
        <h2>Settings for <?php echo $option->name; ?> <a class="button add-new-h2" href="?page=sh-slideshow/mini-slideshow.php">Go Back</a></h2>
    	<h3>Common Settings</h3>
    	<table class="form-table">
        	<tbody>
            	<tr>
                	<th scope="row"><label for="width">Width</label></th>
                    <td><input type="text" id="width" name="width" value="<?php echo $option->width; ?>" class="small-text" /> px</td>
                </tr>
                <tr>
                	<th scope="row"><label for="height">Height</label></th>
                    <td><input type="text" id="height" name="height" value="<?php echo $option->height; ?>" class="small-text" /> px</td>
                </tr>
                <tr>
                	<th scope="row"><label for="bgcolor">Background color</label></th>
                    <td><input type="color" id="bgcolor" name="bgcolor" value="<?php echo get_option('sh_ss_bgcolor'); ?>" data-hex="true" class="medium-text" /></td>
                </tr>
            </tbody>
        </table>
        <h3>Effect Settings</h3>
    	<table class="form-table">
        	<tbody>
            	<tr>
                	<th scope="row"><label for="transition">Transition Speed</label></th>
                    <td><input type="text" id="transition" name="transition" value="<?php echo $option->transition; ?>" class="small-text" /> Seconds</td>
                </tr>
                <tr>
                	<th scope="row"><label for="timeout">Stop time</label></th>
                    <td><input type="text" id="timeout" name="timeout" value="<?php echo $option->timeout; ?>" class="small-text" /> Seconds</td>
                </tr>
                <tr>
                	<th scope="row"><label for="pause">Stop when mouseover</label></th>
                    <td>
                    	<select name='pause' id='pause' class='postform' >
                        	<option value="0" <?php if($option->pause==0){ echo 'selected'; } ?>>No</option>
                            <option value="1" <?php if($option->pause==1){ echo 'selected'; } ?>>Yes</option>
                        </select>
                    </td>
                </tr>
                <tr>
                	<th scope="row"><label for="auto">Animation</label></th>
                    <td>
                    	<select name='auto' id='auto' class='postform' >
                        	<option value="0" <?php if($option->auto==0){ echo 'selected'; } ?>>Manually</option>
                            <option value="1" <?php if($option->auto==1){ echo 'selected'; } ?>>Loop Continuously</option>
                            <option value="2" <?php if($option->auto==2){ echo 'selected'; } ?>>Animate Once</option>
                            <option value="3" <?php if($option->auto==3){ echo 'selected'; } ?>>Animate Once (return to first slide)</option>
                        </select>
                    </td>
                </tr>
                <tr>
                	<th scope="row"><label for="target">Link Target</label></th>
                    <td>
                    	<select name='target' id='target' class='postform' >
                        	<option value="_blank" <?php if($option->target=='_blank'){ echo 'selected'; } ?>>Open link in new window</option>
                            <option value="_self" <?php if($option->target=='_self'){ echo 'selected'; } ?>>Open link in the same window</option>
                        </select>
                    </td>
                </tr>
                <tr valign="top">
                	<th scope="row"><label for="effect">Effects</label></th>
                    <td>
                    	<table>
						<?php for($i=0;$i<count($effects);$i++): ?>
                        	<?php if(($i%6) == 0): ?>
                            <tr>
                            <?php endif; ?>
                            	<td><input type="checkbox" name="effect[]" value="<?php echo $effects[$i]; ?>" 
								<?php 
									if(is_array($es)):
										if(in_array($effects[$i],$es)):
											echo 'checked="checked"';
										endif;
									else:
										if($effects[$i] == $es):
											echo 'checked="checked"';
										endif;
									endif;
								?> /> <?php echo $effects[$i]; ?></td>
                            <?php if((($i+1)%6) == 0): ?>
                            </tr>
                            <?php endif; ?>
                        <?php endfor; ?>
                    	</table>
                    </td>
                </tr>
                <tr>
                	<th scope="row"><label for="random">Random Effects</label></th>
                    <td>
                    	<select name='random' id='random' class='postform' >
                        	<option value="0" <?php if($option->random==0){ echo 'selected'; } ?>>No</option>
                            <option value="1" <?php if($option->random==1){ echo 'selected'; } ?>>Yes</option>
                        </select>
                        <span class="description">(Need to select more than one effect and not applicable to shuffle)</span>
                    </td>
                </tr>
       		</tbody>
    	</table>
        <h3>Navigation Settings</h3>
    	<table class="form-table">
        	<tbody>
            	<tr>
                	<th scope="row"><label for="nav_transition">Navigation Transition Speed</label></th>
                    <td><input type="text" id="nav_transition" name="nav_transition" value="<?php echo $option->nav_transition; ?>" class="small-text" /> Seconds <span class="description">(force fast transitions when triggered manually. 0 for disable.)</span></td>
                </tr>
                <tr>
                	<th scope="row"><label for="navigation">Display Navigation</label></th>
                    <td>
                    	<select name='navigation' id='navigation' class='postform' >
                        	<option value="0" <?php if($option->navigation==0){ echo 'selected'; } ?>>No</option>
                            <option value="1" <?php if($option->navigation==1){ echo 'selected'; } ?>>Yes</option>
                        </select>
                    </td>
                </tr>
                <tr>
                	<th scope="row"><label for="nav_type">Navigation Type</label></th>
                    <td>
                    	<select name='nav_type' id='nav_type' class='postform' >
                        	<option value="1" <?php if($option->nav_type==1){ echo 'selected'; } ?>>Slide Numbers</option>
                            <option value="2" <?php if($option->nav_type==2){ echo 'selected'; } ?>>Prev-Next</option>
                        </select>
                    </td>
                </tr>
                <tr>
                	<th scope="row"><label for="nav_pos">Navigation Position</label></th>
                    <td>
                    	<select name='nav_pos' id='nav_pos' class='postform' >
                        	<option value="0" <?php if($option->nav_pos==0){ echo 'selected'; } ?>>Outside</option>
                            <option value="1" <?php if($option->nav_pos==1){ echo 'selected'; } ?>>Inside</option>
                        </select>
                    </td>
                </tr>
			</tbody>
		</table>
        <h3>Style Settings</h3>
    	<table class="form-table">
        	<tbody>
            	<tr valign="top">
					<th scope="row"><label for="css">Add slideshow CSS</label></th>
					<td>
						<select id="css" name="css">
							<option value="1" <?php if($option->css){ echo 'selected'; } ?>>Yes (the plugin will add CSS)</option>
							<option value="0" <?php if(!$option->css){ echo 'selected'; } ?>>No (you must add CSS to your theme)</option>
						</select>
					</td>
				</tr>
                <tr valign="top">
					<th scope="row"><label for="next_text">Navigation Next Text</label></th>
					<td><input type="text" id="next_text" name="next_text" class="medium-text" value="<?php echo $option->next_text; ?>"></td>
				</tr>
                <tr valign="top">
					<th scope="row"><label for="prev_text">Navigation Prev Text</label></th>
					<td><input type="text" id="prev_text" name="prev_text" class="medium-text" value="<?php echo $option->prev_text; ?>"></td>
				</tr>
                <tr valign="top">
					<th scope="row"><label for="nav_spacing">Navigation Spacing</label></th>
					<td><input type="text" id="nav_spacing" name="nav_spacing" class="medium-text" value="<?php echo $option->nav_spacing; ?>"> px</td>
				</tr>
                <tr valign="top">
					<th scope="row"><label for="nav_top">Navigation From top</label></th>
					<td><input type="text" id="nav_top" name="nav_top" class="medium-text" value="<?php echo $option->nav_top; ?>"> px</td>
				</tr>
                <tr valign="top">
					<th scope="row"><label for="nav_left">Navigation From Left</label></th>
					<td><input type="text" id="nav_left" name="nav_left" class="medium-text" value="<?php echo $option->nav_left; ?>"> px</td>
				</tr>
                <tr valign="top">
					<th scope="row"><label for="nav_link_color">Navigation Link</label></th>
					<td><input type="color" id="nav_link_color" name="nav_link_color" class="medium-text" data-hex="true" value="<?php echo $option->nav_link_color; ?>"></td>
				</tr>
                <tr valign="top">
					<th scope="row"><label for="nav_link_hover_color">Navigation Link hover</label></th>
					<td><input type="color" id="nav_link_hover_color" name="nav_link_hover_color" class="medium-text" data-hex="true" value="<?php echo $option->nav_link_hover_color; ?>"></td>
				</tr>
                <tr valign="top">
					<th scope="row"><label for="nav_link_underline">Navigation Link Underline</label></th>
					<td>
                        <select name="nav_link_underline" id="nav_link_underline">
                            <option value="1" <?php if($option->nav_link_underline){ echo 'selected'; } ?>>Underline</option>
                            <option value="0" <?php if(!$option->nav_link_underline){ echo 'selected'; } ?>>None</option>
                        </select>
					</td>
				</tr>
            </tbody>
		</table>
        <p class="submit">
        	<input type="submit" name="submit" value="Update" />
        </p>
        <h3>CSS Style</h3>
        <pre style="border:1px solid #333; padding:3px; background-color:#FFF; width:680px; overflow:auto;">&lt;style type="text/css"&gt;
div#shslideshow_<?php echo $id; ?>{
	width:(WIDTH)px;
	background-color:(BACKGROUND COLOR);
	margin:auto;
}
div#shslideshow_<?php echo $id; ?> div.slides{
	position:relative;
	width:100%;
	height:(HEIGHT)px;
	z-index:1;
}
div#shslideshow_<?php echo $id; ?> div.slides img{
	width:(WIDTH)px;
	height:(HEIGHT)px;
}
div#shslideshow_nav_<?php echo $id; ?>{
	margin-left:(NAVIGATION FROM LEFT)px;
}
div#shslideshow_nav_pre_<?php echo $id; ?>,div#shslideshow_nav_next_<?php echo $id; ?>{
	display:block;
	float:left;
}
div#shslideshow_nav_pre_<?php echo $id; ?>:hover,div#shslideshow_nav_next_<?php echo $id; ?>:hover{
	cursor:pointer;
}
div#shslideshow_nav_<?php echo $id; ?> a,div#shslideshow_nav_pre_<?php echo $id; ?>,div#shslideshow_nav_next_<?php echo $id; ?>{
	margin-right: (SPACING BETWEEN EACH NAVIGATION)px;
	color:(NAVIGATION LINK COLOR);
}
div#shslideshow_nav_<?php echo $id; ?> a:hover,div#shslideshow_nav_<?php echo $id; ?> a.activeSlide,div#shslideshow_nav_pre_<?php echo $id; ?>:hover,div#shslideshow_nav_next_<?php echo $id; ?>:hover{
	color:(NAVIGATION HOVER COLOR);
}
/* Only if navigation is inside the slideshow */
div#shslideshow_nav_<?php echo $id; ?>{
	position:absolute;
	margin-top:(NAVIGATION FROM TOP)px;
	z-index:5;
}
/* ------------------------------------------ */
	
/* Navigation with underline */
div#shslideshow_nav_<?php echo $id; ?> a,div#shslideshow_nav_pre_<?php echo $id; ?>,div#shslideshow_nav_next_<?php echo $id; ?>{
	text-decoration:underline;
}
/* ------------------------- */
	
/* Navigation without underline */
div#shslideshow_nav_<?php echo $id; ?> a,div#shslideshow_nav_pre_<?php echo $id; ?>,div#shslideshow_nav_next_<?php echo $id; ?>{
	text-decoration:none;
}
/* ---------------------------- */
&lt;/style&gt;</pre>
    </div>
    </form>
<?php
    endforeach;
}

// Slides
function shslideshow_slides($id){
	global $wpdb;
	if($_REQUEST['submit']):
		$slides = $_REQUEST['slide'];
		$i = 1;
		$wpdb->query('delete from sh_slides where slideshow = '.$_REQUEST['sid']);
		foreach($slides as $slide):
			$result = $wpdb->query('insert into sh_slides (slideshow,slide,weight,link_url,custom_url) values ('.$_REQUEST['sid'].',"'.$slide.'",'.$i.',"'.$_REQUEST['slide_link'][$i-1].'","'.$_REQUEST['custom_url'][$i-1].'")');
			$i++;
		endforeach;
		if($result):
			echo '<div id="message" class="updated fade">Successfully updated</div>';
		else:
			echo '<div id="message" class="error fade">Error appear</div>';
		endif;
	endif;
	$slideshow = $wpdb->get_row('select name from sh_slideshow where id='.$id);
	$sql = 'select * from sh_slides where slideshow = '.$id.' order by weight';
	$slides = $wpdb->get_results($sql);
?>
<script>
jQuery(document).ready(function(){
	jQuery('#shslides').sortable({
		cursor:'crosshair',
		items: 'tr',
		forcePlaceholderSize: true
	});
});
</script>
	<form method="post">
    	<input type="hidden" name="sid" value="<?php echo $id; ?>" />
    	<h3><?php echo $slideshow->name; ?>'s slides <a id="add_sh_slide" class="button add-new-h2">Add New Slide</a> <a class="button add-new-h2" href="?page=sh-slideshow/mini-slideshow.php">Go Back</a></h3>
        <table class="form-table" id="shslides">
        	<tbody id="slides">
		<?php if(empty($slides)): ?>
            	<tr>
                    <th valign="top" scope="row"><span class="sort-icon">#1</span></th>
                <td valign="top"><div style="width:355px;"><input type="text" name="slide[]" value="" class="shslideshow_slide regular-text" /><input type="button" class="shslideshow_upload" value="Browse"></div></td>
                    <?php
						$pages = get_pages();
						$posts = get_posts('numberposts=-1');
						global $page;
						global $post;
					?>
                  <td valign="top">
                        <select name="slide_link[]" class="slide_link postform">
                            <option value="0"  <?php if(!$slide->link_url){ echo 'selected'; } ?>>No Link</option>
                            <option value="manual"  <?php if($slide->link_url=='manual'){ echo 'selected'; } ?>>Manually</option>
                        <?php foreach($pages as $page): ?>
                            <option value="<?php echo $page->ID; ?>" <?php if($slide->link_url==$page->ID){ echo 'selected'; } ?>>Page: <?php echo $page->post_title; ?></option>
                        <?php endforeach; ?>
                        <?php foreach($posts as $post): ?>
                            <option value="<?php echo $post->ID; ?>" <?php if($slide->link_url==$post->ID){ echo 'selected'; } ?>>Post: <?php echo $post->post_title; ?></option>
                        <?php endforeach; ?>
                        <?php
                            if(get_option('sh_ss_custom')!=''):
                                $customs = explode(',',get_option('sh_ss_custom'));
                                foreach($customs as $custom):
                                    $custom_posts = explode('|',$custom);
                                    $name = $custom_posts[0];
                                    $slug = $custom_posts[1];
                                    query_posts('post_type='.$slug);
                                    while(have_posts()): the_post();
                        ?>
                            <option value="<?php echo $post->ID; ?>" <?php if($slide->link_url==$post->ID){ echo 'selected'; } ?>><?php echo $name; ?>: <?php echo the_title(); ?></option>
                        <?php
                                    endwhile;
                                    wp_reset_query();
                                endforeach;
                            endif;
                        ?>
                        </select>
                        <input type="text" name="custom_url[]" class="manual_link regular-text" value="<?php echo $slide->custom_url; ?>">
                    </td>
                </tr>
     	<?php
        	else:
				foreach($slides as $slide):
		?>
                <tr slideid="<?php echo $slide->id; ?>">
                    <th valign="top" scope="row"><span class="sort-icon">#<?php echo $slide->weight; ?></span></th>
                <td valign="top"><div style="width:355px;"><input type="text" name="slide[]" value="<?php echo $slide->slide; ?>" class="shslideshow_slide regular-text" /><input type="button" class="shslideshow_upload" value="Browse"></div></td>
                    <?php
						$pages = get_pages();
						$posts = get_posts('numberposts=-1');
						global $page;
						global $post;
					?>
               	  <td valign="top">
                        <select name="slide_link[]" class="slide_link postform">
                            <option value="0"  <?php if(!$slide->link_url){ echo 'selected'; } ?>>No Link</option>
                            <option value="manual"  <?php if($slide->link_url=='manual'){ echo 'selected'; } ?>>Manually</option>
                        <?php foreach($pages as $page): ?>
                            <option value="<?php echo $page->ID; ?>" <?php if($slide->link_url==$page->ID){ echo 'selected'; } ?>>Page: <?php echo $page->post_title; ?></option>
                        <?php endforeach; ?>
                        <?php foreach($posts as $post): ?>
                            <option value="<?php echo $post->ID; ?>" <?php if($slide->link_url==$post->ID){ echo 'selected'; } ?>>Post: <?php echo $post->post_title; ?></option>
                        <?php endforeach; ?>
                        <?php
                            if(get_option('sh_ss_custom')!=''):
                                $customs = explode(',',get_option('sh_ss_custom'));
                                foreach($customs as $custom):
                                    $custom_posts = explode('|',$custom);
                                    $name = $custom_posts[0];
                                    $slug = $custom_posts[1];
                                    query_posts('post_type='.$slug);
                                    while(have_posts()): the_post();
                        ?>
                            <option value="<?php echo $post->ID; ?>" <?php if($slide->link_url==$post->ID){ echo 'selected'; } ?>><?php echo $name; ?>: <?php echo the_title(); ?></option>
                        <?php
                                    endwhile;
                                    wp_reset_query();
                                endforeach;
                            endif;
                        ?>
                        </select>
                        <input type="text" name="custom_url[]" class="manual_link regular-text" value="<?php echo $slide->custom_url; ?>">
                        <span class="del_sh_slide" onclick="del_slide(<?php echo $slide->id; ?>,<?php echo $slide->weight; ?>)">Delete</span>
                    </td>
            	</tr>
     	<?php
				endforeach;
			endif;
		?>
            </tbody>
        </table>
        
        <p class="submit">
        	<input type="submit" name="submit" value="Update" />
        </p>
        
	</form>
<?php
}

// Delete
function shslideshow_delete($id){
	global $wpdb;
	$sql = 'delete from sh_slideshow where id = '.$id;
	$result = $wpdb->query($sql);
	$sql = 'delete from sh_slides where slideshow = '.$id;
	$wpdb->query($sql);
	return $result;
}
?>