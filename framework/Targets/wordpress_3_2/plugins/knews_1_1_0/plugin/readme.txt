=== Knews Multilingual Newsletters ===
Contributors: creverter
Donate link: http://www.knewsplugin.com/multi-language/
Tags: newsletter, email, mail, emailing, multi language, multilingual, wysiwyg, smtp, cron, batch sending, mailing list
Requires at least: 3.0
Tested up to: 3.4.1
Stable tag: 1.1.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Finally, newsletters are multilingual, quick and professional.

== Description ==

Knews is a powerful multilingual plug-in that allows you to **build professional looking newsletters**, segment subscribers in different mailing lists as well as segment them by language all in a matter of minutes.

Includes a custom, unique **modular WYSIWYG** (What You See Is What You Get) editor. Based on templates, with no need to know HTML.

= Features =

* Possibility of creating **your own templates** [(tutorial here)](http://www.knewsplugin.com/tutorial/).
* **Multilingual**: it recognizes the languages of the blog or website automatically; compatible with WPML and qTranslate.
* **Segmentation** of subscribers by language and in different mailing lists
* Support for **SMTP** sending [(tutorial here)](http://www.knewsplugin.com/configure-smtp-submits/).
* **Total control** of deferred sending, pause, start, end, logs, error reports and re-sending.
* Support for **CRON** [(tutorial here)](http://www.knewsplugin.com/configure-webserver-cron/) and Cron emulation by JavaScript.
* **Personalisation of all interaction messages** with users, in any installed language.
* **Multilingual back office**: English, German, French, Italian, Spanish and Catalan.
* **Widget** for subscriber and surfing language capture.
* **Automated** subscription, cancellation and confirmation of subscribers. 
* Flexible, simple and intuitive **import wizard**: any order of columns and encoding will be correctly interpreted in a .CSV file.
* **Free and without limitations**.

More info here: [http://www.knewsplugin.com](http://www.knewsplugin.com/).

A WYSIWYG Editor Demo:
[youtube http://www.youtube.com/watch?v=axDO5ZIW-9s]

**Admin languages:**

* NEW: Arabic added - ar (thanks to: Hasan Yousef hasanfazary@yahoo.com )
* NEW: Finnish added - fi (thanks to: Eccola http://eccola.fi )
* English - en_US (Knews Team)
* French - fr_FR (thanks to: Ypsilon http://www.ypsilonet.com )
* German - de_DE (thanks to: Ypsilon http://www.ypsilonet.com )
* Italian - it_IT (thanks to: Ypsilon http://www.ypsilonet.com )
* Spanish - es_ES (Knews Team)
* Catalan - ca (Knews Team)

= Future release =

* Continued improvement of the WYSIWYG editor.
* More templates.
* Automated emailing submits.


== Installation ==

1. **Add the plug-in using wordpress admin, find or upload it, via website or FTP.**

2. **Activate it.**

3. **Go to the Knews configuration page and configure:** Sender: Name and e-mail will appear as those of the submitted newsletters.

4. **You can also optionally configure:**

5. a) **Multilingual**: Knews works as monolingual by default, but it can recognise the languages defined in WPML or qTranslate if you choose. 

6. b) **CRON**: By default Knews works with wp_cron, but it can be changed (highly recommendable for websites with low traffic). [(tutorial here)](http://www.knewsplugin.com/configure-webserver-cron/).

7. c) **SMTP** sending: by default Knews sends by wp_mail (). You will have more features and fewer newsletters ending up as spam if you configure data sending using SMTP. [(tutorial here)](http://www.knewsplugin.com/configure-smtp-submits/)

8. **As an option, you can create different mailing lists: open to all registered wordpress users and/or segmented by language.**

9. **You can optionally modify all the texts and dialogues in all the installed languages.**

10. **To place the subscription form, you have the following options:**

11. a) Drag the Knews **widget** to the sidebar.

12. b) Put the following **shortcode** on any page or post: [knews_form]

13. c) **Write** in your theme: `<?php echo knews_plugin_form(); ?>`

14. **If you already have subscribers in some other system or e-mail programme, save them as CSV files: with the import wizard everything will be simple and intuitive.**

== Frequently Asked Questions ==

**Can someone with no knowledge of HTML create a newsletter and send it to their clients?**

Yes, absolutely. The newsletter editor is WYSIWYG and modular. It is not necessary to have any knowledge of HTML and in a matter of minutes you can make a professional looking newsletter that will be seen correctly by all devices and e-mail programmes. 

**What is special about Knews editor?**

Knews editor is unique. It takes advantage of HTML5 properties to create a really unique WYSIWYG editor. You can drag modules to build the newsletter as large as you have things to say, load content of the posts or enter new ones, upload images or use those of the multimedia library, change colours, fonts, images and much more...

**Knews is not translated into my language**

Knews allows you to easily modify all the interactive texts for the website visitor (subscriptions, cancellations, etc.). In your case, you will begin with texts in English. There are 20 sentences in all. If you want to collaborate and translate the whole of Knews into your own language, you will find the necessary files in directories/languages. Contact us and help us make Knews great! 

**How do I install additional languages in Knews?**

You don't have to. If you have configured Knews to work with WPML or qTranslate, when you configure a new language in these plug-ins, Knews will already be ready. 

**Why do you recommend configuring CRON, when wp_cron already works in wordpress without doing anything?**

Knews works initially with wp_cron that is based on running the tasks assigned from time to time. Now, if a blog or website doesn't receive many visits, it won't be reliable, because wp_cron has not been run if there are no website visits, so, start sends and the rate at which they are sent depends on the visits to the website. [(tutorial here)](http://www.knewsplugin.com/configure-webserver-cron/).

**Knews have statistics?**

Since version 1.1.0, Knews has statistics. This include: sign ups, unsubscriptions, newsletter submits, user clicks, user can't read click, etc. 

**I am a designer and I need to give my clients a customised template**

[Here you have a tutorial](http://www.knewsplugin.com/tutorial/) to create a 100% personalised template. You will be able to define which areas are editable and which are not (images, colours, texts, links, etc.), and preview the different types of information to display, creating different modules for the newsletter editor. 

**Why do you recommend configuring an SMTP account?**

If sending is done by SMTP, the amount of e-mails reported as SPAM will drop. Knews sends the e-mails one by one by SMTP as you would, therefore guaranteeing a high rate of assured sending. [(tutorial here)](http://www.knewsplugin.com/configure-smtp-submits/).

**My newsletter must go out today, CRON doesn't work and I don't have enough entries in the website.**

No problem. Simply choose the option to use Cron emulation in JavaScript and send normally. You will have to keep a window open until the sending ends that's all.

**Does Knews only have 3 templates?**

Yes, at the moment Knews only has 3 templates, but we will be adding more. In any case, the degree of personalisation is immense, with thousands of different combinations available. You can also follow our tutorial to modify a template or create a new custom one for yourself.

== Screenshots ==

1. Personalisation of all interaction messages in all languages.
2. Segmentation of subscribers by language in different mailing lists.
3. The WYSIWYG Editor doing module insertion (doing drag).
4. The submit process.
5. The Clean Blue Template and a sample customisation (Sports Car Magazine).
6. The Sweet Barcelona Template and a sample customization (Wine).
7. The Casablanca Template and a sample customisation (Christmas).

== Changelog ==

= 1.1.0 =

* WORDPRESS 3.4 COMPATIBLE
* NEW: MULTISITE SUPPORT
* NEW: STATISTICS
* TEMPLATES ARE MULTILANGUAGE
* ALL THE TEMPLATES HAS SOCIAL BUTTONS
* RIGHT TO LEFT LANGUAGES SUPPORT
* WYSIWYG IMPROVEMENTS:
* wizard dialog for social buttons, to easily insert the URLs and hide undesired icons.
* Image oversize limitation (non breaking layout)
* Image attributes edition (alternate text, links, border, vspace and hspace properties)
* The horizontal insertion spaces between modules are hidden in edition time, only appears during dragging module
* There are a zoom view in the editor
* The editor has now an insert image button at the cursor position
* SMTP / CRON TUTORIALS ADDED
* -- languages --
* NEW: Arabic added - ar (thanks to: Hasan Yousef hasanfazary@yahoo.com )
* NEW: Finnish added - fi (thanks to: Eccola http://eccola.fi )
* -- fixes --
* Save newsletter error re-fixed (thanks to Javier)
* User and password SMTP fields autocomplete issue solved (thanks to Esa Rantanen and Thorsten Wollenhöfer)
* WP_DEBUG alerts breaking ajax actions and WYSIWYG editor (from current theme or another plugins error messages) fixed
* Wordpress core files inside directory broken URLs fixed (different WP address and Site address) (thanks to Manuel Burak and André Hilhorst)


= 1.0.5 =

* Support for image selection in Multisite WordPress sites (thanks to Esa Rantanen)
* WP-CRON interactions with other plugins fixed (thanks to Thorsten Wollenhöfer)
* Save newsletter error fixed (thanks to Javier)
* Image selection blank dialog fixed (thanks to Javier)

= 1.0.4 =

* URGENT: Preview and can't read newsletters bug fixed (thanks to Esa Rantanen)
* Image resize bugs fixed (thanks to Esa Rantanen)

= 1.0.3 =

* MAJOR BUGS Fixed in Windows webservers (thanks to Hans-Heinz Bieling)
* Resolved WYSIWYG editor issues in Macintosh Chrome (thanks to Max Schanfarber)
* Minor bug in modal window after subscription on twenty elevens theme (thanks to Esa Rantanen)
* Fixed customised messages bug (thanks to Hans-Heinz Bieling)

= 1.0.2 =

* WYSIWYG improvements:
* Solved change image bug when no link is provided (thanks to Alfredo Pradanos)
* Now you can resize template images in situ, with re-sharp and undo buttons (click on images)

= 1.0.1 =

* Template Casablanca improvements: background and layout issues with Gmail solved
* Duplication of newsletters option added (not necessary start from scratch every newsletter)

= 1.0.0 =

* Habemus Plugin!!!

== Upgrade Notice ==

= 1.1.0 =

* WORDPRESS 3.4 COMPATIBLE
* NEW: MULTISITE SUPPORT
* NEW: STATISTICS
* TEMPLATES ARE MULTILANGUAGE
* ALL THE TEMPLATES HAS SOCIAL BUTTONS
* RIGHT TO LEFT LANGUAGES SUPPORT
* WYSIWYG IMPROVEMENTS:
* wizard dialog for social buttons, to easily insert the URLs and hide undesired icons.
* Image oversize limitation (non breaking layout)
* Image attributes edition (alternate text, links, border, vspace and hspace properties)
* The horizontal insertion spaces between modules are hidden in edition time, only appears during dragging module
* There are a zoom view in the editor
* The editor has now an insert image button at the cursor position
* SMTP / CRON TUTORIALS ADDED
* -- languages --
* NEW: Arabic added - ar (thanks to: Hasan Yousef hasanfazary@yahoo.com )
* NEW: Finnish added - fi (thanks to: Eccola http://eccola.fi )
* -- fixes --
* Save newsletter error re-fixed (thanks to Javier)
* User and password SMTP fields autocomplete issue solved (thanks to Esa Rantanen and Thorsten Wollenhöfer)
* WP_DEBUG alerts breaking ajax actions and WYSIWYG editor (from current theme or another plugins error messages) fixed
* Wordpress core files inside directory broken URLs fixed (different WP address and Site address) (thanks to Manuel Burak and André Hilhorst)
