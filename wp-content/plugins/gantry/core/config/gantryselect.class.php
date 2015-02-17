<?php
/**
 * @version        $Id: gantryselect.class.php 58623 2012-12-15 22:01:32Z btowles $
 * @author         RocketTheme http://www.rockettheme.com
 * @copyright      Copyright (C) 2007 - 2014 RocketTheme, LLC
 * @license        http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 *
 * derived from Joomla with original copyright and license
 * @copyright      Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license        GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('GANTRY_VERSION') or die();

class GantrySelect
{
	/**
	 * @param    string    The value of the option
	 * @param    string    The text for the option
	 * @param    string    The returned object property name for the value
	 * @param    string    The returned object property name for the text
	 *
	 * @return    object
	 */
	function option($value, $text = '', $value_name = 'value', $text_name = 'text', $disable = false)
	{
		$obj              = new stdClass;
		$obj->$value_name = $value;
		$obj->$text_name  = trim($text) ? $text : $value;
		$obj->disable     = $disable;
		return $obj;
	}

	/**
	 * @param    string    The text for the option
	 * @param    string    The returned object property name for the value
	 * @param    string    The returned object property name for the text
	 *
	 * @return    object
	 */
	function optgroup($text, $value_name = 'value', $text_name = 'text')
	{
		$obj              = new stdClass;
		$obj->$value_name = '<OPTGROUP>';
		$obj->$text_name  = $text;
		return $obj;
	}

	/**
	 * Generates just the option tags for an HTML select list
	 *
	 * @param    array     An array of objects
	 * @param    string    The name of the object variable for the option value
	 * @param    string    The name of the object variable for the option text
	 * @param    mixed     The key that is selected (accepts an array or a string)
	 *
	 * @returns    string    HTML for the select list
	 */
	function options($arr, $key = 'value', $text = 'text', $selected = null, $translate = false)
	{
		$html = '';

		foreach ($arr as $i => $option) {
			$element =& $arr[$i]; // since current doesn't return a reference, need to do this

			$isArray = is_array($element);
			$extra   = '';
			if ($isArray) {
				$k  = $element[$key];
				$t  = $element[$text];
				$id = (isset($element['id']) ? $element['id'] : null);
				if (isset($element['disable']) && $element['disable']) {
					$extra .= ' disabled="disabled"';
				}
			} else {
				$k  = $element->$key;
				$t  = $element->$text;
				$id = (isset($element->id) ? $element->id : null);
				if (isset($element->disable) && $element->disable) {
					$extra .= ' disabled="disabled"';
				}
			}

			// This is real dirty, open to suggestions,
			// barring doing a propper object to handle it
			if ($k === '<OPTGROUP>') {
				$html .= '<optgroup label="' . $t . '">';
			} else if ($k === '</OPTGROUP>') {
				$html .= '</optgroup>';
			} else {
				//if no string after hypen - take hypen out
				$splitText = explode(' - ', $t, 2);
				$t         = $splitText[0];
				if (isset($splitText[1])) {
					$t .= ' - ' . $splitText[1];
				}

				//$extra = '';
				//$extra .= $id ? ' id="' . $arr[$i]->id . '"' : '';
				if (is_array($selected)) {
					foreach ($selected as $val) {
						$k2 = is_object($val) ? $val->$key : $val;
						if ($k == $k2) {
							$extra .= ' selected="selected"';
							break;
						}
					}
				} else {
					$extra .= ((string)$k == (string)$selected ? ' selected="selected"' : '');
				}

				//if flag translate text
				if ($translate) {
					$t = _r($t);
				}

				// ensure ampersands are encoded
				$k = GantrySelect::ampReplace($k);
				$t = GantrySelect::ampReplace($t);

				$html .= '<option value="' . $k . '" ' . $extra . '>' . $t . '</option>';
			}
		}

		return $html;
	}

	/**
	 * Generates an HTML select list
	 *
	 * @param    array     An array of objects
	 * @param    string    The value of the HTML name attribute
	 * @param    string    Additional HTML attributes for the <select> tag
	 * @param    string    The name of the object variable for the option value
	 * @param    string    The name of the object variable for the option text
	 * @param    mixed     The key that is selected (accepts an array or a string)
	 *
	 * @returns    string    HTML for the select list
	 */
	function genericlist($arr, $name, $attribs = null, $key = 'value', $text = 'text', $selected = NULL, $idtag = false, $translate = false)
	{
		if (is_array($arr)) {
			reset($arr);
		}

		if (is_array($attribs)) {
			$attribs = GantrySelect::arrayToString($attribs);
		}

		$id = $name;

		if ($idtag) {
			$id = $idtag;
		}

		$id = str_replace('[', '', $id);
		$id = str_replace(']', '', $id);

		$html = '<select name="' . $name . '" id="' . $id . '" ' . $attribs . '>';
		$html .= GantrySelect::Options($arr, $key, $text, $selected, $translate);
		$html .= '</select>';

		return $html;
	}

	/**
	 * Generates a select list of integers
	 *
	 * @param int    The start integer
	 * @param int    The end integer
	 * @param int    The increment
	 * @param string The value of the HTML name attribute
	 * @param string Additional HTML attributes for the <select> tag
	 * @param mixed  The key that is selected
	 * @param string The printf format to be applied to the number
	 *
	 * @returns string HTML for the select list
	 */
	function integerlist($start, $end, $inc, $name, $attribs = null, $selected = null, $format = "")
	{
		$start = intval($start);
		$end   = intval($end);
		$inc   = intval($inc);
		$arr   = array();

		for ($i = $start; $i <= $end; $i += $inc) {
			$fi    = $format ? sprintf("$format", $i) : "$i";
			$arr[] = GantrySelect::Option($fi, $fi);
		}

		return GantrySelect::genericlist($arr, $name, $attribs, 'value', 'text', $selected);
	}

	/**
	 * Generates an HTML radio list
	 *
	 * @param array  An array of objects
	 * @param string The value of the HTML name attribute
	 * @param string Additional HTML attributes for the <select> tag
	 * @param mixed  The key that is selected
	 * @param string The name of the object variable for the option value
	 * @param string The name of the object variable for the option text
	 *
	 * @returns string HTML for the select list
	 */
	function radiolist($arr, $name, $attribs = null, $key = 'value', $text = 'text', $selected = null, $idtag = false, $translate = false)
	{
		reset($arr);
		$html = '';

		if (is_array($attribs)) {
			$attribs = GantrySelect::arrayToString($attribs);
		}

		$id_text = $name;
		if ($idtag) {
			$id_text = $idtag;
		}

		for ($i = 0, $n = count($arr); $i < $n; $i++) {
			$k  = $arr[$i]->$key;
			$t  = $translate ? _r($arr[$i]->$text) : $arr[$i]->$text;
			$id = (isset($arr[$i]->id) ? @$arr[$i]->id : null);

			$extra = '';
			$extra .= $id ? " id=\"" . $arr[$i]->id . "\"" : '';
			if (is_array($selected)) {
				foreach ($selected as $val) {
					$k2 = is_object($val) ? $val->$key : $val;
					if ($k == $k2) {
						$extra .= " selected=\"selected\"";
						break;
					}
				}
			} else {
				$extra .= ((string)$k == (string)$selected ? " checked=\"checked\"" : '');
			}
			$html .= "\n\t<input type=\"radio\" name=\"$name\" id=\"$id_text$k\" value=\"" . $k . "\"$extra $attribs />";
			$html .= "\n\t<label for=\"$id_text$k\">$t</label>";
		}
		$html .= "\n";
		return $html;
	}

	/**
	 * Generates a yes/no radio list
	 *
	 * @param string The value of the HTML name attribute
	 * @param string Additional HTML attributes for the <select> tag
	 * @param mixed  The key that is selected
	 *
	 * @returns string HTML for the radio list
	 */
	function booleanlist($name, $attribs = null, $selected = null, $yes = 'yes', $no = 'no', $id = false)
	{
		$arr = array(
			GantrySelect::Option('0', _r($no)),
			GantrySelect::Option('1', _r($yes))
		);
		return GantrySelect::radiolist($arr, $name, $attribs, 'value', 'text', (int)$selected, $id);
	}

	/**
	 * Replaces &amp; with & for xhtml compliance
	 *
	 * @todo  There must be a better way???
	 *
	 * @static
	 * @since 1.5
	 */
	function ampReplace($text)
	{
		$text = str_replace('&&', '*--*', $text);
		$text = str_replace('&#', '*-*', $text);
		$text = str_replace('&amp;', '&', $text);
		$text = preg_replace('|&(?![\w]+;)|', '&amp;', $text);
		$text = str_replace('*-*', '&#', $text);
		$text = str_replace('*--*', '&&', $text);

		return $text;
	}

	function arrayToString($array = null, $inner_glue = '=', $outer_glue = ' ', $keepOuterKey = false)
	{
		$output = array();

		if (is_array($array)) {
			foreach ($array as $key => $item) {
				if (is_array($item)) {
					if ($keepOuterKey) {
						$output[] = $key;
					}
					// This is value is an array, go and do it again!
					$output[] = GantrySelect::arrayToString($item, $inner_glue, $outer_glue, $keepOuterKey);
				} else {
					$output[] = $key . $inner_glue . '"' . $item . '"';
				}
			}
		}

		return implode($outer_glue, $output);
	}
}
