<?php
/**
 * @version   $Id: assignment_functions.php 60344 2014-01-03 22:06:04Z jakub $
 * @author    RocketTheme http://www.rockettheme.com
 * @copyright Copyright (C) 2007 - 2014 RocketTheme, LLC
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 */

class GantryAssignmentWalker extends Walker
{
	var $tree_type = array('post_type', 'taxonomy', 'custom');
	var $db_fields = array('parent' => 'parent_id', 'id' => 'id');

	/**
	 * @see   Walker::start_lvl()
	 * @since 3.0.0
	 *
	 * @param string $output Passed by reference. Used to append additional content.
	 * @param int    $depth  Depth of page. Used for padding.
	 */
	function start_lvl(&$output, $depth = 0, $args = array())
	{
		$indent = str_repeat("\t", $depth);
		$output .= "\n$indent<ul class=\"sub-menu\">\n";
	}

	/**
	 * @see   Walker::end_lvl()
	 * @since 3.0.0
	 *
	 * @param string $output Passed by reference. Used to append additional content.
	 * @param int    $depth  Depth of page. Used for padding.
	 */
	function end_lvl(&$output, $depth = 0, $args = array())
	{
		$indent = str_repeat("\t", $depth);
		$output .= "$indent</ul>\n";
	}

	/**
	 * @see   Walker::start_el()
	 * @since 3.0.0
	 *
	 * @param string $output       Passed by reference. Used to append additional content.
	 * @param object $item         Menu item data object.
	 * @param int    $depth        Depth of menu item. Used for padding.
	 * @param int    $current_page Menu item ID.
	 * @param object $args
	 */
	function start_el(&$output, $item, $depth = 0, $args = array(), $current_object_id = 0)
	{
		global $wp_query;
		global $gantry_override_assignment_info;
		$indent = ($depth) ? str_repeat("\t", $depth) : '';

		$class_names = $value = '';

		$classes = empty($item->classes) ? array() : (array)$item->classes;

		$assigns = $args->assignments;

		if (isset($assigns[$item->archetype]) && isset($assigns[$item->archetype][$item->type]) && ((is_array($assigns[$item->archetype][$item->type]) && in_array($item->id, $assigns[$item->archetype][$item->type])) || (is_bool($assigns[$item->archetype][$item->type]) && $assigns[$item->archetype][$item->type] === true))
		) {
			array_push($classes, "added");
		}

		$class_names = join(' ', apply_filters('nav_menu_css_class', array_filter($classes), $item));
		$class_names = ' class="' . esc_attr($class_names) . '"';

		$output .= $indent . '<li id="' . $item->type . '-menu-item-' . $item->id . '"' . $value . $class_names . '>';

		$item_pass = $item->archetype . '::' . $item->type;
		$item_pass .= (isset($item->id)) ? '::' . $item->id : '';

		if ((isset($item->id) && isset($args->assignments[$item->archetype][$item->type]) && is_array($args->assignments[$item->archetype][$item->type]) && in_array($item->id, $args->assignments[$item->archetype][$item->type])) || (!isset($item->id) && isset($args->assignments[$item->archetype][$item->type]) && $args->assignments[$item->archetype][$item->type] == true)
		) {
			$gantry_override_assignment_info[$item_pass] = $item;
		}

		$attributes = !empty($item->title) ? ' title="' . esc_attr($item->title) . '"' : '';
		$attributes .= ' rel="' . $item_pass . '"';
		$item->url = null;
		$attributes .= !empty($item->url) ? ' href="' . esc_attr($item->url) . (!strstr(esc_attr($item->url), '?') ? '?' : '&amp;') . 'TB_iframe=true&amp;height=600&amp;width=800"' : '';
		if (empty($item->url)) $attributes .= ' href="#"' . "\n";

		$item_output = '';
		if (isset($args->before)) $item_output = $args->before;
		$item_output .= '<label class="menu-item-' . $item->id . '">' . "\n";
		$item_output .= '	<input class="assignment-checkbox" type="checkbox" name="menu-item-' . $item->id . '" value="' . $item->id . '" />' . "\n";
		$item_output .= '</label>' . "\n";
		$item_output .= ' 	<a class="' . (empty($item->url) ? 'no-link-item' : 'thickbox') . '"' . $attributes . '>' . "\n";
		if (isset($args->link_before)) $item_output .= $args->link_before;
		$item_output .= apply_filters('the_title', $item->title, $item->id);
		if (isset($args->link_after)) $item_output .= $args->link_after;
		$item_output .= '</a>';
		if (isset($args->after)) $item_output .= $args->after;

		$output .= apply_filters('walker_gantry_assignments_start_el', $item_output, $item, $depth, $args);
	}

