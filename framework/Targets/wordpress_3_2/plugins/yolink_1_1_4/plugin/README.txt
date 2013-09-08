=== yolink Search for WordPress ===
Contributors: technosailor, tigerlogic
Tags: search, social sharing
Requires at least: 3.0.5
Tested up to: 3.2.1
Stable tag: 1.1.4

Drop-in replacement for WordPress search that actually provides relevant results.  To initialize the plugin, click “Activate” to the left, then click “Settings” and follow the instructions to generate and register your API key.
== Description ==

yolink Search for WordPress delivers better search on your site or blog instantly. yolink's powerful indexing technology provides your readers with relevance-based results, plus a wealth of extra features like sharing integrations and keyword highlighting.

Check out this introductory video. [youtube http://www.youtube.com/watch?v=rJFOSSPDylw]
:
WordPress native search is time-consuming, unhelpful, and fails to order results based on relevancy. Likewise, Google Custom Search is cluttered with ads -- that don't make you money – and it completely ignores your site's design.

yolink Search for WordPress is our solution to overcome these shortcomings.

List of benefits: 

* Provides better search results based on relevancy
* Enables searching of both pages and posts on WordPress sites
* Searches behind links to surface and highlight search terms in context
* Identifies relevant search content with multi-color keyword highlighting
* Indexes fast, real-time and in the cloud
* Eliminates unwanted advertisements
* Shares easily to Google Docs, Evernote, EasyBib, Facebook, Twitter, and more


yolink Search for WordPress features fast, cloud-based indexing that supports sites of all sizes. On top of just better search, yolink search for WordPress offers a variety of display options and social integrations, including:  multi-color keyword highlighting; expanded search results that show search terms in context from behind links; and one-click sharing with Twitter, Facebook, Evernote, Google Docs and more... 

Help your readers search, find and do more with your content on the first try. Whether you run a blog or a business, yolink search for WordPress will work with you, not against you. The plugin is absolutely free for personal sites and very affordable for businesses.  [Click here](http://www.yolink.com/yolink/api/pricing.jsp) to view our detailed pricing, or 
try our [Priciing Wizard](http://www.yolink.com/yolink/pricing/).

Anyone can try yolink Search for WordPress free for 30 days! Just download the plugin and get your auto-generated API key that's good for up to 50 indexed pages. Then register with us and the sky's the limit!

yolink Search for WordPress was co-developed by [TigerLogic Corporation](http://tigerlogic.com) and [WP Engine](http://wpengine.com). Thanks for checking it out!

Learn more at [Learn more at:  yolink.com/wordpress](http://yolink.com/wordpress).
== Installation ==

Installation instructions:

For more detailed instructions, [click here](http://www.yolink.com/yolink/wordpress/install.jsp).

After downloading, there are two simple ways to install the plugin. Choose the method you're most comfortable with.

*	The easiest installation method is to use your WordPress.org Dashboard to upload and install the plugin. 
*	Alternatively, you can add the plugin to your WordPress Plugins folder manually.


Installation:

1.  Download the yolink Search for WordPress plugin.

2.  Install the yolink Search for WordPress plugin.
    1.  Dashboard Installation (easiest):
        1.  Login to your WordPress Admin Dashboard.
        2.  Click the "Plugins" menu and choose "Add New".
        3.  Click "Choose File", locate the yolink-search.zip file, and
            click "Install Now."
        4.  You should then receive a confirmation message.
    2.  Manual Installation:
        1.  Unzip the yolink-search.zip you downloaded in step 1.
        2.  Upload the contents of yolink-search.zip into your plugins
            directory
3.  Activate the yolink Search for WordPress plugin
    1.  Once you receive the installation confirmation message, click
        "Plugins" on your WordPress dashboard.
    2.  Find the yolink plugin, and click "Activate".
    3.  Open the yolink administration menu from the "yolink search"
        button in the dashboard sidebar.
    4.  To automatically generate the API key required to use the
        plugin, click "Agree" after agreeing to the terms and
        conditions.
4.  Choose the type of content you wish to index (i.e., posts and/or
    pages) and click the "Crawl" button to initiate indexing of your
    site's content.
5.  Register your yolink Search for WordPress plugin.
6.  Click the link at the top of the plugin administration dashboard to
    begin the registration process.
7.  Unregistered API keys can index up to 50 pages. Registration of your
    API key is required to index more pages. yolink Search for WordPress
    is free for personal sites and pricing for businesses start at just
    $60 per year.

== Frequently Asked Questions ==

= Q:  I installed yolink Search for WordPress but it's not working right away. What's the deal? =

A:  When you first install yolink Search for WordPress, you need to manually execute the initial Crawl. From the plugin's dashboard, select pages and/or posts and click "Crawl." It may take some time to index your entire site, especially if you have more than a few thousand pages. If you have a site with more than 5,000 pages, please [contact us](http://www.yolink.com/yolink/product-info/contact-us.jsp) so we can help with the indexing. 

= Q:  My search results are not working properly. Where can I find developer support forums? =

A:  yolink documentation, developer forums, and extended FAQ can be found [here](http://developer.yolink.com/docs/read/faqs). If your problem persists or if you're unable to find a solution, please [contact us](http://www.yolink.com/yolink/product-info/contact-us.jsp).

= Q:  How much does yolink Search for WordPress cost? =

A:  yolink Search is free for personal use, and affordable for business. To find the plan that's right for you, try our [Pricing Wizard](http://www.yolink.com/yolink/pricing/) or review our [detailed pricing](http://www.yolink.com/yolink/api/pricing.jsp). 

= Q:  What if I don't register my API key? =

A: Unregistered API keys are limited to 50 indexed pages per site. If your site contains more than 50 pages, you will need to register the API key (through the link provided in your plugin dashboard) to gain full access to the capabilities of yolink Search for WordPress.

= Q:  I like the yolink excerpts and now I don't want the usual WordPress previews. How do I remove the WordPress previews from my search results page? = 

A:  If you're comfortable editing the functions.php file for your theme, hiding the default WordPress search result blurb should be easy. In your theme's search.php file, add this code: 
`function yolink_custom_css() {
	echo '<style type="text/css">a.yolink-href-key { display:none }</style>';
}
add_action( 'wp_head', 'yolink_custom_css' );`

Of course, themes vary, so if this doesn't work you'll have to investigate the code for your default WordPress blurbs.

= Q:  Will yolink Search for WordPress work with any version of WordPress? =

A: yolink Search for WordPress supports PHP 5.2 or greater and WordPress 3.0.5 or greater. 

= Q:  yolink Search for WordPress isn't working with my theme, is there anything I can do? =

A:  While yolink Search for WordPress is designed to work with many themes seamlessly, there may be some that require additional customization. Please [contact us](http://www.yolink.com/yolink/product-info/contact-us.jsp) for additional information.

= Q:  What are "yolink sharing services"? After I install yolink Search for WordPress, I don't see the yolink sharing features. How do I activate them? =

A:  yolink sharing services give your users the ability to share/repurpose the information they find on your site or blog. If you want to add any or all of the sharing options, simply check the corresponding boxes in the plugin dashboard.

The first share button allows your users to share search results to EasyBib, Evernote, Delicious, etc. The Google Docs&copy; icon allows your users to save content directly to their Google Docs&copy; accounts. The Facebook button lets your users post content directly to Facebook. The Twitter icon allows your users to tweet out website content. 

= Q:  yolink Search for WordPress is working, but the results clash with my site design. How do I make it mesh well with the current theme I'm using? =

A:  yolink results can be styled with CSS, just not directly through the plugin. If you'd like more information about styling yolink Search for WordPress, please [contact us](http://www.yolink.com/yolink/product-info/contact-us.jsp). We are currently working on an FAQ.

= Q:  I'm confident that I've installed yolink Search for WordPress correctly, and I've read the entire FAQ, but it still doesn't appear to be working properly. What could the problem be? =

A:  There may have been an issue in creating the API key during the activation process. Please go to the options page to see if you see, "The API Key for this blog is y0l1nk", where y0l1nk is a series of letters and numbers. If you don't have an API key, please [contact us](http://www.yolink.com/yolink/product-info/contact-us.jsp) to get one. 

= Q:  Can I exclude certain types of content from my search results? = 

A:  Yes. In addition to the option of indexing pages, posts, and custom post types you can further customize your search results. Please view our [Developer Documentation](http://developer.yolink.com/docs) or [contact us](http://www.yolink.com/yolink/product-info/contact-us.jsp) for assistance.

= Q:  Where can I report a bug? =

A:  Please use the [contact form](http://www.yolink.com/yolink/product-info/contact-us.jsp) to report any bugs.

= Q:  I have a WordPress Multisite installation.  How do I enable each site with yolink Search for WordPress?  Do I need a different API key for each site? =

A:  No, you do not need a different key for each site.  For instructions on how to enable multiple sites, please [contact us](http://www.yolink.com/yolink/product-info/contact-us.jsp. We are working on a seperate FAQ item for this. 

= Q:   I'm a WordPress theme developer, and I'd like to bundle yolink Search for WordPress with my theme(s). Is this okay? =

A:  Sure, bundle away! Please note that each user of your theme(s) will need their own API key. If you'd like to learn about our Preferred Developer program, please [contact us](http://www.yolink.com/yolink/product-info/contact-us.jsp).

= Q:  Is yolink Search for WordPress available in other languages? =

A:  The yolink Search for WordPress user interface and dashboard is currently available only in English. The search functionality is officially supported in English, French, German, and Spanish. Other languages may also work, but may have some limitations.

== Screenshots ==

1. The Crawl Content option allows you to choose to index pages and/or posts.
2. Choose from our available sharing options to add to your yolink results.
3. Register to get more out of your API Key.
4. yolink Search for WordPress delivers the most relevant results first.
5. Example two-column implementation of yolink Search for WordPress.
6. Example implementation of yolink Search for WordPress.

== Changelog ==
= 1.1.4 = 
* Added support for affiliate registrations 

= 1.1.3 = 
* Fixes bug for blogs with exceedingly large posts
* Added extra customization options

= 1.0.5 =
* Support for additional themes
* Removed robots.txt restrictions on crawl requests for blog posts
* Minor bug fixes

= 1.0 =
* Initial Release
