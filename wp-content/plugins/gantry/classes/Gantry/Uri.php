<?php
/**
 * @version   $Id: Uri.php 59350 2013-03-13 17:14:16Z btowles $
 * @author    RocketTheme http://www.rockettheme.com
 * @copyright Copyright (C) 2007 - 2014 RocketTheme, LLC
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 */

// Copyright (c) 2009 The H5 Authors. All rights reserved.
// Use of this source code is governed by a BSD-style license that can be
// found in the LICENSE file.


class Gantry_Uri
{
	const PORT_UNDEFINED = -1;

	/**
	 * @var array
	 *      An internal map of common schemes and their default port numbers.
	 */
	private static $schemeToPortMap = array(
		'ftp'    => 21,
		'gopher' => 70,
		'http'   => 80,
		'https'  => 443,
		'news'   => 119,
		'nntp'   => 119,
		'wais'   => 210,
		'telnet' => 23,
	);

	/**
	 * URI Regular Expression Match Parts
	 * 0 - whole URI
	 * 1 - path for urls with no scheme or authority
	 * 2 - scheme
	 * 3 - userinfo
	 * 4 - host
	 * 5 - port
	 * 6 - path for urls with an authority
	 * 7 - path for schemes without an authority (urn,news,mailto,tel)
	 * 8 - query string
	 * 9- fragment/anchor
	 */
	private static $uriParseMap = array(
		1 => 'path',
		2 => 'scheme',
		3 => 'userInfo',
		4 => 'host',
		5 => 'port',
		6 => 'path',
		7 => 'path',
		8 => 'query',
		9 => 'fragment',
	);

	/**
	 * @var string
	 */
	private $originalString;

	/**
	 * @var string
	 *      The scheme name for this URI.
	 */
	private $scheme;

	/**
	 * @var string
	 *      The user-specific information associated with the specified URI.
	 */
	private $userInfo;

	/**
	 * @var string
	 *      The host name component of the Gantry_Uri.
	 */
	private $host;

	/**
	 * @var integer
	 *      The port number for this URI.
	 */
	private $port = self::PORT_UNDEFINED;

	/**
	 * @var string
	 *      The path component of the Gantry_Uri.
	 */
	private $path = '';

	/**
	 * @var string
	 *      The query information included in the specified URI.
	 */
	private $query = '';

	/**
	 * @var string
	 *      The URI fragment information.
	 */
	private $fragment = '';

	/**
	 * @var array
	 *      The query params expanded
	 */
	private $query_params = array();

	/**
	 * Constructs the new URI from the provided {@link $uriString}.
	 *
	 * @param string $uriString
	 *        The URI string to convert to an object.
	 */
	public function __construct($uriString)
	{
		$this->originalString = $uriString;

		$this->parseUriString($this->originalString);
	}

	/**
	 * Returns the string representation of this object.
	 *
	 * @return string
	 *         The absolute or relative URI string.
	 */
	public function __toString()
	{
		return $this->isAbsoluteUri() ? $this->getAbsoluteUri() : $this->getRelativeUri();
	}

	/**
	 * Returns the original URI string which was passed to the constructor.
	 *
	 * @return string
	 *         The original URI string passed to the constructor.
	 */
	public function getOriginalString()
	{
		return $this->originalString;
	}

	/**
	 * Determines whether this object represents an absolute URI.
	 *
	 * URI is absolute if it has its scheme and host components specified.
	 *
	 * @return boolean
	 *         TRUE if this object represents an absolute URI; otherwise, FALSE.
	 */
	public function isAbsoluteUri()
	{
		return ($this->scheme !== null) && ($this->host !== null);
	}

	/**
	 * Determines whether this URI's port is a default one for it's scheme.
	 *
	 * @return boolean
	 *         TRUE if this URI's port is default one for it's scheme.
	 */
	public function isDefaultPort()
	{
		if (!isset(self::$schemeToPortMap[$this->getScheme()])) {
			return false;
		}

		return self::$schemeToPortMap[$this->getScheme()] === $this->getPort();
	}

	/**
	 * @param Gantry_Uri   $uri
	 * @param array $components
	 *
	 * @return Gantry_Uri
	 * @see    UriBuilder::copyComponents()
	 */
	public function copyComponents(Gantry_Uri $uri, array $components = null)
	{
		return Gantry_Uri_Builder::copyComponents($uri, $this, $components);
	}