	/**
	 * @see   Walker::end_el()
	 * @since 3.0.0
	 *
	 * @param string $output Passed by reference. Used to append additional content.
	 * @param object $item   Page data object. Not used.
	 * @param int    $depth  Depth of page. Not Used.
	 */
	function end_el(&$output, $item, $depth = 0, $args = array())
	{
		$output .= "</li>\n";
	}
}


function gantry_assignment_post_type_meta_boxes()
{
	global $gantry_override_types;

	$post_types = get_post_types(array('show_ui' => true), 'object');

	if (!$post_types) return;

	foreach ($post_types as $post_type) {
		$post_type = apply_filters('gantry_assignment_meta_box_object', $post_type);
		if ($post_type) {
			$type                                                         = new AssignmentType();
			$type->name                                                   = $post_type->name;
			$type->archetype                                              = "post_type";
			$type->type_label                                             = $post_type->labels->name;
			$type->type                                                   = $post_type->name;
			$type->single_label                                           = $post_type->labels->singular_name;
			$gantry_override_types[$type->archetype . '::' . $type->type] = $type;
			add_meta_box($type->archetype . '_' . $type->type, $type->type_label, 'gantry_assignment_item_post_type_meta_box', 'gantry_assignments', 'panel', 'default', $type);

			$taxonomies = get_object_taxonomies($post_type->name);
			if (!empty($taxonomies)) {
				$type                                                         = new AssignmentType();
				$type->name                                                   = $post_type->name;
				$type->archetype                                              = "taxonomy";
				$type->type_label                                             = sprintf(_g('%s: Terms'), $post_type->labels->name);
				$type->type                                                   = $post_type->name;
				$type->single_label                                           = sprintf(_g('%s: Term'), $post_type->labels->singular_name);
				$gantry_override_types[$type->archetype . '::' . $type->type] = $type;
				add_meta_box($type->archetype . '_' . $type->type, $type->type_label, 'gantry_assignment_item_post_type_taxonomies_meta_box', 'gantry_assignments', 'panel', 'default', $type);
			}


		}
	}
}

/**
 * Displays a metabox for a post type menu item.
 *
 * @since 3.0.0
 *
 * @param string $object    Not used.
 * @param string $post_type The post type object.
 */
function gantry_assignment_item_post_type_meta_box($object, $post_type, $assignments)
{
	global $_nav_menu_placeholder, $nav_menu_selected_id;

	$post_type_name = $post_type['args']->type;

	$args = array(
		'order'                  => 'ASC',
		'orderby'                => 'title',
		'post_type'              => $post_type_name,
		'suppress_filters'       => true,
		'update_post_term_cache' => false,
		'update_post_meta_cache' => false,
		'posts_per_page'         => -1
	);

	if (isset($post_type['args']->_default_query)) $args = array_merge($args, (array)$post_type['args']->_default_query);

	$get_posts = new WP_Query;
	$posts     = $get_posts->query($args);

	if (!$get_posts->post_count) {
		echo '<p>' . _g('No items.') . '</p>';
		return;
	}
	$newposts = array();
	foreach ($posts as $post_key => $post) {
		$item                = new AssignmentItem();
		$item->archetype     = $post_type['args']->archetype;
		$item->type          = $post_type_name;
		$item->id            = $post->ID;
		$item->single_label  = $post_type['args']->single_label;
		$item->title         = $post->post_title;
		$item->preview_url   = get_permalink($item->id);
		$item->parent_id     = $post->post_parent;
		$newposts[$post_key] = $item;
	}
	$posts = $newposts;

//    $post_type_object = get_post_type_object($post_type_name);

	if (!$posts) $error = '<li id="error">' . $post_type['args']->labels->not_found . '</li>';

	$walker              = new GantryAssignmentWalker;
	$args['walker']      = $walker;
	$args['assignments'] = $assignments;

	?>
	<div id="posttype-<?php echo $post_type_name; ?>" class="posttypediv">
		<ul id="<?php echo $post_type_name; ?>checklist"
		    class="list:<?php echo $post_type_name?> categorychecklist form-no-clear">
			<?php
			$checkbox_items = gantry_walk_assignment_tree($posts, 0, (object)$args);
			echo $checkbox_items;
			?>
		</ul>
	</div><!-- /.posttypediv -->
<?php

}

