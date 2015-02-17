<?php
/**
 * @version   4.1.2 May 18, 2014
 * @author    RocketTheme http://www.rockettheme.com
 * @copyright Copyright (C) 2007 - 2014 RocketTheme, LLC
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 */

require_once( dirname( __FILE__ ) . '/gantry_dropdown/theme.php' );
GantryWidgetMenu::registerTheme( dirname( __FILE__ ) . '/gantry_dropdown', 'gantry_dropdown', __( 'Gantry Dropdown' ), 'GantryDropdownMenuTheme');

require_once( dirname( __FILE__ ) . '/gantry_splitmenu/theme.php' );
GantryWidgetMenu::registerTheme( dirname( __FILE__ ) . '/gantry_splitmenu', 'gantry_splitmenu', __( 'Gantry SplitMenu' ), 'GantrySplitMenuTheme');