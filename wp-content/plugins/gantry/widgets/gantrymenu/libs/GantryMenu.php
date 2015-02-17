<?php
/**
 * @version   $Id: GantryMenu.php 59361 2013-03-13 23:10:27Z btowles $
 * @author    RocketTheme http://www.rockettheme.com
 * @copyright Copyright (C) 2007 - 2014 RocketTheme, LLC
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 */

if (class_exists('GantryRokMenu')) return;

class GantryMenu extends RokMenu
{

	private $theme;

	public function __construct($theme, $instance)
	{
		$this->theme = $theme;
		parent::__construct($instance);

	}

	protected function getProvider()
	{
		/** @global $gantry Gantry */
		global $gantry;
		$providerClass = "GantryMenuProvider" . ucfirst($gantry->platform->platform);
		$file          = dirname(__FILE__) . '/providers/' . $providerClass . '.php';
		if (!class_exists($providerClass) && file_exists($file)) {
			require_once($file);
		}
		if (class_exists($providerClass)) {
			return new $providerClass($this->args);
		} else {
			return false;
		}
	}

	protected function getRenderer()
	{
		/** @global $gantry Gantry */
		global $gantry;
		$rendererClass = "GantryMenuRenderer" . ucfirst($gantry->platform->platform);
		$file          = dirname(__FILE__) . '/renderers/' . $rendererClass . '.php';
		if (!class_exists($rendererClass) && file_exists($file)) {
			require_once($file);
		}
		if (class_exists($rendererClass)) {
			/** @var $renderer GantryMenuRendererWordpress */
			$renderer = new $rendererClass($this->args);
		} else {
			return false;
		}
		$renderer->setTheme($this->theme);
		return $renderer;
	}

	public function enqueueHeaderFiles()
	{
		/** @global $gantry Gantry */
		global $gantry;
		foreach ($this->layout->getScriptFiles() as $name => $script) {
			$gantry->addScript($script['url']);
		}
		foreach ($this->layout->getStyleFiles() as $name => $style) {
			$gantry->addScript($style['url']);
		}
	}

	public function renderInlineHeader()
	{
		/** @global $gantry Gantry */
		global $gantry;
		$style = $this->layout->getInlineStyle();
		if (!empty($style)) {
			$gantry->addInlineStyle($style);
		}
		$js = $this->layout->getInlineScript();
		if (!empty($js)) {
			$gantry->addInlineScript($js);
		}
		return;
	}

	public function render()
	{
		$this->renderHeader();
		return $this->renderMenu();
	}
}
