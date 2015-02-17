<?php
/**
 * @version   4.1.2 May 18, 2014
 * @author    RocketTheme http://www.rockettheme.com
 * @copyright Copyright (C) 2007 - 2014 RocketTheme, LLC
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 */
defined('ABSPATH') or die();

gantry_import('core.config.gantryformfield');
class GantryFormFieldImagePicker extends GantryFormField {

    protected $type = 'imagepicker';
    protected $basetype = 'imagepicker';

	function getInput(){
		global $gantry, $ajaxurl;

		$active_plugins = get_option('active_plugins');
		$layout = $link = $dropdown = "";
		$options = $choices = array();
		$nomargin = false;
		$rokgallery = !in_array('wp_rokgallery/rokgallery.php', $active_plugins) ? false : true;
		//$rokgallery = false; // debug

		$value = str_replace("'", '"', $this->value);
		$data = json_decode($value);
		if (!$data && strlen($value)){
			$nomargin = true;
			$data = json_decode('{"path":"'.$value.'"}');
		}
		$preview = "";
		$preview_width = 'width="100"';
		$preview_height = 'height="70"';

		if (!$data && (!isset($data->preview) || !isset($data->path))) $preview = $gantry->gantryUrl . '/admin/widgets/imagepicker/images/no-image.png';
		else if (isset($data->preview)) $preview = $data->preview;
		else {
			//$preview = site_url() . '/' . $data->path;
			$preview = $data->path;
			$preview_height = "";
		}

		$blank = $gantry->gantryUrl . '/admin/widgets/imagepicker/images/no-image.png';

		add_thickbox();

		if (!defined('ELEMENT_RTIMAGEPICKER')){
			$gantry->addStyle($gantry->gantryUrl . '/admin/widgets/imagepicker/css/imagepicker.css');

			$gantry->addInlineScript("
			if (typeof send_to_editor == 'undefined'){
				function send_to_editor(html) {
					var editor = window.currentIMGPickerID;

					var source = html.match(/(src)=(\"[^\"]*\")/i), img;
					text = source[2].replace(/\\\"/g, '');
					//img = '".site_url()."/' + text;
					img = text;


					document.getElementById(editor + '-img').src = img;
					document.getElementById(editor + '-img').removeProperty('height');
					document.getElementById(editor).value = JSON.encode({path: text});

					tb_remove();
				};
			};
			");

			$gantry->addInlineScript("
				var AdminURI = '" . $ajaxurl . "';
				var GalleryPickerInsertText = function(input, string, size, minithumb){
					var data = {
						path: string,
						width: size.width,
						height: size.height,
						preview: minithumb
					};

					document.getElementById(input + '-img').src = minithumb;
					document.getElementById(input + '-infos').innerHTML = data.width + ' x ' + data.height;
					document.getElementById(input).value = JSON.encode(data);

				};

				var empty_background_img = '" . $gantry->gantryUrl . "/admin/widgets/imagepicker/images/no-image.png';
			");


            define('ELEMENT_RTIMAGEPICKER', true);
        }

		$gantry->addInlineScript("
			window.addEvent('domready', function(){
				document.id('".$this->id."').addEvent('keyup', function(value){
					document.id('".$this->id."-infos').innerHTML = '';
					if (!value || !value.length) document.id('".$this->id."-img').set('src', empty_background_img);
					else {
						var data = JSON.decode(value);
						document.id('".$this->id."-img').set('src', (data.preview ? data.preview : '" . site_url() . "/' + data.path));
						if (!data.preview){
							document.id('".$this->id."-img').removeProperty('height');
						} else {
							document.id('".$this->id."-img').set('height', '50');
							if (data.width && data.height) document.id('".$this->id."-infos').innerHTML = data.width + ' x ' + data.height;
						}
					}

					this.setProperty('value', value);
				});

				document.id('".$this->id."-clear').addEvent('click', function(e){
					e.stop();
					document.id('".$this->id."').set('value', '').fireEvent('set', '');
				document.getElementById('".$this->id."-img').src = '".$blank."';
				document.getElementById('".$this->id."-infos').innerHTML = '';
				});

				document.id('".$this->id."-link').addEvent('mouseover', function(e){
					window.currentIMGPickerID = '".$this->id."';
				});

				var dropdown = document.id('".$this->id."mediatype');
				if (dropdown){
					dropdown.addEvent('change', function(){
						document.id('".$this->id."-link').set('href', this.value);
					});
				}
			});
		");

        if ($rokgallery) $link = get_bloginfo('wpurl') . '/wp-admin/admin-ajax.php?action=rokgallery_gallerypicker&TB_iframe=true&height=675&width=450&modal=false';
        else $link = get_bloginfo('wpurl') . '/wp-admin/media-upload.php?type=image&TB_iframe=true&height=640&width=620&tab=library&modal=false';

        if ($rokgallery){
			$choices = array(
				array('RokGallery', get_bloginfo('wpurl') . '/wp-admin/admin-ajax.php?action=rokgallery_gallerypicker&TB_iframe=true&height=675&width=450&modal=false'),
		    	array('Media Library', get_bloginfo('wpurl') . '/wp-admin/media-upload.php?type=image&TB_iframe=true&height=640&width=620&tab=library&modal=false')
		    );

			foreach ($choices as $option){
				$options[] = GantryHtmlSelect::option($option[1], $option[0], 'value', 'text');
			}

			include_once($gantry->gantryPath . '/admin/forms/fields/selectbox.php');
			$selectbox = new GantryFormFieldSelectBox;
			$selectbox->id = $this->id . 'mediatype';
			$selectbox->value = $link;
			$selectbox->addOptions($options);
			$dropdown = '<div id="'.$this->id.'-mediadropdown" class="mediadropdown">'.$selectbox->getInput() ."</div>";
        }

        $value = str_replace('"', "'", $value);
		$layout .= '
			<div class="wrapper">'."\n".'
				<div id="' . $this->id . '-wrapper" class="backgroundpicker">'."\n".'
					<img id="'.$this->id.'-img" class="backgroundpicker-img" '.$preview_width.' '.$preview_height.' alt="" src="'.$preview.'" />

					<div id="'.$this->id.'-infos" class="backgroundpicker-infos" '.($rokgallery && !$nomargin ? 'style="display:block;"' : 'style="display:none;"').' >'
						.((isset($data->width) && (isset($data->height))) ? $data->width.' x '.$data->height : '').
					'</div>


					<a id="'.$this->id.'-link" href="'.$link.'" class="bg-button thickbox modal">'."\n".'
						<span class="bg-button-right">'."\n".'
							Select
						</span>'."\n".'
					</a>'."\n".'
					<a id="'.$this->id.'-clear" href="#" class="bg-button bg-button-clear">'."\n".'
					<span class="bg-button-right">'."\n".'
							Reset
						</span>'."\n".'
					</a>'."\n".'

					' . $dropdown . '

					<input class="background-picker" type="hidden" id="' . $this->id . '" name="' . $this->name . '" value="' . $value . '" />'."\n".'
					<div class="clr"></div>
				</div>'."\n".'
			</div>'."\n".'
		';

		return $layout;
	}

}

?>
