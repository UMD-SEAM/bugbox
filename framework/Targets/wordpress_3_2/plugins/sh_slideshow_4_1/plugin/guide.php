<div class="wrap" id="top">
    <div id="icon-edit-pages" class="icon32"><br></div>
    <h2>SH Slideshow User Guide</h2>
    <div style="float:right">
    	<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
            <input type="hidden" name="cmd" value="_donations" />
            <input type="hidden" name="business" value="samhoamt@gmail.com" />
            <input type="hidden" name="item_name" value="SH Slideshow" />
            <input type="hidden" name="currency_code" value="USD" />
            <input type="image" src="<?php echo WP_PLUGIN_URL; ?>/sh-slideshow/donate_btn.gif" name="submit" alt="Make payments with payPal - it's fast, free and secure!" />
        </form>
    </div>
	<h3>Using SH Slideshow</h3>
    <p>SH Slideshow is a Wordpress plugin that will create an animated promo slideshow on your site. You can use it to promote any pages, posts, custom post types in your site and pages from outside. It is fully customisable through the admin pages of your blog. Unlike some other slideshow components, SH Slideshow makes use of jQuery JavaScript frameworks and jQuery plugins.</p>
    <h3>Contents</h3>
    <ol>
    	<li><a href="#c1" title="Installing SH Slideshow">Installing SH Slideshow</a></li>
        <li><a href="#c2" title="Adding a slideshow to your theme">Adding a slideshow to your theme</a></li>
        <li><a href="#c3" title="Modifying your settings">Modifying your settings</a></li>
        <li><a href="#c4" title="Adding your promo Slides">Adding your promo slides</a></li>
        <li><a href="#c5" title="Styling your slideshow">Styling your slideshow</a></li>
    </ol>
    
    <!-- Installing SH Slideshow -->
    <h3 id="c1">Installing SH Slideshow</h3>
    <p>Installing the SH Slideshow plugin is quick and simple:</p>
	<ol style="list-style-type:decimal; margin-left:50px;">
		<li>Begin by downloading the plugin file (ZIP)</li>
		<li>Extract the files to your local machine</li>
		<li>Upload the whole sh-slideshow folder (including the folder itself) to your plugins directory - typically http://www.yourdomain.com/wp-content/plugins/</li>
		<li>Activate the plugin using your Wordpress admin pages</li>
		<li>Familiarise yourself with the documentation under <span class="description">'SH Slideshow &gt; Options'</span></li>
		<li>Add the slideshow code to your theme</li>
	</ol>
    
    <!-- Adding a slideshow to your theme -->
    <h3 id="c2">Adding a slideshow to your theme</h3>
    <p>Adding slideshows to your themes is incredibly easy and just requires one line of PHP code. You can introduce your slideshow as part of any of your theme files using the following code:</p>
	<span class="description">&lt;?php shslideshow(); ?&gt;</span>
    
    <!-- Modifying your settings -->
    <h3 id="c3">Modifying your settings</h3>
    <p>The SH Slideshow options screen lets you customise almost every aspect of your slideshow. You can access this through your Wordpress admin screens under <span class="description">'SH Slideshow &gt; Options'</span>.</p>
    <h4>Common Settings</h4>
    <p>These options allow you to customise your slideshow common settings.</p>
    <ul style="list-style-type:disc; margin-left:50px;">
		<li><strong>Width</strong> The width of your slideshow.</li>
		<li><strong>Height</strong> The height of your slideshow.</li>
		<li><strong>Background Color</strong> The background color of your slideshow.</li>
	</ul>
    <h4>Effect Settings</h4>
    <p>These options allow you to customise your slideshow Effect.</p>
    <ul style="list-style-type:disc; margin-left:50px;">
		<li><strong>Transition Speed</strong> The transition speed of your slideshow, in seconds.</li>
		<li><strong>Stop time</strong> The stop time of each slide of your slideshow, in seconds.</li>
		<li><strong>Stop when mouseover</strong> If you want your slideshow stop when mouse on the slide, please choose <span class="description">'Yes'</span>.</li>
        <li><strong>Animation</strong> Animation style for slideshow.</li>
        <li><strong>Link Target</strong> The link target of each slide of your slideshow.</li>
		<li><strong>Effects</strong> The slideshow effects.</li>
		<li><strong>Random Effects</strong> Random effects.<span class="description">(Need to select more than one effect and not applicable to shuffle)</span></li>
	</ul>
    <h4>Navigation Settings</h4>
    <p>These options allow you to customise your slideshow navigation.</p>
    <ul style="list-style-type:disc; margin-left:50px;">
    	<li><strong>Navigation Transition Speed</strong> Force fast transitions when triggered manually. 0 for disable.</li>
		<li><strong>Display Navigation</strong> Display navigation for your slideshow.</li>
		<li><strong>Navigation Type</strong> Navigation type of your slideshow. <span class="description">(Only affected when you choose 'Yes' in 'Display Navigation')</span></li>
		<li><strong>Navigation Position</strong> Navigation position from your slideshow. Inside of your slideshow or ouside from your slideshow. <span class="description">(Only affected when you choose 'Yes' in 'Display Navigation')</span></li>
	</ul>
    
    <!-- Adding your promo Slides -->
    <h3 id="c4">Adding your promo slides</h3>
    <h4>Slideshow Settings</h4>
    <p>These options allow you to add your promo slides into your slideshow.</p>
    <ul style="list-style-type:disc; margin-left:50px;">
		<li><strong>Custom Post Types</strong> Adding your theme custom post types into slide link selection. Each of custom post type seperate by comma(,) and each custom post type with name|slug combination. For example: <span class="description">NAME1|SLUG1,NAME2|SLUG2,NAME3|SLUG3</span></li>
		<li><strong>Slides</strong> How many slides you want to display in your slideshow. <span class="description">(You need to update after you change your slides number.)</span></li>
        <li><strong>Use Recent Posts</strong> Using recent posts for slides. Slides image is get from the first image of post gallery. <span class="description">(How many posts will put into your slideshow is depended on your slides number.)</span></li>
		<li><strong>Choose your slides and links</strong> Choose your slides and links for your slideshow.</li>
	</ul>
    <h4>Insert your slide image</h4>
    <p>Simple upload your slide image using wordpress built-in media uploader by clicking <span class="description">Browse</span> button. After uploaded, press <span class="description">Insert into Post</span> to insert your slide image.</p>
    <h4>Choose link for slide image</h4>
    <p>Simple choose the link under <span class="description">Slide Link</span> column.</p>
    <ul style="list-style-type:disc; margin-left:50px;">
    	<li><strong>No Link</strong> No link for the slide.</li>
        <li><strong>From Outside</strong> Manually put outside link in text field beside the dropdown menu.</li>
    </ul>
    
    <!-- Styling your slideshow -->
    <h3 id="c5">Styling your slideshow</h3>
    <h4>Appearance</h4>
    <p>These options allow you to customise your slideshow style.</p>
    <ul style="list-style-type:disc; margin-left:50px;">
		<li><strong>Add slideshow CSS</strong> Auto add slideshow CSS.</li>
		<li><strong>Navigation Next Text</strong> Next link text. <span class="description">(Only affected when you choose 'Pre-Next' in 'Options &gt; Navigation Settings &gt; Navigation Type')</span></li>
        <li><strong>Navigation Prev Text</strong> Previous link text. <span class="description">(Only affected when you choose 'Pre-Next' in 'Options &gt; Navigation Settings &gt; Navigation Type')</span></li>
		<li><strong>Navigation Spacing</strong> Spacing between each navigation link</li>
        <li><strong>Navigation From top</strong> Navigation spacing from top</li>
        <li><strong>Navigation From Left</strong> Navigation spacing from left</li>
        <li><strong>Navigation Link</strong> Navigation link color</li>
        <li><strong>Navigation Link hover</strong> Navigation link mouseover color</li>
        <li><strong>Navigation Link Underline</strong> Navigation link put underline or not.</li>
	</ul>
    <h4>Manually add css into your theme</h4>
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
	padding-top:(NAVIGATION FORM TOP)px;
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
	margin-top:(NAVIGATION FORM TOP)px;
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
    
    <p><a href="#top">Back to Top</a></p>
</div>