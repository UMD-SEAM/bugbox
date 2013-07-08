=== Newsletter Manager ===
Contributors: f1logic
Donate link: http://xyzscripts.com/donate/

Tags: email newsletter, newsletter manager, email manager, mailing list manager, email marketing, newsletter, opt-in form, subscription form, newsletter subscription
Requires at least: 2.8
Tested up to: 3.3.1
Stable tag: 1.0.2

Create and send html or plain text email newsletters to your subscribers.

== Description ==

Newsletter manager allows you to create and send html or plain text email newsletters to your subscribers. The plugin supports unlimited email campaigns, unlimited email addresses,  double opt-in anti-spam compliance, hourly email sending limit and much more. Opt-in form is available as HTML code, shortcode as well as standard Wordpress widget. The import/export tool allows to create and restore backup of your subscriber list.

= Features =

Opt-in Form

    HTML subscription (opt-in) forms for your websites
    Shortcode for opt-in form
    Opt-in form widget
    Flexible redirection options in opt-in form

Email addresses

    Unlimited email addresses
    Extract email addresses from any unformatted text
    Import email addresses from CSV or other text based files
    Backup email addresses by exporting as CSV file

Email Campaigns

    Unlimited email campaigns
    Create HTML or plain text messages
    Create campaign messages using WYSIWYG editor
    Personalize your message using name field
    Upload unlimited attachments
    Easy to add unsubscription link
    Preview campaign by test mail
    Pause/resume active campaigns
    Selective execution of campaigns
    Email firing batch size for each campaign
    Automate email sending using cron job or scheduler
    Efficient email sending engine which prevents duplicate emails
    Detailed statistics of email campaigns

Email Sending Configurations

    Configure hourly limits
    Auto responders for subscription and unsubscription

Anti-spam compliance

    Email confirmation option for subscribers
    One click unsubscribe link for subscribers


= Want more features ? =

Want more features and options ? Learn more about [XYZ Email Manager](http://xyzscripts.com/advertising/xyz-email-manager/details "XYZ Email Manager"), the standalone version of this plugin.

= About =

Newsletter Manager is developed and maintained by [XYZScripts](http://xyzscripts.com/ "xyzscripts.com"). For any support, you may [contact us](http://xyzscripts.com/support/ "XYZScripts Support").

== Installation ==

1. Extract `newsletter-manager.zip` to your `/wp-content/plugins/` directory.
2. In the admin panel under plugins activate Newsletter Manager.
3. You can configure the basic settings from Newsletter-Manager menu.
4. Once settings are done, you may add email addresses, generate opt-in forms as well create email campaigns.

If you need any further help, you may contact our [support desk](http://xyzscripts.com/support/ "XYZScripts Support").

== Frequently Asked Questions ==

= 1. The Newsletter Manager is not working properly. =

Please check the wordpress version you are using. Make sure it meets the minimum version recommended by us. Make sure all files of the `newsletter manager` plugin uploaded to the folder `wp-content/plugins/`

= 2. Why are the emails are not being sent ? =

Please ensure that PHP mail() function is enabled in your server. Also some servers enforce a validation which requires that the sender email address must belong to same domain,ie, if your site is xyz.com, then the sender email must be someone@xyz.com 

= 3. How can i automate email sending ? =

You need to set a cron job or scheduled task in your hosting control panel. You can get the syntax of the cron job from the settings page. 

= 4. Can i use the opt-in form in pages which are not part of wordpress ? =

Yes you can the opt-in form html code in any page (even in other websites). But the shortcode will work only in wordpress pages. 

= 5. How can i load an existing list of email addresses to the system ? =

If you have any text based file like csv or txt, you can import the email addresses using the import tool. If your data is unformatted, you may use the 'Add Emails' option to extract emails. 

= 6. Can i use custom fields other than 'Name' ? =

No, right now the plugin allows only one custom field called 'Name'. If you need more fields, you can check the standalone version of this plugin (XYZ Email Manager) at our site.

= 7. Can i multiple email lists ? =

No, as of now the plugin allows only one default list. If you need to create more lists, you can check the standalone version of this plugin (XYZ Email Manager) at our site.

= 8. My hosting company has set an hourly limit on outgoing emails. Can the plugin take care of this ? =

Sure, you can specify the hourly outgoing limit and the plugin will ensure that no campaigns are fired once the limit is reached  for any particular hour. 

= 9. Does the plugin comply with anti-spam policies  ? =

Yes, the plugin supports double opt-in which will ensure that subscriptions are genuine.  

= 10. Where can i get the standalone version ? =

You can purchase the email manager script from our website [xyzscripts.com](http://xyzscripts.com/advertising/xyz-email-manager/details "XYZ Email Manager").

More questions ? [Drop a mail](http://xyzscripts.com/members/support/ "XYZScripts Support") and we shall get back to you with the answers.


== Screenshots ==

1. This is the configuration page where you can modify all the settings related to Newsletter Manager.
2. This is opt-in form generation page.
3. This page is used to create an email campaign.

== Changelog ==

= 1.0.2 =
* Option to search emails.
* Fix for tinymce &lt;p&gt; and &lt;br&gt; autoremoval.
* Bug fix in csv import. 

= 1.0.1 =
* Fix for utf-8 character issue.
* Admin widget for quick statistics.
* Option to activate unsubscribed emails.

= 1.0 =
* First official launch.

== Upgrade Notice ==

= 1.0.2 =
If you had some issue with &lt;p&gt; and &lt;br&gt; tags in  tinymce editor, you must do this update.  

= 1.0.1 =
If you had some issue with utf-8 characters, you must do this update.  


== More Information ==


= Troubleshooting =

Please read the FAQ first if you are having problems.

= Requirements =

    WordPress 2.8+
    PHP 5+ 

= Feedback =

We would like to receive your feedback and suggestions. You may submit them at our [support desk](http://xyzscripts.com/members/support/ "XYZScripts Support").
