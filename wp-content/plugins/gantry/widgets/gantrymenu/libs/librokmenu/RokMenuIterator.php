<?php
/**
 * @version   $Id: RokMenuIterator.php 59361 2013-03-13 23:10:27Z btowles $
 * @author    RocketTheme http://www.rockettheme.com
 * @copyright Copyright (C) 2007 - 2014 RocketTheme, LLC
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 */


class RokMenuIterator implements RecursiveIterator
{
	protected $ar;

	public function __construct(RokMenuNodeBase $menuNode)
	{
		$this->ar = $menuNode->getChildren();
	}

	public function rewind()
	{
		reset($this->ar);
	}

	public function valid()
	{
		return !is_null(key($this->ar));
	}

	public function key()
	{
		return key($this->ar);
	}

	public function next()
	{
		next($this->ar);
	}

	public function current()
	{
		return current($this->ar);
	}

	public function hasChildren()
	{
		$current = current($this->ar);
		return $current->hasChildren();
	}

	public function getChildren()
	{
		return new RokMenuIterator($this->current());
	}
}
