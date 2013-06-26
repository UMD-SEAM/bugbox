=== Artiss Code Embed ===
Contributors: dartiss
Donate link: http://artiss.co.uk/donate
Tags: artiss, embed, code, html, javascript, script, simple, video, xhtml, youtube
Requires at least: 2.0
Tested up to: 3.3.1
Stable tag: 2.0.1

Artiss Code Embed (formally Simple Code Embed) provides a very easy and efficient way to embed code (JavaScript and HTML) in your posts and pages.

== Description ==

Artiss Code Embed (formally Simple Code Embed) allows you to embed code - JavaScript and HTML primarily - in a post. This is incredibly useful for embedding video, etc, when required. It cannot be used for server side code, such as PHP.

Features include..

* Add HTML or JavaScript to posts or pages - particularly useful for embedding videos!
* Embed in widgets using the [Widget Logic](http://wordpress.org/extend/plugins/widget-logic/ "Widget Logic") plugin
* Global embedding allows you set up some code in one post or page and then access it from another
* Modify the keywords or identifiers used for embedding the code to your own choice
* Search for embedding code via a simple search option
* Add a simple suffix to the embed code to convert videos to responsive output
* Embed an external script directly using just the URL
* Fully internationalized ready for translations. **If you would like to add a translation to his plugin then please [contact me](http://artiss.co.uk/contact "Contact").**

Here's how easy it is...

1. Once you have the plugin installed start a new post or page.
2. In the `Custom Fields` meta box enter a name of CODE1 and your embed code as the value. Save this.
3. In your post add `%CODE1%` where you wish the embed code to appear.

And that's it - when the post or page is viewed `%CODE1%` will be replaced with the code that you asked to be embedded.

This should get you started - for more information and advanced options please read the "Other Notes" tab.

Although this plugin works for both posts and pages for simplicity I will simply refer to posts - bear in mind that pages work in the same way.

**For help with this plugin, or simply to comment or get in touch, please read the appropriate section in "Other Notes" for details. This plugin, and all support, is supplied for free, but [donations](http://artiss.co.uk/donate "Donate") are always welcome.**

== Options Screen ==

In the administration menu there is a new sidebar menu named "Code Embed". Under this is a sub-menu named "Options".

Code embedding is performed via a special keyword that you must use to uniquely identify where you wish the code to appear. This consist of an opening identifier (some that that goes at the beginning), a keyword and then a closing identifier. You may also add a suffix to the end of the keyword if you wish to embed multiple pieces of code within the same post.

From this options screen you can specify the above identifier that you wish to use. By default the opening and closing identifiers are percentage signs and the keyword is `CODE`. During these instructions these will be used in all examples.

The options screen is only available to those that with a capability of able to manage options or greater. All the other Code Embed menu options are available to users with a capability to edit posts or greater.

== Embedding ==

To embed in a post you need to find the meta box under the post named "Custom Fields". If this is missing you may need to add it by clicking on the "Screen Options" tab at the top of the new post screen.

Now create a new custom field with the name of your keyword - e.g. `CODE`. The value of this field will be the code that you wish to embed. Save this custom field.

Now, wherever you wish the code to appear in your post, simply put the full identifier (opening, keyword and closing characters). For example, `%CODE%`.

If you wish to embed multiple pieces of code within a post you can add a suffix to the keyword. So we may set up 2 custom fields named `CODE1` and `CODE2`. Then in our post we would specify either `%CODE1%` or `%CODE2%` depending on which you wish to display.

Don't forget - via the options screen you can change any part of this identifier to your own taste.

== URL Embedding ==

If you specify a URL within your post, surrounded by your choice of identifiers, then the contents of the URL will be embedded within your post.

Obviously, be careful when embedding a URL that you have no control over, as this may be used to hijack your post by injecting, for example, dangerous JavaScript.

For example, using the default options you could embed the contents of a URL using the following method...

`%http://www.example.com/code.php%`

or

`%https://www.example.com/code.html%`

== Global Embedding ==

You can also create global embeds - that is creating one piece of embed code and using it in multiple posts or pages.

To do this simply make reference to an already defined (but unique) piece of embed code from another post or page.

So, let's say in one post you define a custom field named `CODE1`. You can, if you wish, place `%CODE1%` not just in that post but also in another and it will work.

However, bear in mind that the embed code name must be unique - you can't have defined it in multiple posts otherwise the plugin won't know which one you're referring to (although it will report this and list the posts that it has been used in).

In the administration menu there is a sidebar menu named "Code Embed". Under this is a sub-menu named "Search". Use this to search for specific embed names and it will list all the posts/pages that they're used on, along with the code for each.

== Embedding in Widgets ==

Natively you cannot use the embed facilities within sidebar widgets. However, if you install the plugin [Widget Logic](http://wordpress.org/extend/plugins/widget-logic/ "Widget Logic") then Artiss Code Embed has been set up to make use of this and add the ability.

* Install [Widget Logic](http://wordpress.org/extend/plugins/widget-logic/ "Widget Logic") and activate.
* In Administration, select the Widgets page from the Appearance menu. At the bottom there will be a set of Widget Logic options.
* Ensure Use 'widget_content' filter is ticked and press Save.

Although you cannot set up embed code within a widget you can make reference to it, for example by writing `%CODE1%` in the widget.

== Responsive Output Conversion ==

Responsive output is where an element on a web page dynamically resizes depending upon the current available size. Most video embeds, for instance, will be a fixed size. This is fine if your website is also of a fixed size, however if you have a responsive site then this is not suitable.

Artiss Code Embed provides a simple suffix that can be added to an embed code and will convert the output to being responsive. This works best with videos.

To use, when adding the embed code onto the page, simply add `_RES` to the end, before the final identifier. For example, `%CODE1_RES%`. The `_RES` should not be added to the custom fields definition.

This will now output the embedded code full width, but a width that is dynamic and will resize when required.

If you don't wish the output to be full width you can specify a maximum width by adding an additonal `_x` on the end, where `x` is the required width in pixels. For example, `%CODE1_RES_500%` this will output `CODE1` as responsive but with a maximum width of 500 pixels.

**It should be noted that this is an experimental addition and will not work in all circumstances.**

== Licence ==

This WordPress plugin is licensed under the [GPLv2 (or later)](http://wordpress.org/about/gpl/ "GNU General Public License").

== Support ==

All of my plugins are supported via [my website](http://www.artiss.co.uk "Artiss.co.uk").

Please feel free to visit the site for plugin updates and development news - either visit the site regularly, follow [my news feed](http://www.artiss.co.uk/feed "RSS News Feed") or [follow me on Twitter](http://www.twitter.com/artiss_tech "Artiss.co.uk on Twitter") (@artiss_tech).

For problems, suggestions or enhancements for this plugin, there is [a dedicated page](http://www.artiss.co.uk/code-embed "Artiss Code Embed") and [a forum](http://www.artiss.co.uk/forum "WordPress Plugins Forum"). The dedicated page will also list any known issues and planned enhancements.

**This plugin, and all support, is supplied for free, but [donations](http://artiss.co.uk/donate "Donate") are always welcome.**

== Reviews & Mentions ==

"Works like a dream. Fantastic!" - Anita.

"Thank you for this plugin. I tried numerous other iframe plugins and none of them would work for me! This plugin worked like a charm the FIRST time." - KerryAnn May.

[Embedding content](http://wsdblog.westbrook.k12.me.us/blog/2009/12/24/embedding-content/ "Embedding content") - WSD Blogging Server.

[Animating images with PhotoPeach](http://comohago.conectandonos.gov.ar/2009/08/05/animando-imagenes-con-photopeach/ "Animando imágenes con PhotoPeach") - Cómo hago.

== Installation ==

1. Upload the entire `simple-code-embed` folder to your wp-content/plugins/ directory.
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. Under the Settings section of the administration menu there should now be a new option named "Code Embed". Select this option to set the default options.
4. Add the identifier code to the appropriate posts and pages where you wish the code to be embedded.

== Frequently Asked Questions ==

= My code doesn't work =

If your code contains the characters `]]>` then you'll find that it doesn't - WordPress modifies this itself.

= What's the maximum size of the embed code that I can save in a custom field? =

WordPress stores the custom field contents in a MySQL table using the `longtext` format. This can hold over 4 billion characters.

= Which version of PHP does this plugin work with? =

It has been tested and been found valid from PHP 4 upwards.

Please note, however, that the minimum for WordPress is now PHP 5.2.4. Even though this plugin supports a lower version, I am not coding specifically to achieve this - therefore this minimum may change in the future.

== Screenshots ==

1. The custom field meta box with a Code Embed field set up to show some YouTube embed code
2. Example embed code in a post
3. The resultant video from the previous example code
4. The administration menu with optional README option
5. The options screen
6. The search screen, showing search results

== Changelog ==

= 2.0.1 =
* Enhancement: Removed restriction on embed code length

= 2.0 =
* Maintenance: Removed dashboard widget
* Maintenance: Further code tidying
* Maintenance: Added new code for contextual help to use new WP 3.3 elements
* Enhancement: New admin menu option, under which existing option screens now exist along with a support screen. If you have the [README Parser plugin](http://wordpress.org/extend/plugins/wp-readme-parser/ "README Parser") installed then it will also add a sub-menu displaying README instructions
* Enhancement: Added internationalization to code
* Enhancement: Will now work with widgets if you install the plugin [Widget Logic](http://wordpress.org/extend/plugins/widget-logic/ "Widget Logic")
* Enhancement: Added experimental ability to convert to responsive output
* Enhancement: Added option to specify a URL instead of an embed code
* Enhancement: Added feature pointer for when plugin is activated

= 1.6.1 =
* Bug: Fixed bug where name of plugin folder was incorrect

= 1.6 =
* Maintenance: Improved code further from 1.5, including seperating code into seperate includes
* Enhancement: Added global embeds option
* Enhancement: New tools option in the administration menu which allows you to search for code embeds

= 1.5.1 =
* Enhancement: Added form security

= 1.5 =
* Maintenance: Renamed plugin to bring in line with new plugin conventions
* Maintenance: Plugin re-write to create more efficient code - can now also completely personalise the embed code used in the post
* Maintenance: PHPDoc used throughout for documentation purposes, plus new coding standards
* Maintenance: Instructions completely re-written
* Enhancement: Support information improved, including contextual help on the settings screen (if supported)
versions of this plugin

= 1.4.1 =
* Bug: Version details as HTML comments were being output whether an embed existed or not - corrected

= 1.4 =
* Enhancement: Option screen which allows you to specify the maximum number of possible embeds per post and the embed word

= 1.3 =
* Enhancement: Increased limit of number of code embeds from 5 to 20

= 1.2 =
* Maintenance: Simplification of code

= 1.1 =
* Maintenance: The instructions have been corrected (thanks to John J. Camilleri for pointing it out!)
* Maintenance: Plugin has been tested with version 2.8 of WordPress. No code changes have been made

= 1.0 =
* Initial release

== Upgrade Notice ==

= 2.0.1 =
* Upgrade to remove embed code length restriction

= 2.0 =
* Upgrade to improve the administration menus and add further features such as internationalization and responsive video conversion

= 1.6.1 =
* Fixes critical bug in version 1.6

= 1.6 =
* Added ability to specify global code embeds

= 1.5.1 =
* Added form security

= 1.5 =
* Much more efficient performance and ability to totally personalise the embed code used in posts

= 1.4.1 =
* Minor bug fix

= 1.4 =
* Update to specify your own embed word and max. embeds per post

= 1.3 =
* Upgrade if you'd like to be able to embed more than 5 scripts on a single page