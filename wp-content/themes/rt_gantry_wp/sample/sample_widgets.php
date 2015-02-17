<?php
        function replace_token_url($var){
        $out = $var;
        if (is_string($var)){
            $out = str_replace("@RT_SITE_URL@", get_bloginfo("wpurl"), $var);
        }
        return $out;
    }

    function filter_token_url($value, $oldvalue) {
        if (is_array($value)){
            return multidimensionalArrayMap("replace_token_url", $value);
        }
        else if (is_string($value))
            return replace_token_url($value);
        else
            return $value;
    }

    function multidimensionalArrayMap( $func, $arr )
    {
    $newArr = array();
    foreach( $arr as $key => $value )
        {
        $newArr[ $key ] = ( is_array( $value ) ? multidimensionalArrayMap( $func, $value ) : $func( $value ) );
        }
    return $newArr;
   }

    // unpublish hellow world
     $hello_world = array();
     $hello_world["ID"] = 1;
     $hello_world["post_status"] = "draft";
     wp_update_post( $hello_world );
      
    
        add_filter('pre_update_option_rt_gantry_wp-template-options', 'filter_token_url', 10, 2);

        update_option('rt_gantry_wp-template-options',array (
  'template_full_name' => 'Gantry',
  'template_author' => 'RocketTheme',
  'grid_system' => '12',
  'template_prefix' => 'gantry-',
  'cookie_time' => '31536000',
  'name' => 'Preset1',
  'copy_lang_files_if_diff' => '1',
  'custom_widget_variations' => '1',
  'blog' => 
  array (
    'cat' => '',
    'post' => 
    array (
      'lead-items' => '1',
      'intro-items' => '3',
      'columns' => '3',
    ),
    'query' => 
    array (
      'type' => 'post',
      'order' => 'date',
      'custom' => '',
    ),
    'content' => 'content',
    'page-heading' => 
    array (
      'enabled' => '0',
      'text' => '',
    ),
    'post-title' => 
    array (
      'enabled' => '1',
      'link' => '1',
    ),
    'meta-author' => 
    array (
      'enabled' => '1',
      'prefix' => 'Written by',
    ),
    'meta-date' => 
    array (
      'enabled' => '1',
      'prefix' => 'Published on',
      'format' => 'l, d F Y H:i',
    ),
    'meta-modified' => 
    array (
      'enabled' => '0',
      'prefix' => 'Last Updated on',
      'format' => 'l, d F Y H:i',
    ),
    'meta-comments' => 
    array (
      'enabled' => '1',
      'link' => '1',
    ),
    'meta-category' => 
    array (
      'enabled' => '1',
      'link' => '1',
      'prefix' => 'Category:',
    ),
    'meta-category-parent' => 
    array (
      'enabled' => '0',
      'link' => '0',
      'prefix' => '',
    ),
    'readmore' => 
    array (
      'text' => 'Learn More',
      'show' => 'never',
    ),
  ),
  'page' => 
  array (
    'page-heading' => 
    array (
      'enabled' => '0',
      'text' => '',
    ),
    'title' => 
    array (
      'enabled' => '1',
      'link' => '1',
    ),
    'meta-author' => 
    array (
      'enabled' => '0',
      'prefix' => '',
    ),
    'meta-date' => 
    array (
      'enabled' => '0',
      'prefix' => '',
      'format' => 'l, d F Y H:i',
    ),
    'meta-modified' => 
    array (
      'enabled' => '0',
      'prefix' => 'Last Updated on',
      'format' => 'l, d F Y H:i',
    ),
    'meta-comments' => 
    array (
      'enabled' => '0',
      'link' => '0',
    ),
    'featured-image' => '0',
    'comments-form' => 
    array (
      'enabled' => '0',
      'html-tags' => '1',
    ),
  ),
  'single' => 
  array (
    'page-heading' => 
    array (
      'enabled' => '0',
      'text' => '',
    ),
    'title' => 
    array (
      'enabled' => '1',
      'link' => '0',
    ),
    'meta-author' => 
    array (
      'enabled' => '1',
      'prefix' => '',
    ),
    'meta-date' => 
    array (
      'enabled' => '1',
      'prefix' => '',
      'format' => 'l, d F Y H:i',
    ),
    'meta-modified' => 
    array (
      'enabled' => '0',
      'prefix' => 'Last Updated on',
      'format' => 'l, d F Y H:i',
    ),
    'meta-comments' => 
    array (
      'enabled' => '1',
      'link' => '0',
    ),
    'meta-category' => 
    array (
      'enabled' => '0',
      'link' => '0',
      'prefix' => '',
    ),
    'meta-category-parent' => 
    array (
      'enabled' => '0',
      'link' => '0',
      'prefix' => '',
    ),
    'featured-image' => '0',
    'tags' => '1',
    'footer' => '1',
    'comments-form' => 
    array (
      'enabled' => '1',
      'html-tags' => '1',
    ),
  ),
  'category' => 
  array (
    'count' => '5',
    'content' => 'content',
    'page-heading' => 
    array (
      'enabled' => '1',
      'text' => '',
    ),
    'post-title' => 
    array (
      'enabled' => '1',
      'link' => '0',
    ),
    'meta-author' => 
    array (
      'enabled' => '1',
      'prefix' => '',
    ),
    'meta-date' => 
    array (
      'enabled' => '1',
      'prefix' => '',
      'format' => 'l, d F Y H:i',
    ),
    'meta-modified' => 
    array (
      'enabled' => '0',
      'prefix' => 'Last Updated on',
      'format' => 'l, d F Y H:i',
    ),
    'meta-comments' => 
    array (
      'enabled' => '1',
      'link' => '0',
    ),
    'meta-category' => 
    array (
      'enabled' => '0',
      'link' => '0',
      'prefix' => '',
    ),
    'meta-category-parent' => 
    array (
      'enabled' => '0',
      'link' => '0',
      'prefix' => '',
    ),
    'readmore' => 
    array (
      'text' => 'Read more ...',
      'show' => 'auto',
    ),
  ),
  'archive' => 
  array (
    'count' => '5',
    'content' => 'content',
    'page-heading' => 
    array (
      'enabled' => '1',
      'text' => '',
    ),
    'post-title' => 
    array (
      'enabled' => '1',
      'link' => '0',
    ),
    'meta-author' => 
    array (
      'enabled' => '1',
      'prefix' => '',
    ),
    'meta-date' => 
    array (
      'enabled' => '1',
      'prefix' => '',
      'format' => 'l, d F Y H:i',
    ),
    'meta-modified' => 
    array (
      'enabled' => '0',
      'prefix' => 'Last Updated on',
      'format' => 'l, d F Y H:i',
    ),
    'meta-comments' => 
    array (
      'enabled' => '1',
      'link' => '0',
    ),
    'meta-category' => 
    array (
      'enabled' => '0',
      'link' => '0',
      'prefix' => '',
    ),
    'meta-category-parent' => 
    array (
      'enabled' => '0',
      'link' => '0',
      'prefix' => '',
    ),
    'readmore' => 
    array (
      'text' => 'Read more ...',
      'show' => 'auto',
    ),
  ),
  'tag' => 
  array (
    'count' => '5',
    'content' => 'content',
    'page-heading' => 
    array (
      'enabled' => '1',
      'text' => '',
    ),
    'post-title' => 
    array (
      'enabled' => '1',
      'link' => '0',
    ),
    'meta-author' => 
    array (
      'enabled' => '1',
      'prefix' => '',
    ),
    'meta-date' => 
    array (
      'enabled' => '1',
      'prefix' => '',
      'format' => 'l, d F Y H:i',
    ),
    'meta-modified' => 
    array (
      'enabled' => '0',
      'prefix' => 'Last Updated on',
      'format' => 'l, d F Y H:i',
    ),
    'meta-comments' => 
    array (
      'enabled' => '1',
      'link' => '0',
    ),
    'meta-category' => 
    array (
      'enabled' => '0',
      'link' => '0',
      'prefix' => '',
    ),
    'meta-category-parent' => 
    array (
      'enabled' => '0',
      'link' => '0',
      'prefix' => '',
    ),
    'readmore' => 
    array (
      'text' => 'Read more ...',
      'show' => 'auto',
    ),
  ),
  'search' => 
  array (
    'count' => '5',
    'content' => 'content',
    'page-heading' => 
    array (
      'enabled' => '1',
      'text' => '',
    ),
    'post-title' => 
    array (
      'enabled' => '1',
      'link' => '0',
    ),
    'meta-author' => 
    array (
      'enabled' => '1',
      'prefix' => '',
    ),
    'meta-date' => 
    array (
      'enabled' => '1',
      'prefix' => '',
      'format' => 'l, d F Y H:i',
    ),
    'meta-modified' => 
    array (
      'enabled' => '0',
      'prefix' => 'Last Updated on',
      'format' => 'l, d F Y H:i',
    ),
    'meta-comments' => 
    array (
      'enabled' => '1',
      'link' => '0',
    ),
    'meta-category' => 
    array (
      'enabled' => '0',
      'link' => '0',
      'prefix' => '',
    ),
    'meta-category-parent' => 
    array (
      'enabled' => '0',
      'link' => '0',
      'prefix' => '',
    ),
    'readmore' => 
    array (
      'text' => 'Read more ...',
      'show' => 'auto',
    ),
  ),
  'thumbnails-enabled' => '1',
  'logo' => 
  array (
    'type' => 'gantry',
    'custom' => 
    array (
      'image' => '',
    ),
  ),
  'headerstyle' => 'dark',
  'linkcolor' => '#2698de',
  'responsive-menu' => 'panel',
  'thumb' => 
  array (
    'width' => '142',
    'height' => '88',
    'position' => 'left',
  ),
  'webfonts' => 
  array (
    'enabled' => '0',
    'source' => 'google',
  ),
  'font' => 
  array (
    'family' => 's:helvetica',
    'size' => 'default',
    'size-is' => 'default',
  ),
  'pagination' => 
  array (
    'enabled' => '1',
    'show-results' => '1',
    'count' => '8',
  ),
  'wordpress-comments' => '1',
  'customcss' => '',
  'rtl-priority' => '7',
  'childcss-priority' => '100',
  'thumbnails-priority' => '1',
  'webfonts-priority' => '5',
  'styledeclaration-enabled' => '1',
  'pagesuffix' => 
  array (
    'enabled' => '0',
    'class' => '',
    'priority' => '2',
  ),
  'feedlinks' => 
  array (
    'enabled' => '1',
    'priority' => '1',
  ),
  'title' => 
  array (
    'format' => '',
    'priority' => '5',
  ),
  'widgetshortcodes' => 
  array (
    'enabled' => '1',
    'priority' => '2',
  ),
  'rokstyle' => 
  array (
    'enabled' => '1',
    'priority' => '5',
  ),
  'analytics' => 
  array (
    'enabled' => '0',
    'code' => '',
    'priority' => '3',
  ),
  'top' => 
  array (
    'layout' => '3,3,3,3',
    'showall' => '0',
    'showmax' => '6',
  ),
  'header' => 
  array (
    'layout' => 'a:1:{i:12;a:2:{i:4;a:4:{i:0;i:3;i:1;i:3;i:2;i:3;i:3;i:3;}i:2;a:2:{i:0;i:3;i:1;i:9;}}}',
    'showall' => '0',
    'showmax' => '6',
  ),
  'showcase' => 
  array (
    'layout' => '3,3,3,3',
    'showall' => '0',
    'showmax' => '6',
  ),
  'feature' => 
  array (
    'layout' => '3,3,3,3',
    'showall' => '0',
    'showmax' => '6',
  ),
  'utility' => 
  array (
    'layout' => '3,3,3,3',
    'showall' => '0',
    'showmax' => '6',
  ),
  'maintop' => 
  array (
    'layout' => '3,3,3,3',
    'showall' => '0',
    'showmax' => '6',
  ),
  'mainbodyPosition' => 'a:1:{i:12;a:2:{i:1;a:1:{s:2:"mb";i:12;}i:2;a:2:{s:2:"mb";i:8;s:2:"sa";i:4;}}}',
  'mainbottom' => 
  array (
    'layout' => '3,3,3,3',
    'showall' => '0',
    'showmax' => '6',
  ),
  'extension' => 
  array (
    'layout' => '3,3,3,3',
    'showall' => '0',
    'showmax' => '6',
  ),
  'bottom' => 
  array (
    'layout' => '3,3,3,3',
    'showall' => '0',
    'showmax' => '6',
  ),
  'footer' => 
  array (
    'layout' => '3,3,3,3',
    'showall' => '0',
    'showmax' => '6',
  ),
  'copyright' => 
  array (
    'layout' => '3,3,3,3',
    'showall' => '0',
    'showmax' => '6',
  ),
  'loadposition-enabled' => '1',
  'layout-mode' => 'responsive',
  'maintenancemode' => 
  array (
    'enabled' => '0',
    'message' => 'Site is currently in the maintenance mode. Please try again later.',
  ),
  'loadtransition' => '0',
  'component-enabled' => '1',
  'mainbody-enabled' => '1',
  'rtl-enabled' => '1',
  'autoparagraphs' => 
  array (
    'enabled' => '1',
    'type' => 'both',
    'priority' => '5',
  ),
  'texturize-enabled' => '0',
  'selectivizr-enabled' => '0',
  'less' => 
  array (
    'compression' => '1',
    'compilewait' => '2',
    'debugheader' => '0',
  ),
  'contact' => 
  array (
    'email' => '',
    'recaptcha' => 
    array (
      'enabled' => '0',
      'publickey' => '',
      'privatekey' => '',
    ),
  ),
));

        remove_filter('pre_update_option_rt_gantry_wp-template-options', 'filter_token_url', 10, 2);

        add_filter('pre_update_option_rt_gantry_wp-template-options-override-4', 'filter_token_url', 10, 2);

        update_option('rt_gantry_wp-template-options-override-4',array (
  'maintop' => 
  array (
    'layout' => 'a:1:{i:12;a:2:{i:4;a:4:{i:0;i:3;i:1;i:3;i:2;i:3;i:3;i:3;}i:3;a:3:{i:0;i:3;i:1;i:6;i:2;i:3;}}}',
    'showall' => '0',
    'showmax' => '6',
  ),
  'mainbodyPosition' => 'a:1:{i:12;a:2:{i:2;a:2:{s:2:"mb";i:8;s:2:"sa";i:4;}i:3;a:3:{s:2:"sa";i:3;s:2:"mb";i:6;s:2:"sb";i:3;}}}',
));

        remove_filter('pre_update_option_rt_gantry_wp-template-options-override-4', 'filter_token_url', 10, 2);

        add_filter('pre_update_option_rt_gantry_wp-template-options-override-assignments-1', 'filter_token_url', 10, 2);

        update_option('rt_gantry_wp-template-options-override-assignments-1',array (
  'post_type' => 
  array (
    'page' => 
    array (
      0 => 7,
    ),
  ),
));

        remove_filter('pre_update_option_rt_gantry_wp-template-options-override-assignments-1', 'filter_token_url', 10, 2);

        add_filter('pre_update_option_rt_gantry_wp-template-options-override-assignments-2', 'filter_token_url', 10, 2);

        update_option('rt_gantry_wp-template-options-override-assignments-2',array (
  'post_type' => 
  array (
    'page' => 
    array (
      0 => 8,
    ),
  ),
));

        remove_filter('pre_update_option_rt_gantry_wp-template-options-override-assignments-2', 'filter_token_url', 10, 2);

        add_filter('pre_update_option_rt_gantry_wp-template-options-override-assignments-3', 'filter_token_url', 10, 2);

        update_option('rt_gantry_wp-template-options-override-assignments-3',array (
  'post_type' => 
  array (
    'page' => 
    array (
      0 => 21,
    ),
  ),
));

        remove_filter('pre_update_option_rt_gantry_wp-template-options-override-assignments-3', 'filter_token_url', 10, 2);

        add_filter('pre_update_option_rt_gantry_wp-template-options-override-assignments-4', 'filter_token_url', 10, 2);

        update_option('rt_gantry_wp-template-options-override-assignments-4',array (
  'templatepage' => 
  array (
    'front_page' => true,
    'home' => true,
  ),
));

        remove_filter('pre_update_option_rt_gantry_wp-template-options-override-assignments-4', 'filter_token_url', 10, 2);

        add_filter('pre_update_option_rt_gantry_wp-template-options-override-assignments-5', 'filter_token_url', 10, 2);

        update_option('rt_gantry_wp-template-options-override-assignments-5',array (
  'post_type' => 
  array (
    'page' => 
    array (
      0 => 38,
    ),
  ),
));

        remove_filter('pre_update_option_rt_gantry_wp-template-options-override-assignments-5', 'filter_token_url', 10, 2);

        add_filter('pre_update_option_rt_gantry_wp-template-options-override-sidebar-1', 'filter_token_url', 10, 2);

        update_option('rt_gantry_wp-template-options-override-sidebar-1',array (
  'sidebar' => 
  array (
    0 => 'text-10002',
    1 => 'text-10003',
    2 => 'text-10004',
  ),
  'content-bottom' => 
  array (
    0 => 'text-10005',
    1 => 'text-10007',
    2 => 'gantrydivider-10003',
    3 => 'text-10006',
    4 => 'text-10008',
  ),
  'wp_inactive_widgets' => 
  array (
  ),
));

        remove_filter('pre_update_option_rt_gantry_wp-template-options-override-sidebar-1', 'filter_token_url', 10, 2);

        add_filter('pre_update_option_rt_gantry_wp-template-options-override-sidebar-2', 'filter_token_url', 10, 2);

        update_option('rt_gantry_wp-template-options-override-sidebar-2',array (
  'wp_inactive_widgets' => 
  array (
  ),
  'sidebar' => 
  array (
    0 => 'text-20003',
    1 => 'text-20004',
    2 => 'text-20005',
    3 => 'text-20007',
    4 => 'text-20006',
  ),
));

        remove_filter('pre_update_option_rt_gantry_wp-template-options-override-sidebar-2', 'filter_token_url', 10, 2);

        add_filter('pre_update_option_rt_gantry_wp-template-options-override-sidebar-3', 'filter_token_url', 10, 2);

        update_option('rt_gantry_wp-template-options-override-sidebar-3',array (
  'sidebar' => 
  array (
  ),
  'wp_inactive_widgets' => 
  array (
  ),
));

        remove_filter('pre_update_option_rt_gantry_wp-template-options-override-sidebar-3', 'filter_token_url', 10, 2);

        add_filter('pre_update_option_rt_gantry_wp-template-options-override-sidebar-4', 'filter_token_url', 10, 2);

        update_option('rt_gantry_wp-template-options-override-sidebar-4',array (
  'wp_inactive_widgets' => 
  array (
  ),
  'showcase' => 
  array (
    0 => 'text-40002',
  ),
  'maintop' => 
  array (
    0 => 'text-40003',
    1 => 'gantrydivider-40003',
    2 => 'text-40005',
    3 => 'gantrydivider-40004',
    4 => 'text-40004',
  ),
  'sidebar' => 
  array (
    0 => 'gantry_menu-40005',
    1 => 'text-40006',
    2 => 'gantrydivider-40005',
    3 => 'text-40007',
  ),
  'footer' => 
  array (
    0 => 'text-40008',
    1 => 'gantrydivider-40006',
    2 => 'text-40009',
    3 => 'gantrydivider-40008',
    4 => 'text-40010',
    5 => 'gantrydivider-40007',
    6 => 'text-40011',
  ),
));

        remove_filter('pre_update_option_rt_gantry_wp-template-options-override-sidebar-4', 'filter_token_url', 10, 2);

        add_filter('pre_update_option_rt_gantry_wp-template-options-override-sidebar-5', 'filter_token_url', 10, 2);

        update_option('rt_gantry_wp-template-options-override-sidebar-5',array (
  'breadcrumb' => 
  array (
    0 => 'gantry_breakcrumbs-50002',
  ),
  'wp_inactive_widgets' => 
  array (
  ),
));

        remove_filter('pre_update_option_rt_gantry_wp-template-options-override-sidebar-5', 'filter_token_url', 10, 2);

        add_filter('pre_update_option_rt_gantry_wp-template-options-override-widgets-1', 'filter_token_url', 10, 2);

        update_option('rt_gantry_wp-template-options-override-widgets-1',array (
  'widget_text' => 
  array (
    10002 => 
    array (
      'title' => 'Box1 Widget Variation',
      'text' => '<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>

<a href="#" class="readon">Button</a>',
      'filter' => false,
      'box-variation' => 'box1',
      'title-variation' => '',
      'custom-variations' => '',
      'widget_chrome' => '',
      'box-variations' => 'box1',
      'title-variations' => '',
    ),
    10003 => 
    array (
      'title' => 'Box2 Widget Variation',
      'text' => '<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>

<a href="#" class="readon">Button</a>',
      'filter' => false,
      'box-variation' => 'box2',
      'title-variation' => '',
      'custom-variations' => '',
      'widget_chrome' => '',
      'box-variations' => 'box2',
      'title-variations' => '',
    ),
    10004 => 
    array (
      'title' => 'Box3 Widget Variation',
      'text' => '<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>

<a href="#" class="readon">Button</a>',
      'filter' => false,
      'box-variation' => 'box3',
      'title-variation' => '',
      'custom-variations' => '',
      'widget_chrome' => '',
      'box-variations' => 'box3',
      'title-variations' => '',
    ),
    10005 => 
    array (
      'title' => 'Title1 Widget Variation',
      'text' => '<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>

<a href="#" class="readon">Button</a>',
      'filter' => false,
      'box-variation' => '',
      'title-variation' => 'title1',
      'custom-variations' => '',
      'widget_chrome' => '',
      'box-variations' => '',
      'title-variations' => 'title1',
    ),
    10006 => 
    array (
      'title' => 'Title2 Widget Variation',
      'text' => '<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>

<a href="#" class="readon">Button</a>',
      'filter' => false,
      'box-variation' => '',
      'title-variation' => 'title2',
      'custom-variations' => '',
      'widget_chrome' => '',
      'box-variations' => '',
      'title-variations' => 'title2',
    ),
    10007 => 
    array (
      'title' => 'Title3 Widget Variation',
      'text' => '<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>

<a href="#" class="readon">Button</a>',
      'filter' => false,
      'box-variation' => '',
      'title-variation' => 'title3',
      'custom-variations' => '',
      'widget_chrome' => '',
      'box-variations' => '',
      'title-variations' => 'title3',
    ),
    10008 => 
    array (
      'title' => 'Title4 Widget Variation',
      'text' => '<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>

<a href="#" class="readon">Button</a>',
      'filter' => false,
      'box-variation' => '',
      'title-variation' => 'title4',
      'custom-variations' => '',
      'widget_chrome' => '',
      'box-variations' => '',
      'title-variations' => 'title4',
    ),
    '_multiwidget' => 1,
  ),
  'widget_gantrydivider' => 
  array (
    10003 => 
    array (
    ),
    '_multiwidget' => 1,
  ),
));

        remove_filter('pre_update_option_rt_gantry_wp-template-options-override-widgets-1', 'filter_token_url', 10, 2);

        add_filter('pre_update_option_rt_gantry_wp-template-options-override-widgets-2', 'filter_token_url', 10, 2);

        update_option('rt_gantry_wp-template-options-override-widgets-2',array (
  'widget_text' => 
  array (
    20003 => 
    array (
      'title' => 'MainBody Layouts',
      'text' => '<p>Configure at <strong>Admin Dashboard &rarr; Gantry Theme</strong>, then go to <strong>Layouts</strong> tab and set the varying Mainbody/Sidebar layout possibilities.</p>

<p>Here, you can change both the grid size of the <strong>Mainbody/Sidebar</strong> position, but also the location of the sidebars. You can place the sidebars at varying <strong>combinations</strong> to the left, right or both or the Mainbody.</p>',
      'filter' => false,
      'box-variation' => 'box2',
      'title-variation' => '',
      'custom-variations' => '',
      'widget_chrome' => '',
      'box-variations' => 'box2',
      'title-variations' => '',
    ),
    20004 => 
    array (
      'title' => 'Forced Positions',
      'text' => '<p>There are times when you just don\'t want to have your widgets taking up all the room in a horizontal row no matter what the layout. For example you might want to have a widget on the left and a widget on the right, with nothing in the middle.</p> 

<p>This is made easy with Gantry with the <strong>Force Positions</strong> parameter for each layout, allowing you to set the <strong>count</strong> to a specific row number, such as 4, even if 4 widgets are not placed in that row.</p>',
      'filter' => false,
      'box-variation' => '',
      'title-variation' => '',
      'custom-variations' => '',
      'widget_chrome' => '',
      'box-variations' => '',
      'title-variations' => '',
    ),
    20005 => 
    array (
      'title' => 'Injected Gizmos',
      'text' => '<p><strong>Gizmos</strong> are specific elements of functionality, that are incredibly flexible and can be used to perform any kind of logic you would need. They are injected into a site when enabled, and are responsible for certain functionallity; inclusive of: Page Class Suffix, <strong>RokStyle</strong>, Custom Title Tag and more.</p>',
      'filter' => false,
      'box-variation' => 'box1',
      'title-variation' => '',
      'custom-variations' => '',
      'widget_chrome' => '',
      'box-variations' => 'box1',
      'title-variations' => '',
    ),
    20006 => 
    array (
      'title' => 'Collapsible Positions',
      'text' => '<p>If no widgets are placed in a position, the entire area or row will not appear or collapse.</p>',
      'filter' => false,
      'box-variation' => 'box2',
      'title-variation' => '',
      'custom-variations' => '',
      'widget_chrome' => '',
      'box-variations' => 'box2',
      'title-variations' => '',
    ),
    20007 => 
    array (
      'title' => 'Grid Sizes',
      'text' => '<p>Configure at <strong>Admin Dashboard &rarr; Gantry Theme</strong>, then go to <strong>Layouts</strong> to set the grid widths and allocated positions.</p>

<p>By default, each grid is given an <strong>equal</strong> distribution, but this can be modified to a <strong>custom</strong> distribution between widgets, such as <strong>3/4/5</strong> instead of <strong>4/4/4</strong>. These options are available for when <strong>2-6</strong> widgets are present.</p>',
      'filter' => false,
      'box-variation' => '',
      'title-variation' => '',
      'custom-variations' => '',
      'widget_chrome' => '',
      'box-variations' => '',
      'title-variations' => '',
    ),
    '_multiwidget' => 1,
  ),
));

        remove_filter('pre_update_option_rt_gantry_wp-template-options-override-widgets-2', 'filter_token_url', 10, 2);

        add_filter('pre_update_option_rt_gantry_wp-template-options-override-widgets-3', 'filter_token_url', 10, 2);

        update_option('rt_gantry_wp-template-options-override-widgets-3',array (
  'widget_gantry_menu' => 
  array (
    '_multiwidget' => 1,
  ),
));

        remove_filter('pre_update_option_rt_gantry_wp-template-options-override-widgets-3', 'filter_token_url', 10, 2);

        add_filter('pre_update_option_rt_gantry_wp-template-options-override-widgets-4', 'filter_token_url', 10, 2);

        update_option('rt_gantry_wp-template-options-override-widgets-4',array (
  'widget_text' => 
  array (
    40002 => 
    array (
      'title' => '',
      'text' => '<div class="promo-image">
<img src="@RT_SITE_URL@/wp-content/rockettheme/rt_gantry_wp/general/gantryrocket.png"/>
</div>
<div class="promo-desc">
<h1><b>What is Gantry?</b></h1>
<p><b>Gantry</b> is a comprehensive set of <b>building blocks to enable the rapid development</b> and <b>realization of a design into a flexible</b> and <b>powerful web platform theme.</b></p>
<a href="http://www.gantry-framework.org" class="readon">More Information</a>
</div>',
      'filter' => false,
      'box-variation' => '',
      'title-variation' => '',
      'custom-variations' => 'promo',
      'widget_chrome' => '',
      'box-variations' => '',
      'title-variations' => '',
    ),
    40003 => 
    array (
      'title' => 'Feature Focus',
      'text' => '<h4 class="icon-asterisk"> LESS Support</h4><br />
<p>A extended stylesheet language with dynamic behaviours.</p>

<h4 class="icon-asterisk"> Streamlined UI</h4><br />
<p>A new and optimized UI for swifter template configuration.</p>

<a href="http://www.gantry-framework.org" class="readon">More Features</a>',
      'filter' => false,
      'box-variation' => '',
      'title-variation' => '',
      'custom-variations' => '',
      'widget_chrome' => '',
    ),
    40004 => 
    array (
      'title' => 'LESS is More',
      'text' => '<p><strong>LESS</strong> is a stylesheet language, that extends CSS with <a href="#">dynamic behaviours</a> such as variables.</p>

<p>The <strong>LESS</strong> files are compiled on the server into <a href="#">compressed CSS stylesheets</a>, for <strong>optimization</strong>.</p>

<a href="http://lesscss.org/" class="readon">Learn More</a>',
      'filter' => false,
      'box-variation' => 'box3',
      'title-variation' => '',
      'custom-variations' => '',
      'widget_chrome' => '',
      'box-variations' => 'box3',
      'title-variations' => '',
    ),
    40005 => 
    array (
      'title' => 'Powerful Responsive Layout',
      'text' => '<p>A <strong>responsive</strong> layout adapts to the size of the viewing device, such as a desktop, smartphone or <strong>tablet</strong>, versus showing a separate layout.</p>

<div class="gantry-width-50 gantry-width-block">
<p class="gantry-left">
    <strong class="icon-adjust"> Five Modes:</strong> Responses for phones, tablets &amp; desktops.
</p>
<p class="gantry-left">
    <strong class="icon-external-link"> Navigation:</strong> Basic select box menu system for smartphones.
</p>
</div>

<div class="gantry-width-50 gantry-width-block">
    <p class="gantry-right hidden-phone">
		<strong class="icon-flag"> Grid System:</strong> Responsive structure, up to 6 per row.
	</p>
<p class="gantry-right hidden-phone">
	<strong class="icon-print"> Support Classes:</strong> Visible &amp; Hide classes for each response modes.
</p>

<p class="gantry-left visible-phone">
	<strong class="icon-flag"> Grid System:</strong> Responsive structure, up to 6 per row.
</p>
<p class="gantry-left visible-phone">
	<strong class="icon-print"> Support Classes:</strong> Visible &amp; Hide classes for each response modes.
</p>
</div>

<div class="clear"></div>

<a href="http://www.gantry-framework.org" class="readon">Learn More</a>',
      'filter' => false,
      'box-variation' => '',
      'title-variation' => '',
      'custom-variations' => '',
      'widget_chrome' => '',
    ),
    40006 => 
    array (
      'title' => 'Help & Guides',
      'text' => '<p class="hidden-large">A rich resource of Video and Written tutorials are available at Gantry-Framework.org.</p>
<p class="visible-large">A rich resource of Video and Written tutorials are available.</p>
<p><a class="readon" href="http://www.gantry-framework.org/">Read More</a></p>',
      'filter' => false,
      'box-variation' => '',
      'title-variation' => '',
      'custom-variations' => 'nomargintop nopaddingtop',
      'widget_chrome' => '',
      'box-variations' => '',
      'title-variations' => '',
    ),
    40007 => 
    array (
      'title' => 'More Features',
      'text' => '<ul>
    <li>
        <strong>Built-in Features</strong><br />
    	<span>Examples include font-sizer, Google PrettyPrint, Google Analytics, to-top slider.</span>
	</li>
	<li>
		<strong>4 Column Mainbody:</strong><br />
		<span>Up to 3 total sidebars with each column\'s location being configurable.</span>
	</li>
	<li class="hidden-tablet hidden-phone">
		<strong>Flexible Parameters:</strong><br />
		<span>Ability to set parameters via URL, Cookie, Session, Presets, etc.</span>
	</li>
</ul>

<a href="http://www.gantry-framework.org/" class="readon">Read More</a>',
      'filter' => false,
      'box-variation' => 'box2',
      'title-variation' => '',
      'custom-variations' => '',
      'widget_chrome' => '',
      'box-variations' => 'box2',
      'title-variations' => '',
    ),
    40008 => 
    array (
      'title' => 'Typography',
      'text' => '<div class="customhidden-phone">
	Powerful <strong>typography</strong> from Bootstrap provides styling for tables, <strong>buttons</strong>, and other standard interface elements.</div>',
      'filter' => false,
      'box-variation' => '',
      'title-variation' => '',
      'custom-variations' => 'hidden-phone',
      'widget_chrome' => '',
      'box-variations' => '',
      'title-variations' => '',
    ),
    40009 => 
    array (
      'title' => 'Ajax Base',
      'text' => '<div class="customhidden-phone">
	Our <strong>AJAX</strong> system allows dynamic functionality in features as well as allowing AJAX for <strong>3rd party</strong> addons.</div>',
      'filter' => false,
      'box-variation' => '',
      'title-variation' => '',
      'custom-variations' => 'hidden-phone',
      'widget_chrome' => '',
      'box-variations' => '',
      'title-variations' => '',
    ),
    40010 => 
    array (
      'title' => 'Streamlined',
      'text' => '<div class="customhidden-phone">
	<strong>Gantry</strong> has an optimized codebase with speed, size, being the core tenets behind the <strong>framework</strong> design.</div>',
      'filter' => false,
      'box-variation' => '',
      'title-variation' => '',
      'custom-variations' => 'hidden-phone',
      'widget_chrome' => '',
      'box-variations' => '',
      'title-variations' => '',
    ),
    40011 => 
    array (
      'title' => 'Compatibility',
      'text' => '<div class="custom">
	<strong>Gantry 4</strong> is backwards compatible with all of the Gantry 1.3 <strong>templates and plugins.</strong></div>',
      'filter' => false,
      'box-variation' => '',
      'title-variation' => '',
      'custom-variations' => 'hidden-phone',
      'widget_chrome' => '',
      'box-variations' => '',
      'title-variations' => '',
    ),
    '_multiwidget' => 1,
  ),
  'widget_gantrydivider' => 
  array (
    40003 => 
    array (
    ),
    40004 => 
    array (
    ),
    40005 => 
    array (
    ),
    40006 => 
    array (
    ),
    40007 => 
    array (
    ),
    40008 => 
    array (
    ),
    '_multiwidget' => 1,
  ),
  'widget_gantry_menu' => 
  array (
    40005 => 
    array (
      'title' => 'Main Menu',
      'nav_menu' => 'menu',
      'theme' => 'gantry_splitmenu',
      'style' => '',
      'limit_levels' => '1',
      'startLevel' => '0',
      'endLevel' => '0',
      'showAllChildren' => '1',
      'show_empty_menu' => '0',
      'maxdepth' => '10',
      'box-variations' => '',
      'title-variations' => '',
      'widget_chrome' => '',
      'custom-variations' => 'hidden-phone nomarginbottom',
      'box-variation' => 0,
      'title-variation' => 0,
    ),
    '_multiwidget' => 1,
  ),
  'widget_gantry_loginform' => 
  array (
    '_multiwidget' => 1,
  ),
));

        remove_filter('pre_update_option_rt_gantry_wp-template-options-override-widgets-4', 'filter_token_url', 10, 2);

        add_filter('pre_update_option_rt_gantry_wp-template-options-override-widgets-5', 'filter_token_url', 10, 2);

        update_option('rt_gantry_wp-template-options-override-widgets-5',array (
  'widget_gantry_breakcrumbs' => 
  array (
    50002 => 
    array (
      'prefix' => 'You are here:',
      'category' => '1',
      'box-variation' => '',
      'title-variation' => '',
      'widget_chrome' => '',
      'custom-variations' => '',
    ),
    '_multiwidget' => 1,
  ),
));

        remove_filter('pre_update_option_rt_gantry_wp-template-options-override-widgets-5', 'filter_token_url', 10, 2);

        add_filter('pre_update_option_rt_gantry_wp-template-options-overrides', 'filter_token_url', 10, 2);

        update_option('rt_gantry_wp-template-options-overrides',array (
  1 => 'Widget Variations',
  2 => 'Widget Positions',
  3 => 'Typography',
  4 => 'Front Page',
  5 => 'WordPress',
));

        remove_filter('pre_update_option_rt_gantry_wp-template-options-overrides', 'filter_token_url', 10, 2);

        add_filter('pre_update_option_sidebars_widgets', 'filter_token_url', 10, 2);

        update_option('sidebars_widgets',array (
  'wp_inactive_widgets' => 
  array (
  ),
  'drawer' => 
  array (
  ),
  'top' => 
  array (
  ),
  'header' => 
  array (
    0 => 'gantry_logo-2',
    1 => 'gantrydivider-2',
    2 => 'gantry_menu-2',
  ),
  'showcase' => 
  array (
  ),
  'feature' => 
  array (
  ),
  'utility' => 
  array (
  ),
  'maintop' => 
  array (
  ),
  'fullwidth' => 
  array (
  ),
  'breadcrumb' => 
  array (
  ),
  'sidebar' => 
  array (
    0 => 'gantry_menu-3',
    1 => 'gantry_menu-4',
    2 => 'gantry_loginform-2',
  ),
  'content-top' => 
  array (
  ),
  'content-bottom' => 
  array (
  ),
  'mainbottom' => 
  array (
  ),
  'extension' => 
  array (
  ),
  'bottom' => 
  array (
  ),
  'footer' => 
  array (
  ),
  'copyright' => 
  array (
    0 => 'gantry_branding-2',
  ),
  'analytics' => 
  array (
  ),
  'debug' => 
  array (
  ),
  'array_version' => 3,
));

        remove_filter('pre_update_option_sidebars_widgets', 'filter_token_url', 10, 2);

        add_filter('pre_update_option_posts_per_page', 'filter_token_url', 10, 2);

        update_option('posts_per_page','1');

        remove_filter('pre_update_option_posts_per_page', 'filter_token_url', 10, 2);

        add_filter('pre_update_option_gantry_bugfix_WGANTRYFW_5', 'filter_token_url', 10, 2);

        update_option('gantry_bugfix_WGANTRYFW_5','1');

        remove_filter('pre_update_option_gantry_bugfix_WGANTRYFW_5', 'filter_token_url', 10, 2);

        add_filter('pre_update_option_permalink_structure', 'filter_token_url', 10, 2);

        update_option('permalink_structure','/%postname%/');

        remove_filter('pre_update_option_permalink_structure', 'filter_token_url', 10, 2);

        add_filter('pre_update_option_active_plugins', 'filter_token_url', 10, 2);

        update_option('active_plugins',array (
  0 => 'gantry/gantry.php',
));

        remove_filter('pre_update_option_active_plugins', 'filter_token_url', 10, 2);

        add_filter('pre_update_option_widget_gantry_logo', 'filter_token_url', 10, 2);

        update_option('widget_gantry_logo',array (
  2 => 
  array (
    'box-variations' => '',
    'title-variations' => '',
    'widget_chrome' => '',
    'custom-variations' => '',
    'box-variation' => 0,
    'title-variation' => 0,
  ),
  '_multiwidget' => 1,
));

        remove_filter('pre_update_option_widget_gantry_logo', 'filter_token_url', 10, 2);

        add_filter('pre_update_option_widget_gantrydivider', 'filter_token_url', 10, 2);

        update_option('widget_gantrydivider',array (
  2 => 
  array (
  ),
  '_multiwidget' => 1,
));

        remove_filter('pre_update_option_widget_gantrydivider', 'filter_token_url', 10, 2);

        add_filter('pre_update_option_widget_gantry_menu', 'filter_token_url', 10, 2);

        update_option('widget_gantry_menu',array (
  2 => 
  array (
    'title' => '',
    'nav_menu' => 'menu',
    'theme' => 'gantry_dropdown',
    'style' => '',
    'limit_levels' => '0',
    'startLevel' => '0',
    'endLevel' => '0',
    'showAllChildren' => '1',
    'show_empty_menu' => '0',
    'maxdepth' => '10',
    'box-variations' => '',
    'title-variations' => '',
    'widget_chrome' => 'menu',
    'custom-variations' => '',
    'box-variation' => 0,
    'title-variation' => 0,
  ),
  3 => 
  array (
    'title' => 'Page Menu',
    'nav_menu' => 'menu',
    'theme' => 'gantry_splitmenu',
    'style' => '',
    'limit_levels' => '1',
    'startLevel' => '1',
    'endLevel' => '1',
    'showAllChildren' => '0',
    'show_empty_menu' => '0',
    'maxdepth' => '10',
    'box-variations' => '',
    'title-variations' => '',
    'widget_chrome' => '',
    'custom-variations' => '',
    'box-variation' => 0,
    'title-variation' => 0,
  ),
  4 => 
  array (
    'title' => 'Main Menu',
    'nav_menu' => 'menu',
    'theme' => 'gantry_splitmenu',
    'style' => '',
    'limit_levels' => '1',
    'startLevel' => '0',
    'endLevel' => '1',
    'showAllChildren' => '0',
    'show_empty_menu' => '0',
    'maxdepth' => '10',
    'box-variations' => '',
    'title-variations' => '',
    'widget_chrome' => '',
    'custom-variations' => '',
    'box-variation' => 0,
    'title-variation' => 0,
  ),
  '_multiwidget' => 1,
));

        remove_filter('pre_update_option_widget_gantry_menu', 'filter_token_url', 10, 2);

        add_filter('pre_update_option_widget_gantry_loginform', 'filter_token_url', 10, 2);

        update_option('widget_gantry_loginform',array (
  2 => 
  array (
    'title' => 'Login Form',
    'user_greeting' => 'Hi,',
    'pretext' => '',
    'posttext' => '',
    'box-variations' => '',
    'title-variations' => '',
    'widget_chrome' => '',
    'custom-variations' => '',
    'box-variation' => 0,
    'title-variation' => 0,
  ),
  '_multiwidget' => 1,
));

        remove_filter('pre_update_option_widget_gantry_loginform', 'filter_token_url', 10, 2);

        add_filter('pre_update_option_widget_gantry_branding', 'filter_token_url', 10, 2);

        update_option('widget_gantry_branding',array (
  2 => 
  array (
    'box-variations' => '',
    'title-variations' => '',
    'widget_chrome' => '',
    'custom-variations' => '',
    'box-variation' => 0,
    'title-variation' => 0,
  ),
  '_multiwidget' => 1,
));

        remove_filter('pre_update_option_widget_gantry_branding', 'filter_token_url', 10, 2);