/**
 * Displays a metabox for a taxonomy menu item.
 *
 * @since 3.0.0
 *
 * @param string $object   Not used.
 * @param string $taxonomy The taxonomy object.
 */
function gantry_assignment_item_post_type_taxonomies_meta_box($object, $post_type, $assignments)
{
	$post_type_name = $post_type['args']->name;

	$args = array(
		'child_of'                 => 0,
		'exclude'                  => '',
		'hide_empty'               => false,
		'hierarchical'             => 1,
		'include'                  => '',
		'include_last_update_time' => false,
		'order'                    => 'ASC',
		'orderby'                  => 'name',
		'pad_counts'               => false,
	);

	$terms_list = array();

	$taxonomies = get_object_taxonomies($post_type_name);
	foreach ($taxonomies as $taxonomy_name) {
		$taxomony = get_taxonomy($taxonomy_name);

		$terms = get_terms($taxonomy_name, $args);

		$newterms = array();
		foreach ($terms as $term_key => $term) {
			$item                = new AssignmentItem();
			$item->archetype     = $post_type['args']->archetype;
			$item->type          = $taxonomy_name;
			$item->id            = $term->term_id;
			$item->single_label  = $post_type['args']->single_label;
			$item->title         = $term->name;
			$item->preview_url   = get_term_link($term, $term->taxonomy);
			$item->parent_id     = $term->parent;
			$newterms[$term_key] = $item;
		}

		$terms_list[$taxomony->labels->name] = $newterms;
	}

	if (empty($terms_list) || is_wp_error($terms)) {
		echo '<p>' . _g('No items.') . '</p>';
		return;
	}

	$walker              = new GantryAssignmentWalker();
	$args['walker']      = $walker;
	$args['assignments'] = $assignments;

	?>
	<div id="posttype-terms-<?php echo $post_type_name; ?>" class="posttypetermsdiv">
		<ul id="<?php echo $post_type_name; ?>-terms-checklist"
		    class="list:<?php echo $post_type_name?>-terms categorychecklist form-no-clear">
			<?php
			foreach ($terms_list as $tax_name => $terms) {
				$item_output = ' - ' . '<a class="no-link-item sub-list-label">' . $tax_name . '</a>';
				echo $item_output;
				echo gantry_walk_assignment_tree($terms, 0, (object)$args);
			}
			?>
		</ul>
	</div><!-- /.taxonomydiv -->
<?php

}

/**
 * Creates metaboxes for any taxonomy menu item.
 *
 * @since 3.0.0
 */
function gantry_assignment_taxonomy_meta_boxes()
{
	global $gantry_override_types;
	$taxonomies = get_taxonomies(array('show_ui' => true), 'object');
	if (!$taxonomies) return;
	$taxonomies = array_copy($taxonomies);

	foreach ($taxonomies as $tax) {
		$tax = apply_filters('gantry_assignment_meta_box_object', $tax);
		if ($tax) {
			$type                                                         = new AssignmentType();
			$type->name                                                   = $tax->name;
			$type->archetype                                              = "taxonomy";
			$type->type_label                                             = sprintf(_g('Taxonomy: %s'), $tax->labels->name);
			$type->type                                                   = $tax->name;
			$type->single_label                                           = sprintf(_g('Taxonomy: %s'), $tax->labels->singular_name);
			$gantry_override_types[$type->archetype . '::' . $type->type] = $type;
			add_meta_box($type->archetype . '_' . $type->type, $type->type_label, 'gantry_assignment_item_taxonomy_meta_box', 'gantry_assignments', 'panel', 'default', $type);
		}
	}
}

