<?php
/**
 * @version   $Id: taxonomy.class.php 58623 2012-12-15 22:01:32Z btowles $
 * @author    RocketTheme http://www.rockettheme.com
 * @copyright Copyright (C) 2007 - 2014 RocketTheme, LLC
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 */
gantry_import('core.rules.gantryoverridefact');

class GantryFactTaxonomy extends GantryOverrideFact
{

	private $children = null;

	private function setupBaseInfo()
	{
		if (is_taxonomy_hierarchical($this->type) && null == $this->children) {
			$this->children = _get_term_hierarchy($this->type);
		}
	}

	public function getNiceName()
	{
		$term = get_term($this->id, $this->type);
		return $term->name;
	}

	public function hasTerm($query)
	{
		if (!isset($query->post) || !isset($query->post->post_type)) return false;
		$terms = wp_get_post_terms($query->post->ID, $this->type, array('fields' => 'ids'));
		return in_array($this->id, $terms);
	}

	public function isParentOf($query)
	{
		$this->setupBaseInfo();
		if (!is_taxonomy_hierarchical($this->type)) return false;

		$item_terms = wp_get_post_terms($query->post->ID, $this->type, array('fields' => 'ids'));

		foreach ($item_terms as $item_term) {
			$depth = 0;
			if ($this->findChild($this->id, $item_term, $this->children, $depth)) return true;
		}
	}

	public function getDepthToChild($query)
	{
		$this->setupBaseInfo();
		$item_terms = wp_get_post_terms($query->post->ID, $this->type, array('fields' => 'ids'));
		foreach ($item_terms as $item_term) {
			$depth = 0;
			if ($this->findChild($this->id, $item_term, $this->children, $depth)) return $depth;
		}
		return 0;
	}

	private function findChild($current_parent, $search_child, &$list, &$depth = 0)
	{
		if (isset($list[$current_parent])) {
			$depth++;
			$children = $list[$current_parent];
			if (in_array($search_child, $children)) return true;
			foreach ($list[$current_parent] as $child_id) {
				if ($this->findChild($child_id, $search_child, $list, $depth)) {
					return true;
				}
			}
		}
		return false;
	}
}
