<?php require_once( dirname( __FILE__ ) .'/../../../../wp-load.php'); ?>
<style type="text/css">




#system_notice_area {
	position: fixed;
	margin-bottom:40px;
	left:25%;
	width:50%;
	height:20px;
	border-radius:5px;
	-moz-border-radius:5px;
	-webkit-border-radius:5px;
	font-weight:bold;
	display:none;
	padding:5px;
	color: #000000;
	text-align: center;
	<?php
	if(is_admin_bar_showing()){
	?>
	top	:28px;
	<?php
	}else{
	?>
	top: 0px;
	<?php
	}
	?>


	}


#newsletter-manager .plugin-version-author-uri {
	
	background-color: #F4F4F4;
	min-height:16px;
	border-radius:5px;
	margin-bottom: 7px;
	padding: 5px;
	color: #111111;
	
}
#newsletter-manager{
background: #64cfe8; /* Old browsers */

background: -moz-linear-gradient(top,  #ffffff 0%, #64cfe8 100%); /* FF3.6+ */
background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#ffffff), color-stop(100%,#64cfe8)); /* Chrome,Safari4+ */
background: -webkit-linear-gradient(top,  #ffffff 0%,#64cfe8 100%); /* Chrome10+,Safari5.1+ */
background: -o-linear-gradient(top,  #ffffff 0%,#64cfe8 100%); /* Opera 11.10+ */
background: -ms-linear-gradient(top,  #ffffff 0%,#64cfe8 100%); /* IE10+ */
background: linear-gradient(top,  #ffffff 0%,#64cfe8 100%); /* W3C */

}
 .xyz_fbook{
	
	background-image: url('<?php echo plugins_url("images/facebook.png",__FILE__) ?>');
	height:16px;
	background-repeat: no-repeat;
	background-position: left center;
	padding-left: 15px;
	
	
	
	
}
.xyz_fbook{
	
	background-image: url('<?php echo plugins_url("images/facebook.png",__FILE__) ?>');
	height:16px;
	background-repeat: no-repeat;
	background-position: left center;
	padding-left: 15px;
	text-decoration: none;
	
	
	
}
.xyz_support{
	
	background-image: url('<?php echo plugins_url("images/support.png",__FILE__) ?>');
	height:16px;
	background-repeat: no-repeat;
	background-position: left center;
	padding-left: 15px;
	
	
	
	
}
.xyz_twitt{
	
	background-image: url('<?php echo plugins_url("images/twitter.png",__FILE__) ?>');
	height:16px;
	background-repeat: no-repeat;
	background-position: left center;
	padding-left: 15px;
	text-decoration: none;
	
	
	
}
.xyz_gplus{
	
	background-image: url('<?php echo plugins_url("images/gplus.png",__FILE__) ?>');
	height:16px;
	background-repeat: no-repeat;
	background-position: left center;
	padding-left: 15px;
	text-decoration: none;
	
	margin-left: 3px;
	
}
#newsletter-manager .plugin-version-author-uri a,
#newsletter-manager .plugin-version-author-uri a:link,
#newsletter-manager .plugin-version-author-uri a:hover,
#newsletter-manager .plugin-version-author-uri a:active,
#newsletter-manager .plugin-version-author-uri a:visited{
	
	
	color: #111111;
	text-decoration: none;
	
}
#newsletter-manager .plugin-version-author-uri a:hover{

color:#cc811a;
}
#newsletter-manager .plugin-title{

background-image: url('<?php echo plugins_url("images/xyz_logo.png",__FILE__) ?>');
background-repeat: no-repeat;
background-position: left  bottom;

}
</style>