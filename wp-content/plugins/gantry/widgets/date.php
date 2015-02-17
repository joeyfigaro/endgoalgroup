<?php
/**
 * @version   $Id: date.php 60832 2014-05-12 09:47:23Z jakub $
 * @author    RocketTheme http://www.rockettheme.com
 * @copyright Copyright (C) 2007 - 2014 RocketTheme, LLC
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 */

defined('GANTRY_VERSION') or die();

gantry_import('core.gantrywidget');

add_action('widgets_init', array("GantryWidgetDate", "init"));

class GantryWidgetDate extends GantryWidget
{
	var $short_name = 'date';
	var $wp_name = 'gantry_date';
	var $long_name = 'Gantry Date';
	var $description = 'Gantry Date Widget';
	var $css_classname = 'widget_gantry_date';
	var $width = 200;
	var $height = 400;

	static function init()
	{
		register_widget("GantryWidgetDate");
	}

	function render_widget_open($args, $instance)
	{
	}

	function render_widget_close($args, $instance)
	{
	}


	function render($args, $instance)
	{
		/** @global $gantry Gantry */
        global $gantry;

		if (isset($instance['clientside']) && $instance['clientside']) {
			$gantry->addScript('gantry-date.js');
			$gantry->addDomReadyScript($this->_dateFormat($instance));
		}

		gantry_import('core.utilities.gantrydate');
		$now          = new GantryDate();
		$now->_offset = get_option('gmt_offset') * 3600;

		ob_start();
		?>
		<div class="date-block">
			<span class="date"><?php echo $now->toFormat($instance['format']); ?></span>
		</div>
		<?php
		echo ob_get_clean();
	}

	function _dateLanguage()
	{

		$days = array(
			'Sun',
			'Mon',
			'Tue',
			'Wed',
			'Thu',
			'Fri',
			'Sat',
			'Sunday',
			'Monday',
			'Tuesday',
			'Wednesday',
			'Thursday',
			'Friday',
			'Saturday'
		);

		$months = array(
			'Jan',
			'Feb',
			'Mar',
			'Apr',
			'May',
			'Jun',
			'Jul',
			'Aug',
			'Sep',
			'Oct',
			'Nov',
			'Dec',
			'January',
			'February',
			'March',
			'April',
			'May',
			'June',
			'July',
			'August',
			'September',
			'October',
			'November',
			'December'
		);

		return "dayNames:['" . implode("', '", $days) . "'], monthNames:['" . implode("', '", $months) . "']";
	}

	function _dateFormat($instance)
	{
		gantry_import('core.utilities.gantrydate');

		/** @global $gantry Gantry */
global $gantry;
		$now = new GantryDate();

		$formats = str_replace("%", "$", $instance['format']);

		$gantry->addInlineScript("dateFormat.i18n = {" . $this->_dateLanguage() . "};var dateFeature = new Date().format('$formats');\n");
		$js = "
				var dates = $$('.date-block .date');
				if (dates.length) {
					dates.each(function(date) {
						date.set('text', dateFeature);
					});
				}
		\n";

		return $js;
	}

}
