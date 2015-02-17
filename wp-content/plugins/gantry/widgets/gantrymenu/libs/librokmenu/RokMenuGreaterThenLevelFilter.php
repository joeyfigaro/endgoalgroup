<?php
/**
 * @version   $Id: RokMenuGreaterThenLevelFilter.php 58623 2012-12-15 22:01:32Z btowles $
 * @author    RocketTheme http://www.rockettheme.com
 * @copyright Copyright (C) 2007 - 2014 RocketTheme, LLC
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 */

class RokMenuGreaterThenLevelFilter extends RecursiveFilterIterator
{
	protected $level;

	public function __construct(RecursiveIterator $recursiveIter, $end)
	{
		$this->level = $end;
		parent::__construct($recursiveIter);
	}

	public function accept()
	{
		return $this->hasChildren() || $this->current()->getLevel() > $this->level;
	}

	public function getChildren()
	{
		return new self($this->getInnerIterator()->getChildren(), $this->level);
	}
}
