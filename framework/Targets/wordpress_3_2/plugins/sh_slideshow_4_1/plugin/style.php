<!-- Add Cycle Script -->
<script language="javascript" type="text/javascript" src="<?php echo WP_PLUGIN_URL; ?>/sh-slideshow/jquery.cycle.all.js"></script>
<script language="javascript">
	var path = '<?php echo WP_PLUGIN_URL; ?>/sh-slideshow/';
	jQuery(document).ready(function(){
		var preview = jQuery('#preview').clone();
		var sample = jQuery('#sample').clone();
		if(jQuery('#pre').val()==1){
			jQuery('#sample').remove();
			jQuery('#preview').remove();
			preview.prependTo('#shslideshow');
		}else{
			jQuery('#sample').remove();
			jQuery('#preview').remove();
			sample.prependTo('#shslideshow');
		}
		// Slideshow effect
		<?php
			if(is_array(get_option('sh_ss_effect'))):
				$i = 0;
				foreach(get_option('sh_ss_effect') as $effect):
					if($i == 1):
						$effects .= ',';
					endif;
					$effects .= $effect;
					$i = 1;
				endforeach;
			else:
				$effects = get_option('sh_ss_effect');
			endif;
		?>
		jQuery('#shslideshow .slide').cycle({
			fx: '<?php echo $effects; ?>',
			random:<?php echo get_option('sh_ss_random'); ?>,
			fastOnEvent:<?php echo (get_option('sh_ss_nav_transition')*1000); ?>,
			fit: 1,
		<?php if(get_option('sh_ss_pause')): ?>
			pause: 1,
		<?php endif; ?>
		<?php if(get_option('sh_ss_navigation')): ?>
			<?php if(get_option('sh_ss_navtype')==1): ?>
					pager: '#shslideshow_nav',
			<?php elseif(get_option('sh_ss_navtype')==2): ?>
					next:   '#shslideshow_nav_next',
					prev:   '#shslideshow_nav_pre',
			<?php endif; ?>
		<?php endif; ?>
			speed: <?php echo (get_option('sh_ss_transition')*1000); ?>,
		<?php if(get_option('sh_ss_atuo')==0): ?>
			timeout: 0
		<?php elseif(get_option('sh_ss_atuo')==2): ?>
			autostop: 1,
			timeout: <?php echo (get_option('sh_ss_timeout')*1000); ?>
		<?php elseif(get_option('sh_ss_atuo')==3): ?>
			autostop: 1,
			autostopCount: <?php echo (get_option('sh_ss_slideno')+1); ?>,
			timeout: <?php echo (get_option('sh_ss_timeout')*1000); ?>
		<?php else: ?>
			timeout: <?php echo (get_option('sh_ss_timeout')*1000); ?>
		<?php endif; ?>
			
		});
	});
