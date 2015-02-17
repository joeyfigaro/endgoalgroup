<?php
/**
 * @version   $Id: Components.php 58836 2013-01-15 01:40:58Z btowles $
 * @author    RocketTheme http://www.rockettheme.com
 * @copyright Copyright (C) 2007 - 2014 RocketTheme, LLC
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 */

/**
 * Specifies the parts of Gantry_Uri.
 */
final class Gantry_Uri_Components
{
	/**
	 * The Gantry_Uri::getScheme() data.
	 */
	const SCHEME = 1;

	/**
	 *
	 */
	const AUTHORITY_START = 2;

	/**
	 * The Gantry_Uri::getUserInfo() data.
	 */
	const USERINFO = 4;

	/**
	 * The Gantry_Uri::getHost() data.
	 */
	const HOST = 8;

	/**
	 * The Gantry_Uri::getPort() data. If there is no port or there is the default one,
	 * it is omitted.
	 */
	const PORT = 16;

	/**
	 * The Gantry_Uri::getPort() data.
	 */
	const STRONG_PORT = 32;


	/**
	 * The Gantry_Uri::getPath() data.
	 */
	const PATH = 64;

	/**
	 * The Gantry_Uri::getQuery() data.
	 */
	const QUERY = 128;

	/**
	 * The Gantry_Uri::getFragment() data.
	 */
	const FRAGMENT = 256;


	/**
	 * The Gantry_Uri::getHost() and Gantry_Uri::getPort() data.
	 */
	const HOST_AND_PORT = 24;

	/**
	 * The Gantry_Uri::getScheme(), Gantry_Uri::getHost() and Gantry_Uri::getPort() data.
	 */
	const SCHEME_AND_SERVER = 27;

	/**
	 * The Gantry_Uri::getUserInfo(), Gantry_Uri::getHost() and Gantry_Uri::getPort() data.
	 * If there is not port or there is the default one, it is omitted.
	 */
	const AUTHORITY = 28;

	/**
	 * The Gantry_Uri::getBaseUri() data.
	 */
	const BASEURI = 31;


	/**
	 * The Gantry_Uri::getUserInfo(), Gantry_Uri::getHost() and Gantry_Uri::getPort() data.
	 */
	const STRONG_AUTHORITY = 47;

	/**
	 * The Gantry_Uri::getPath() and Gantry_Uri::getQuery() data.
	 */
	const PATH_AND_QUERY = 192;

	/**
	 * The Gantry_Uri::getRelativeUri() data.
	 */
	const RELATIVE_URI = 448;

	/**
	 *
	 */
	const SCHEME_RELATIVE_URI = 478;

	/**
	 * The Gantry_Uri::getAbsoluteUri() data.
	 */
	const ABSOLUTE_URI = 479;
}