<?php
/**
 * Gantry For Wordpress
 *
 * @version   $Id: CHANGELOG.php 60857 2014-05-16 08:38:19Z jakub $
 * @author    RocketTheme http://www.rockettheme.com
 * @copyright Copyright (C) 2007 - 2014 RocketTheme, LLC
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 */
die();
?>

1. Copyright and disclaimer
----------------


2. Changelog
------------
This is a non-exhaustive changelog for Gantry, inclusive of any alpha, beta, release candidate and final versions.

Legend:

* -> Security Fix
# -> Bug Fix
+ -> Addition
^ -> Change
- -> Removed
! -> Note

------- 4.1.2 Release [] ------
# Fixed FontAwesome font files paths in the WordPress Multisite
^ Modified get_content_template to support only current page context, leaving the post formats to the template
^ Modified the Gantry Breadcrumbs widget to support more formats and support filters
# Fixed issue when wp_enqueue_scripts hook was fired too late
+ It is now possible to load scripts in the footer using addScript(s), addInlineScript, addDomReadyScript and addLoadScript
+ Revamped the Widget Variation Chooser to be much more flexible and usable (requires minor theme changes)
^ Widget input fields should now properly use data allowed in wp_filter_post_kses ie. some HTML code
# Fixed assigning taxonomy archives to overrides
+ $gantry->displayBodyTag() allows now to use the same parameters as body_class()
+ Added widget_title filter to the Gantry Framework core widgets
+ Added compatibility for the WP Menu Cart plugin
+ Added missing BuddyPress Notifications Component to the possible Assignments list
^ Moved Analytics to Universal Analytics
# Possible fix for some minor Font Awesome 3.0 issues
# Fixed compatibility for NextGen Gallery
^ Minor UI improvements

------- 4.1.1 Release [] ------
# Fix for the JS error when editing Widgets in the overrides which could cause widgets to disappear
# Fix for the gantryUrl that could prevent some scripts from using SSL
^ Changed CSS margins for the Gantry Widget forms to fix spacing
# Fixed loading font variants for Google Fonts
+ Updated Google Fonts catalogue to support all currently available fonts and variants
# Fixes for the Strict Standard notices in PHP 5.4 when debug mode enabled

------- 4.1.0 Release [] ------
# Compatibility fix in the MooRainbow for the Visual Composer plugin
^ RokBox 2 support in the Login Button widget
# Fix for the multiple body classes in Page Suffix gizmo
# Updated IE11 detection
^ Updated Pretty Print JS files
# Fixed font style issues caused by Font Awesome 3.2.1 icons
+ Added Font Awesome 4 assets for future use
# Fixed missing special characters with Google fonts ie. Polish characters
^ Removed RocketTheme references in Copyright widget
# Fixed the display of overrided options in the theme settings
# Gantry admin UI fixes for the WordPress 3.8

------- 4.0.8 Release [] ------
+ Added core support for the BuddyPress 1.8+ - Gantry BuddyPress plugin is no longer required and has been deprecated
# Fix for the Gizmo options not being saved/loaded in the Presets of the theme
+ Compatibility fix for the NextGen Gallery plugin
# Fixed rendering of the dollar sign ($) in the site title
+ You can now use the %YEAR% and %year% in the Gantry Copyright widget to display the current year
# Fix for the bad path to the font files of the FontAwesome 3.2.1

------- 4.0.7 Release [] ------
+ Added support for Font Awesome 3.2.1
^ Added in performance improvement for Menus in the Rules Engine (thanks David Fritsch)
+ Added Gantry Mobile Dectect core utility (required by some premium templates ie. Lumiere)
+ Added two new admin form fields - filelist and imagelist

------- 4.0.6 Release [] ------
+ Cart66 support added to compatibility gizmo
# Shopp plugin compatibility fix
# Fixed parseQueryString method on override widgets that wasn't allowing the "+" caracther in text 
# Google Web Fonts should now use proper protocol when SSL detected
# Filter in Title gizmo should now have all required parameters
# Changed the way how Gantry outputs header and footer which can solve potential issues with scripts and regular expressions
^ Gantry Menu cache should now respect the get_locale();
^ Other minor improvements

------- 4.0.5 Release [] ------
# Fixed Google WebFonts and breaking fonts caused by s: and g: added to font body class
+ You can now keep your Gantry Framework translation files (.mo) in the WordPress languages directory ie. wp-content/languages/gantry/
+ Gantry is now going to automatically add the templateName-custom.css file if found under css/ directory
+ Added fix for the WP SEO when "Force Rewrite Titles" enabled
^ Title gizmo should now respect the wp_title filter

------- 4.0.4 Release [] ------
# Fixed a bug introduced in 4.0.3 where CSS files could load with double slashes

