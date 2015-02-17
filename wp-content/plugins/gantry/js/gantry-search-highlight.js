/**
 * @version $Id: gantry-inputs.js 58623 2012-12-15 22:01:32Z btowles $
 * @author RocketTheme http://www.rockettheme.com
 * @copyright Copyright (C) 2007 - 2014 RocketTheme, LLC
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 */

function highlight(term) {
	var container = document.getElement('.component-content'),
		content = container.get('html'),
		replacement = "",
		index = -1;

	var lcterm = term.toLowerCase(),
		lccontent = content.toLowerCase();

	while (content.length > 0){
		index = lccontent.indexOf(lcterm, index + 1);

		if (index < 0){
			replacement += content;
			content = "";
		} else {
			// skip anything inside an HTML tag
			if (content.lastIndexOf(">", index) >= content.lastIndexOf("<", index)){
			// skip anything inside a <script> block
			if (lccontent.lastIndexOf("/script>", index) >= lccontent.lastIndexOf("<script", index)){
					replacement += content.substring(0, index) + '<span class="highlight">' + content.substr(index, term.length) + '</span>';
					content = content.substr(index + term.length);
					lccontent = content.toLowerCase();
					index = -1;
				}
			}
		}
	}

	container.set('html', replacement);
}