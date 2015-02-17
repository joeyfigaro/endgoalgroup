<?php
/**
 * @version   $Id: RokMenuIdFilter.php 58623 2012-12-15 22:01:32Z btowles $
 * @author    RocketTheme http://www.rockettheme.com
 * @copyright Copyright (C) 2007 - 2014 RocketTheme, LLC
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 */

class RokMenuIdFilter extends RecursiveFilterIterator
{
	protected $id;

	public function __construct(RecursiveIterator $recursiveIter, $id)
	{
		$this->id = $id;
		parent::__construct($recursiveIter);
	}

	public function accept()
	{
		return $this->hasChildren() || $this->current()->getId() == $this->id;
	}

	public function getChildren()
	{
		return new self($this->getInnerIterator()->getChildren(), $this->id);
	}
}