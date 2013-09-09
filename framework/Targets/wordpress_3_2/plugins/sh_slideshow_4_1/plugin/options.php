<?php
	$effects = array('fade','scrollUp','scrollDown','scrollLeft','scrollRight','scrollHorz','scrollVert','shuffle','blindX','blindY','blindZ','cover','curtainX','curtainY','fadeZoom','growX','growY','slideX','slideY','toss','turnUp','turnDown','turnLeft','turnRight','uncover','wipe','zoom');
?>
<!-- Add ColorPicker Script -->
<script language="javascript">
	var path = '<?php echo WP_PLUGIN_URL; ?>/sh-slideshow/';
</script>
<script type="text/javascript" src="<?php echo WP_PLUGIN_URL; ?>/sh-slideshow/mColorPicker.js" charset="UTF-8"></script>
<script type="text/javascript" src="<?php echo WP_PLUGIN_URL; ?>/sh-slideshow/myscript.js" charset="UTF-8"></script>
<div class="wrap">
    <div id="icon-options-general" class="icon32"><br></div>
    <h2>SH Slideshow Options</h2>
    <div style="float:right">
    	<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
            <input type="hidden" name="cmd" value="_donations" />
            <input type="hidden" name="business" value="samhoamt@gmail.com" />
            <input type="hidden" name="item_name" value="SH Slideshow" />
            <input type="hidden" name="currency_code" value="USD" />
            <input type="image" src="<?php echo WP_PLUGIN_URL; ?>/sh-slideshow/donate_btn.gif" name="submit" alt="Make payments with payPal - it's fast, free and secure!" />
        </form>
    </div>
    <?php
		if($_REQUEST['submit']){
			// Common Settings
			update_option('sh_ss_width',$_REQUEST['width']);
			update_option('sh_ss_height',$_REQUEST['height']);
			update_option('sh_ss_bgcolor',$_REQUEST['bgcolor']);
			
			// Effect Settings
			update_option('sh_ss_transition',$_REQUEST['transition']);
			update_option('sh_ss_timeout',$_REQUEST['timeout']);
			update_option('sh_ss_pause',$_REQUEST['pause']);
			update_option('sh_ss_atuo',$_REQUEST['auto']);
			update_option('sh_ss_target',$_REQUEST['target']);
			update_option('sh_ss_effect',$_REQUEST['effect']);
			update_option('sh_ss_random',$_REQUEST['random']);
			
			// Navigation Settings
			update_option('sh_ss_nav_transition',$_REQUEST['nav_transition']);
			update_option('sh_ss_navigation',$_REQUEST['navigation']);
			update_option('sh_ss_navtype',$_REQUEST['navtype']);
			update_option('sh_ss_navpos',$_REQUEST['navpos']);
			
			// Slideshow Settings
			update_option('sh_ss_custom',$_REQUEST['custom']);
			update_option('sh_ss_slideno',$_REQUEST['slides']);
			update_option('sh_ss_recent_posts',$_REQUEST['recent']);
			
			// Slides
			$slides = $_REQUEST['slide'];
			$slide_links = $_REQUEST['slide_link'];
			$outside_links = $_REQUEST['outside_link'];
			for($i=0;$i<count($slides);$i++):
				update_option('sh_ss_slide'.($i+1).'_img',$slides[$i]);
				update_option('sh_ss_slide'.($i+1),$slide_links[$i]);
				update_option('sh_ss_link'.($i+1),$outside_links[$i]);
			endfor;
			
			// Message
			echo '<div><p align="center" style="color:red;">Successfully updated!</p></div>';
		}
	?>
    <form method="post">
    	<h3>Common Settings</h3>
        <table class="form-table">
        	<tbody>
            	<tr valign="top">
                	<th scope="row"><label for="width">Width</label></th>
                    <td><input type="text" id="width" name="width" value="<?php echo get_option('sh_ss_width'); ?>" class="small-text" /> px</td>
                </tr>
                <tr valign="top">
                	<th scope="row"><label for="height">Height</label></th>
                    <td><input type="text" id="height" name="height" value="<?php echo get_option('sh_ss_height'); ?>" class="small-text" /> px</td>
                </tr>
                <tr valign="top">
                	<th scope="row"><label for="bgcolor">Background color</label></th>
                    <td><input type="color" id="bgcolor" name="bgcolor" value="<?php echo get_option('sh_ss_bgcolor'); ?>" data-hex="true" class="medium-text" /></td>
                </tr>
            </tbody>
        </table>
		<h3>Effect Settings</h3>
    	<table class="form-table">
        	<tbody>
            	<tr valign="top">
                	<th scope="row"><label for="transition">Transition Speed</label></th>
                    <td><input type="text" id="transition" name="transition" value="<?php echo get_option('sh_ss_transition'); ?>" class="small-text" /> Seconds</td>
                </tr>
                <tr valign="top">
                	<th scope="row"><label for="timeout">Stop time</label></th>
                    <td><input type="text" id="timeout" name="timeout" value="<?php echo get_option('sh_ss_timeout'); ?>" class="small-text" /> Seconds</td>
                </tr>
                <tr valign="top">
                	<th scope="row"><label for="pause">Stop when mouseover</label></th>
                    <td>
                    	<select name='pause' id='pause' class='postform' >
                        	<option value="0" <?php if(get_option('sh_ss_pause')==0){ echo 'selected'; } ?>>No</option>
                            <option value="1" <?php if(get_option('sh_ss_pause')==1){ echo 'selected'; } ?>>Yes</option>
                        </select>
                    </td>
                </tr>
                <tr valign="top">
                	<th scope="row"><label for="auto">Animation</label></th>
                    <td>
                    	<select name='auto' id='auto' class='postform' >
                        	<option value="0" <?php if(get_option('sh_ss_atuo')==0){ echo 'selected'; } ?>>Manually</option>
                            <option value="1" <?php if(get_option('sh_ss_atuo')==1){ echo 'selected'; } ?>>Loop Continuously</option>
                            <option value="2" <?php if(get_option('sh_ss_atuo')==2){ echo 'selected'; } ?>>Animate Once</option>
                            <option value="3" <?php if(get_option('sh_ss_atuo')==3){ echo 'selected'; } ?>>Animate Once (return to first slide)</option>
                        </select>
                    </td>
                </tr>
                <tr valign="top">
                	<th scope="row"><label for="target">Link Target</label></th>
                    <td>
                    	<select name='target' id='target' class='postform' >
                        	<option value="_blank" <?php if(get_option('sh_ss_target')=='_blank'){ echo 'selected'; } ?>>Open link in new window</option>
                            <option value="_self" <?php if(get_option('sh_ss_target')=='_self'){ echo 'selected'; } ?>>Open link in the same window</option>
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
									if(is_array(get_option('sh_ss_effect'))):
										if(in_array($effects[$i],get_option('sh_ss_effect'))):
											echo 'checked="checked"';
										endif;
									else:
										if($effects[$i] == get_option('sh_ss_effect')):
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
                <tr valign="top">
                	<th scope="row"><label for="random">Random Effects</label></th>
                    <td>
                    	<select name='random' id='random' class='postform' >
                        	<option value="0" <?php if(get_option('sh_ss_random')==0){ echo 'selected'; } ?>>No</option>
                            <option value="1" <?php if(get_option('sh_ss_random')==1){ echo 'selected'; } ?>>Yes</option>
                        </select>
                        <span class="description">(Need to select more than one effect and not applicable to shuffle)</span>
                    </td>
                </tr>
            </tbody>
        </table>
        <h3>Navigation Settings</h3>
    	<table class="form-table">
        	<tbody>
            	<tr valign="top">
                	<th scope="row"><label for="nav_transition">Navigation Transition Speed</label></th>
                    <td><input type="text" id="nav_transition" name="nav_transition" value="<?php echo get_option('sh_ss_nav_transition'); ?>" class="small-text" /> Seconds <span class="description">(force fast transitions when triggered manually. 0 for disable.)</span></td>
                </tr>
            	<tr valign="top">
                	<th scope="row"><label for="navigation">Display Navigation</label></th>
                    <td>
                    	<select name='navigation' id='navigation' class='postform' >
                        	<option value="0" <?php if(get_option('sh_ss_navigation')==0){ echo 'selected'; } ?>>No</option>
                            <option value="1" <?php if(get_option('sh_ss_navigation')==1){ echo 'selected'; } ?>>Yes</option>
                        </select>
                    </td>
                </tr>
                <tr valign="top">
                	<th scope="row"><label for="navtype">Navigation Type</label></th>
                    <td>
                    	<select name='navtype' id='navtype' class='postform' >
                        	<option value="1" <?php if(get_option('sh_ss_navtype')==1){ echo 'selected'; } ?>>Slide Numbers</option>
                            <option value="2" <?php if(get_option('sh_ss_navtype')==2){ echo 'selected'; } ?>>Prev-Next</option>
                        </select>
                    </td>
                </tr>
                <tr valign="top">
                	<th scope="row"><label for="navpos">Navigation Position</label></th>
                    <td>
                    	<select name='navpos' id='navpos' class='postform' >
                        	<option value="0" <?php if(get_option('sh_ss_navpos')==0){ echo 'selected'; } ?>>Outside</option>
                            <option value="1" <?php if(get_option('sh_ss_navpos')==1){ echo 'selected'; } ?>>Inside</option>
                        </select>
                    </td>
                </tr>
            </tbody>
        </table>
        <h3>Slideshow Settings</h3>
    	<table class="form-table">
        	<tbody>
            	<tr valign="top">
                	<th scope="row"><label for="custom">Custom Post Types</label><br><span class="description">Name|slug,<br>Name|slug</span></th>
                    <td><textarea name="custom" id="custom" rows="5" class="large-text"><?php echo get_option('sh_ss_custom'); ?></textarea></td>
                </tr>
            	<tr valign="top">
                	<th scope="row"><label for="slides">Slides</label></th>
                    <td>
                    	<select name='slides' id='slides' class='postform' >
                        <?php for($s=1;$s<=15;$s++): ?>
                        	<option value="<?php echo $s; ?>" <?php if(get_option('sh_ss_slideno')==$s){ echo 'selected'; } ?>><?php echo $s; ?></option>
                        <?php endfor; ?>
                        </select>
                    </td>
                </tr>
                <tr valign="top">
                	<th scope="row"><label for="recent">Use Recent Posts</label></th>
                    <td>
                    	<select name='recent' id='recent' class='postform' >
                        	<option value="0" <?php if(!get_option('sh_ss_recent_posts')){ echo 'selected'; } ?>>No</option>
                            <option value="1" <?php if(get_option('sh_ss_recent_posts')){ echo 'selected'; } ?>>Yes</option>
                        </select>
                    </td>
                </tr>
            </tbody>
        </table>
        <h4>Choose your slides and links</h4>
        <table class="form-table">
        	<tbody>
            	<tr valign="top">
                	<th scope="column">Slide No</th>
                    <th scope="column">Slide Image</th>
                    <th scope="column">Slide Link</th>
                </tr>
            
            	<?php for($ss=1;$ss<=get_option('sh_ss_slideno');$ss++): ?>
                <?php
					$pages = get_pages();
					$posts = get_posts('numberposts=-1');
					global $page;
					global $post;
				?>
                <tr valign="top" class="slide">
                	<td>#<?php echo $ss; ?></td>
                    <td><input type="text" name="slide[]" value="<?php echo get_option('sh_ss_slide'.$ss.'_img'); ?>" class="shslideshow_slide regular-text"><input type="button" class="shslideshow_upload" value="Browse"></td>
                    <td>
                    <select name="slide_link[]" class="slide_link postform">
                    	<option value="0"  <?php if(!get_option('sh_ss_slide'.$ss)){ echo 'selected'; } ?>>No Link</option>
                        <option value="manual"  <?php if(get_option('sh_ss_slide'.$ss)=='manual'){ echo 'selected'; } ?>>From Outside</option>
                    <?php foreach($pages as $page): ?>
                    	<option value="<?php echo $page->ID; ?>" <?php if(get_option('sh_ss_slide'.$ss)==$page->ID){ echo 'selected'; } ?>>Page: <?php echo $page->post_title; ?></option>
                    <?php endforeach; ?>
                    <?php foreach($posts as $post): ?>
                    	<option value="<?php echo $post->ID; ?>" <?php if(get_option('sh_ss_slide'.$ss)==$post->ID){ echo 'selected'; } ?>>Post: <?php echo $post->post_title; ?></option>
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
                    	<option value="<?php echo $post->ID; ?>" <?php if(get_option('sh_ss_slide'.$ss)==$post->ID){ echo 'selected'; } ?>><?php echo $name; ?>: <?php echo the_title(); ?></option>
                    <?php
								endwhile;
								wp_reset_query();
							endforeach;
						endif;
					?>
                    </select>
                    <input type="text" name="outside_link[]" class="manual_link regular-text" value="<?php echo get_option('sh_ss_link'.$ss); ?>">
                    </td>
                </tr>
                <?php endfor; ?>
            </tbody>
        </table>
        <p class="submit">
    		<input type="submit" name="submit" value="Update" />
    	</p>
    </form>
</div>