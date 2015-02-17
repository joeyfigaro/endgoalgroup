<?php
/**
 * @version   4.1.2 May 18, 2014
 * @author    RocketTheme http://www.rockettheme.com
 * @copyright Copyright (C) 2007 - 2014 RocketTheme, LLC
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 */
 
class GantryDropdownMenuFormatter extends AbstractRokMenuFormatter {
	function format_subnode( &$node ) {

		if ($node->getId() == $this->current_node) $node->addListItemClass('last');
	
	}
}