</script>
<!-- Add ColorPicker Script -->
<script type="text/javascript" src="<?php echo WP_PLUGIN_URL; ?>/sh-slideshow/mColorPicker.js" charset="UTF-8"></script>
<div class="wrap">
    <div id="icon-themes" class="icon32"><br></div>
    <h2>SH Slideshow Appearance</h2>
    <div style="float:right">
    	<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
            <input type="hidden" name="cmd" value="_donations" />
            <input type="hidden" name="business" value="samhoamt@gmail.com" />
            <input type="hidden" name="item_name" value="SH Slideshow" />
            <input type="hidden" name="currency_code" value="USD" />
            <input type="image" src="<?php echo WP_PLUGIN_URL; ?>/sh-slideshow/donate_btn.gif" name="submit" alt="Make payments with payPal - it's fast, free and secure!" />
        </form>
    </div>
    <form method="post">
    <div class="wrap">
    	<h3>Slideshow Preview</h3>
        
        <label>Display Preview using: </label>
        <select id="pre" name="pre">
        	<option value="0" <?php if(!$_REQUEST['pre']){ echo 'selected'; } ?>>Sample</option>
            <option value="1" <?php if($_REQUEST['pre']){ echo 'selected'; } ?>>Real Slides</option>
        </select>
    	<div id="shslideshow">
        	<div id="sample" class="slide">
                <img src="<?php echo WP_PLUGIN_URL; ?>/sh-slideshow/images/slide1.png">
                <img src="<?php echo WP_PLUGIN_URL; ?>/sh-slideshow/images/slide2.png">
                <img src="<?php echo WP_PLUGIN_URL; ?>/sh-slideshow/images/slide3.png">
                <img src="<?php echo WP_PLUGIN_URL; ?>/sh-slideshow/images/slide4.png">
                <img src="<?php echo WP_PLUGIN_URL; ?>/sh-slideshow/images/slide5.png">
            </div>
            <div id="preview" class="slide">
            <?php
				$slide_no = get_option('sh_ss_slideno');
				$slide = 0;
				for($i=1;$i<=$slide_no;$i++):
					if(get_option('sh_ss_slide'.$i.'_img')!=''):
						$slide++;
						if(!get_option('sh_ss_slide'.$i)):
							echo '<img src="'.get_option('sh_ss_slide'.$i.'_img').'" title="" />';
						elseif(get_option('sh_ss_slide'.$i)=='manual'):
							echo '<a href="'.get_option('sh_ss_link'.$i).'" title="" target="'.get_option('sh_ss_target').'">';
							echo '<img src="'.get_option('sh_ss_slide'.$i.'_img').'" title="" />';
							echo '</a>';
						else:
							$permalink = get_permalink(get_option('sh_ss_slide'.$i));
							$title = get_the_title(get_option('sh_ss_slide'.$i));
							echo '<a href="'.$permalink.'" title="'.$title.'" target="'.get_option('sh_ss_target').'">';
							echo '<img src="'.get_option('sh_ss_slide'.$i.'_img').'" title="'.$title.'" />';
							echo '</a>';
						endif;
					endif;
				endfor;
			?>
            </div>
			<?php if(get_option('sh_ss_navigation')): ?>
            <div id="shslideshow_nav">
            <?php if(get_option('sh_ss_navtype')==2): ?>
            	<div id="shslideshow_nav_pre"><?php echo get_option('sh_ss_prev_text'); ?></div>
            	<div id="shslideshow_nav_next"><?php echo get_option('sh_ss_next_text'); ?></div>
            <?php endif; ?>
            </div>
            <?php endif; ?>
           	<div style="clear:both;"></div>
        </div> 
    </div>
    <div class="wrap">
    	<h3>Slideshow Style</h3>
        <?php
			if($_REQUEST['submit']){
				update_option('sh_ss_css',$_REQUEST['add_css']);
				update_option('sh_ss_next_text',$_REQUEST['next_text']);
				update_option('sh_ss_prev_text',$_REQUEST['prev_text']);
				update_option('sh_ss_nav_spacing',$_REQUEST['spacing']);
				update_option('sh_ss_nav_top',$_REQUEST['from_top']);
				update_option('sh_ss_nav_left',$_REQUEST['from_left']);
				update_option('sh_ss_nav_link_color',$_REQUEST['link_color']);
				update_option('sh_ss_nav_link_hover_color',$_REQUEST['link_hover_color']);
				update_option('sh_ss_nav_link_underline',$_REQUEST['underline']);
				
				// Message
				echo '<div><p style="color:red;">Successfully updated!</p></div>';
			}
		?>
<style type="text/css">
	div#shslideshow{
		width:<?php echo get_option('sh_ss_width'); ?>px;
		background-color:<?php echo get_option('sh_ss_bgcolor'); ?>;
		margin:auto;
	}
	div#shslideshow div.slide{
		position:relative;
		width:100%;
		height:<?php echo get_option('sh_ss_height'); ?>px;
		z-index:1;
	}
	div#shslideshow div.slide img{
		width:<?php echo get_option('sh_ss_width'); ?>px;
		height:<?php echo get_option('sh_ss_height'); ?>px;
	}
	div#shslideshow_nav{
		margin-left:<?php echo get_option('sh_ss_nav_left'); ?>px;
	}
	<?php if(get_option('sh_ss_navpos')): ?>
	div#shslideshow_nav{
		position:absolute;
		margin-top:<?php if(get_option('sh_ss_nav_top')<0){ echo get_option('sh_ss_nav_top'); }else{ echo '-30'; } ?>px;
		z-index:5;
	}
	<?php else: ?>
	div#shslideshow_nav{
		padding-top:<?php echo get_option('sh_ss_nav_top'); ?>px;
	}
	<?php endif; ?>
	div#shslideshow_nav_pre,div#shslideshow_nav_next{
		display:block;
		float:left;
	}
	div#shslideshow_nav_pre:hover,div#shslideshow_nav_next:hover{
		cursor:pointer;
	}
	div#shslideshow_nav a,div#shslideshow_nav_pre,div#shslideshow_nav_next{
		margin-right: <?php echo get_option('sh_ss_nav_spacing'); ?>px;
		color:<?php echo get_option('sh_ss_nav_link_color'); ?>;
	}
	div#shslideshow_nav a:hover,div#shslideshow_nav a.activeSlide,div#shslideshow_nav_pre:hover,div#shslideshow_nav_next:hover{
		color:<?php echo get_option('sh_ss_nav_link_hover_color'); ?>;
	}
	<?php if(get_option('sh_ss_nav_link_underline')): ?>
	div#shslideshow_nav a,div#shslideshow_nav_pre,div#shslideshow_nav_next{
		text-decoration:underline;
	}
	<?php else: ?>
	div#shslideshow_nav a,div#shslideshow_nav_pre,div#shslideshow_nav_next{
		text-decoration:none;
	}
	<?php endif; ?>
