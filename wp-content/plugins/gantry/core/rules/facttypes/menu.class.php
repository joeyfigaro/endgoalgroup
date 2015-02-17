<?php
/**
 * @version   $Id: menu.class.php 59858 2013-08-29 19:38:24Z btowles $
 * @author    RocketTheme http://www.rockettheme.com
 * @copyright Copyright (C) 2007 - 2013 RocketTheme, LLC
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 */

gantry_import('core.rules.gantryoverridefact');

class GantryFactMenu extends GantryOverrideFact
{
	private $children = null;
	private $menu_items = null;
	private $current_url = null;
	private $fact_menu_item = null;

	public function getNiceName()
	{
		return $this->fact_menu_item->title;
	}

	private function setupBaseInfo()
	{
		static $menu_objects;
		if (!$menu_objects) {
			$menu_objects = array();
		}

		if (null == $this->current_url) {
			$this->current_url = GantryFactMenu::_curPageURL();
		}
		if (null == $this->menu_items) {
			if (isset($menu_objects[$this->type])) {
				$this->menu_items = $menu_objects[$this->type];
			} else {
				$o = wp_get_nav_menu_object($this->type);
				$this->menu_items = wp_get_nav_menu_items($o);
				$menu_objects[$this->type] = $this->menu_items;
			}
		}
		if (null == $this->fact_menu_item) {
			foreach ($this->menu_items as $item) {
				if ($item->ID == $this->id) {
					$this->fact_menu_item = $item;
					break;
				}
			}
		}
		if (null == $this->children) {
			$this->children = array();
			$list           = array();
			foreach ($this->menu_items as $item) {
				$thisref              = & $this->children[$item->ID];
				$thisref['parent_id'] = $item->menu_item_parent;
				if ($item->menu_item_parent == 0) {
					$list[$item->ID] = & $thisref;
				} else {
					$this->children[$item->menu_item_parent]['children'][] = $item->ID;
				}
			}
		}
	}

	private function findMenuItemByUrl()
	{
		foreach ($this->menu_items as $item) {
			if ($item->url == $this->current_url) {
				return $item;
				break;
			}
		}
		return false;
	}

	private function findMenuItem($id)
	{
		foreach ($this->menu_items as $item) {
			if ($item->ID == $id) {
				return $item;
				break;
			}
		}
		return false;
	}

	function isParentOf($query)
	{
		$this->setupBaseInfo();
		$menu_item = $this->findMenuItemByUrl();
		if ($menu_item === false) return false;
		$depth = 0;
		$found = $this->findChild($this->id, $menu_item->ID, $this->children, $depth);
		if ($found) {
			return true;
		}
		return false;
	}

	function getDepthToChild($query)
	{
		$this->setupBaseInfo();
		$menu_item = $this->findMenuItemByUrl();
		if ($menu_item === false) return false;
		$depth = 0;
		$found = $this->findChild($this->id, $menu_item->ID, $this->children, $depth);
		if ($found !== false) return $depth;
		return 0;
	}

	function isMenuItem($query)
	{
		$this->setupBaseInfo();
		if (null == $this->fact_menu_item) return false;
		if ($this->fact_menu_item->url == GantryFactMenu::_curPageURL()) {
			return true;
		}
		return false;
	}

	private function findChild($current_parent, $search_child, &$list, &$depth = 0)
	{
		if (isset($list[$current_parent]['children'])) {
			$depth++;
			$children = $list[$current_parent]['children'];
			if (in_array($search_child, $children)) return true;
			foreach ($list[$current_parent]['children'] as $child_id) {
				if ($this->findChild($child_id, $search_child, $list, $depth)) {
					return true;
				}
			}
		}
		return false;
	}

	function _curPageURL()
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

}