/**
 * Creates metaboxes for any taxonomy menu item.
 *
 * @since 3.0.0
 */
function gantry_assignment_archives_meta_boxes()
{
	global $gantry_override_types;
	$taxonomies = get_taxonomies(array('show_ui' => true), 'object');

	if (!$taxonomies) return;

	$taxonomies = array_copy($taxonomies);

	foreach ($taxonomies as $tax) {
		$tax = apply_filters('gantry_assignment_meta_box_object', $tax);
		if ($tax) {
			$type                                                         = new AssignmentType();
			$type->name                                                   = $tax->name;
			$type->archetype                                              = "archive";
			$type->type_label                                             = sprintf(_g('Archives: %s'), $tax->labels->name);
			$type->type                                                   = $tax->name;
			$type->single_label                                           = sprintf(_g('Archive: %s'), $tax->labels->singular_name);
			$gantry_override_types[$type->archetype . '::' . $type->type] = $type;
			add_meta_box($type->archetype . '_' . $type->type, $type->type_label, 'gantry_assignment_item_taxonomy_meta_box', 'gantry_assignments', 'panel', 'default', $type);
		}
	}
}

/**
 * Displays a metabox for a taxonomy menu item.
 *
 * @since 3.0.0
 *
 * @param string $object   Not used.
 * @param string $taxonomy The taxonomy object.
 */
function gantry_assignment_item_taxonomy_meta_box($object, $taxonomy, $assignments)
{
	$taxonomy_name = $taxonomy['args']->name;

	$args = array(
		'child_of'                 => 0,
		'exclude'                  => '',
		'hide_empty'               => false,
		'hierarchical'             => 1,
		'include'                  => '',
		'include_last_update_time' => false,
		'order'                    => 'ASC',
		'orderby'                  => 'name',
		'pad_counts'               => false,
	);

	$terms = get_terms($taxonomy_name, $args);

	if (!$terms || is_wp_error($terms)) {
		echo '<p>' . _g('No items.') . '</p>';
		return;
	}

	$walker              = new GantryAssignmentWalker();
	$args['walker']      = $walker;
	$args['assignments'] = $assignments;

	$newterms = array();
	foreach ($terms as $term_key => $term) {
		$item                = new AssignmentItem();
		$item->archetype     = $taxonomy['args']->archetype;
		$item->type          = $taxonomy_name;
		$item->id            = $term->term_id;
		$item->single_label  = $taxonomy['args']->single_label;
		$item->title         = $term->name;
		$item->preview_url   = get_term_link($term, $term->taxonomy);
		$item->parent_id     = $term->parent;
		$newterms[$term_key] = $item;
	}
	$terms = $newterms;

	?>
	<div id="taxonomy-<?php echo $taxonomy_name; ?>" class="taxonomydiv">
		<ul id="<?php echo $taxonomy_name; ?>checklist"
		    class="list:<?php echo $taxonomy_name?> categorychecklist form-no-clear">
			<?php
			echo gantry_walk_assignment_tree($terms, 0, (object)$args);
			?>
		</ul>
	</div><!-- /.taxonomydiv -->
<?php

}


function gantry_assignment_menus_meta_boxes()
{
	$menus = wp_get_nav_menus(array('orderby' => 'name'));
	if (!$menus) return;

	foreach ($menus as $menu) {
		$menu = apply_filters('gantry_assignment_meta_box_object', $menu);
		if ($menu) {
			$type               = new AssignmentType();
			$type->name         = $menu->name;
			$type->archetype    = "menu";
			$type->type_label   = sprintf(_g('Menu: %s'), $menu->name);
			$type->type         = $menu->name;
			$type->single_label = sprintf(_g('Menu: %s'), $menu->name);
			add_meta_box($type->archetype . '_' . $type->type, sprintf(_g('Menu: %s'), $type->name), 'gantry_assignment_item_menu_meta_box', 'gantry_assignments', 'panel', 'default', $type);
		}
	}

}