	/**
	 * @param      $components
	 * @param bool $format
	 *
	 * @return string
	 * @see    UriBuilder::getComponents()
	 */
	public function getComponents($components, $format = Gantry_Uri_Builder::FORMAT_ESCAPED)
	{
		return Gantry_Uri_Builder::getComponents($this, $components, $format);
	}

	/**
	 * @param array $components
	 *
	 * @return Gantry_Uri
	 * @see    UriBuilder::setComponents()
	 */
	public function setComponents(array $components)
	{
		return Gantry_Uri_Builder::setComponents($this, $components);
	}

	/**
	 * @return string?
	 */
	public function getScheme()
	{
		return $this->scheme;
	}

	/**
	 * @param  string? $value
	 *
	 * @return Gantry_Uri
	 */
	public function setScheme($value)
	{
		$this->scheme = strtolower($value);

		return $this;
	}

	/**
	 * @return string?
	 */
	public function getUserInfo()
	{
		return $this->userInfo;
	}

	/**
	 * @param  string? $value
	 *
	 * @return Gantry_Uri
	 */
	public function setUserInfo($value)
	{
		$this->userInfo = $value;

		return $this;
	}

	/**
	 * @return string?
	 */
	public function getHost()
	{
		return $this->host;
	}

	/**
	 * @param  string? $value
	 *
	 * @return Gantry_Uri
	 */
	public function setHost($value)
	{
		$this->host = strtolower($value);

		return $this;
	}

	/**
	 * @return int
	 */
	public function getPort()
	{
		if (($this->port === self::PORT_UNDEFINED) && isset(self::$schemeToPortMap[$this->scheme])) {
			return self::$schemeToPortMap[$this->scheme];
		}

		return $this->port;
	}