$gantry_menu_items = array();
function rokimport_get_post_from_guid($guid) {
    global $wpdb;
    $guid = replace_token_url($guid);
    $posts = $wpdb->get_results("SELECT ID FROM " . $wpdb->posts . " WHERE guid = '" . $guid . "'");
    return (count($posts) > 0) ? $posts[0]->ID : 0;
}
function rokimport_get_taxonomy($name, $taxonomy) {
    $taxfield = get_term_by( "slug", $name, $taxonomy);
    return $taxfield->term_id;
}
global $wp_version;
if (version_compare($wp_version,"3.0",">=")){
$importing_menu = wp_get_nav_menu_object("menu");$menu_item_mapping = array(0=>0);$menu_item_mapping[11] = wp_update_nav_menu_item($importing_menu->term_id, 0, array('menu-item-parent-id' => $menu_item_mapping[0],'menu-item-type' => 'custom','menu-item-title' => 'Home','menu-item-status' => 'publish','menu-item-target' => '','menu-item-classes' => '','menu-item-description' => '','menu-item-xfn' => '','menu-item-position' => '1','menu-item-attr-title' => '','menu-item-url' => '@RT_SITE_URL@/'));$gantry_menu_items["menu"][$menu_item_mapping[11]] = array (
  'gantrymenu_item_subtext' => '',
  'gantrymenu_customimage' => '',
  'gantrymenu_customicon' => '',
  'gantrymenu_columns' => '1',
  'gantrymenu_distribution' => 'evenly',
  'gantrymenu_manual_distribution' => '',
  'gantrymenu_children_group' => '0',
  'gantrymenu_dropdown_width' => '',
  'gantrymenu_column_widths' => '',
);$menu_item_mapping[6] = wp_update_nav_menu_item($importing_menu->term_id, 0, array('menu-item-parent-id' => $menu_item_mapping[0],'menu-item-type' => 'custom','menu-item-title' => 'Features','menu-item-status' => 'publish','menu-item-target' => '','menu-item-classes' => '','menu-item-description' => '','menu-item-xfn' => '','menu-item-position' => '2','menu-item-attr-title' => '','menu-item-url' => '#'));$gantry_menu_items["menu"][$menu_item_mapping[6]] = array (
  'gantrymenu_item_subtext' => '',
  'gantrymenu_customimage' => '',
  'gantrymenu_customicon' => '',
  'gantrymenu_columns' => '1',
  'gantrymenu_distribution' => 'evenly',
  'gantrymenu_manual_distribution' => '',
  'gantrymenu_children_group' => '0',
  'gantrymenu_dropdown_width' => '',
  'gantrymenu_column_widths' => '',
);$menu_item_mapping[13] = wp_update_nav_menu_item($importing_menu->term_id, 0, array('menu-item-parent-id' => $menu_item_mapping[6],'menu-item-type' => 'post_type','menu-item-title' => 'Widget Variations','menu-item-status' => 'publish','menu-item-target' => '','menu-item-classes' => '','menu-item-description' => '','menu-item-xfn' => '','menu-item-position' => '3','menu-item-attr-title' => '','menu-item-object-id' => rokimport_get_post_from_guid('@RT_SITE_URL@/?page_id=7'),'menu-item-object' => 'page'));$gantry_menu_items["menu"][$menu_item_mapping[13]] = array (
  'gantrymenu_item_subtext' => '',
  'gantrymenu_customimage' => '',
  'gantrymenu_customicon' => '',
  'gantrymenu_columns' => '1',
  'gantrymenu_distribution' => 'evenly',
  'gantrymenu_manual_distribution' => '',
  'gantrymenu_children_group' => '0',
  'gantrymenu_dropdown_width' => '',
  'gantrymenu_column_widths' => '',
);$menu_item_mapping[12] = wp_update_nav_menu_item($importing_menu->term_id, 0, array('menu-item-parent-id' => $menu_item_mapping[6],'menu-item-type' => 'post_type','menu-item-title' => 'Widget Positions','menu-item-status' => 'publish','menu-item-target' => '','menu-item-classes' => '','menu-item-description' => '','menu-item-xfn' => '','menu-item-position' => '4','menu-item-attr-title' => '','menu-item-object-id' => rokimport_get_post_from_guid('@RT_SITE_URL@/?page_id=8'),'menu-item-object' => 'page'));$gantry_menu_items["menu"][$menu_item_mapping[12]] = array (
  'gantrymenu_item_subtext' => '',
  'gantrymenu_customimage' => '',
  'gantrymenu_customicon' => '',
  'gantrymenu_columns' => '1',
  'gantrymenu_distribution' => 'evenly',
  'gantrymenu_manual_distribution' => '',
  'gantrymenu_children_group' => '0',
  'gantrymenu_dropdown_width' => '',
  'gantrymenu_column_widths' => '',
);$menu_item_mapping[14] = wp_update_nav_menu_item($importing_menu->term_id, 0, array('menu-item-parent-id' => $menu_item_mapping[6],'menu-item-type' => 'custom','menu-item-title' => 'Menu Example','menu-item-status' => 'publish','menu-item-target' => '','menu-item-classes' => '','menu-item-description' => '','menu-item-xfn' => '','menu-item-position' => '5','menu-item-attr-title' => '','menu-item-url' => '#'));$gantry_menu_items["menu"][$menu_item_mapping[14]] = array (
  'gantrymenu_item_subtext' => '',
  'gantrymenu_customimage' => '',
  'gantrymenu_customicon' => '',
  'gantrymenu_columns' => '2',
  'gantrymenu_distribution' => 'manual',
  'gantrymenu_manual_distribution' => '3,1',
  'gantrymenu_children_group' => '0',
  'gantrymenu_dropdown_width' => '320',
  'gantrymenu_column_widths' => '',
);$menu_item_mapping[15] = wp_update_nav_menu_item($importing_menu->term_id, 0, array('menu-item-parent-id' => $menu_item_mapping[14],'menu-item-type' => 'custom','menu-item-title' => 'Menu Icon','menu-item-status' => 'publish','menu-item-target' => '','menu-item-classes' => '','menu-item-description' => '','menu-item-xfn' => '','menu-item-position' => '6','menu-item-attr-title' => '','menu-item-url' => '#'));$gantry_menu_items["menu"][$menu_item_mapping[15]] = array (
  'gantrymenu_item_subtext' => '',
  'gantrymenu_customimage' => '',
  'gantrymenu_customicon' => 'icon-list-ul',
  'gantrymenu_columns' => '1',
  'gantrymenu_distribution' => 'evenly',
  'gantrymenu_manual_distribution' => '',
  'gantrymenu_children_group' => '0',
  'gantrymenu_dropdown_width' => '',
  'gantrymenu_column_widths' => '',
);$menu_item_mapping[16] = wp_update_nav_menu_item($importing_menu->term_id, 0, array('menu-item-parent-id' => $menu_item_mapping[14],'menu-item-type' => 'custom','menu-item-title' => 'SubText Line','menu-item-status' => 'publish','menu-item-target' => '','menu-item-classes' => '','menu-item-description' => '','menu-item-xfn' => '','menu-item-position' => '7','menu-item-attr-title' => '','menu-item-url' => '#'));$gantry_menu_items["menu"][$menu_item_mapping[16]] = array (
  'gantrymenu_item_subtext' => 'Example Text',
  'gantrymenu_customimage' => '',
  'gantrymenu_customicon' => '',
  'gantrymenu_columns' => '1',
  'gantrymenu_distribution' => 'evenly',
  'gantrymenu_manual_distribution' => '',
  'gantrymenu_children_group' => '0',
  'gantrymenu_dropdown_width' => '',
  'gantrymenu_column_widths' => '',
);$menu_item_mapping[17] = wp_update_nav_menu_item($importing_menu->term_id, 0, array('menu-item-parent-id' => $menu_item_mapping[14],'menu-item-type' => 'custom','menu-item-title' => 'Menu Image','menu-item-status' => 'publish','menu-item-target' => '','menu-item-classes' => '','menu-item-description' => '','menu-item-xfn' => '','menu-item-position' => '8','menu-item-attr-title' => '','menu-item-url' => '#'));$gantry_menu_items["menu"][$menu_item_mapping[17]] = array (
  'gantrymenu_item_subtext' => '',
  'gantrymenu_customimage' => 'icon-notes.png',
  'gantrymenu_customicon' => '',
  'gantrymenu_columns' => '1',
  'gantrymenu_distribution' => 'evenly',
  'gantrymenu_manual_distribution' => '',
  'gantrymenu_children_group' => '0',
  'gantrymenu_dropdown_width' => '',
  'gantrymenu_column_widths' => '',
);$menu_item_mapping[18] = wp_update_nav_menu_item($importing_menu->term_id, 0, array('menu-item-parent-id' => $menu_item_mapping[14],'menu-item-type' => 'custom','menu-item-title' => 'Grouped Items','menu-item-status' => 'publish','menu-item-target' => '','menu-item-classes' => '','menu-item-description' => '','menu-item-xfn' => '','menu-item-position' => '9','menu-item-attr-title' => '','menu-item-url' => '#'));$gantry_menu_items["menu"][$menu_item_mapping[18]] = array (
  'gantrymenu_item_subtext' => '',
  'gantrymenu_customimage' => '',
  'gantrymenu_customicon' => '',
  'gantrymenu_columns' => '1',
  'gantrymenu_distribution' => 'evenly',
  'gantrymenu_manual_distribution' => '',
  'gantrymenu_children_group' => '1',
  'gantrymenu_dropdown_width' => '',
  'gantrymenu_column_widths' => '',
);$menu_item_mapping[19] = wp_update_nav_menu_item($importing_menu->term_id, 0, array('menu-item-parent-id' => $menu_item_mapping[18],'menu-item-type' => 'custom','menu-item-title' => 'Child Item','menu-item-status' => 'publish','menu-item-target' => '','menu-item-classes' => '','menu-item-description' => '','menu-item-xfn' => '','menu-item-position' => '10','menu-item-attr-title' => '','menu-item-url' => '#'));$gantry_menu_items["menu"][$menu_item_mapping[19]] = array (
  'gantrymenu_item_subtext' => '',
  'gantrymenu_customimage' => '',
  'gantrymenu_customicon' => '',
  'gantrymenu_columns' => '1',
  'gantrymenu_distribution' => 'evenly',
  'gantrymenu_manual_distribution' => '',
  'gantrymenu_children_group' => '0',
  'gantrymenu_dropdown_width' => '',
  'gantrymenu_column_widths' => '',
);$menu_item_mapping[20] = wp_update_nav_menu_item($importing_menu->term_id, 0, array('menu-item-parent-id' => $menu_item_mapping[18],'menu-item-type' => 'custom','menu-item-title' => 'Child Item','menu-item-status' => 'publish','menu-item-target' => '','menu-item-classes' => '','menu-item-description' => '','menu-item-xfn' => '','menu-item-position' => '11','menu-item-attr-title' => '','menu-item-url' => '#'));$gantry_menu_items["menu"][$menu_item_mapping[20]] = array (
  'gantrymenu_item_subtext' => '',
  'gantrymenu_customimage' => '',
  'gantrymenu_customicon' => '',
  'gantrymenu_columns' => '1',
  'gantrymenu_distribution' => 'evenly',
  'gantrymenu_manual_distribution' => '',
  'gantrymenu_children_group' => '0',
  'gantrymenu_dropdown_width' => '',
  'gantrymenu_column_widths' => '',
);$menu_item_mapping[24] = wp_update_nav_menu_item($importing_menu->term_id, 0, array('menu-item-parent-id' => $menu_item_mapping[0],'menu-item-type' => 'post_type','menu-item-title' => 'Typography','menu-item-status' => 'publish','menu-item-target' => '','menu-item-classes' => '','menu-item-description' => '','menu-item-xfn' => '','menu-item-position' => '12','menu-item-attr-title' => '','menu-item-object-id' => rokimport_get_post_from_guid('@RT_SITE_URL@/?page_id=21'),'menu-item-object' => 'page'));$gantry_menu_items["menu"][$menu_item_mapping[24]] = array (
  'gantrymenu_item_subtext' => '',
  'gantrymenu_customimage' => '',
  'gantrymenu_customicon' => '',
  'gantrymenu_columns' => '1',
  'gantrymenu_distribution' => 'evenly',
  'gantrymenu_manual_distribution' => '',
  'gantrymenu_children_group' => '0',
  'gantrymenu_dropdown_width' => '',
  'gantrymenu_column_widths' => '',
);$menu_item_mapping[39] = wp_update_nav_menu_item($importing_menu->term_id, 0, array('menu-item-parent-id' => $menu_item_mapping[0],'menu-item-type' => 'post_type','menu-item-title' => 'WordPress','menu-item-status' => 'publish','menu-item-target' => '','menu-item-classes' => '','menu-item-description' => '','menu-item-xfn' => '','menu-item-position' => '13','menu-item-attr-title' => '','menu-item-object-id' => rokimport_get_post_from_guid('@RT_SITE_URL@/?page_id=38'),'menu-item-object' => 'page'));$gantry_menu_items["menu"][$menu_item_mapping[39]] = array (
  'gantrymenu_item_subtext' => '',
  'gantrymenu_customimage' => '',
  'gantrymenu_customicon' => '',
  'gantrymenu_columns' => '1',
  'gantrymenu_distribution' => 'evenly',
  'gantrymenu_manual_distribution' => '',
  'gantrymenu_children_group' => '0',
  'gantrymenu_dropdown_width' => '',
  'gantrymenu_column_widths' => '',
);$menu_item_mapping[25] = wp_update_nav_menu_item($importing_menu->term_id, 0, array('menu-item-parent-id' => $menu_item_mapping[0],'menu-item-type' => 'post_type','menu-item-title' => 'Presets','menu-item-status' => 'publish','menu-item-target' => '','menu-item-classes' => '','menu-item-description' => '','menu-item-xfn' => '','menu-item-position' => '14','menu-item-attr-title' => '','menu-item-object-id' => rokimport_get_post_from_guid('@RT_SITE_URL@/?page_id=22'),'menu-item-object' => 'page'));$gantry_menu_items["menu"][$menu_item_mapping[25]] = array (
  'gantrymenu_item_subtext' => '',
  'gantrymenu_customimage' => '',
  'gantrymenu_customicon' => '',
  'gantrymenu_columns' => '1',
  'gantrymenu_distribution' => 'evenly',
  'gantrymenu_manual_distribution' => '',
  'gantrymenu_children_group' => '0',
  'gantrymenu_dropdown_width' => '',
  'gantrymenu_column_widths' => '',
);$menu_item_mapping[27] = wp_update_nav_menu_item($importing_menu->term_id, 0, array('menu-item-parent-id' => $menu_item_mapping[25],'menu-item-type' => 'custom','menu-item-title' => 'Preset 1','menu-item-status' => 'publish','menu-item-target' => '','menu-item-classes' => '','menu-item-description' => '','menu-item-xfn' => '','menu-item-position' => '15','menu-item-attr-title' => '','menu-item-url' => '@RT_SITE_URL@/?presets=preset1'));$gantry_menu_items["menu"][$menu_item_mapping[27]] = array (
  'gantrymenu_item_subtext' => '',
  'gantrymenu_customimage' => '',
  'gantrymenu_customicon' => '',
  'gantrymenu_columns' => '1',
  'gantrymenu_distribution' => 'evenly',
  'gantrymenu_manual_distribution' => '',
  'gantrymenu_children_group' => '0',
  'gantrymenu_dropdown_width' => '',
  'gantrymenu_column_widths' => '',
);$menu_item_mapping[28] = wp_update_nav_menu_item($importing_menu->term_id, 0, array('menu-item-parent-id' => $menu_item_mapping[25],'menu-item-type' => 'custom','menu-item-title' => 'Preset 2','menu-item-status' => 'publish','menu-item-target' => '','menu-item-classes' => '','menu-item-description' => '','menu-item-xfn' => '','menu-item-position' => '16','menu-item-attr-title' => '','menu-item-url' => '@RT_SITE_URL@/?presets=preset2'));$gantry_menu_items["menu"][$menu_item_mapping[28]] = array (
  'gantrymenu_item_subtext' => '',
  'gantrymenu_customimage' => '',
  'gantrymenu_customicon' => '',
  'gantrymenu_columns' => '1',
  'gantrymenu_distribution' => 'evenly',
  'gantrymenu_manual_distribution' => '',
  'gantrymenu_children_group' => '0',
  'gantrymenu_dropdown_width' => '',
  'gantrymenu_column_widths' => '',
);$menu_item_mapping[29] = wp_update_nav_menu_item($importing_menu->term_id, 0, array('menu-item-parent-id' => $menu_item_mapping[25],'menu-item-type' => 'custom','menu-item-title' => 'Preset 3','menu-item-status' => 'publish','menu-item-target' => '','menu-item-classes' => '','menu-item-description' => '','menu-item-xfn' => '','menu-item-position' => '17','menu-item-attr-title' => '','menu-item-url' => '@RT_SITE_URL@/?presets=preset3'));$gantry_menu_items["menu"][$menu_item_mapping[29]] = array (
  'gantrymenu_item_subtext' => '',
  'gantrymenu_customimage' => '',
  'gantrymenu_customicon' => '',
  'gantrymenu_columns' => '1',
  'gantrymenu_distribution' => 'evenly',
  'gantrymenu_manual_distribution' => '',
  'gantrymenu_children_group' => '0',
  'gantrymenu_dropdown_width' => '',
  'gantrymenu_column_widths' => '',
);$menu_item_mapping[30] = wp_update_nav_menu_item($importing_menu->term_id, 0, array('menu-item-parent-id' => $menu_item_mapping[25],'menu-item-type' => 'custom','menu-item-title' => 'Preset 4','menu-item-status' => 'publish','menu-item-target' => '','menu-item-classes' => '','menu-item-description' => '','menu-item-xfn' => '','menu-item-position' => '18','menu-item-attr-title' => '','menu-item-url' => '@RT_SITE_URL@/?presets=preset4'));$gantry_menu_items["menu"][$menu_item_mapping[30]] = array (
  'gantrymenu_item_subtext' => '',
  'gantrymenu_customimage' => '',
  'gantrymenu_customicon' => '',
  'gantrymenu_columns' => '1',
  'gantrymenu_distribution' => 'evenly',
  'gantrymenu_manual_distribution' => '',
  'gantrymenu_children_group' => '0',
  'gantrymenu_dropdown_width' => '',
  'gantrymenu_column_widths' => '',
);update_option("gantry_menu_items",$gantry_menu_items);}