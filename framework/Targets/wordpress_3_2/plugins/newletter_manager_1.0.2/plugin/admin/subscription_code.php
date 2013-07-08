<div>

	<h2>Opt-in Form</h2>
	You may use shortcode or html code to display the opt-in form. Preview of the opt-in form is given below.
	<p></p> 
	<?php 
	global $wpdb;


		include(dirname( __FILE__ )."../../shortcodes/htmlcode.php") ;



	?>

	<table class="widefat" style="width:98%;">
		<tr>
			<td style="font-size:14px; font-weight:bold;">Short Code</td>
		</tr>
		<tr>
			<td id="bottomBorderNone">[xyz_em_subscription_html_code]</td>
		</tr>
	</table>
	<div style="height:40px">&nbsp;</div>
	<table class="widefat" style="width:98%;">
		<tr>
			<td style="font-size:14px; font-weight:bold;">HTML Code</td>
		</tr>
		<tr>
			<td colspan="2">Please copy the HTML code displayed in the text area
				below and paste it into the page where you need the subscription
				form.</td>
		</tr>
		<tr>
			<td colspan="2" id="bottomBorderNone"><textarea id="textareas" style="width:100%;">
					<?php 
					
					
					 include(dirname( __FILE__ ).'../../shortcodes/htmlcode.php') ;
					
					?>
				</textarea></td>
		</tr>

		
	</table>

<div style="height:40px">&nbsp;</div>
	<table class="widefat" style="width:98%;">
		<tr>
			<td style="font-size:14px; font-weight:bold;">Widget</td>
		</tr>
		<tr>
			<td id="bottomBorderNone"> In addition to the above  options you can also use the newsletter manager opt-in widget from the <a href="<?php echo admin_url().'widgets.php'; ?>">widgets page</a> for displaying the form.</td>
		</tr>
	</table>
	
	
	
</div>
