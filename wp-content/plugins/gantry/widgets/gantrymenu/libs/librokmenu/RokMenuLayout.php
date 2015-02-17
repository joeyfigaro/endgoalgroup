<?php
/**
 * @version   $Id: RokMenuLayout.php 58623 2012-12-15 22:01:32Z btowles $
 * @author    RocketTheme http://www.rockettheme.com
 * @copyright Copyright (C) 2007 - 2014 RocketTheme, LLC
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 */

if (!interface_exists('RokMenuLayout')) {

	/**
	 *
	 */
	interface RokMenuLayout
	{
		/**
		 * @abstract
		 *
		 * @param  $args
		 *
		 * @return void
		 */
		public function __construct(&$args);


		/**
		 * @abstract
		 *
		 * @param  $menu
		 *
		 * @return void
		 */
		public function renderMenu(&$menu);

		/**
		 * @abstract
		 * @return void
		 */
		public function getScriptFiles();

		/**
		 * @abstract
		 * @return void
		 */
		public function getStyleFiles();

		/**
		 * @abstract
		 * @return void
		 */
		public function getInlineStyle();

		/**
		 * @abstract
		 * @return void
		 */
		public function getInlineScript();

		/**
		 * @abstract
		 * @return void
		 */
		public function doStageHeader();

		/**
		 * @abstract
		 * @return void
		 */
		public function stageHeader();
	}
}