	/**
	 * @param  int $value
	 *
	 * @return Gantry_Uri
	 */
	public function setPort($value)
	{
		$this->port = (int)$value;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getAuthority()
	{
		return Gantry_Uri_Builder::getComponents($this, Gantry_Uri_Components::AUTHORITY, Gantry_Uri_Builder::FORMAT_RAW);
	}

	/**
	 * @return string
	 */
	public function getPath()
	{
		return $this->path;
	}

	/**
	 * @param  string $value
	 *
	 * @return Gantry_Uri
	 */
	public function setPath($value)
	{
		$this->path = $this->cleanPath(trim($value, "/\\"));
		return $this;
	}

	public function appendPath($value)
	{
		$this->path .= '/' . trim($value, "/\\");
		$this->path = trim($this->path, "/\\");
		return $this;
	}

	/**
	 * @return string
	 */
	public function getQuery()
	{
		return $this->query;
	}

	/**
	 * @param  string $value
	 *
	 * @return Gantry_Uri
	 */
	public function setQuery($value)
	{
		$this->query = trim($value, '&?');
		$this->generateParamsFromQuery();
		return $this;
	}

	/**
	 * @return string
	 */
	public function getFragment()
	{
		return $this->fragment;
	}

	/**
	 * @param  string $value
	 *
	 * @return Gantry_Uri
	 */
	public function setFragment($value)
	{
		$this->fragment = trim($value, '#');

		return $this;
	}

	/**
	 * @return string
	 * @throws \BadMethodCallException
	 */
	public function getAbsoluteUri()
	{
		return Gantry_Uri_Builder::getComponents($this, Gantry_Uri_Components::ABSOLUTE_URI, Gantry_Uri_Builder::FORMAT_RAW);
	}

	/**
	 * @return string
	 */
	public function getRelativeUri()
	{
		return Gantry_Uri_Builder::getComponents($this, Gantry_Uri_Components::RELATIVE_URI, Gantry_Uri_Builder::FORMAT_RAW);
	}

	/**
	 * @param array $query_params
	 *
	 * @return \Gantry_Uri
	 */
	public function setQueryParams($query_params)
	{
		$this->query_params = $query_params;
		return $this;
	}

	/**
	 * @return array
	 */
	public function getQueryParams()
	{
		return $this->query_params;
	}

	/**
	 * Gets the value of a passed query string key
	 *
	 * @param string $key the query param to get the value for
	 *
	 * @return string|bool The string value passed for the key  or FALSE if the key doesnt exist
	 */
	public function getQueryParam($key)
	{
		$ret = false;
		if (array_key_exists($key, $this->query_params)) {
			$ret = $this->query_params[$key];
		}
		return $ret;
	}

	public function addQueryParam($key, $value = null)
	{
		if (is_null($value)) {
			$value = '';
		}
		$this->query_params[$key] = $value;
		$this->query              = $this->generateQueryFromParams();
		return $this;
	}

	protected function generateQueryFromParams()
	{
		$vars = array();

		foreach ($this->query_params as $key => &$value) {
			if (is_null($value)) {
				$vars[] = $key;
			} else {
				$value  = array($value);
				$vars[] = implode('&', array_map(create_function('$value', 'return  \'' . $key . '=\' . urlencode($value);'), $value));
			}
		}
		$query = implode('&', array_filter($vars, create_function('$var', 'return strlen($var);')));
		return $query;
	}


	/**
	 * Checks to see if a particular query string param was passed
	 *
	 * @param string $key
	 *
	 * @return bool
	 */
	public function isQueryParamSet($key)
	{
		return array_key_exists($key, $this->query_params);
	}

	/**
	 * @param  string $uriString
	 */
	protected function parseUriString($uriString)
	{
		if (empty($uriString)) {
			return;
		}

		if (preg_match('/^WIN/', PHP_OS)){
			$uriString = str_replace('\\', '/',$uriString);
			$this->originalString = $uriString;
		}

		/*
		* URI Regular Expression Match Parts
		* 0 - whole URI
		* 1 - path for urls with no scheme or authority
		* 2 - scheme
		* 3 - userinfo
		* 4 - host
		* 5 - port
		* 6 - path for urls with an authority
		* 7 - path for schemes without an authority (urn,news,mailto,tel)
		* 8 - query string
		* 9 - fragment/anchor
		*/
		$parse_result = preg_match("/^(?:((?:[a-z0-9\\-._~%!$&'()*+,;=@]+(?:\/[a-z0-9\\-._~%!$&'()*+,;=:@]+)*\/?|(?:\/[ a-z0-9\\-._~%!$&'()*+,;=:@]+)+\/?))|(?:([a-z][a-z0-9+\\-.]*):)?(?:\/\/(?:(?:([a-z0-9\\-._~%!$&:'()*+,;=]+)?@)?([a-z0-9\\-._~%]+|\\[[a-f0-9:.]+\\]|\\[v[a-f0-9][a-z0-9\\-._~%!$&'()*+,;=:]+\\])(?::([0-9]+)?)?)?((?:\/[ a-z0-9\\-._~%!$&'()*+,;=:@]+)*\/?)|((?:\/?[a-z0-9\\-._~%!$&'()*+,;=:@]+(?:\/[a-z0-9\\-._~%!$&'()*+,;=:@]+)*\/?)?)))(?:\\?([a-z0-9\\-._~%!$&'()*+,;=:@\/?]*))?(?:\\#([a-z0-9\\-._~%!$&'()*+,;=:@\/?]*))?$/iu", $uriString, $m);
		if ($parse_result) {
			foreach ($m as $component_key => $component_value) {
				if (0 !== (int)$component_key && !empty($component_value)) {
					$part = Gantry_Uri::$uriParseMap[$component_key];
					$this->{'set' . $part}($component_value);
				}
			}
		}

		$this->generateParamsFromQuery();
	}

	protected function generateParamsFromQuery()
	{
		//Split the Query String
		$query_string_parts = array();
		if (isset($this->query)) {
			$this->query        = preg_replace('/&(?!amp;)/i', '&amp;', $this->query);
			$query_string_parts = explode('&amp;', $this->query);
		}
		foreach ($query_string_parts as $query_string_part) {
			if (trim($query_string_part) == '') {
				continue;
			}
			if (strpos($query_string_part, '=') !== false) {
				list($part_key, $part_value) = explode('=', $query_string_part);
				$this->query_params[$part_key] = urldecode($part_value);
			} else {
				$this->query_params[$query_string_part] = '';
			}
		}
	}

	/**
	 * @param $path
	 *
	 * @return mixed
	 */
	protected function cleanPath($path)
	{
		if (!preg_match('#^/$#', $path)) {
			$path = preg_replace('#[/\\\\]+#', '/', $path);
			$path = preg_replace('#/$#', '', $path);
		}
		if (preg_match('/^[a-zA-Z]$/', $this->scheme)) {
			$path         = $this->scheme . ':/' . $path;
			$this->scheme = 'file';
		}
		return rtrim($path, '/\\');
	}
}