------- 4.0.3 Release [] ------
# Fixed compatibility with WordPress Multi Site installations
# Fixed a notice where siteurl option didn't had path set

------- 4.0.2 Release [] ------
# Reverted some CSS changes in gantry.css which could cause some bad color appearance in couple themes

------- 4.0.1 Release [] ------
# Fixed incompatibility with PHP 5.2

------- 4.0.0 Release [] ------
! Next Gantry Framework Major Release. Please always do a full backup of your site before upgrading!
+ LESS support
+ Responsive layouts are fully supported
+ Brand new Admin UI
+ Caching improvements
+ Filtering in the Assignments tab
+ Ability to choose a chrome per widget
+ Support for the new Dropdown menu
+ New Search Highlight gizmo to allow to highlight search query without losing page content formatting
+ Added back the gantryjson.class.php file for backwards compatibility with Crystalline
+ Many more!

------- 1.31 Release [] ------
# Fix for the caching issue which caused the "Fatal error: Call to a member function getGridcss()"
! Please clean your site/browser cache after updating Gantry to 1.31

------- 1.30 Release [] ------
# Fix for the "Fatal error: Call to a member function isEnabled()" in some rare cases
# Fixed Notices appearing after enabling Debug mode
# Fix to keep accessibility mode disabled
# Fixed overrides behavior involving presets
# Fixed overrides badges opacity
- Removed some unnecessary CSS for the future theme compatibility
+ Framework modifications to allow usage of responsive layouts in the future

------- 1.29 Release [] ------
+ BBPress is now fully supported
+ Added Maintenance Mode gizmo
+ Jigoshop compatibility fix
# Fixed JS issue with gantry-layer that could block admin in certain themes (ie. Panacea)
# Compatibility fixes for WordPress 3.5

------- 1.28 Release [] ------
^ Much improved 3rd party plugin compatibility - please note that you need to update your theme for this to work
^ WooCommerce gizmo is now Compatibility gizmo which will be used to add support to various 3rd party plugins and keep them in one place
# Add to Cart should now work fine with the WooCommerce product variants

------- 1.27 Release [] ------
# Small CSS fix for the RTL featured image
^ Updated the version of MooTools to 1.4.5 with compatibility layer

------- 1.26 Release [] ------
# Gantry now properly loads the widget_admin.css file
# Added missing CSS and JS code for the "selectedset" field type
# Added missing charset
+ Added <meta> tag in the displayHead function
# Proper fix for the Children items in the Mobile Menu
^ Display Single Post Category in Breadcrumbs widget is on by default

------- 1.25 Release [] ------
# Fix for custom post types and loop hierarchy not properly supported
# Fix for missing $this in the gantrybodylayout.class.php
# Custom Post Type archive theme files should be now loading properly
# Fix a bug when certain options wouldn't be activated after reloading override settings
# Fix for the overflow in the admin area
# Fix for CSS overflow in the Mobile Menu
+ Added WooCommerce support

------- 1.24 Release [] ------
# Fix for the Warning:preg_match() on the Widgets page when The Events Calendar plugin was activated (possible solution for similar issues with other plugins)
+ Added some small CSS fixes for font-size of the meta elements
^ Gantry iPhone Menu is now a Gantry Mobile Menu as it works both on iPhone and Android platforms
# Fix for the same domain check which could cause issues ie. with Wordpress MU Domain Mapping
# Fixed some Ajax behavior on the widget overrides page

------- 1.23 Release [] ------
# Fixed the z-index of WordPress screen meta tabs on the widgets page
+ Added CSS code used for multi-column blog view

------- 1.22 Release [] ------
+ Added support for the custom variations in widgets (custom CSS classes)
# Fixed the count widgets in WP 3.3 theme settings page
# Fixed the z-index value of the WP 3.3 flyout menus on admin pages with Gantry Overrides bar
# Fixed Clear Cache button and Presets switcher

------- 1.21 Release [] ------
# Fixed the incrementation bug in the bugfix.php script which could cause a widgets id conflict

------- 1.20 Release [] ------
# Added a fix to prevent frozen widgets and widgets appearing in wrong overrides
+ Added a script that should automatically fix all existing frozen widgets and overrides
+ Added support for WordPress Multi Site installations
# Added check for instance settings of WordPress widget classes
# The $ signs in page titles should be now displayed properly
^ Added require_once parameter to the locate_type function
# Updated the SmartLoad gizmo JS file to fix XPath Ignores
# Fixed the situation when the MooTools would only get loaded when the Build Spans gizmo is enabled
# Fixed the situation when front-page.php file wouldn't get loaded from the proper location
^ Modified the MU Register gizmo to add very basic width settings to the activate and signup pages

------- 1.19 Release [] ------
! Adjusted for WordPress plugin directory

