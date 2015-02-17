<?php
/**
 * @version   $Id: Builder.php 59417 2013-03-20 16:50:35Z btowles $
 * @author    RocketTheme http://www.rockettheme.com
 * @copyright Copyright (C) 2007 - 2014 RocketTheme, LLC
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 */

// Copyright (c) 2009 The H5 Authors. All rights reserved.
// Use of this source code is governed by a BSD-style license that can be
// found in the LICENSE file.

class Gantry_Uri_Builder
{
	const FORMAT_ESCAPED = true;
	const FORMAT_RAW     = false;

	/**
	 * @var Gantry_Uri_Builder
	 */
	private static $instance;

	/**
	 * @param Gantry_Uri_Builder $instance
	 */
	public static function setInstance(Gantry_Uri_Builder $instance)
	{
		self::$instance = $instance;
	}

	/**
	 * @return Gantry_Uri_Builder
	 */
	public static function getInstance()
	{
		if (self::$instance === null) {
			self::$instance = new Gantry_Uri_Builder();
		}

		return self::$instance;
	}

	public function __construct()
	{

	}

	public function __toString()
	{
		return get_class($this);
	}

	/**
	 * @see Uri::copyUriComponents()
	 */
	public static function copyComponents(Gantry_Uri $fromUri, Gantry_Uri $toUri, array $components = null)
	{
		return self::getInstance()->copyUriComponents($fromUri, $toUri, $components);
	}

	/**
	 * @see UriBuilder::getUriComponents()
	 */
	public static function getComponents(Gantry_Uri $uri, $components, $format = self::FORMAT_ESCAPED)
	{
		return self::getInstance()->getUriComponents($uri, $components, $format);
	}

	/**
	 * @see UriBuilder::setUriComponents()
	 */
	public static function setComponents(Gantry_Uri $uri, array $components)
	{
		return self::getInstance()->setUriComponents($uri, $components);
	}

	/**
	 * Copies the specified URi's {@link $components} from the provided
	 * {@link $fromUri} to {@link $toUri}.
	 *
	 * @param  Gantry_Uri $fromUri
	 *                  The URI to copy from.
	 * @param  Gantry_Uri $toUri
	 *                  The URI to copy to.
	 * @param      array<Gantry_Uri_Components>? $components
	 *                  An array of basic components to copy from the {@link $fromUri},
	 *                  or NULL to copy all components.
	 *
	 * @return Gantry_Uri
	 *         The {@link $toUri} with copied components.
	 */
	public function copyUriComponents(Gantry_Uri $fromUri, Gantry_Uri $toUri, array $components = null)
	{
		if ($components === null) {
			return self::copyAllComponents($fromUri, $toUri);
		}

		return self::copySpecificComponents($fromUri, $toUri, $components);
	}

	/**
	 * Returns a string representing the specified {@link $components}
	 * of the provided {@link $uri}.
	 *
	 * @param  Gantry_Uri     $uri
	 * @param  integer $components
	 *         A bitwise combination of the {@link Gantry_Uri_Components} values that
	 *         specifies which parts of the provided URI instance to return
	 *         to the caller.
	 * @param  boolean $format
	 *         Whether to escape certain components.
	 *
	 * @return string
	 *         A string that contains the components.
	 */
	public function getUriComponents(Gantry_Uri $uri, $components, $format = self::FORMAT_ESCAPED)
	{
		$esc = $format === self::FORMAT_ESCAPED;

		$absolute = self::getAbsoluteComponents($uri, $components, $esc);
		$relative = self::getRelativeComponents($uri, $components, $esc);

		return $absolute . $relative;
	}

	/**
	 * Sets the specified {@link $components} of the provided {@link $uri}.
	 *
	 * @param  Gantry_Uri $uri
	 * @param      array<Gantry_Uri_Components,string> $components
	 *
	 * @return Gantry_Uri
	 *         The provided {@link $uri} with changed components.
	 */
	public function setUriComponents(Gantry_Uri $uri, array $components)
	{
		foreach ($components as $component => $value) {
			switch ($component) {
				case Gantry_Uri_Components::SCHEME:
					$uri->setScheme($value);
					break;

				case Gantry_Uri_Components::USERINFO:
					$uri->setUserInfo($value);
					break;

				case Gantry_Uri_Components::HOST:
					$uri->setHost($value);
					break;

				case Gantry_Uri_Components::PORT:
					$uri->setPort($value);
					break;

				case Gantry_Uri_Components::PATH:
					$uri->setPath($value);
					break;

				case Gantry_Uri_Components::QUERY:
					$uri->setQuery($value);
					break;

				case Gantry_Uri_Components::FRAGMENT:
					$uri->setFragment($value);
					break;
			}
		}

		return $uri;
	}