</style>
        	<table class="form-table">
            	<tbody>
                	<tr valign="top">
                    	<th scope="row"><label for="add_css">Add slideshow CSS</label></th>
                        <td>
                        	<select id="add_css" name="add_css">
                            	<option value="1" <?php if(get_option('sh_ss_css')){ echo 'selected'; } ?>>Yes (the plugin will add CSS)</option>
                                <option value="0" <?php if(!get_option('sh_ss_css')){ echo 'selected'; } ?>>No (you must add CSS to your theme)</option>
                            </select>
                        </td>
                    </tr>
                	<tr valign="top">
                    	<th scope="row"><label for="next_text">Navigation Next Text</label></th>
                        <td><input type="text" id="next_text" name="next_text" class="medium-text" value="<?php echo get_option('sh_ss_next_text'); ?>"></td>
                    </tr>
                    <tr valign="top">
                    	<th scope="row"><label for="prev_text">Navigation Prev Text</label></th>
                        <td><input type="text" id="prev_text" name="prev_text" class="medium-text" value="<?php echo get_option('sh_ss_prev_text'); ?>"></td>
                    </tr>
                    <tr valign="top">
                    	<th scope="row"><label for="spacing">Navigation Spacing</label></th>
                        <td><input type="text" id="spacing" name="spacing" class="small-text" value="<?php echo get_option('sh_ss_nav_spacing'); ?>"> px</td>
                    </tr>
                    <tr valign="top">
                    	<th scope="row"><label for="from_top">Navigation From top</label></th>
                        <td><input type="text" id="from_top" name="from_top" class="small-text" value="<?php echo get_option('sh_ss_nav_top'); ?>"> px</td>
                    </tr>
                    <tr valign="top">
                    	<th scope="row"><label for="from_left">Navigation From Left</label></th>
                        <td><input type="text" id="from_left" name="from_left" class="small-text" value="<?php echo get_option('sh_ss_nav_left'); ?>"> px</td>
                    </tr>
                    <tr valign="top">
                    	<th scope="row"><label for="link_color">Navigation Link</label></th>
                        <td><input type="color" id="link_color" name="link_color" class="medium-text" data-hex="true" value="<?php echo get_option('sh_ss_nav_link_color'); ?>"></td>
                    </tr>
                    <tr valign="top">
                    	<th scope="row"><label for="link_hover_color">Navigation Link hover</label></th>
                        <td><input type="color" id="link_hover_color" name="link_hover_color" class="medium-text" data-hex="true" value="<?php echo get_option('sh_ss_nav_link_hover_color'); ?>"></td>
                    </tr>
                    <tr valign="top">
                    	<th scope="row"><label for="underline">Navigation Link Underline</label></th>
                        <td>
                        	<select name="underline" id="underline">
                            	<option value="1" <?php if(get_option('sh_ss_nav_link_underline')){ echo 'selected'; } ?>>Underline</option>
                                <option value="0" <?php if(!get_option('sh_ss_nav_link_underline')){ echo 'selected'; } ?>>None</option>
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
div#shslideshow{
	width:(WIDTH)px;
	background-color:(BACKGROUND COLOR);
	margin:auto;
}
div#shslideshow div#slide{
	position:relative;
	width:100%;
	height:(HEIGHT)px;
	z-index:1;
}
div#shslideshow div#slide img{
	width:(WIDTH)px;
	height:(HEIGHT)px;
}
div#shslideshow_nav{
	margin-left:(NAVIGATION FROM LEFT)px;
}
div#shslideshow_nav_pre,div#shslideshow_nav_next{
	display:block;
	float:left;
}
div#shslideshow_nav_pre:hover,div#shslideshow_nav_next:hover{
	cursor:pointer;
}
div#shslideshow_nav a,div#shslideshow_nav_pre,div#shslideshow_nav_next{
	margin-right: (SPACING BETWEEN EACH NAVIGATION)px;
	color:(NAVIGATION LINK COLOR);
}
div#shslideshow_nav a:hover,div#shslideshow_nav a.activeSlide,div#shslideshow_nav_pre:hover,div#shslideshow_nav_next:hover{
	color:(NAVIGATION HOVER COLOR);
}
/* Only if navigation is inside the slideshow */
div#shslideshow_nav{
	position:absolute;
	margin-top:(NAVIGATION FROM TOP)px;
	z-index:5;
}
/* ------------------------------------------ */
	
/* Navigation with underline */
div#shslideshow_nav a,div#shslideshow_nav_pre,div#shslideshow_nav_next{
	text-decoration:underline;
}
/* ------------------------- */
	
/* Navigation without underline */
div#shslideshow_nav a,div#shslideshow_nav_pre,div#shslideshow_nav_next{
	text-decoration:none;
}
/* ---------------------------- */
&lt;/style&gt;</pre>
    </div>
    </form>
</div>