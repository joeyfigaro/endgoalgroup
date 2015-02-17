#MixPanel Plugin for WordPress. 

To install the plugin, either upload the .zip file using the plugin installer in your wp-admin, or
SFTP the files over to your wp-content/plugins/ directory.

##Configuration Options

There are some variables that are necessary to make the plugin do it's thing. 

1. **MIXPANEL_TOKEN**: This is the token for your specific project. 
1. **MIXPANEL_DEBUG:** If your site is in development mode, you can turn this on and see debug messages in your Firebug Console
1. **MIXPANEL_SUBDOMAIN_COOKIE:** Make this true if you have MixPanel on two sites with the same token. Like blog.domain.com, app.domain.com 

##How does it work? 

On every page in WordPress, you'll see an option panel for MixPanel Event Label. Whatever value you put in this label, will
show up as an event in MixPanel.