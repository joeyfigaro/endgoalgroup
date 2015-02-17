<?php
/**
 * @version   $Id: GantryMenuProviderWordpress.php 60817 2014-05-11 11:31:40Z jakub $
 * @author    RocketTheme http://www.rockettheme.com
 * @copyright Copyright (C) 2007 - 2014 RocketTheme, LLC
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 */

if (!class_exists('GantryMenuProviderWordpress')) {

	class GantryMenuProviderWordpress extends AbstractRokMenuProvider
	{
		const ROOT_ID = 0;

		protected $current_url;
		const PREFIX = "gantrymenu_";

		public function __construct(&$args)
		{
			parent::__construct($args);
			$this->current_url = $this->currentPageURL();
		}

		// Left over cause we are bypassing the abstract
		public function getMenuItems()
		{
			$menuitems = $this->getFullMenuItems($this->args);
			return $menuitems;
		}


		/**
		 * @param  $nodeList
		 *
		 * @return void
		 */
		protected function populateActiveBranch($nodeList)
		{

		}

		public function getFullMenuItems($args)
		{
			$nav_menu_name = $args['nav_menu'];
			if (wp_get_nav_menu_object($nav_menu_name) == false) return array();
			$menu_items = apply_filters('gantry_nav_menu_' . $nav_menu_name . '_items', wp_get_nav_menu_items($nav_menu_name), $args);
			$outputNodes = array();
			foreach ($menu_items as $menu_item) {
				//Create the new Node
				$node = new RokMenuNode();
				$node->setId($menu_item->ID);
				$node->setParent($menu_item->menu_item_parent);
				$node->setTitle($menu_item->title);
				$node->setLink($menu_item->url);
				$node->setTarget($menu_item->target);
				$node->setItemId($menu_item->object_id);
				$node->setItemType($menu_item->object);
				if (!empty($menu_item->description)) $node->addAttribute('description', $menu_item->description);
				if (!empty($menu_item->xfn)) $node->addLinkAttrib('rel', $menu_item->xfn);
				if (!empty($menu_item->attr_title)) $node->addLinkAttrib('title', $menu_item->attr_title);

				foreach ($menu_item->classes as $miclass) {
					$node->addListItemClass($miclass);
				}

				$menu_item_vars = get_object_vars($menu_item);
				foreach ($menu_item_vars as $menu_item_var => $menu_item_value) {
					if (preg_match('/^' . self::PREFIX . '(\w+)$/', $menu_item_var, $matches)) {
						$node->addAttribute($matches[1], $menu_item_value);
					}
				}
				$node->addListItemClass("item" . $node->getId());
				$node->addSpanClass('menuitem');
				if ($node->getLink() == $this->current_url && $this->current_node == 0) $this->current_node = $node->getId();
				$outputNodes[$node->getId()] = $node;
			}

			return apply_filters('gantry_nav_menu_' . $nav_menu_name . '_output_nodes', $outputNodes, $args);
		}

		public function getMenuTree()
		{
			global $wp_query;
			gantry_import('core.utilities.gantrycache');
			$cache_handler = GantryCache::getCache('gantry-menu', 0, true);
            $menu_args = array();
            foreach( $this->args as $key => $value ) {
                if( is_array( $value ) ) {
                    foreach( $value as $k => $v ) {
                        $menu_args[$key . '-' . $k] = $v;
                    }
                } else {
                    $menu_args[$key] = $value;
                }
            }
			$menu_id = apply_filters( 'gantry_menu_cache_menu_id', 'menu-' . md5( implode( '-', $menu_args ) . get_locale() ) );
			$menu = $cache_handler->get($menu_id);
			if ($menu == false) {
				$menu = $this->getRealMenuTree();
				$cache_handler->set($menu_id, $menu);
			}
			$this->menu = $menu;

			// set the active item
			$nodeIterator = new RecursiveIteratorIterator($menu, RecursiveIteratorIterator::SELF_FIRST);
			/** @var $node RokMenuNode */
			foreach ($nodeIterator as $node) {

				if (isset($wp_query->queried_object_id) && (int)$node->getItemId() == (int)$wp_query->queried_object_id) {
					if (post_type_exists($node->getItemType()) && isset($wp_query->queried_object->post_type) && $wp_query->queried_object->post_type == $node->getItemType()) {
						$this->current_node = $node->getId();
						break;
					} else if (taxonomy_exists($node->getItemType()) && isset($wp_query->queried_object->taxonomy) && $wp_query->queried_object->taxonomy == $node->getItemType()) {
						$this->current_node = $node->getId();
						break;
					}
				}
				if ($node->getLink() == $this->current_url && $this->current_node == 0) {
					$this->current_node = $node->getId();
					break;
				}
			}

			$this->active_branch = $this->findActiveBranch($this->menu, $this->current_node);
			return $this->menu;
		}

		/**
		 * @return RokMenuNodeTree
		 */
		public function getRealMenuTree()
		{

			$menuitems = $this->getFullMenuItems($this->args);
			$menu      = $this->createMenuTree($menuitems, $this->args['maxdepth']);


			return $menu;
		}

		protected function createMenuTree(&$nodes, $maxdepth)
		{

			$menu = new RokMenuNodeTree(self::ROOT_ID);
			// TODO: move maxdepth to higher processing level?
			if (!empty($nodes)) {
				// Build Menu Tree root down (orphan proof - child might have lower id than parent)
				$ids        = array();
				$ids[0]     = true;
				$unresolved = array();

				// pop the first item until the array is empty if there is any item
				if (is_array($nodes)) {
					while (count($nodes) && !is_null($node = array_shift($nodes))) {
						if (!$menu->addNode($node)) {
							if (!array_key_exists($node->getId(), $unresolved) || $unresolved[$node->getId()] < $maxdepth) {
								array_push($nodes, $node);
								if (!isset($unresolved[$node->getId()])) $unresolved[$node->getId()] = 1; else $unresolved[$node->getId()]++;
							}
						}
					}
				}
			}
			return $menu;
		}

		private function currentPageURL()
		{
			$pageURL = 'http';
			if (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on") {
				$pageURL .= "s";
			}
			$pageURL .= "://";
			if ($_SERVER["SERVER_PORT"] != "80") {
				$pageURL .= $_SERVER["SERVER_NAME"] . ":" . $_SERVER["SERVER_PORT"] . $_SERVER["REQUEST_URI"];
			} else {
				$pageURL .= $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"];
			}
			return $pageURL;
		}

		/**
		 * Gets the current active based on the current_node
		 *
		 * @param RokMenuNodeTree $menu
		 * @param                 $active_id
		 *
		 * @return array
		 */
		protected function findActiveBranch(RokMenuNodeTree $menu, $active_id)
		{
			$active_branch = array();
			/** @var $current RokMenuNode */
			$current = $menu->findNode($active_id);
			if ($current) {
				do {
					$active_branch[$current->getId()] = $current;
					if ($current->getParent() == self::ROOT_ID) break;
				} while ($current = $current->getParentRef());
				$active_branch = array_reverse($active_branch, true);
			}
			return $active_branch;
		}
	}
}