	/**
	 * @param  Gantry_Uri $fromUri
	 * @param  Gantry_Uri $toUri
	 *
	 * @return Gantry_Uri
	 */
	private static function copyAllComponents(Gantry_Uri $fromUri, Gantry_Uri $toUri)
	{
		return $toUri->setScheme($fromUri->getScheme())->setUserInfo($fromUri->getUserInfo())->setHost($fromUri->getHost())->setPort($fromUri->getPort())->setPath($fromUri->getPath())->setQuery($fromUri->getQuery())->setFragment($fromUri->getFragment());
	}

	/**
	 * @param  Gantry_Uri $fromUri
	 * @param  Gantry_Uri $toUri
	 * @param      array<Gantry_Uri_Components>
	 *
	 * @return Gantry_Uri
	 */
	private static function copySpecificComponents(Gantry_Uri $fromUri, Gantry_Uri $toUri, array $components)
	{
		foreach ($components as $component) {
			switch ($component) {
				case Gantry_Uri_Components::SCHEME:
					$toUri->setScheme($fromUri->getScheme());
					break;

				case Gantry_Uri_Components::USERINFO:
					$toUri->setUserInfo($fromUri->getUserInfo());
					break;

				case Gantry_Uri_Components::HOST:
					$toUri->setHost($fromUri->getHost());
					break;

				case Gantry_Uri_Components::PORT:
					$toUri->setPort($fromUri->getPort());
					break;

				case Gantry_Uri_Components::PATH:
					$toUri->setPath($fromUri->getPath());
					break;

				case Gantry_Uri_Components::QUERY:
					$toUri->setQuery($fromUri->getQuery());
					break;

				case Gantry_Uri_Components::FRAGMENT:
					$toUri->setFragment($fromUri->getFragment());
					break;
			}
		}

		return $toUri;
	}

	/**
	 * @param  Gantry_Uri   $uri
	 * @param  array $components
	 * @param  bool  $esc
	 *
	 * @return string
	 */
	private static function getAbsoluteComponents(Gantry_Uri $uri, $components, $esc)
	{
		$result = '';

		if ($components & Gantry_Uri_Components::SCHEME) {
			$cmp = $uri->getScheme();
			if (!empty($cmp)){
				$result .= "{$cmp}:";
			}
		}
		// Authority Start
		if ($components & Gantry_Uri_Components::AUTHORITY_START)
		{
			$result .= "//";
		}

		//User info
		if ($components & Gantry_Uri_Components::USERINFO) {
			$cmp = $uri->getUserInfo();
			if (!empty($cmp)){
				if ($esc && (strpos($cmp, ':') !== false)) {
					$parts = explode(':', $cmp, 2);

					$result .= rawurlencode($parts[0]) . ':' . rawurlencode($parts[1]);
				} else if ($esc) {
					$result .= rawurlencode($cmp);
				} else {
					$result .= $cmp;
				}

				$result .= '@';
			}
		}

		//HOST:
		if ($components & Gantry_Uri_Components::HOST) {
			$cmp = $uri->getHost();

			if (!empty($cmp)){
				$result .= $cmp;
			}
		}

		//PORT:
		if (($components & Gantry_Uri_Components::STRONG_PORT) || ($components & Gantry_Uri_Components::PORT)) {
			$cmp = $uri->getPort();

			if (!(($cmp === -1) || (($components & Gantry_Uri_Components::PORT) && $uri->isDefaultPort()))){
				$result .= ":{$cmp}";
			}
		}
		return $result;
	}

	/**
	 * @param  Gantry_Uri   $uri
	 * @param  array $components
	 * @param  bool  $esc
	 *
	 * @return string
	 */
	private static function getRelativeComponents(Gantry_Uri $uri, $components, $esc)
	{
		$result = '';

		if ($components & Gantry_Uri_Components::PATH) {
			$cmp = $uri->getPath();

			$result .= '/';

			if ($esc) {
				foreach (explode('/', $cmp) as $key => $value) {
					$result .= ($key === 0 ? '' : '/') . rawurlencode($value);
				}
			} else {
				$result .= $cmp;
			}
		}

		if ($components & Gantry_Uri_Components::QUERY) {
			$cmp = $uri->getQuery();

			if (!empty($cmp)){

				if ($esc) {
					parse_str($cmp, $cmp);

					$result .= '?' . str_replace('+', '%20', http_build_query($cmp, null, '&'));
				} else {
					$result .= "?{$cmp}";
				}
			}
		}

		//FRAGMENT:
		if ($components & Gantry_Uri_Components::FRAGMENT) {
			$cmp = $uri->getFragment();

			if (!empty($cmp)){

				if ($esc) {
					$result .= '#' . rawurlencode($cmp);
				} else {
					$result .= "#{$cmp}";
				}
			}
		}

//		RESULT:
		return $result;
	}
}