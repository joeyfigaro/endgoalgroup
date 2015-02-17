<?php
/**
 * @version   $Id: gantrycache.class.php 59361 2013-03-13 23:10:27Z btowles $
 * @author    RocketTheme http://www.rockettheme.com
 * @copyright Copyright (C) 2007 - 2014 RocketTheme, LLC
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 */

require_once(gantry_dirname(__FILE__) . '/cache/cache.class.php');
require_once(gantry_dirname(__FILE__) . '/cache/wpTransientCacheDriver.class.php');

/**
 *
 */
class GantryCache
{
	/**
	 * @var
	 */
	static $instances = array();

	/**
	 *
	 */
	const GROUP_NAME = 'Gantry';
	/**
	 *
	 */
	const ADMIN_GROUP_NAME = 'GantryAdmin-4.1.2';

	const ADMIN_LIFETIME = 86400;

	const DEFAULT_LIFETIME = 900;

	/**
	 * Files to watch for changes.  Invalidate cache
	 * @var array
	 */
	protected $watch_files = array();

	/**
	 * The cache object.
	 *
	 * @var Cache
	 */
	protected $cache = null;

	/**
	 * Lifetime of the cache
	 * @access private
	 * @var int
	 */
	protected $lifetime = 900;

	/**
	 * @var string
	 */
	protected $base_cache_dir;

	/**
	 * @var
	 */
	protected $group;

	/**
	 * @static
	 *
	 * @param bool $admin
	 *
	 * @return GantryCache
	 */
	public static function getInstance($admin = false)
	{
		if ($admin) {
			$instance = self::getCache(self::ADMIN_GROUP_NAME);
		} else {
			$instance = self::getCache(self::GROUP_NAME);
	}
		return $instance;
	}

	/**
	 * @param bool $admin
	 */
	public function __construct($admin = false)
	{
		$this->cache = new GantryCacheLib();
	}


	/**
	 * @static
	 *
	 * @param      $group
	 * @param int  $lifetime
	 * @param bool $noexpire
	 *
	 * @return GantryCache
	 */
	public static function getCache($group, $lifetime = self::DEFAULT_LIFETIME, $noexpire = false)
	{
		$instance        = new GantryCache();
		$instance->group = $group;
		if (isset(self::$instances[$group])) return self::$instances[$group];
		if (!$noexpire) {

			$driver = new WpTransientCacheDriver($group, $lifetime);
		} else {
			$driver = new WpTransientCacheDriver($group, WpTransientCacheDriver::NO_EXPIRE_LIFETIME);
		}
		$instance->cache->addDriver($group, $driver);
		self::$instances[$group] = $instance;
		return $instance;
	}

	public function init()
	{
		/** @global $gantry Gantry */
		global $gantry;
		if (!is_admin()) {
			$this->group = self::GROUP_NAME;
			$this->cache->addDriver('frontend',  new WpTransientCacheDriver($this->group, $this->lifetime));
		} elseif (is_admin()) {
			// TODO get lifetime for backend cache
			$this->group = self::ADMIN_GROUP_NAME . '-4.1.2';
			$this->cache->addDriver('admin',  new WpTransientCacheDriver($this->group, self::ADMIN_LIFETIME));
		}
	}

	/**
	 *
	 */
	protected function checkForClear()
	{
		if ($this->checkWatchedFiles()) {
			$this->clearGroupCache();
		}
	}

	/**
	 * @param       $identifier
	 * @param null  $function
	 * @param array $arguments
	 *
	 * @return mixed
	 */
	public function call($identifier, $function = null, $arguments = array())
	{
		$this->checkForClear();
		$ret = $this->cache->get($this->group, $identifier);
		if ($ret == false && $function != null) {
			$ret = call_user_func_array($function, $arguments);
			$this->cache->set($this->group, $identifier, $ret);
		}
		return $ret;
	}

	/**
	 * @param $identifier
	 *
	 * @return mixed
	 */
	public function get($identifier, $checkTime = true)
	{
		$this->checkForClear();
		return $this->cache->get($this->group, $identifier);
	}

	/**
	 * @param $identifier
	 * @param $data
	 *
	 * @return bool
	 */
	public function set($identifier, $data)
	{
		return $this->cache->set($this->group, $identifier, $data);
	}

	/**
	 * @return bool
	 */
	public function clearAllCache()
	{
		return $this->cache->clearAllCache();
	}

	/**
	 * @return bool
	 */
	public function clearGroupCache()
	{
		return $this->cache->clearGroupCache($this->group);
	}

	/**
	 * @param $identifier
	 *
	 * @return bool
	 */
	public function clear($identifier)
	{
		return $this->cache->clearCache($this->group, $identifier);
	}

	/**
	 * Gets the lifetime for gantry
	 * @access public
	 * @return int
	 */
	public function getLifetime()
	{
		return $this->lifetime;
	}

	/**
	 * Sets the lifetime for gantry
	 * @access public
	 *
	 * @param int $lifetime
	 */
	public function setLifetime($lifetime)
	{
		$this->lifetime = $lifetime;
		$this->cache->setLifeTime($lifetime);
	}

	/**
	 * @param $filepath
	 */
	public function addWatchFile($filepath)
	{
		if (file_exists($filepath) && !in_array($filepath, $this->watch_files)) {
			$key                     = md5($filepath);
			$this->watch_files[$key] = $filepath;
			if ($this->cache->get($this->group, $key) === false) {
				$this->cache->set($this->group, $key, filemtime($filepath));
			}
		}
	}

	/**
	 * @return bool true if the cache needs to be clean
	 */
	protected function checkWatchedFiles()
	{
		// check the watch files
		foreach ($this->watch_files as $key => $watchfile) {
			if (file_exists($watchfile)) {
				$file_mtime       = filemtime($watchfile);
				$cached_filemtime = $this->cache->get($this->group, $key);
				if ($cached_filemtime != false && $file_mtime != $cached_filemtime) {
					return true;
				}
			}
		}
		return false;
	}
}