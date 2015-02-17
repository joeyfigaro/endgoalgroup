<?php
 /**
  * @version   $Id: autoload.php 59361 2013-03-13 23:10:27Z btowles $
  * @author    RocketTheme http://www.rockettheme.com
  * @copyright Copyright (C) 2007 - 2014 RocketTheme, LLC
  * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
  */

require_once dirname(__FILE__) . '/classes/Gantry/Loader.php';
if (!defined('GANTRY_PATH'))
{
	define('GANTRY_PATH',dirname(__FILE__));
}
spl_autoload_register(array('Gantry_Loader', 'loadClass'), true);