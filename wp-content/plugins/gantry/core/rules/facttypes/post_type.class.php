<?php
/**
 * @version   $Id: post_type.class.php 58623 2012-12-15 22:01:32Z btowles $
 * @author    RocketTheme http://www.rockettheme.com
 * @copyright Copyright (C) 2007 - 2014 RocketTheme, LLC
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 */

gantry_import('core.rules.gantryoverridefact');

class GantryFactPost_Type extends GantryOverrideFact
{

	private $children = null;

	private function setupBaseInfo()
	{
		if (is_post_type_hierarchical($this->type) && null == $this->children) {
			$this->_populateChildren($this->id);
		}
	}

	public function getNiceName()
	{
		if (null != $this->id) {
			$post = get_post($this->id);
			return $post->post_title;
		} else {
			return $this->type;
		}
	}

	public function matchesPostType($query)
	{
		return isset($query->post) && isset($query->post->post_type) && $this->type == $query->post->post_type;
	}

	function isParentOf($query)
	{
		$this->setupBaseInfo();
		if (!is_post_type_hierarchical($this->type)) return false;
		if (!isset($query->post) || !isset($query->post->post_type) || $this->type != $query->post->post_type) return false;
		$depth = 0;
		$found = $this->findChild($this->id, $query->post->ID, $this->children, $depth);
		return $found;
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

	function getDepthToChild($query)
	{
		$this->setupBaseInfo();
		$depth = 0;
		$found = $this->findChild($this->id, $query->post->ID, $this->children, $depth);
		if ($found !== false) return $depth;
		return 0;
	}

	private function _populateChildren($parent_id)
	{
		if (null == $this->children) {
			$this->children = array();
		}
		$args        = array(
			'order'                  => 'ASC',
			'orderby'                => 'ID',
			'post_type'              => $this->type,
			'suppress_filters'       => true,
			'update_post_term_cache' => false,
			'update_post_meta_cache' => false,
			'posts_per_page'         => -1,
			'post_parent'            => $parent_id
		);
		$child_posts = get_children($args);
		if ($child_posts === false) return false;
		foreach ($child_posts as $child_post) {
			$this->children[$parent_id]['children'][] = $child_post->ID;
			$this->_populateChildren($child_post->ID);
		}
		return true;
	}
}
