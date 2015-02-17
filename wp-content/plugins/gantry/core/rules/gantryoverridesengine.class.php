<?php
/**
 * @version   $Id: gantryoverridesengine.class.php 59361 2013-03-13 23:10:27Z btowles $
 * @author    RocketTheme http://www.rockettheme.com
 * @copyright Copyright (C) 2007 - 2014 RocketTheme, LLC
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 */

gantry_import('core.rules.phprules.ActionFassade');
gantry_import('core.rules.phprules.Fact');
gantry_import('core.rules.phprules.Rule');
gantry_import('core.rules.phprules.RuleBase');
gantry_import('core.rules.phprules.RuleReader');
gantry_import('core.rules.phprules.RuleSession');
gantry_import('core.rules.phprules.WorkingMemory');
gantry_import('core.rules.gantryoverrides');
gantry_import('core.rules.gantryoverridefact');
gantry_import('core.rules.gantrypagecallfact');


gantry_import('core.rules.facttypes.archive');
gantry_import('core.rules.facttypes.menu');
gantry_import('core.rules.facttypes.post_type');
gantry_import('core.rules.facttypes.taxonomy');
gantry_import('core.rules.facttypes.templatepage');

/**
 *
 */
class GantryOverridesEngine
{
	/**
	 * @var WorkingMemory
	 */
	protected $wm;
	/**
	 * @var RuleSession
	 */
	protected $session;
	/**
	 * @var array
	 */
	protected $fact_paths = array();


	/**
	 *
	 */
	public function __construct()
	{
		$this->wm      = new WorkingMemory();
		$rr            = new RuleReader();
		$rulebase      = $rr->parseFile(gantry_dirname(__FILE__) . '/stylerules.srl');
		$this->session = new RuleSession($rulebase, $this->wm);
	}


	/**
	 * @param $templateName
	 */
	public function init($templateName)
	{

		global $gantry_path;
		$facttypes_location = $gantry_path . '/core/rules/facttypes/';

		$this->wm->insertActionFassade('output', new GantryOverrides());
		$override_catalog = gantry_get_override_catalog($templateName);
		foreach ($override_catalog as $override_id => $override_name) {
			$assignments_option_name = $templateName . '-template-options-override-assignments-' . $override_id;
			$assignments             = get_option($assignments_option_name);
			if ($assignments !== false) {
				foreach ($assignments as $archetype => $types) {
					$facttypeclass = "GantryFact" . ucfirst($archetype);
					if (!class_exists($facttypeclass)) {
						$facttypepath = $facttypes_location . $archetype . ".class.php";
						require_once($facttypepath);
						$this->fact_paths[] = $facttypepath;
					}
					foreach ($types as $type => $items) {
						if ($items === true) {
							$this->wm->insert(new $facttypeclass($override_id, $archetype, $type));
						}
						if (is_array($items)) {
							foreach ($items as $item) {
								$this->wm->insert(new $facttypeclass($override_id, $archetype, $type, $item));
							}
						}
					}
				}
			}
		}
	}

	/**
	 * @param $wp_query
	 *
	 * @return mixed
	 */
	public function run($wp_query)
	{
		foreach ($this->fact_paths as $path) {
			require_once($path);
		}
		$pagecall        = new GantryPageCallFact();
		$pagecall->query =& $wp_query;
		$this->wm->insert($pagecall);
		$this->session->maxFiringPerRule = 1;
		$this->session->fireAll();
		$ret = $this->wm->getActionFassades();
		if (is_array($ret['output'])) {
			ksort($ret['output']);
		}
		return $ret['output'];
	}
}
