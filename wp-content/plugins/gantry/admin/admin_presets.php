<?php
 /**
  * @version   $Id: admin_presets.php 59361 2013-03-13 23:10:27Z btowles $
  * @author    RocketTheme http://www.rockettheme.com
  * @copyright Copyright (C) 2007 - 2014 RocketTheme, LLC
  * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
  */
 ?>
<div id="hack-panel">
	<?php
	$fields = $form->getFullFieldset('toolbar-panel');
	foreach ($fields as $name => $field) {
		$status = 'hide';
		if (isset($_COOKIE['gantry-' . $gantry->templateName . '-adminpresets'])) {
			$status = htmlentities($_COOKIE['gantry-' . $gantry->templateName . '-adminpresets']);
		}
		$style = ' style="display: none";';

		if ($status != 'hide') {
			$status = 'hide';
			$style  = '';
		}

		echo "<div id=\"contextual-" . $field->type . "-wrap\" class=\"contextual-custom-wrap\"" . $style . ">\n";
		echo "		<div class=\"metabox-prefs\">\n";

		echo $field->input;

		echo "		</div>\n";
		echo "</div>\n";
	}
	?>
</div>