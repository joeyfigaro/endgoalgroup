<?php
/**
 * @version   $Id: gantryoverrides.class.php 59361 2013-03-13 23:10:27Z btowles $
 * @author    RocketTheme http://www.rockettheme.com
 * @copyright Copyright (C) 2007 - 2014 RocketTheme, LLC
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 */

class GantryOverrides
{
	/**
	 * @var array
	 */
	private $overrides = array();

	/**
	 * @param GantryOverrideItem $overrideItem
	 */
	public function addOverride(GantryOverrideItem $overrideItem)
	{
		$this->overrides[] = $overrideItem;
	}

	/**
	 * @return array
	 */
	public function getOverrideList()
	{
		return GantryOverrides::sortOverridesList($this->overrides);
	}

	/**
	 * @param $overrides
	 *
	 * @return array
	 */
	public static function sortOverridesList($overrides)
	{
		GantryOverrides::_sort($overrides, '!priority', 'distance');
		$output            = array();
		$override_priority = array();
		foreach ($overrides as $override) {
			if (!array_key_exists($override->override_id, $override_priority)) {
				$output[]                                  = $override;
				$override_priority[$override->override_id] = $override;
			}
		}
		$output = array_reverse($output);
		return $output;
	}

	/**
	 * @param $base
	 * @param $path
	 *
	 * @return null
	 */
	private static function _hod(&$base, $path)
	{
		$keys       = explode("->", $path);
		$keys[0]    = str_replace('$', '', $keys[0]);
		$ret        = null;
		$expression = '$ret = ';
		$expression .= '$';
		$licz = 0;
		foreach ($keys as $key) {
			if (++$licz == 1) {
				$expression .= 'base->';
			} else {
				$expression .= $key . '->';
			}
		}
		$expression = substr($expression, 0, -2);
		$expression .= ';';
		eval($expression);
		return $ret;
	}

	/**
	 * @param      $a
	 * @param null $b
	 *
	 * @return int
	 */
	private static function _sort_func($a, $b = NULL)
	{
		static $keys;
		if ($b === NULL) return $keys = $a;
		foreach ($keys as $k) {
			if ($k[0] == '!') {
				$k = substr($k, 1);
				if (GantryOverrides::_hod($a, '$a->' . $k) !== GantryOverrides::_hod($b, '$b->' . $k)) {
					return (int)GantryOverrides::_hod($b, '$b->' . $k) - (int)GantryOverrides::_hod($a, '$a->' . $k);
					//return strcmp(GantryOverrides::_hod($b, '$b->' . $k), GantryOverrides::_hod($a, '$a->' . $k));
				}
			} else if (GantryOverrides::_hod($a, '$a->' . $k) !== GantryOverrides::_hod($b, '$b->' . $k)) {
				return (int)GantryOverrides::_hod($a, '$a->' . $k) - (int)GantryOverrides::_hod($b, '$b->' . $k);
				//return strcmp(GantryOverrides::_hod($a, '$a->' . $k), GantryOverrides::_hod($b, '$b->' . $k));
			}
		}
		return 0;
	}

	/**
	 * @param $array
	 *
	 * @return array
	 */
	private static function _sort(&$array)
	{
		if (!$array) return array();
		$keys = func_get_args();
		array_shift($keys);
		GantryOverrides::_sort_func($keys);
		usort($array, array('GantryOverrides', '_sort_func'));
	}

}

/**
 *
 */
class GantryOverrideItem
{
	/**
	 * @var
	 */
	public $override_id;
	/**
	 * @var
	 */
	public $priority;
	/**
	 * @var null
	 */
	public $rulename;
	/**
	 * @var null
	 */
	public $matchdata;
	/**
	 * @var int
	 */
	public $distance;
	/**
	 * @var null
	 */
	public $nice_name;

	/**
	 * @param      $override_id
	 * @param      $priority
	 * @param int  $distance
	 * @param null $rulename
	 * @param null $matchdata
	 * @param null $nice_name
	 */
	public function __construct($override_id, $priority, $distance = 0, $rulename = null, $matchdata = null, $nice_name = null)
	{
		$this->override_id = $override_id;
		$this->priority    = $priority;
		$this->rulename    = $rulename;
		$this->matchdata   = $matchdata;
		$this->distance    = $distance;
		$this->nice_name   = $nice_name;
	}
}



