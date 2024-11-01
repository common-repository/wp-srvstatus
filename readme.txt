=== WP Server Status ===
Contributors: bartschatten
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=blaphase%40gmx%2ede&item_name=wp%20srvStatus&item_number=Support%20Open%20Source&no_shipping=0&no_note=1&tax=0&currency_code=EUR&lc=DE&bn=PP%2dDonationsBF&charset=UTF%2d8
Tags: Trackmania, Server, maintenance, offline, online, widget, admin, Administration, Status, Gameserver
Requires at least: 2.8.0
Tested up to: 3.0.1
Stable tag: 0.3


== Description ==
This plugin will display the online/offline status of defined server in your sidebar.
This idea was born in case of setting up dedicated trackmania servers and 
I want to show the actually stats for the blog visitors / clan members.
The plugin is easy to configure and easy to use. Now with Trackmania colorcode support.
See installation section for more information.

Check out http://www.martin-fredrich.de/wordpress/wordpress-plugin-wp-srvstatus/ for an detailed description.


**Translations**

Not available at the moment, english only.


== Installation ==

To do a new installation of the plugin, please follow these steps

1.	Download the wp-srvstatus.zip file to your local machine.
2.	Unzip the file 
3.	Upload `wp-srvstatus` folder to the `/wp-content/plugins/` directory
4.	Change the count of your desired servers in wp-srvstatus.php, line 29. The default value amount two servers.
5.	Activate the plugin through the 'Plugins' menu in WordPress
6.	Define the servers in the settingsmenu
7.	Place widget in your sidebar.

That's it.

To upgrade your installation

1. De-activate the plugin
2. Get and upload the new files (do steps 1. - 4. from "new installation" instructions)
3. Reactivate the plugin. Your settings should have been retained from the previous version.


== Upgrade Notice ==
= 0.3 =
* Update for Trackmania colorcode support

= 0.2 =
* Dynamic value of servers implemented

= 0.1 =
* Initial release


== Screenshots ==
1. Widget in sidebar
2. Option menu in admin section
3. Widget options

== Frequently Asked Questions ==
= Works your Plugin only with Trackmania servers? =
No, you can use WP Server Status for any server, who ist reachable over the http-protocoll

= Which features are planned and coming up? =
There are two features in the queue:
* *Multilanguage support*
* *Multiple outputstyle generation*


== Changelog ==

= 0.3 =
* Trackmania colorcode support implemented
* alt-tags for online/offline images

= 0.2 =
* Dynamic value of servers implemented
* Code review

= 0.1 =
* There are no release notes for this version