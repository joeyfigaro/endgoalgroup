<?php
/**
 * @version   4.1.2 May 18, 2014
 * @author    RocketTheme http://www.rockettheme.com
 * @copyright Copyright (C) 2007 - 2014 RocketTheme, LLC
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 */

defined( 'GANTRY_VERSION' ) or die();

gantry_import( 'core.gantrygizmo' );

/**
 * @package     gantry
 * @subpackage  features
 */
class GantryGizmoStyleDeclaration extends GantryGizmo {

	var $_name = 'styledeclaration';
	
	function isEnabled(){
		global $gantry;
		$menu_enabled = $this->get('enabled');

		if (1 == (int)$menu_enabled) return true;
		return false;
	}

	function query_parsed_init() {
		global $gantry;
		$browser = $gantry->browser;

		// Colors
        $linkColor = new Color($gantry->get('linkcolor'));
        $css = 'a, ul.menu li .separator {color:'.$gantry->get('linkcolor').';}';
        $css .= '.button, .readon, .readmore, button.validate, #member-profile a, #member-registration a, .formelm-buttons button, .btn-primary {border-color:'.$linkColor->darken('20%').';}';

        // Gradients
        $css .= '.button, .readon, .readmore, button.validate, #member-profile a, #member-registration a, .formelm-buttons button, .btn-primary {background-color: '.$linkColor->lighten('4%').'; '.$this->_createGradient('top', $linkColor->lighten('4%'), '1', '0%', $linkColor->darken('9%'), '1', '100%').'}'."\n";
        $css .= '.button:hover, .readon:hover, .readmore:hover, button.validate:hover, #member-profile a:hover, #member-registration a:hover, .formelm-buttons button:hover, .btn-primary:hover {background-color: '.$linkColor->lighten('10%').'; '.$this->_createGradient('top', $linkColor->lighten('10%'), '1', '0%', $linkColor->darken('3%'), '1', '100%').'}'."\n";
        $css .= '.button:active, .readon:active, .readmore:active, button.validate:active, #member-profile a:active, #member-registration a:active, .formelm-buttons button:active, .btn-primary:active {background-color: '.$linkColor->darken('2%').'; '.$this->_createGradient('top', $linkColor->darken('2%'), '1', '0%', $linkColor->lighten('8%'), '1', '100%').'}'."\n";

        // Logo
        $css .= $this->buildLogo();

		$this->_disableRokBoxForiPhone();

		$gantry->addInlineStyle($css);      
        if ($gantry->get('layout-mode')=="responsive") $gantry->addLess('mediaqueries.less', 'mediaqueries.css', 9);
        if ($gantry->get('layout-mode')=="960fixed") $gantry->addLess('960fixed.less');
        if ($gantry->get('layout-mode')=="1200fixed") $gantry->addLess('1200fixed.less'); 

		// add inline css from the Custom CSS field
		$gantry->addInlineStyle($gantry->get('customcss'));

	}

	function buildLogo(){
		global $gantry;

		if ($gantry->get('logo-type')!="custom") return "";

		$source = $width = $height = "";

		$logo = str_replace("&quot;", '"', str_replace("'", '"', $gantry->get('logo-custom-image')));
		$data = json_decode($logo);

		if (!$data){
			if (strlen($logo)) $source = $logo;
			else return "";
		} else {
			$source = $data->path;
		}

		$baseUrl = trailingslashit(get_bloginfo('wpurl'));

		if (substr($baseUrl, 0, strlen($baseUrl)) == substr($source, 0, strlen($baseUrl))){
			$file = ABSPATH . substr($source, strlen($baseUrl));
		} else {
			$file = ABSPATH . $source;
		}

		if (isset($data->width) && isset($data->height)){
			$width = $data->width;
			$height = $data->height;
		} else {
			$size = @getimagesize($file);
			$width = $size[0];
			$height = $size[1];
		}

		$output = "";
		$output .= "#rt-logo {background: url(".$source.") 50% 0 no-repeat !important;}"."\n";
		$output .= "#rt-logo {width: ".$width."px;height: ".$height."px;}"."\n";

		$file = preg_replace('/\//i', DS, $file);

		return (file_exists($file)) ?$output : '';
	}

