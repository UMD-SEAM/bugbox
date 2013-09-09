<style>
div.sh_nav{
	display:block;
	float:left;
	width:100%;
}
ul.sh_slideshow_nav{
	display:block;
	float:right;
}
ul.sh_slideshow_nav li{
	display:inline;
	float:left;
	padding:5px;
}
ul.sh_slideshow_nav li a{
	text-decoration:none;
}
ul.sh_slideshow_nav li.current_page a{
	color:#000;
}
span.sort-icon{
	display:block;
	width:36px;
	height:16px;
	text-align:right;
	background:url(<?php echo WP_PLUGIN_URL; ?>/sh-slideshow/images/sort.png) left center no-repeat transparent;
}
select.slide_link{
	float:left;
}
span.del_sh_slide{
	display:block;
	float:left;
	width:16px;
	height:16px;
	margin:auto 5px;
	text-indent:-3000px;
	background:url(<?php echo WP_PLUGIN_URL; ?>/sh-slideshow/images/delete.png) left center no-repeat transparent;
}
</style>
<script language="javascript">
	var path = '<?php echo WP_PLUGIN_URL; ?>/sh-slideshow/';
</script>
<script type="text/javascript" src="<?php echo WP_PLUGIN_URL; ?>/sh-slideshow/mColorPicker.js" charset="UTF-8"></script>
<script type="text/javascript" src="<?php echo WP_PLUGIN_URL; ?>/sh-slideshow/myscript.js" charset="UTF-8"></script>
<?php
	include_once('functions.php'); 
?>
<div class="wrap">
    <div id="icon-options-general" class="icon32"><br></div>
    <h2>Mini Slideshows</h2>
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
		switch($_REQUEST['action']):
			case 'settings':
				shslideshow_settings($_REQUEST['id']);
			break;
			case 'slides':
				shslideshow_slides($_REQUEST['id']);
			break;
			case 'delete':
				if(shslideshow_delete($_REQUEST['id'])):
					echo '<div id="message" class="updated fade">Successfully deleted</div>';
				else:
					echo '<div id="message" class="error fade">Error to delete.</div>';
				endif;
				sh_manage_slideshow();
			break;
			default:
				sh_manage_slideshow();
			break;
		endswitch;
	?>
</div>