------- 1.18 Release [] ------
# Fixed oddity in PHP 5.2.9 where some settings in the backend wouldn't load
+ Added support for additional content type dirs
^ Added ability to filter out page title in Title gizmo
+ Added support for the 9 grid layout
+ Ability to define author name to appear as the page title in the theme settings page


------- 1.17 Release [] ------
+ Added widgets tooltips support
# Fixed styling for the multiple instances of inner-tabs
# Multiple instances of inner-tabs are now having default item selected
^ Changed Cache classname to RokCache for better compatibility
^ Upgraded mootools version
^ Upgraded RokNavMenu version to fix couple issues

------- 1.16 Release [] ------
^ Added WP version check for compatibility with WordPress 3.2
+ Added new Google WebFonts
+ Added support for All In One SEO home page title in Title gizmo

------- 1.15 Release [] ------
+ Added support for the All In One SEO single post and single page titles
+ Added check to see if there's more than 1 widget before any of the dividers in position
# Fix for bad URL generated by the getCurrentUrl

------- 1.14 Release [] ------
# Fixed support for the ordered body layouts

------- 1.13 Release [] ------
# Fix for fusion menu dropdowns
^ JS Speed Optimization: Toggles, SelectBoxes, Gradients, ColorChooser, use a more smart way to get initialized and on load time nothing is initialized until you interact with a field (backward compatible).
^ Admin Tips now allow IDs to better manage the tips XML files (backward compatible).
^ Load page size saved by 35-40%
+ Ability to display single post category in breadcrumbs
+ Added styling for textarea admin field
^ Removed Recent Comments default avatar styling in favor of rt-image class
# Fix for widget divider first with nothing above in sidebar
+ Added LoadPosition gizmo which lets you to load different widget positions in your content using shortcode and positions ID ie. [loadposition id="showcase"]
# Fix for nested layout object reverts

------- 1.12 Release [] ------
+ Added Categories admin field

------- 1.11 Release [] ------
^ Change to help prevent conflict with template modifiers like wptouch

------- 1.10 Release [] -------
^ Added input and tokens to the title gizmo
+ Added accessibility css code
# Fixed bug with currentUrl not being set right
^ Moved base init to template redirect
^ Added user css code
^ Added Category field to the Recent Posts widget

------- 1.9 Release [] -------
# Added better Cookie Path handling
# Fixed widget override selection bug! Yee Ha!

------- 1.8 Release [] -------
# Fixed Menu Items Assignements in Overrides
# Fixed Preset Saver in the backend
+ Added RokStyle gizmo
+ Added AutoParagraph gizmo
^ Updated MooTools to 1.3

------- 1.7 Release [] -------
# Added RTL css file support from gantry
+ Added Page Suffix gizmo
^ Modified the default comment styling
^ Logo points now to the Site URL not WP URL
^ Breadcrumbs 'Home' button points now to the Site URL not WP URL
+ Added default styling and home image for the breadcrumbs
^ Moved the Home button in breadcrumbs to be widget powered
^ Fixed pagination position in RTL mode
^ Swapped left padding to right one for lists in RTL
+ Added get_header, get_footer, and get_sidebar actions to help plugin compatibility
# Fixed Push pull for sidebars in RTL
+ Added Overlay field type
^ Added spans to links in certain widgets
^ Added default styling for Recent Comments widget
^ Breadcrumbs pathway will only appear on the single post or custom page

------- 1.6 Release [] -------
# Fixed swapping widget IDs in overrides

------- 1.5 Release [] -------
# Added check for empty widget postions in render
^ Gantry Logo widget 'Per Style' setting is no longer hidden
^ Changed some JS binds to follow the new ES5 specs
# Fixed Colorchooser and Gradient fields
+ Added cache removal to default widget and override widget ajax actions
^ Gantry Pages widget is adding extra 'active' class for current page
^ Gantry Categories widget is adding extra 'active' class for current category
^ Changed width and padding for the MU Register form
# Fix for cache clear issues
+ Made widget instance overrides available to ajax calls

------- 1.4 Release [] -------
^ Added displayFooter function and supporting renders for themes.
^ Add mootools script in jstools gizmo
# Fix for calling wp_head on the admin side.

------- 1.3 Release [] -------
# Fix for layouts not reversing in RTL mode
# Fix for Duplicating and missing CSS files

------- 1.2 Release [] -------
+ Added Minefield to the list of Browsers
+ Add support for Signup page to template page overrides
^ Force Widget Accessability Mode off for Gantry Themes
# Fixed addStyle to better handle -override files and get propper css file overrides from template
^ Moved cache to be WP Transients based.
+ Added base level diagnostics


------- 1.1 Release [17-Aug-2010] -------
# Fixed support for non WP_Widget based classes.

------- 1.0 Release [15-Aug-2010] -------
! Changelog Creation