	function _createGradient($direction, $from, $fromOpacity, $fromPercent, $to, $toOpacity, $toPercent){
		global $gantry;
		$browser = $gantry->browser;

		$fromColor = $this->_RGBA($from, $fromOpacity);
		$toColor = $this->_RGBA($to, $toOpacity);
		$gradient = $default_gradient = '';

		$default_gradient = 'background: linear-gradient('.$direction.', '.$fromColor.' '.$fromPercent.', '.$toColor.' '.$toPercent.');';

		switch ($browser->engine) {
			case 'gecko':
				$gradient = ' background: -moz-linear-gradient('.$direction.', '.$fromColor.' '.$fromPercent.', '.$toColor.' '.$toPercent.');';
				break;

			case 'webkit':
				if ($browser->shortversion < '5.1'){

					switch ($direction){
						case 'top':
							$from_dir = 'left top'; $to_dir = 'left bottom'; break;
						case 'bottom':
							$from_dir = 'left bottom'; $to_dir = 'left top'; break;
						case 'left':
							$from_dir = 'left top'; $to_dir = 'right top'; break;
						case 'right':
							$from_dir = 'right top'; $to_dir = 'left top'; break;
					}
					$gradient = ' background: -webkit-gradient(linear, '.$from_dir.', '.$to_dir.', color-stop('.$fromPercent.','.$fromColor.'), color-stop('.$toPercent.','.$toColor.'));';
				} else {
					$gradient = ' background: -webkit-linear-gradient('.$direction.', '.$fromColor.' '.$fromPercent.', '.$toColor.' '.$toPercent.');';
				}
				break;

			case 'presto':
				$gradient = ' background: -o-linear-gradient('.$direction.', '.$fromColor.' '.$fromPercent.', '.$toColor.' '.$toPercent.');';
				break;

			case 'trident':
				if ($browser->shortversion >= '10'){
					$gradient = ' background: -ms-linear-gradient('.$direction.', '.$fromColor.' '.$fromPercent.', '.$toColor.' '.$toPercent.');';
				} else if ($browser->shortversion <= '6'){
					$gradient = $from;
					$default_gradient = '';
				} else {

					$gradient_type = ($direction == 'left' || $direction == 'right') ? 1 : 0;
					$from_nohash = str_replace('#', '', $from);
					$to_nohash = str_replace('#', '', $to);

					if (strlen($from_nohash) == 3) $from_nohash = str_repeat(substr($from_nohash, 0, 1), 6);
					if (strlen($to_nohash) == 3) $to_nohash = str_repeat(substr($to_nohash, 0, 1), 6);

					if ($fromOpacity == 0 || $fromOpacity == '0' || $fromOpacity == '0%') $from_nohash = '00' . $from_nohash;
					if ($toOpacity == 0 || $toOpacity == '0' || $toOpacity == '0%') $to_nohash = '00' . $to_nohash;

					$gradient = " filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#".$to_nohash."', endColorstr='#".$from_nohash."',GradientType=".$gradient_type." );";

					$default_gradient = '';

				}
				break;

			default:
				$gradient = $from;
				$default_gradient = '';
				break;
		}

		return  $default_gradient . $gradient;
	}

	function _HEX2RGB($hexStr, $returnAsString = false, $seperator = ','){
		$hexStr = preg_replace("/[^0-9A-Fa-f]/", '', $hexStr);
		$rgbArray = array();
	
		if (strlen($hexStr) == 6){
			$colorVal = hexdec($hexStr);
			$rgbArray['red'] = 0xFF & ($colorVal >> 0x10);
			$rgbArray['green'] = 0xFF & ($colorVal >> 0x8);
			$rgbArray['blue'] = 0xFF & $colorVal;
		} elseif (strlen($hexStr) == 3){
			$rgbArray['red'] = hexdec(str_repeat(substr($hexStr, 0, 1), 2));
			$rgbArray['green'] = hexdec(str_repeat(substr($hexStr, 1, 1), 2));
			$rgbArray['blue'] = hexdec(str_repeat(substr($hexStr, 2, 1), 2));
		} else {
			return false;
		}
	
		return $returnAsString ? implode($seperator, $rgbArray) : $rgbArray;
	}
	
	function _RGBA($hex, $opacity){
		return 'rgba(' . $this->_HEX2RGB($hex, true) . ','.$opacity.')';
	}

	function _disableRokBoxForiPhone() {
		global $gantry;

		if ($gantry->browser->platform == 'iphone' || $gantry->browser->platform == 'android') {
			$gantry->addInlineScript("window.addEvent('domready', function() {\$\$('a[rel^=rokbox]').removeEvents('click');});");
		}
	}
}