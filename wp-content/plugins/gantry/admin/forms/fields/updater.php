<?php
/**
 * @version   $Id: updater.php 59361 2013-03-13 23:10:27Z btowles $
 * @author    RocketTheme http://www.rockettheme.com
 * @copyright Copyright (C) 2007 - 2014 RocketTheme, LLC
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 */
defined('GANTRY_VERSION') or die();

gantry_import('core.config.gantryformfield');

/**
 * @package     gantry
 * @subpackage  admin.elements
 */
class GantryFormFieldUpdater extends GantryFormField
{

	protected $type = 'updatrer';
	protected $basetype = 'none';

	public function getInput()
	{

		/** @global $gantry Gantry */
		global $gantry;

		$currentVersion = GANTRY_VERSION;

		if ($currentVersion == "\4.1.2") $currentVersion = "[DEV]";

		// curl check
		if (!function_exists('curl_version')) {
			$upd = "<strong>cURL is required to check for latest versions of the Gantry Framework. </strong> Learn more at <a href='http://www.php.net/manual/en/book.curl.php'>http://www.php.net</a>";

			return "
				<div id='updater' class='update'>
					<div id='updater-bar' class='h2bar'>Gantry <span>v" . $currentVersion . "</span></div>
					<div id='updater-desc'>" . $upd . "</div>
				</div>
			";
		}

		gantry_import('core.gantryini');
		gantry_import('core.utilities.gantryxml');

		/** @global $gantry Gantry */
		global $gantry;

		$klass      = "noupdate";
		$output     = "";
		$statusText = "";

		$now        = time();
		$cache_file = $gantry->custom_dir . DS . 'gantry_version';


		if (file_exists($cache_file) && is_file($cache_file) && is_readable($cache_file)) {
			$old_cache_data = GantryINI::read($cache_file, $this->_name, 'check');
		} else {
			$old_cache_data['version'] = GANTRY_VERSION;
			$old_cache_data['date']    = 1;
			$old_cache_data['link']    = '';
		}

		$old_cache_date = time($old_cache_data['date']);

		// only grab from the web if its been more the 24 hours since the last check
		if (($old_cache_date + (24 * 60 * 60)) < $now) {
			$data = $this->_get_url_contents('http://code.google.com/feeds/p/gantry-framework/downloads/basic');

			if (!empty($this->_error)) {
				$klass          = "update";
				$upd            = "<strong>Error checking version:</strong> " . $this->_error;
				$latest_version = GANTRY_VERSION;
			} else {
				$xml = new GantryXML();
				$xml->loadString($data);

				foreach ($xml->document->entry as $entry) {
					$title = (string)$entry->title[0]->data();
					if (preg_match('/gantry_wordpress_framework-(.*).zip/', $title, $matches)) {
						$linkattribs                                  = $entry->link[0]->attributes();
						$link                                         = (string)$linkattribs['href'];
						$latest_version                               = $matches[1];
						$cache_data[$this->_name]['check']['version'] = $latest_version;
						$cache_data[$this->_name]['check']['link']    = $link;
						$cache_data[$this->_name]['check']['date']    = $now->toUNIX();
						GantryINI::write($cache_file, $cache_data, false);
						break;
					}
				}
			}
		} else {
			$latest_version = $old_cache_data['version'];
			$link           = $old_cache_data['link'];
		}

		if ($latest_version != GANTRY_VERSION) {
			$klass = "update";
			$upd   = "<strong>Version " . $latest_version . " of the Gantry Framework is Available</strong>.  Please <a href='" . $link . "'>download the latest version</a> now.";
		} else {

			$upd = "<strong>The Gantry Framework is up-to-date!</strong><br />You are running the latest version, you will be notified here if a newer version is available.";
		}

		$output = "
		<div id='updater' class='" . $klass . "'>
			<div id='updater-bar' class='h2bar'>Gantry <span>v" . $currentVersion . "</span></div>
			<div id='updater-desc'>" . $upd . "<br />
			Your server is using <b>" . php_sapi_name() . "</b> as PHP interface.</div>
		</div>";

		return $output;

	}

	public function getLabel()
	{
		return "";
	}
}