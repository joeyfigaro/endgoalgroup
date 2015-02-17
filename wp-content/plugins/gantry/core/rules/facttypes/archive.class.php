<?php
/**
 * @version   $Id: archive.class.php 60811 2014-05-08 09:28:29Z jakub $
 * @author    RocketTheme http://www.rockettheme.com
 * @copyright Copyright (C) 2007 - 2014 RocketTheme, LLC
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 */

gantry_import('core.rules.gantryoverridefact');

class GantryFactArchive extends GantryOverrideFact
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
		if ($this->id != null) {
			$term = get_term($this->id, $this->type);
			return $term->name;
		} else {
			return $this->type;
		}
	}

	public function matchesArchivePage($query)
	{
		if (!$query->is_archive) return false;
		if ($query->is_tax) {
			if ($this->type != $query->query_vars['taxonomy']) return false;
			$term = get_term_by('slug', $query->query_vars['term'], $query->query_vars['taxonomy'], OBJECT, 'raw');
			if ($term === false) return false;
			if ($term->term_id == $this->id) return true;

		} else if ($query->is_tag && $this->type == "post_tag" && $this->id == $query->query_vars['tag_id']) return true; else if ($query->is_category && $this->type == "category" && $this->id == $query->query_vars['cat']) return true;
		return false;
	}

	public function matchesArchiveType($query)
	{
		if (!$query->is_archive) return false;
		if ($query->is_tax && $this->type == $query->query_vars['taxonomy']) return true; else if ($query->is_tag && $this->type == "post_tag") return true; else if ($query->is_category && $this->type == "category") return true;
		return false;
	}

	public function isParentOf($query)
	{
		$this->setupBaseInfo();
		if (!$query->is_archive || !is_taxonomy_hierarchical($this->type)) return false;
		$taxonomy = null;
		$term_id  = null;

		if ($query->is_tax) {
			$taxonomy = $query->query_vars['taxonomy'];
			$term     = get_term_by('slug', $query->query_vars['term'], $query->query_vars['taxonomy'], OBJECT, 'raw');
			if ($term === false) return false;
			$term_id = $term->term_id;
		} else if ($query->is_category) {
			$taxonomy = "category";
			$term_id  = $query->query_vars['cat'];
		}

		if ($this->type != $taxonomy) return false;
		$depth = 0;
		return $this->findChild($this->id, $term_id, $this->children, $depth);
	}

	public function getDepthToChild($query)
	{
		$this->setupBaseInfo();
		$taxonomy = null;
		$term_id  = null;
		if ($query->is_tax) {
			$term = get_term_by('slug', $query->query_vars['term'], $query->query_vars['taxonomy'], OBJECT, 'raw');
			if ($term === false) return false;
			$term_id = $term->term_id;
		} else if ($query->is_category) {
			$term_id = $query->query_vars['cat'];
		}
		$depth = 0;
		$found = $this->findChild($this->id, $term_id, $this->children, $depth);
		if ($found !== false) return $depth;
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