function gantry_assignment_item_menu_meta_box($object, $menu, $assignments)
{
	$menu_name = $menu['args']->name;

	$args = array(
		'child_of'                 => 0,
		'exclude'                  => '',
		'hide_empty'               => false,
		'hierarchical'             => 1,
		'include'                  => '',
		'include_last_update_time' => false,
		'order'                    => 'ASC',
		'orderby'                  => 'name',
		'pad_counts'               => false,
	);

	$menu_items = wp_get_nav_menu_items($menu_name);

	if (!$menu_items || is_wp_error($menu_items)) {
		echo '<p>' . _g('No items.') . '</p>';
		return;
	}

	foreach ($menu_items as $menu_item) {
		$menu_item->menu_id = $menu_name;
	}

	$menu_entires = array();
	foreach ($menu_items as $menu_item) {
		$item               = new AssignmentItem();
		$item->archetype    = $menu['args']->archetype;
		$item->type         = $menu_name;
		$item->id           = $menu_item->ID;
		$item->single_label = $menu['args']->single_label;
		$item->parent_id    = empty($menu_item->menu_item_parent) ? get_post_meta($menu_item->ID, '_menu_item_menu_item_parent', true) : $menu_item->menu_item_parent;

		// Get the title and url info
		$menu_item->object_id = empty($menu_item->object_id) ? get_post_meta($menu_item->ID, '_menu_item_object_id', true) : $menu_item->object_id;
		$menu_item->object    = empty($menu_item->object) ? get_post_meta($menu_item->ID, '_menu_item_object', true) : $menu_item->object;
		$menu_item->type      = empty($menu_item->type) ? get_post_meta($menu_item->ID, '_menu_item_type', true) : $menu_item->type;
		if ('post_type' == $menu_item->type) {
			$object           = get_post_type_object($menu_item->object);
			$menu_item->url   = get_permalink($menu_item->object_id);
			$original_object  = get_post($menu_item->object_id);
			$original_title   = $original_object->post_title;
			$menu_item->title = '' == $menu_item->post_title ? $original_title : $menu_item->post_title;

		} elseif ('taxonomy' == $menu_item->type) {
			$object           = get_taxonomy($menu_item->object);
			$term_url         = get_term_link((int)$menu_item->object_id, $menu_item->object);
			$menu_item->url   = !is_wp_error($term_url) ? $term_url : '';
			$original_title   = get_term_field('name', $menu_item->object_id, $menu_item->object, 'raw');
			$menu_item->title = '' == $menu_item->post_title ? $original_title : $menu_item->post_title;

		} else {
			$menu_item->title = $menu_item->post_title;
			$menu_item->url   = empty($menu_item->url) ? get_post_meta($menu_item->ID, '_menu_item_url', true) : $menu_item->url;
		}

		$item->title    = $menu_item->title;
		$menu_entires[] = $item;
	}

	$walker              = new GantryAssignmentWalker();
	$args['assignments'] = $assignments;

	?>
	<div id="menu-<?php echo $menu_name; ?>" class="menudiv">
		<ul id="<?php echo $menu_name; ?>checklist"
		    class="list:<?php echo $menu_name?> categorychecklist form-no-clear">
			<?php
			$args['walker'] = $walker;
			echo gantry_walk_assignment_tree($menu_entires, 0, (object)$args);
			?>
		</ul>
	</div><!-- /.menudiv -->
<?php

}

function gantry_assignment_template_pages_meta_boxes()
{
	$type               = new AssignmentType();
	$type->archetype    = "templatepage";
	$type->type_label   = _g('Template Page Types');
	$type->single_label = _g('Template Page Type');
	$type->name         = _g('Template Page Type');
	$menu               = apply_filters('gantry_assignment_meta_box_object', $type);
	add_meta_box($type->archetype, _g('Template Page Types'), 'gantry_assignment_item_template_pages_meta_box', 'gantry_assignments', 'panel', 'high', $type);
}

