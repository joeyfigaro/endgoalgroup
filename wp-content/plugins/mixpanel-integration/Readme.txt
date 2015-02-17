=== MixPanel  ===
Contributors: jittarao
Tags: analytics, metrics, funnels, mixpanel, kissmetrics
Requires at least: 3.3
Tested up to: 4.0
Stable tag: trunk 
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

This is a plugin that allows you to get MixPanel analytics up and running on WordPress very easily.  

== Description ==

This plugin adds a meta box to the bottom of every page, which will allow you to mark the event of the page landing in MixPanel accordingly.
You no longer need to add the mixpanel.track() javascript snippet in your theme files. 

== Installation ==  
To install the plugin, either upload the .zip file using the plugin installer in your wp-admin, or
SFTP the files over to your wp-content/plugins/ directory and activate it. 

Configuration Options

There are some variables that are necessary to make the plugin do it's thing, you can set these settings under the Settings -> Mixpanel Options page

1. MIXPANEL_TOKEN: This is the token for your specific project. 
1. MIXPANEL_DEBUG: If your site is in development mode, you can turn this on and see debug messages in your Firebug Console
1. MIXPANEL_SUBDOMAIN_COOKIE: Make this true if you have MixPanel on two sites with the same token. Like blog.domain.com, app.domain.com 

How does it work? 

On every page in WordPress, you'll see an option panel for MixPanel Event Label. Whatever value you put in this label, will
show up as an event in MixPanel.

@TODO
1. build some logic to do smarter tracking
2. add stuff to track links in a smart way