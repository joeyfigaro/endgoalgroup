<?php
/**
 * @version   $Id: Util.php 60706 2014-04-07 16:57:27Z jakub $
 * @author    RocketTheme http://www.rockettheme.com
 * @copyright Copyright (C) 2007 - 2014 RocketTheme, LLC
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 */

class Gantry_Uri_Util
{

	const FULL_URL = Gantry_Uri_Components::ABSOLUTE_URI;

	const SCHEME_RELATIVE_URL = Gantry_Uri_Components::SCHEME_RELATIVE_URI;

	const RELATIVE_URL = Gantry_Uri_Components::RELATIVE_URI;

	/**
	 * @var Gantry_Uri
	 */
	protected $site_uri;

	/**
	 * @var
	 */
	protected $site_root_path;
	/**
	 * @var
	 */
	protected $site_root_url;
	/**
	 * @var
	 */
	protected $server_root_path;


	/**
	 * @var string;
	 */
	protected $original;

	/**
	 * @param $site_root_path
	 * @param $site_root_url
	 */
	public function __construct($site_root_path, $site_root_url)
	{
		$this->site_root_path = self::cleanFilesystemPath($site_root_path);
		$this->site_root_url  = $site_root_url;
		$this->site_uri       = new Gantry_Uri($site_root_url);
		if (isset($_SERVER['DOCUMENT_ROOT'])) {
			$this->server_root_path = self::cleanFilesystemPath($_SERVER['DOCUMENT_ROOT']);
		}
	}

	/**
	 * @param $path
	 *
	 * @return mixed
	 */
	public static function cleanFilesystemPath($path)
	{
		if (!preg_match('#^/$#', $path)) {
			$path = preg_replace('#[/\\\\]+#', '/', $path);
			$path = preg_replace('#/$#', '', $path);
		}
		if (preg_match('/^WIN/', PHP_OS) && preg_match('#^[a-zA-Z]:#', $path)) {
			$path = lcfirst($path);
		}
		return rtrim($path, '/\\');
	}

	/**
	 * Determine if the the passed url is external to the current running platform
	 *
	 * @param Gantry_Uri $uri
	 *
	 * @return mixed
	 */
	public function isExternal($uri)
	{
		if (is_string($uri)) $uri = new Gantry_Uri($uri);
		if (@file_exists($uri->getComponents(Gantry_Uri_Components::PATH, Gantry_Uri_Builder::FORMAT_RAW))) return false;

		//if the url does not have a scheme must be internal
		if (is_null($uri->getHost())) return false;
		if ($uri->getHost() == $this->site_uri->getHost()) {
			if ($uri->getPort() === Gantry_Uri::PORT_UNDEFINED) return false;
			if ($uri->getPort() === $this->site_uri->getPort()) return false;
		}
		// if its a vfs url its a unit test and local
		if ($uri->getScheme() == 'vfs') return false;
		//the url has a host and it isn't internal
		return true;
	}

	/**
	 * @param Gantry_Uri $uri
	 *
	 * @internal param $url
	 *
	 * @return bool|string
	 */
	public function getFilesystemPath($uri)
	{
		if (is_string($uri)) $uri = new Gantry_Uri($uri);
		// if its an external link dont even process
		if ($this->isExternal($uri)) return false;

		$clean_uri_path = (preg_match('/^WIN/', PHP_OS) && preg_match('/^[a-zA-Z]:/', $uri->getPath())) ? '' : '/';
		$clean_uri_path .= self::cleanFilesystemPath($uri->getPath());

		$clean_site_uri_path = (preg_match('/^WIN/', PHP_OS) && preg_match('/^[a-zA-Z]:/', $this->site_uri->getPath())) ? '' : '/';
		$clean_site_uri_path .= self::cleanFilesystemPath($this->site_uri->getPath());

		// see if it is already a local file url with a full path
		if ((is_null($uri->getScheme()) || ($uri->getScheme() == 'file' && (is_null($uri->getHost()) || strtolower($uri->getHost()) == 'localhost'))) && @file_exists($clean_uri_path)) {
			return $clean_uri_path;
		}

		$missing_ds = (substr($clean_uri_path, 0, 1) != '/') ? '/' : '';
		// Normal case of uri path is in line with the site path
		if (!is_null($this->site_uri->getPath()) && strpos($clean_uri_path, $clean_site_uri_path) === 0) {
			$stripped_base = $clean_uri_path;
			if (strpos($stripped_base, $clean_site_uri_path) == 0) {
				$stripped_base = substr_replace($stripped_base, '', 0, strlen($clean_site_uri_path));
				$stripped_base = ((substr($stripped_base, 0, 1) != '/') ? '/' : '') . $stripped_base;
			}
			$return_path = $this->site_root_path . $missing_ds . $stripped_base;

		} elseif (is_null($this->site_uri->getPath()) && @file_exists($this->site_root_path . '/' . $clean_uri_path)) {
			$return_path = $this->site_root_path . $missing_ds . $clean_uri_path;
		} elseif (!is_null($this->server_root_path) && @file_exists($this->server_root_path . '/' . $clean_uri_path)) {
			$return_path = $this->server_root_path . $missing_ds . $clean_uri_path;
		} else {
			$return_path = $clean_uri_path;
		}
		return $return_path;
	}

	/**
	 * @param     $path
	 * @param int $type
	 *
	 * @return mixed|string
	 */
	public function getUrlForPath($path, $type = self::RELATIVE_URL)
	{
		if (is_string($path)) $uri = new Gantry_Uri($path);
		else if ($path instanceof Gantry_Uri) $uri = $path;
		else return false;


		// if its external  just return the external url
		if ($this->isExternal($uri)) return $path;


		$clean_uri_path = (preg_match('/^WIN/', PHP_OS) && preg_match('/^[a-zA-Z]:/', $uri->getPath())) ? '' : '/';
		$clean_uri_path .= self::cleanFilesystemPath($uri->getPath());

		$clean_site_uri_path = (preg_match('/^WIN/', PHP_OS) && preg_match('/^[a-zA-Z]:/', $this->site_uri->getPath())) ? '' : '/';
		$clean_site_uri_path .= self::cleanFilesystemPath($this->site_uri->getPath());

		if (!@file_exists($clean_uri_path)) {
			return $clean_uri_path;
		}

		$return_uri = clone $this->site_uri;
		// check if the path seems to be in the instances  or  server path
		// leave it as is if not one of the two
		if (!empty($this->site_root_path) && strpos($clean_uri_path, $this->site_root_path) === 0) {
			// its an instance path
			$return_uri->appendPath(str_replace($this->site_root_path, '', $clean_uri_path));
		} elseif (!empty($this->server_root_path) && strpos($clean_uri_path, $this->server_root_path) === 0) {
			// its a server path
			$return_uri->setPath(str_replace($this->server_root_path, '', $clean_uri_path));
		}
		else if (empty($this->server_root_path) && !isset($_SERVER['DOCUMENT_ROOT']))
		{
			trigger_error('No $_SERVER[\'DOCUMENT_ROOT\'] value is set.  Unable to find path to map to URL. Assuming root.',E_USER_NOTICE);
			$return_uri = $uri;
		}
		else
		{
			$return_uri = $uri;
		}

		return $return_uri->getComponents($type);
	}

}

if(false === function_exists('lcfirst'))
{
    /**
     * Make a string's first character lowercase
     *
     * @param string $str
     * @return string the resulting string.
     */
    function lcfirst( $str ) {
        $str[0] = strtolower($str[0]);
        return (string)$str;
    }
}