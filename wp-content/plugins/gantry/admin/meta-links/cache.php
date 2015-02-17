<?php
/**
 * @version   $Id: cache.php 59361 2013-03-13 23:10:27Z btowles $
 * @author    RocketTheme http://www.rockettheme.com
 * @copyright Copyright (C) 2007 - 2014 RocketTheme, LLC
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 */

/** @global $gantry Gantry */
global $gantry;
$meta_name = "cache";

$gantry->addDomReadyScript("
		var screenMeta = document.id('screen-meta');
		if (screenMeta) document.id('contextual-" . $meta_name . "-wrap').inject(screenMeta, 'top');

		(function($){
			var othersmeta = $('#screen-meta-links > div[id!=meta-" . $meta_name . "-link-wrap]');
			$('#meta-" . $meta_name . "-link').click(function () {
				if (!$('#contextual-" . $meta_name . "-wrap').hasClass('contextual-" . $meta_name . "-open'))
					$('#screen-meta-links > div[id!=meta-" . $meta_name . "-link-wrap]').css('visibility', 'hidden');
				
				$('#contextual-" . $meta_name . "-wrap').slideToggle('fast', function() {
					if ($(this).hasClass('contextual-" . $meta_name . "-open')) {
						$('#meta-" . $meta_name . "-link').css({'backgroundImage':'url(images/screen-options-right.gif)'});
						othersmeta.css('visibility', '');
						$(this).removeClass('contextual-" . $meta_name . "-open');
					} else {
						$('#meta-" . $meta_name . "-link').css({'backgroundImage':'url(images/screen-options-right-up.gif)'});
						$(this).addClass('contextual-" . $meta_name . "-open');
					}
				});
				
				return false;
			});	
		})(jQuery);
	");

?>

<div id="contextual-<?php echo $meta_name; ?>-wrap" class="hidden contextual-custom-wrap">
	<div class="metabox-prefs">
		this is cache panel.
	</div>
</div>