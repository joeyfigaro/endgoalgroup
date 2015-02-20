<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
  <title>
    <?php
      if(is_tag()) {
        echo 'Tag Archive for &quot;'.$tag.'&quot; | '; bloginfo( 'name' );
      } elseif ( is_archive() ) {
        wp_title(); echo ' Archive | '; bloginfo( 'name' );
      } elseif ( is_search() ) {
        echo 'Search for &quot;'.wp_specialchars($s).'&quot; | '; bloginfo( 'name' );
      } elseif ( is_home() ) {
        bloginfo( 'name' ); echo ' | '; bloginfo( 'description' );
      }  elseif ( is_404() ) {
        echo 'Error 404 Not Found | '; bloginfo( 'name' );
      } else {
        echo wp_title( ' | ', false, right ); bloginfo( 'name' );
      }
    ?>
  </title>

  <!-- SEO -->
  <meta name="keywords" content="<?php wp_title(); echo ' , '; bloginfo( 'name' ); echo ' , '; bloginfo( 'description' ); ?>" />
  <meta name="description" content="<?php wp_title(); echo ' | '; bloginfo( 'description' ); ?>" />

  <meta charset="<?php bloginfo( 'charset' ); ?>" />
  <link rel="profile" href="http://gmpg.org/xfn/11" />
  <link rel="index" title="<?php bloginfo( 'name' ); ?>" href="<?php echo get_option('home'); ?>/" />
  <meta name="viewport" content="width=device-width; initial-scale=1"/>

  <!-- feed links -->
  <link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
  <link rel="alternate" type="application/rss+xml" title="<?php bloginfo( 'name' ); ?>" href="<?php bloginfo( 'rss2_url' ); ?>" />
  <link rel="alternate" type="application/atom+xml" title="<?php bloginfo( 'name' ); ?>" href="<?php bloginfo( 'atom_url' ); ?>" />

  <!-- css reset -->
  <link rel="stylesheet" type="text/css" media="all" href="<?php bloginfo( 'stylesheet_directory' ); ?>/css/reset-min.css" />
  <!-- main stylesheet include -->
  <link rel="stylesheet" type="text/css" media="all" href="<?php bloginfo( 'stylesheet_url' ); ?>" />
  <!-- ie7, ie8 html 5 fix and custom css -->
  <!--[if lt IE 9]>
    <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <link rel="stylesheet" type="text/css" media="all" href="<?php bloginfo( 'template_directory' ); ?>/ie8.css" />
  <![endif]-->

  <!-- wp_head includes -->
  <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<header>
    <nav role="navigation"><?php wp_nav_menu(array('theme_location' => MAIN_MENU_WPNAME )); ?></nav>
</header>