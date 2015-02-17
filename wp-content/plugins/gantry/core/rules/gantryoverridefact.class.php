<?php
/**
 * @version   $Id: gantryoverridefact.class.php 58623 2012-12-15 22:01:32Z btowles $
 * @author    RocketTheme http://www.rockettheme.com
 * @copyright Copyright (C) 2007 - 2014 RocketTheme, LLC
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 */

gantry_import('core.rules.phprules.Fact');

class GantryOverrideFact extends Fact
{
	public $override_id;
	public $archetype;
	public $type;
	public $id;

	public function __construct($override_id, $archetype, $type = null, $id = null)
	{
		$this->override_id = $override_id;
		$this->archetype   = $archetype;
		$this->type        = $type;
		$this->id          = $id;
	}

	public function getMatchData()
	{
		$data[] = $this->archetype;
		$data[] = $this->type;
		if (isset($this->id)) $data[] = $this->id;
		return implode('::', $data);
	}
}