function gantry_assignment_item_template_pages_meta_box($object, $box, $assignments)
{
	$templatepages_name = $box['args']->name;
	$args               = array(
		'child_of'                 => 0,
		'exclude'                  => '',
		'hide_empty'               => false,
		'hierarchical'             => 0,
		'include'                  => '',
		'include_last_update_time' => false,
		'order'                    => 'ASC',
		'orderby'                  => 'name',
		'pad_counts'               => false,
	);
	$page_types         = array(
		'404'            => _g('404 Not Found Page'),
		'search'         => _g('Search Page'),
		'tax'            => _g('Taxonomy Archive Page'),
		'front_page'     => _g('Front Page'),
		'home'           => _g('Home Page'),
		'attachment'     => _g('Attachment Page'),
		'single'         => _g('Single Post Page'),
		'page'           => _g('PAGE page'),
		'category'       => _g('Category Archive Page'),
		'tag'            => _g('Tag Archive Page'),
		'author'         => _g('Author Page'),
		'date'           => _g('Date Archive Page'),
		'preview'        => _g('Preview Page'),
		'comments_popup' => _g('Comments Popup Page')
	);

	$page_types = apply_filters('gantry_admin_page_types', $page_types);

	ksort($page_types);
	$template_page_types = array();
	foreach ($page_types as $page_type_id => $page_type_name) {
		$item                  = new AssignmentItem();
		$item->archetype       = $box['args']->archetype;
		$item->type            = $page_type_id;
		$item->title           = $page_type_name;
		$item->parent_id       = 0;
		$item->single_label    = sprintf(_g('Template Page Type'), $page_type_name);
		$template_page_types[] = $item;
	}
	$args['assignments'] = $assignments;
	$walker              = new GantryAssignmentWalker();

	?>
	<div id="templatepage-<?php echo $templatepages_name; ?>" class="templatepagediv">
		<ul id="<?php echo $templatepages_name; ?>checklist"
		    class="list:<?php echo $templatepages_name?> categorychecklist form-no-clear">
			<?php
			$args['walker'] = $walker;
			echo gantry_walk_assignment_tree($template_page_types, 0, (object)$args);
			?>
		</ul>
	</div>
<?php

}


function gantry_walk_assignment_tree($items, $depth, $r)
{
	$walker = (empty($r->walker)) ? new GantryAssignmentWalker : $r->walker;
	$args   = array($items, $depth, $r);
	return call_user_func_array(array(&$walker, 'walk'), $args);
}

function do_assignment_meta_boxes($page, $context, $object, $assignments = array(), &$assignment_info = array())
{
	global $wp_meta_boxes;
	global $gantry_override_assignment_info;
	static $already_sorted = false;


	$hidden = get_hidden_meta_boxes($page);

	printf('<div id="%s-sortables" class="meta-box-sortables clearfix">', htmlspecialchars($context));

	$i = 0;
	do {
		// Grab the ones the user has manually sorted. Pull them out of their previous context/priority and into the one the user chose
		if (!$already_sorted && $sorted = get_user_option("meta-box-order_$page")) {
			foreach ($sorted as $box_context => $ids) foreach (explode(',', $ids) as $id) if ($id) add_meta_box($id, null, null, $page, $box_context, 'sorted');
		}
		$already_sorted = true;

		if (!isset($wp_meta_boxes) || !isset($wp_meta_boxes[$page]) || !isset($wp_meta_boxes[$page][$context])) break;

		$skip_checkbox = array('templatepage', 'menu', 'taxonomy');

		foreach (array('high', 'sorted', 'core', 'default', 'low') as $priority) {
			if (isset($wp_meta_boxes[$page][$context][$priority])) {
				foreach ((array)$wp_meta_boxes[$page][$context][$priority] as $box) {
					if (false == $box || !$box['title']) continue;
					$i++;
					$style = '';

					//echo '<div id="' . $box['id'] . '" class="postbox ' . postbox_classes($box['id'], $page) . $hidden_class . '" ' . '>' . "\n";

					$data     = $box['args'];
					$assigned = "";
					$checked  = "";
					if (isset($assignments[$data->archetype]) && isset($assignments[$data->archetype][$data->type]) && is_bool($assignments[$data->archetype][$data->type]) && $assignments[$data->archetype][$data->type] === true) {
						$assigned                                                               = " added";
						$checked                                                                = ' checked="checked"';
						$data->single_label                                                     = _g('Type');
						$gantry_override_assignment_info[$data->archetype . '::' . $data->type] = $data;
					}

					$position = ($i % 3 == 0) ? 'right': (($i % 3 == 1) ? 'left' : 'center');
					$position = 'assignments-block-' . $position;


					echo '<div id="' . $box['id'] . '"  class="assignments-block '.$position.'">' . "\n";
					echo "	<h2 class='" . strtolower(str_replace(" ", "-", $box['title'])) . "'>\n";
					if (!in_array($data->archetype, $skip_checkbox)) {
						echo "		<label class=\"global menu-item-" . $box['id'] . "\">\n";
						echo '			<input class="assignment-checkbox global" ' . $checked . ' type="checkbox" name="menu-item-' . $box['id'] . '" value="' . $box['id'] . '" />' . "\n";
						echo " 		</label>\n";
					}
					echo '		<span class="' . $data->archetype . '::' . $data->type . '">' . $box['title'] . "</span></h2>\n";

					echo '	<div class="assignment-search"><input type="text" placeholder="Start typing to filter the list."/><div class="assignment-search-clear">&times;</div></div>'."\n";

					echo '	<div class="inside' . $assigned . '">' . "\n";
					call_user_func($box['callback'], $object, $box, $assignments);
					echo "	</div>\n";
					echo "	<div class=\"footer-block clearfix\">\n";
					echo "		<div class=\"select-all\"><a href=\"#\">Select All</a></div>\n";
					echo "		<div class=\"add-button\"><input class=\"button-secondary add-to-assigned\" type=\"button\" value=\"Add to Assigned\" /></div>\n";
					echo "	</div>\n";
					echo "</div>\n";

					//echo "</div>\n";
				}
			}
		}
	} while (0);

	echo "</div>";

	return $i;

}

class AssignmentItem
{
	var $archetype;
	var $type;
	var $id;
	var $parent_id;
	var $title;
	var $preview_url;
	var $single_label;
}

class AssignmentType
{
	var $archetype;
	var $type;
	var $title;
	var $type_label;
	var $single_label;
	var $name;
}

/**
 * Merges any number of arrays of any dimensions, the later overwriting
 * previous keys, unless the key is numeric, in whitch case, duplicated
 * values will not be added.
 *
 * The arrays to be merged are passed as arguments to the function.
 *
 * @access public
 * @return array Resulting array, once all have been merged
 */
function array_merge_replace_recursive()
{
	// Holds all the arrays passed
	$params = func_get_args();

	// First array is used as the base, everything else overwrites on it
	$ret = array_shift($params);

	// Merge all arrays on the first array
	foreach ($params as $array) {
		foreach ($array as $key => $value) {
			// Numeric keyed values are added (unless already there)
			if (is_numeric($key) && (!in_array($value, $ret))) {
				if (is_array($value)) {
					$ret[] = array_merge_replace_recursive($ret[$$key], $value);
				} else {
					$ret[] = $value;
				}

				// String keyed values are replaced
			} else {
				if (isset ($ret[$key]) && is_array($value) && is_array($ret[$key])) {
					$ret[$key] = array_merge_replace_recursive($ret[$key], $value);
				} else {
					$ret[$key] = $value;
				}
			}
		}
	}

	return $ret;
}

/**
 * make a recursive copy of an array
 *
 * @param array $aSource
 *
 * @return array    copy of source array
 */
function array_copy($aSource)
{
	// check if input is really an array
	if (!is_array($aSource)) {
		throw new Exception("Input is not an Array");
	}

	// initialize return array
	$aRetAr = array();

	// get array keys
	$aKeys = array_keys($aSource);
	// get array values
	$aVals = array_values($aSource);

	// loop through array and assign keys+values to new return array
	for ($x = 0; $x < count($aKeys); $x++) {
		// clone if object
		if (is_object($aVals[$x])) {
			$aRetAr[$aKeys[$x]] = clone $aVals[$x];
			// recursively add array
		} elseif (is_array($aVals[$x])) {
			$aRetAr[$aKeys[$x]] = array_copy($aVals[$x]);
			// assign just a plain scalar value
		} else {
			$aRetAr[$aKeys[$x]] = $aVals[$x];
		}
	}

	return $aRetAr;
}
