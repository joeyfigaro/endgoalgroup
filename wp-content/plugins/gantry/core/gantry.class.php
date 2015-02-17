<?php
/**
 * @version   $Id: gantry.class.php 60838 2014-05-12 19:11:30Z jakub $
 * @author    RocketTheme http://www.rockettheme.com
 * @copyright Copyright (C) 2007 - 2014 RocketTheme, LLC
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 */

defined('GANTRY_VERSION') or die();

gantry_import('core.gantrytemplate');
gantry_import('core.gantryini');
gantry_import('core.gantrypositions');
gantry_import('core.gantrystylelink');
gantry_import('core.rules.gantryoverridesengine');
gantry_import('core.gantryplatform');
gantry_import('core.utilities.gantryurl');


/**
 * This is the base class for the Gantry framework.   It is the primary mechanisim for template definition
 *
 * @package    gantry
 * @subpackage core
 */
class Gantry
{

	/**
	 *
	 */
	const DEFAULT_STYLE_PRIORITY = 10;
	/**
	 *
	 */
	const DEFAULT_GRID_SIZE = 12;

	/**
	 * The max wait time for a less compile in microseconds
	 */
	const LESS_MAX_COMPILE_WAIT_TIME = 2;

	const LESS_SITE_CACHE_GROUP = 'GantryLess';

	const LESS_ADMIN_CACHE_GROUP = 'GantryAdminLess';

	protected static $instance;

	/**
	 * @static
	 *
	 * @return mixed
	 */
	public static function getInstance()
	{
		if (!self::$instance) {
			self::$instance = new Gantry();
		}
		return self::$instance;
	}

	// Cacheable
	/**
	 *
	 */
	public $basePath;
	public $baseUrl;
	public $templateName;
	public $templateUrl;
	public $templatePath;
	public $gantryPath;
	public $gantryUrl;
	public $layoutSchemas = array();
	public $mainbodySchemas = array();
	public $pushPullSchemas = array();
	public $mainbodySchemasCombos = array();
	public $default_grid = self::DEFAULT_GRID_SIZE;
	public $presets = array();
	public $originalPresets = array();
	public $customPresets = array();
	public $dontsetinoverride = array();
	public $defaultMenuItem;
	public $currentMenuItem;
	public $currentMenuTree;
	public $template_prefix;
	public $custom_dir;
	public $custom_menuitemparams_dir;
	public $custom_presets_file;
	public $positions = array();
	public $altindex = false;
	public $platform;
	public $templateInfo;
	/**
	 * @var Gantry_Uri_Util
	 */
	public $uriutil;



	// Not cacheable
	/**
	 * @var GantryBrowser
	 */
	public $browser;
	public $language;
	public $currentUrl;
	public $pageTitle;


	// Private Vars
	/**#@+
	 * @access private
	 */


	// cacheable privates

	/**
	 * @var GantryTemplate
	 */
	public $_template;

	public $_aliases = array();
	public $_preset_names = array();
	public $_param_names = array();
	public $_base_params_checksum = null;
	public $_setbyurl = array();
	public $_setbycookie = array();
	public $_setbysession = array();
	public $_setinsession = array();
	public $_setincookie = array();
	public $_setinoverride = array();
	public $_setbyoverride = array();
	public $_gizmos = array();
	public $_widgets = array();
	public $_widget_configs = array();
	public $_ajaxmodels = array();
	public $_adminajaxmodels = array();
	public $_layouts = array();
	public $_bodyclasses = array();
	public $_custom_bodyclasses = array();
	public $_classesbytag = array();
	public $_ignoreQueryParams = array('reset-settings');
	public $_config_vars = array(
		'layoutschemas'         => 'layoutSchemas',
		'mainbodyschemas'       => 'mainbodySchemas',
		'mainbodyschemascombos' => 'mainbodySchemasCombos',
		'pushpullschemas'       => 'pushPullSchemas',
		'presets'               => 'presets',
		'browser_params'        => '_browser_params',
		'grid'                  => 'grid'
	);
	public $_working_params;
	public $_override_engine = null;
	public $_contentTypePaths = array();

	// non cachable privates
	public $_bodyId = null;
	public $_browser_params = array();
	public $_menu_item_params = array();
	public $_tmp_vars = array();


	// reseetable noncache
	public $_headerscripts = array();
	public $_footerscripts = array();
	public $_styles = array();
	public $_styles_available = array();
	public $_header_full_scripts = array();
	public $_footer_full_scripts = array();
	public $_domready_script = '';
    public $_footer_domready_script = '';
	public $_loadevent_script = '';
    public $_footer_loadevent_script = '';
	public $_inline_script = '';
    public $_footer_inline_script = '';
	public $_inline_style = '';


	public $_override_tree = array();
	/**#@-*/

	protected $__cacheables = array(
		'__cacheables',
		'basePath',
		'baseUrl',
		'templateName',
		'templateUrl',
		'templatePath',
		'gantryPath',
		'gantryUrl',
		'layoutSchemas',
		'mainbodySchemas',
		'pushPullSchemas',
		'mainbodySchemasCombos',
		'default_grid',
		'presets',
		'originalPresets',
		'customPresets',
		'dontsetinoverride',
		'defaultMenuItem',
		'currentMenuItem',
		'currentMenuTree',
		'template_prefix',
		'custom_dir',
		'custom_menuitemparams_dir',
		'custom_presets_file',
		'positions',
		'_template',
		'_aliases',
		'_preset_names',
		'_param_names',
		'_base_params_checksum',
		'_setbyurl',
		'_setbycookie',
		'_setbysession',
		'_setinsession',
		'_setincookie',
		'_setinoverride',
		'_setbyoverride',
		'_ajaxmodels',
		'_adminajaxmodels',
		'_layouts',
		'_bodyclasses',
		'_custom_bodyclasses',
		'_classesbytag',
		'_ignoreQueryParams',
		'_config_vars',
		'_working_params',
		'platform',
		'templateInfo',
		'_override_engine',
		'_gizmos',
		'_contentTypePaths',
		'uriutil'
	);

	/**
	 * @return array
	 */
	public function __sleep()
	{
		return $this->__cacheables;
	}

	/**
	 *
	 */
	public function __wakeup()
	{
		// set the GRID_SYSTEM define;
		if (!defined('GRID_SYSTEM')) {
			define ('GRID_SYSTEM', $this->get('grid_system', $this->default_grid));
		}
	}

	/**
	 * Constructor
	 * @return Gantry
	 */
	protected function __construct()
	{
		//global $mainframe;
		global $gantry_path;
		// load the base gantry path
		$this->gantryPath = $gantry_path;
		$this->gantryUrl  = untrailingslashit(plugin_dir_url($this->gantryPath . '/gantry.php'));

		// set the base class vars
		$this->basePath                  = Gantry_Uri_Util::cleanFilesystemPath(ABSPATH);
		$this->templateName              = $this->getCurrentTemplate();
		$this->templatePath              = get_template_directory();
		$this->custom_dir                = $this->templatePath . DS . 'custom';
		$this->custom_menuitemparams_dir = $this->custom_dir . DS . 'menuitemparams';
		$this->custom_presets_file       = $this->custom_dir . DS . 'presets.ini';

		// Set the call specific URL vars
		$urlinfo       = parse_url(get_option('siteurl'));
		$this->baseUrl = isset($urlinfo["path"]) ? rtrim($urlinfo["path"], '/') : '';
		$this->baseUrl .= "/";

		$urlinfo           = parse_url(get_bloginfo('template_url'));
		$this->templateUrl = $urlinfo["path"];
		$this->templateUrl = isset($urlinfo["path"]) ? rtrim($urlinfo["path"], '/') : '';


		$this->uriutil = new Gantry_Uri_Util(Gantry_Uri_Util::cleanFilesystemPath(ABSPATH), get_option('siteurl'));

		$this->loadConfig();

		// Load up the template details
		$this->_template = new GantryTemplate();

		$this->_template->init($this);
		$this->templateInfo          = $this->_template->getTemplateInfo();
		$this->_base_params_checksum = $this->_template->getParamsHash();

		gantry_import('core.gantryplatform');
		$this->platform = new GantryPlatform();


		// set base ignored query string params    dont pass these back
		$this->_ignoreQueryParams[] = 'reset-settings'; //TODO  Add Filter

		// Put a base copy of the saved params in the working params
		$this->_working_params = $this->_template->getParams();
		$this->_param_names    = array_keys($this->_template->getParams());
		$this->template_prefix = $this->_working_params['template_prefix']['value'];


		// set the GRID_SYSTEM define;
		if (!defined('GRID_SYSTEM')) {
			define ('GRID_SYSTEM', $this->get('grid_system', $this->default_grid));
		}

		// process the presets
		if (!empty($this->presets)) {
			// check for custom presets
			$this->customPresets();
			$this->_preset_names = array_keys($this->presets);
			//$wp_keys = array_keys($this->_templateDetails->params);
			//$this->_param_names = array_diff($wp_keys, $this->_preset_names);
		}

		$this->loadLayouts();
		$this->loadGizmos();

		$this->loadAjaxModels();
		$this->loadAdminAjaxModels();
		$this->loadStyles();

		// set up the positions object for all gird systems defined
		foreach (array_keys($this->mainbodySchemasCombos) as $grid) {
			$this->positions[$grid] = GantryPositions::getInstance($grid);
		}


		$this->_override_engine = $this->loadOverrideEngine();

//		// add GRID_SYSTEM class to body
		$this->addBodyClass("col" . GRID_SYSTEM);
	}

	/**
	 * Initializer.
	 * This should run when gantry is run from the front end in order and before the template file to
	 * populate all user session level data
	 * @return void
	 */
	public function init()
	{
		ob_start();
		if (defined('GANTRY_INIT')) {
			return;
		}
		// Run the admin init
		if ($this->isAdmin()) {
			$this->adminInit();
			return;
		}
		define('GANTRY_INIT', "GANTRY_INIT");

		$this->loadWidgets();
		$this->initWidgets();
		$this->loadGizmos();

		// set the GRID_SYSTEM define;
		if (!defined('GRID_SYSTEM')) {
			define ('GRID_SYSTEM', $this->get('grid_system', $this->default_grid));
		}

		$this->language = get_bloginfo('language');

		// Set the call specific URL vars
		// Set the call specific URL vars
		$urlinfo       = parse_url(get_option('siteurl'));
		$this->baseUrl = isset($urlinfo["path"]) ? rtrim($urlinfo["path"], '/') : '';
		$this->baseUrl .= "/";

		$urlinfo           = parse_url(get_bloginfo('template_url'));
		$this->templateUrl = $urlinfo["path"];
		$this->templateUrl = isset($urlinfo["path"]) ? rtrim($urlinfo["path"], '/') : '';


		$this->_initContentTypePaths();

		// Set the Platform info
		gantry_import('core.gantryplatform');
		$this->platform = new GantryPlatform();

		// Set the Brwoser info
		gantry_import('core.gantrybrowser');
		$this->browser = new GantryBrowser();
	}

	/**
	 *
	 */
	public function adminInit()
	{
		if (defined('GANTRY_INIT')) {
			return;
		}
		define('GANTRY_INIT', "GANTRY_INIT");
		gantry_import('core.gantrybrowser');
		$this->browser = new GantryBrowser();
		// Set the Platform info
		gantry_import('core.gantryplatform');
		$this->platform = new GantryPlatform();
		$this->loadWidgets();
		$this->initWidgets();
		$this->getWidgetConfigs();

		// Init all gizmos
		foreach ($this->_gizmos as $gizmo) {
			/** @var $gizmo_instance GantryGizmo */
			$gizmo_instance = $this->getGizmo($gizmo);
			if ($gizmo_instance !== false && $gizmo_instance->isEnabled() && method_exists($gizmo_instance, 'init')) {
				$gizmo_instance->admin_init();
			}
		}
	}

	/**
	 *
	 */
	public function basicLoad()
	{

		if ($this->_template->getTemplateInfo()->getGridcss()) {
			//add correct grid system css
			$this->addStyle('grid-' . GRID_SYSTEM . '.css', 5);
		}

		if ($this->_template->getTemplateInfo()->getLegacycss()) {
			//add default gantry stylesheet
			$this->addStyle('gantry.css', 5);
			$this->addStyle('wordpress.css', 5);
		}


		// Init all gizmos
		foreach ($this->_gizmos as $gizmo) {
			/** @var $gizmo_instance GantryGizmo */
			$gizmo_instance = $this->getGizmo($gizmo);
			if ($gizmo_instance !== false && $gizmo_instance->isEnabled() && method_exists($gizmo_instance, 'init')) {
				$gizmo_instance->init();
			}
		}
	}

	/**
	 * Function to init params, gizmos, and widgets once the http query string has been parsed
	 * This should only be run once.
	 * @return void
	 */
	public function postParseLoad()
	{
		global $wp_query;
		/** @var $engine_output GantryOverrides */
		$engine_output        = $this->_override_engine->run($wp_query);
		$this->_override_tree = $engine_output->getOverrideList();
		$this->_postParseLoad();
		$this->loadWidgetPositions();
		$this->currentUrl = $_SERVER['REQUEST_URI'];
	}

	/**
	 *
	 */
	protected function _postParseLoad()
	{
		// Populate all the params for the session
		$this->populateParams();
		$this->loadBrowserConfig();
		// Init all gizmos
		foreach ($this->_gizmos as $gizmo) {
			$gizmo_instance = $this->getGizmo($gizmo);
			/** @var $gizmo_instance GantryGizmo */
			if ($gizmo_instance !== false && $gizmo_instance->isEnabled() && method_exists($gizmo_instance, 'query_parsed_init')) {
				$gizmo_instance->query_parsed_init();
			}
		}
		// Init all widgets
		foreach ($this->_widgets as $widget) {
			if (method_exists($widget, 'gantry_init')) {
				call_user_func(array($widget, 'gantry_init'));
			}
		}
	}

	/**
	 *
	 */
	protected function reset()
	{
		if (defined('GANTRY_FINALIZED')) return;
		$this->_headerscripts           = array();
		$this->_header_full_scripts     = array();
		$this->_footerscripts           = array();
		$this->_footer_full_scripts     = array();
		$this->_domready_script         = '';
        $this->_footer_domready_script  = '';
		$this->_loadevent_script        = '';
        $this->_footer_loadevent_script = '';
		$this->_inline_script           = '';
        $this->_footer_inline_script    = '';
		$this->_inline_style            = '';
		$this->_styles                  = array();

		$this->basicLoad();
		$this->_postParseLoad();
	}

	/**
	 *
	 */
	public function finalize()
	{
		if (!defined('GANTRY_FINALIZED')) {
			$this->addStyle($this->templateName . '-custom.css', 1000);
			gantry_import('core.params.overrides.gantrycookieparamoverride');
			gantry_import('core.params.overrides.gantrysessionparamoverride');

			// finalize all widgets
			foreach ($this->_widgets as $widget) {
				if (method_exists($widget, 'gantry_finalize')) {
					call_user_func(array($widget, 'gantry_finalize'));
				}
			}

			// finalize all gizmos
			foreach ($this->_gizmos as $gizmo) {
				/** @var $gizmo_instance GantryGizmo */
				$gizmo_instance = $this->getGizmo($gizmo);
				if ($gizmo_instance !== false && $gizmo_instance->isEnabled() && method_exists($gizmo_instance, 'finalize')) {
					$gizmo_instance->finalize();
				}
			}

			// Run the cleanup or store on cookies and sessions
			if (isset($_REQUEST['reset-settings'])) {
				GantrySessionParamOverride::clean();
				GantryCookieParamOverride::clean();
			} else {
				GantrySessionParamOverride::store();
				GantryCookieParamOverride::store();
			}

			// Apply compression if enabled
			if ($this->get("gzipper-enabled", false)) {
				gantry_import('core.gantrygzipper');
				GantryGZipper::processCSSFiles();
				GantryGZipper::processJsFiles();
			}

			define('GANTRY_FINALIZED', true);
		}

		if ($this->altindex !== false) {
			ob_get_contents();
			ob_end_clean();
			ob_start();
			echo $this->altindex;
		}

		$output = ob_get_clean();

		// process page output to add header in
		$this->_displayHead($output);
		$this->_displayFooter($output);
		$this->_displayBodyTag($output);
		echo $output;
	}

	/**
	 *
	 */
	public function finalizeAdmin()
	{
		if (!defined('GANTRY_FINALIZED')) {
			// Apply compression if enabled
			if ($this->get("gzipper-enabled", false)) {
				gantry_import('core.gantrygzipper');
				GantryGZipper::processCSSFiles();
				GantryGZipper::processJsFiles();
			}
			define('GANTRY_FINALIZED', true);
		}

		$output = ob_get_clean();

		// process page output to add header in
		$this->_displayHead($output);
		$this->_displayFooter($output);
		$this->_displayBodyTag($output);
		echo $output;
	}

	/**
	 * @return bool
	 */
	public function isAdmin()
	{
		return is_admin();
	}

	/**
	 * @param bool   $param
	 * @param string $default
	 *
	 * @return string
	 */
	public function get($param = false, $default = "")
	{
		if (array_key_exists($param, $this->_working_params)) $value = $this->_working_params[$param]['value']; else $value = $default;
		return $value;
	}

	/**
	 * @param bool $param
	 *
	 * @return string
	 */
	public function getDefault($param = false)
	{
		$value = "";
		if (array_key_exists($param, $this->_working_params)) $value = $this->_working_params[$param]['default'];
		return $value;
	}

	/**
	 * @param      $param
	 * @param bool $value
	 *
	 * @return bool
	 */
	function set($param, $value = false)
	{
		$return = false;
		if (array_key_exists($param, $this->_working_params)) {
			$this->_working_params[$param]['value'] = $value;
			$return                                 = true;
		}
		return $return;
	}

	/**
	 * @param      $model_name
	 * @param bool $admin
	 *
	 * @return bool
	 */
	public function getAjaxModel($model_name, $admin = false)
	{
		$model_path = false;
		if ($admin) {
			if (array_key_exists($model_name, $this->_adminajaxmodels)) {
				$model_path = $this->_adminajaxmodels[$model_name];
			}
		} else {
			if (array_key_exists($model_name, $this->_ajaxmodels)) {
				$model_path = $this->_ajaxmodels[$model_name];
			}
		}
		return $model_path;
	}

	/**
	 * @param null $position
	 * @param null $pattern
	 *
	 * @return array
	 */
	public function getPositions($position = null, $pattern = null)
	{
		if ($position != null) {
			$positions = $this->_template->parsePosition($position, $pattern);
			return $positions;
		}
		return $this->_template->getPositions();
	}

	/**
	 * @return array
	 */
	public function getUniquePositions()
	{
		return $this->_template->getUniquePositions();
	}

	/**
	 * @param $position_name
	 *
	 * @return mixed
	 */
	public function getPositionInfo($position_name)
	{
		return $this->_template->getPositionInfo($position_name);
	}

	/**
	 * @return string
	 */
	public function getAjaxUrl()
	{
		return admin_url('admin-ajax.php');
	}

	/**
	 * @param null $prefix
	 * @param bool $remove_prefix
	 *
	 * @return array
	 */
	public function getParams($prefix = null, $remove_prefix = false)
	{
		if (null == $prefix) {
			return $this->_working_params;
		}
		$params = array();
		foreach ($this->_working_params as $param_name => $param_value) {
			$matches = array();
			if (preg_match("/^" . $prefix . "-(.*)$/", $param_name, $matches)) {
				if ($remove_prefix) {
					$param_name = $matches[1];
				}
				$params[$param_name] = $param_value;
			}
		}
		return $params;
	}

	/**
	 * Gets the current URL and query string and can ready it for more query string vars
	 *
	 * @param array $ignore
	 *
	 * @return mixed|string
	 */
	public function getCurrentUrl($ignore = array())
	{
		gantry_import('core.utilities.gantryurl');

		$url = GantryUrl::explode($this->currentUrl);

		if (!empty($ignore) && array_key_exists('query_params', $url)) {
			foreach ($ignore as $k) {
				if (array_key_exists($k, $url['query_params'])) unset($url['query_params'][$k]);
			}
		}
		return GantryUrl::implode($url);
	}

	/**
	 * @param       $url
	 * @param array $params
	 *
	 * @return String
	 */
	public function addQueryStringParams($url, $params = array())
	{
		gantry_import('core.utilities.gantryurl');
		return GantryUrl::updateParams($url, $params);
	}

	// wrapper for count modules
	/**
	 * @param      $positionStub
	 *
	 * @return int
	 */
	public function countModules($positionStub)
	{
		if (defined('GANTRY_FINALIZED')) return 0;

		$count = 0;
		gantry_import('core.renderers.gantrywidgetsrenderer');
		add_filter('sidebars_widgets', array('GantryWidgetsRenderer', 'filterWidgetCount'));
		$sidebars_widgets = wp_get_sidebars_widgets();

		if (array_key_exists($positionStub, $sidebars_widgets) && !empty($sidebars_widgets[$positionStub])) {
			$section_counted = false;
			foreach ($sidebars_widgets[$positionStub] as $widget) {
				if (!preg_match("/^gantrydivider/", $widget) && !$section_counted) {
					$count++;
					$section_counted = true;
				} else if (preg_match("/^gantrydivider/", $widget)) {
					$section_counted = false;
				}
			}
		}
		return $count;
	}

	/**
	 * @param  $position
	 *
	 * @return int
	 */
	public function countSubPositionModules($position)
	{
		if (defined('GANTRY_FINALIZED')) return 0;

		$count = 0;
		gantry_import('core.renderers.gantrywidgetsrenderer');
		$sidebars_widgets = wp_get_sidebars_widgets();

		if (array_key_exists($position, $this->_aliases)) {
			return $this->countSubPositionModules($this->_aliases[$position]);
		}

		if (!$this->isAdmin()) {
			if ($this->countModules($position)) {
				$count += count($sidebars_widgets[$position]);
			}
		}
		return $count;
	}

	/**
	 * @param $position
	 *
	 * @return int
	 */
	public function countWidgetsBeforeDivider($position)
	{
		if (defined('GANTRY_FINALIZED')) return 0;

		gantry_import('core.renderers.gantrywidgetsrenderer');
		add_filter('sidebars_widgets', array('GantryWidgetsRenderer', 'filterWidgetCount'));

		$sidebars_widgets = wp_get_sidebars_widgets();
		$filtered_widgets = GantryWidgetsRenderer::filterWidgetCount($sidebars_widgets);

		$widgets        = $filtered_widgets[$position];
		$position_count = 0;

		if (count($widgets) > 0) {
			foreach ($widgets as $widget) {
				if (!preg_match("/^gantrydivider/", $widget)) {
					$position_count++;
					if ($position_count > 1) break;
				} else {
					$position_count = 0;
				}
			}
		}

		return $position_count;

	}

	/**
	 * wrapper for mainbody display
	 *
	 * @param string $bodyLayout
	 * @param string $sidebarLayout
	 * @param string $sidebarChrome
	 * @param string $contentTopLayout
	 * @param string $contentTopChrome
	 * @param string $contentBottomLayout
	 * @param string $contentBottomChrome
	 * @param null   $gridsize
	 * @param string $component_content
	 *
	 * @return string
	 */
	public function displayMainbody($bodyLayout = 'mainbody', $sidebarLayout = 'sidebar', $sidebarChrome = 'standard', $contentTopLayout = 'standard', $contentTopChrome = 'standard', $contentBottomLayout = 'standard', $contentBottomChrome = 'standard', $gridsize = null, $component_content = '')
	{
		if (defined('GANTRY_FINALIZED')) return '';
		gantry_import('core.renderers.gantrymainbodyrenderer');
		return GantryMainBodyRenderer::display($bodyLayout, $sidebarLayout, $sidebarChrome, $contentTopLayout, $contentTopChrome, $contentBottomLayout, $contentBottomChrome, $gridsize, $component_content);
	}


	/**
	 * wrapper for mainbody display
	 *
	 * @param string $bodyLayout
	 * @param string $sidebarLayout
	 * @param string $sidebarChrome
	 * @param string $contentTopLayout
	 * @param string $contentTopChrome
	 * @param string $contentBottomLayout
	 * @param string $contentBottomChrome
	 * @param null   $gridsize
	 * @param string $component_content
	 *
	 * @return string
	 */
	public function displayOrderedMainbody($bodyLayout = 'mainbody', $sidebarLayout = 'sidebar', $sidebarChrome = 'standard', $contentTopLayout = 'standard', $contentTopChrome = 'standard', $contentBottomLayout = 'standard', $contentBottomChrome = 'standard', $gridsize = null, $component_content = '')
	{
		if (defined('GANTRY_FINALIZED')) return '';
		gantry_import('core.renderers.gantryorderedmainbodyrenderer');
		return GantryOrderedMainBodyRenderer::display($bodyLayout, $sidebarLayout, $sidebarChrome, $contentTopLayout, $contentTopChrome, $contentBottomLayout, $contentBottomChrome, $gridsize, $component_content);
	}


	/**
	 * wrapper for display modules
	 *
	 * @param        $positionStub
	 * @param string $layout
	 * @param string $chrome
	 * @param string $gridsize
	 * @param null   $pattern
	 *
	 * @return string
	 */
	public function displayModules($positionStub, $layout = 'standard', $chrome = 'standard', $gridsize = GRID_SYSTEM, $pattern = null)
	{
		if (defined('GANTRY_FINALIZED')) return '';
		gantry_import('core.renderers.gantrywidgetsrenderer');
		return GantryWidgetsRenderer::display($positionStub, $layout, $chrome, $gridsize, $pattern);
	}

	//
	/**
	 * @param bool   $seperate_comments
	 * @param string $layout
	 * @param string $commentLayout
	 *
	 * @return string
	 */
	public function displayComments($seperate_comments = false, $layout = 'basic', $commentLayout = 'basic')
	{
		if (defined('GANTRY_FINALIZED')) return '';
		// check to see if there is a comments.php in the root

		if (file_exists($this->templatePath . '/comments.php')) {
			comments_template('', $seperate_comments);
			return '';
		}
		comments_template($this->gantryPath . '/html/comments.php', $seperate_comments);

		// return empty of not using wordpress comments
		if (!$this->get('wordpress-comments', true)) return '';

		gantry_import('core.renderers.gantrycommentsrenderer');
		return GantryCommentsRenderer::display($layout, $commentLayout);
	}

	/**
	 * @return array
	 */
	public function getWidgetStyles()
	{
		return $this->_template->getWidgetStyles();
	}

	/**
	 * @param $namespace
	 * @param $varname
	 * @param $variable
	 */
	function addTemp($namespace, $varname, &$variable)
	{
		if (defined('GANTRY_FINALIZED')) return;
		$this->_tmp_vars[$namespace][$varname] = $variable;
		return;
	}

	/**
	 * @param      $namespace
	 * @param      $varname
	 * @param null $default
	 *
	 * @return null
	 */
	public function &retrieveTemp($namespace, $varname, $default = null)
	{
		if (defined('GANTRY_FINALIZED')) return null;
		if (!array_key_exists($namespace, $this->_tmp_vars) || !array_key_exists($varname, $this->_tmp_vars[$namespace])) {
			return $default;
		}
		return $this->_tmp_vars[$namespace][$varname];
	}

	/**
	 * @param null $id
	 */
	public function setBodyId($id = null)
	{
		$this->_bodyId = $id;
	}

	/**
	 * @param $class
	 */
	public function addBodyClass($class)
	{
		if (defined('GANTRY_FINALIZED')) return;
		$this->_bodyclasses[] = $class;
	}

	/**
	 * @param $id
	 * @param $class
	 */
	public function addClassByTag($id, $class)
	{
		if (defined('GANTRY_FINALIZED')) return;
		$this->_classesbytag[$id][] = $class;
	}

	/**
	 *
	 */
	public function displayHead()
	{
		if (defined('GANTRY_FINALIZED')) return;
		foreach ($this->_gizmos as $gizmo) {
			/** @var $gizmo_instance GantryGizmo */
			$gizmo_instance = $this->getGizmo($gizmo);
			if (method_exists($gizmo_instance, 'isEnabled')) {
				if ($gizmo_instance->isEnabled() && method_exists($gizmo_instance, 'render')) {
					$gizmo_instance->render();
				}
			}
		}
		do_action('gantry_enqueue_scripts');
		do_action('get_header', null);

		echo "<gantry:header/>";
	}

	/**
	 *
	 */
	public function displayFooter()
	{
		if (defined('GANTRY_FINALIZED')) return;
		do_action('get_footer', null);
		echo "<gantry:footer/>";
	}

	/**
	 * @param $output
	 */
	protected function _displayHead(&$output)
	{
		// get line endings
		$strHtml = '';

		// Enqueue Styles
		$deps = array();
		ksort($this->_styles);
		foreach ($this->_styles as $style_priority) {
			/** @var $strSrc GantryStyleLink */
			foreach ($style_priority as $strSrc) {
				if ($strSrc->getType() == 'local') {
					$path = parse_url($strSrc->getUrl(), PHP_URL_PATH);
					if ($this->baseUrl != "/") {
						$path = '/' . preg_replace('#^' . quotemeta($this->baseUrl) . '#', "", $path);
					}
					$filename = strtolower(basename($path, '.css')) . rand(0, 1000);
					wp_enqueue_style($filename, $path, array(), '4.1.2');
					$deps[] = $path;
				}
			}
		}

		// Add scripts to the header
		$deps = array();
		foreach ($this->_headerscripts as $strSrc) {
			$path = parse_url($strSrc, PHP_URL_PATH);
			if ($this->baseUrl != "/") {
				$path = '/' . preg_replace('#^' . quotemeta($this->baseUrl) . '#', "", $path);
			}
			wp_enqueue_script($path, $path, $deps, '4.1.2');
			$deps[] = $path;
		}
		foreach ($this->_header_full_scripts as $strSrc) {
			wp_enqueue_script($strSrc, $strSrc, $deps, '4.1.2');
			$deps[] = $strSrc;
		}

		if (!$this->isAdmin()) {
			$strHtml .= $this->_renderCharset();
			$strHtml .= $this->_renderTitle();
			add_action('wp_head', array($this, '_renderRemoteStyles'), 8);
			add_action('wp_head', array($this, '_renderRemoteScripts'), 9);
			ob_start();
			wp_head();
			$strHtml .= ob_get_clean();
			$strHtml .= $this->_renderStylesHead();
			$strHtml .= $this->_renderScriptsHead();
		} else {
			ob_start();
			$this->_renderRemoteStyles();
			print_admin_styles();
			$this->_renderRemoteScripts();
			print_head_scripts();
			$strHtml .= ob_get_clean();
			$strHtml .= $this->_renderStylesHead();
			$strHtml .= $this->_renderScriptsHead();
		}

		$output = str_replace('<gantry:header/>', $strHtml, $output);
	}

	/**
	 * @param $output
	 */
	protected function _displayFooter(&$output)
	{
		ob_start();
		$deps = array();
		foreach ($this->_footerscripts as $strSrc) {
			$path = parse_url($strSrc, PHP_URL_PATH);
			if ($this->baseUrl != "/") {
				$path = '/' . preg_replace('#^' . quotemeta($this->baseUrl) . '#', "", $path);
			}
			wp_enqueue_script($path, $path, $deps, '4.1.2', true);
			$deps[] = $path;
		}
		foreach ($this->_footer_full_scripts as $strSrc) {
			wp_enqueue_script($strSrc, $strSrc, $deps, '4.1.2', true);
			$deps[] = $strSrc;
		}

		if (!$this->isAdmin()) {
            add_action('wp_footer', array($this, '_renderFooterRemoteScripts'), 9);
            wp_footer();
        }

		$strHtml = ob_get_clean();
        $strHtml .= $this->_renderScriptsFooter();
		$output  = str_replace('<gantry:footer/>', $strHtml, $output);
	}

	/**
	 *
	 */
	public function _renderRemoteStyles()
	{
		ob_start();
		foreach ($this->_styles as $style_priority) {
			/** @var $strSrc GantryStyleLink */
			foreach ($style_priority as $strSrc) {
				if ($strSrc->getType() == 'url') {
					echo sprintf('<link rel="stylesheet" href="%s" type="text/css"/>', $strSrc->getUrl());
				}
			}
		}
		echo ob_get_clean();
	}

    /**
     *
     */
    public function _renderRemoteScripts()
    {
        ob_start();
        /** @var $strSrc GantryStyleLink */
        foreach ($this->_headerscripts as $strSrc) {
            if (is_object($strSrc) && $strSrc->getType() == 'url') {
                echo sprintf('<script  type="text/javascript" src="%s"></script>', $strSrc->getUrl());
            }
        }
        echo ob_get_clean();
    }

	/**
	 *
	 */
	public function _renderFooterRemoteScripts()
	{
		ob_start();
		/** @var $strSrc GantryStyleLink */
		foreach ($this->_footerscripts as $strSrc) {
			if (is_object($strSrc) && $strSrc->getType() == 'url') {
				echo sprintf('<script  type="text/javascript" src="%s"></script>', $strSrc->getUrl());
			}
		}
		echo ob_get_clean();
	}

	/**
	 * @return string
	 */
	protected function _renderCharset()
	{
		$charset = '<meta http-equiv="Content-Type" content="' . get_bloginfo('html_type') . '; charset=' . get_bloginfo('charset') . '" />' . "\n";
		return $charset;
	}

	/**
	 * @return string
	 */
	protected function _renderTitle()
	{
		if ($this->isAdmin()) return "";

		if (!isset($this->pageTitle)) {
			$this->pageTitle = wp_title('&raquo;', false);
		}

		$this->pageTitle = str_replace('$', chr(36), $this->pageTitle);
		$title           = '<title>' . $this->pageTitle . '</title>' . chr(13);
		return $title;
	}

	/**
	 * @return string
	 */
	protected function _renderScriptsHead()
	{
		// get line endings
		$lnEnd   = "\12";
		$tab     = "\11";
		$strHtml = '';


		// Generate inline script
		if (isset($this->_inline_script) && strlen(trim($this->_inline_script)) > 0) {
			$strHtml .= $tab . '<script type="text/javascript">' . $lnEnd;
			// This is for full XHTML support.
			$strHtml .= $this->_inline_script . $lnEnd;
			$strHtml .= $tab . '</script>' . $lnEnd;
		}

		// Generate domready script
		if (isset($this->_domready_script) && !empty($this->_domready_script) && count($this->_domready_script)) {
			$strHtml .= $tab . '<script type="text/javascript">//<![CDATA[' . $lnEnd;
			// This is for full XHTML support.
			$strHtml .= 'window.addEvent(\'domready\', function() {' . $this->_domready_script . $lnEnd . '});';
			$strHtml .= $tab . '//]]></script>' . $lnEnd;
		}

		// Generate load script
		if (isset($this->_loadevent_script) && !empty($this->_loadevent_script) && count($this->_loadevent_script)) {
			$strHtml .= $tab . '<script type="text/javascript">//<![CDATA[' . $lnEnd;
			// This is for full XHTML support.
			$strHtml .= 'window.addEvent(\'load\', function() {' . $this->_loadevent_script . $lnEnd . '});';
			$strHtml .= $tab . '//]]></script>' . $lnEnd;
		}

		return $strHtml;
	}

    /**
     * @return string
     */
    protected function _renderScriptsFooter()
    {
        // get line endings
        $lnEnd   = "\12";
        $tab     = "\11";
        $strHtml = '';


        // Generate inline script
        if (isset($this->_footer_inline_script) && strlen(trim($this->_footer_inline_script)) > 0) {
            $strHtml .= $tab . '<script type="text/javascript">' . $lnEnd;
            // This is for full XHTML support.
            $strHtml .= $this->_footer_inline_script . $lnEnd;
            $strHtml .= $tab . '</script>' . $lnEnd;
        }

        // Generate domready script
        if (isset($this->_footer_domready_script) && !empty($this->_footer_domready_script) && count($this->_footer_domready_script)) {
            $strHtml .= $tab . '<script type="text/javascript">//<![CDATA[' . $lnEnd;
            // This is for full XHTML support.
            $strHtml .= 'window.addEvent(\'domready\', function() {' . $this->_footer_domready_script . $lnEnd . '});';
            $strHtml .= $tab . '//]]></script>' . $lnEnd;
        }

        // Generate load script
        if (isset($this->_footer_loadevent_script) && !empty($this->_footer_loadevent_script) && count($this->_footer_loadevent_script)) {
            $strHtml .= $tab . '<script type="text/javascript">//<![CDATA[' . $lnEnd;
            // This is for full XHTML support.
            $strHtml .= 'window.addEvent(\'load\', function() {' . $this->_footer_loadevent_script . $lnEnd . '});';
            $strHtml .= $tab . '//]]></script>' . $lnEnd;
        }

        return $strHtml;
    }

	/**
	 * @return string
	 */
	protected function _renderStylesHead()
	{
		// get line endings
		$lnEnd   = "\12";
		$tab     = "\11";
		$strHtml = '';

		// Generate inline css
		if (isset($this->_inline_style) && strlen(trim($this->_inline_style)) > 0) {
			$strHtml .= $tab . '<style type="text/css">' . $lnEnd;
			// This is for full XHTML support.
			$strHtml .= $tab . $tab . '<!--' . $lnEnd;
			$strHtml .= $this->_inline_style . $lnEnd;
			$strHtml .= $tab . $tab . '-->' . $lnEnd;
			$strHtml .= $tab . '</style>' . $lnEnd;
		}
		return $strHtml;
	}

	/**
	 * @return string
	 */
	public function displayBodyTag( $class = '' )
	{
		$classes = array();

		if ( ! empty( $class ) ) {
			if ( !is_array( $class ) )
				$class = preg_split( '#\s+#', $class );
			$classes = array_merge( $classes, $class );
		} else {
			// Ensure that we always coerce class to being an array.
			$class = array();
		}

		$this->_custom_bodyclasses = $classes;

		if (defined('GANTRY_FINALIZED')) return '';
		return "<gantry:bodytag/>";
	}

	/**
	 * @param $output
	 */
	function _displayBodyTag(&$output)
	{
		$body_classes = get_body_class($this->_custom_bodyclasses);
		foreach ($this->_bodyclasses as $param) {
			$param_value = $this->get($param);
			if ($param_value != "") {
				$body_classes[] = strtolower(str_replace(" ", "-", $param . "-" . $param_value));
			} else {
				$body_classes[] = strtolower(str_replace(" ", "-", $param));
			}
		}
		$body_tag = $this->renderLayout('doc_body', array(
			'classes' => implode(" ", $body_classes),
			'id'      => $this->_bodyId
		));
		$output   = preg_replace("#<gantry:bodytag/>#", $body_tag, $output);
	}

	/**
	 * @param $tag
	 *
	 * @return string
	 */
	public function displayClassesByTag($tag)
	{
		if (defined('GANTRY_FINALIZED')) return '';
		$tag_classes = array();

		if (array_key_exists($tag, $this->_classesbytag)) {
			foreach ($this->_classesbytag[$tag] as $param) {
				$param_value = $this->get($param);
				if ($param_value != "") {
					$tag_classes[] = $param . "-" . $param_value;
				} else {
					$tag_classes[] = $param;
				}
			}
		}
		return $this->renderLayout('doc_tag', array('classes' => implode(" ", $tag_classes)));
	}

	// debug function for body
	/**
	 * @param string $bodyLayout
	 * @param string $sidebarLayout
	 * @param string $sidebarChrome
	 *
	 * @return string
	 */
	function debugMainbody($bodyLayout = 'debugmainbody', $sidebarLayout = 'sidebar', $sidebarChrome = 'standard')
	{
		gantry_import('core.renderers.gantrydebugmainbodyrenderer');
		return GantryDebugMainBodyRenderer::display($bodyLayout, $sidebarLayout, $sidebarChrome);
	}

	/**
	 * Returns the relative path from one location to another
	 *
	 * @param $from
	 * @param $to
	 *
	 * @return string
	 */
	function getRelativePath($from, $to)
	{
		// some compatibility fixes for Windows paths
		$from = is_dir($from) ? rtrim($from, '\/') . '/' : $from;
		$to   = is_dir($to)   ? rtrim($to, '\/') . '/'   : $to;
		$from = str_replace('\\', '/', $from);
		$to   = str_replace('\\', '/', $to);

		$from     = explode('/', $from);
		$to       = explode('/', $to);
		$relPath  = $to;

		foreach($from as $depth => $dir) {
			// find first non-matching dir
			if($dir === $to[$depth]) {
				// ignore this directory
				array_shift($relPath);
			} else {
				// get number of remaining dirs to $from
				$remaining = count($from) - $depth;
				if($remaining > 1) {
					// add traversals up to first matching dir
					$padLength = (count($relPath) + $remaining - 1) * -1;
					$relPath = array_pad($relPath, $padLength, '..');
					break;
				} else {
					$relPath[0] = './' . $relPath[0];
				}
			}
		}
		return implode('/', $relPath);
	}

	/**
	 * @param string $lessfile
	 * @param bool   $cssfile
	 * @param int    $priority
	 *
	 * @param array  $options
	 *
	 * @throws RuntimeException
	 * @throws Exception
	 */
	public function addLess($lessfile, $cssfile = null, $priority = self::DEFAULT_STYLE_PRIORITY, array $options = array())
	{

		$less_search_paths = array();
		// setup the less filename
		if (dirname($lessfile) == '.') {
			//set up the check for template with plartform based dirs
			$less_search_paths = $this->platform->getAvailablePlatformVersions($this->templatePath . '/less');
			foreach ($less_search_paths as $less_path) {
				if (is_dir($less_path)) {
					$search_file = preg_replace('#[/\\\\]+#', '/', $less_path . '/' . $lessfile);
					if (is_file($search_file)) {
						$lessfile = $search_file;
						break;
					}
				}
			}
		}
		$lessfile_uri   = new Gantry_Uri($lessfile);
		$less_file_path = $this->uriutil->getFilesystemPath($lessfile_uri);
		$less_file_url  = $this->uriutil->getUrlForPath($lessfile_uri);


		// abort if the less file isnt there
		if (!is_file($less_file_path)) {
			return;
		}

		// get an md5 sum of any passed in options
		$tmp_options = $options;
		array_walk($tmp_options, create_function('&$v,$k', '$v = " * @".$k." = " .$v;'));
		$options_string = implode($tmp_options, "\n");
		$options_md5    = md5($options_string . (string)$this->get('less-compression', true));


		$css_append = '';
		if (!empty($options)) {
			$css_append = '-' . $options_md5;
		}


		if (is_multisite()) {
			$uploads                  = wp_upload_dir();
			$default_compiled_css_dir = rtrim($uploads['basedir'], '/\\') . '/css-compiled';
		} else {
			$default_compiled_css_dir = $this->templatePath . '/css-compiled';
		}

		if (!file_exists($default_compiled_css_dir)) {
			@mkdir($default_compiled_css_dir, 0775, true);
			if (!file_exists($default_compiled_css_dir)) {
				throw new Exception(sprintf('Unable to create default directory (%s) for compiled less files.  Please check your filesystem permissions.', $default_compiled_css_dir));
			}
		}

		// setup the output css file name
		if (is_null($cssfile)) {
			$css_file_path   = $default_compiled_css_dir . '/' . pathinfo($lessfile, PATHINFO_FILENAME) . $css_append . '.css';
			$css_passed_path = pathinfo($css_file_path, PATHINFO_BASENAME);
		} else {
			if (dirname($cssfile) == '.') {
				$css_file_path   = $default_compiled_css_dir . '/' . pathinfo($cssfile, PATHINFO_FILENAME) . $css_append . '.css';
				$css_passed_path = pathinfo($css_file_path, PATHINFO_BASENAME);
			} else {
				$css_file_path   = dirname($this->uriutil->getFilesystemPath($cssfile)) . '/' . pathinfo($cssfile, PATHINFO_FILENAME) . $css_append . '.css';
				$css_passed_path = $css_file_path;
			}
		}
		$cssfile_md5 = md5($css_file_path);

		// set base compile modes
		$force_compile = false;

		if (!$this->isAdmin()) {
			$cachegroup = self::LESS_SITE_CACHE_GROUP;
		} else {
			$cachegroup = self::LESS_ADMIN_CACHE_GROUP;
		}


		$runcompile = false;

		gantry_import('core.utilities.gantrycache');
		$cache_handler = GantryCache::getCache($cachegroup, 0, true);

		$cached_less_compile = $cache_handler->get($cssfile_md5, false);
		if ($cached_less_compile === false || !file_exists($css_file_path)) {
			$cached_less_compile = $less_file_path;
			$runcompile          = true;
		} elseif (is_array($cached_less_compile) && isset($cached_less_compile['root'])) {
			if (isset($cached_less_compile['files']) and is_array($cached_less_compile['files'])) {
				foreach ($cached_less_compile['files'] as $fname => $ftime) {
					if (!file_exists($fname) or filemtime($fname) > $ftime) {
						// One of the files we knew about previously has changed
						// so we should look at our incoming root again.
						$runcompile = true;
						break;
					}
				}
			}
		}

		if ($runcompile) {
			gantry_import('core.utilities.gantrylesscompiler');
			$quick_expire_cache = GantryCache::getCache($cachegroup, $this->get('less-compilewait', self::LESS_MAX_COMPILE_WAIT_TIME));

			$timewaiting = 0;
			while ($quick_expire_cache->get($cssfile_md5 . '-compiling') !== false) {
				$wait = 100000; // 1/10 of a second;
				usleep($wait);
				$timewaiting += $wait;
				if ($timewaiting >= $this->get('less-compilewait', self::LESS_MAX_COMPILE_WAIT_TIME) * 1000000) {
					break;
				}
			}

			$less = new GantryLessCompiler();
			$less->setImportDir($less_search_paths);
			$less->addImportDir($this->gantryPath . '/assets');

			if (!empty($options)) {
				$less->setVariables(apply_filters('gantry_less_compile_options', $options, $default_compiled_css_dir));
			}

			if ($this->get('less-compression', true)) {
				$less->setFormatter("compressed");
			}

			$quick_expire_cache->set($cssfile_md5 . '-compiling', true);
			try {
				$new_cache = $less->cachedCompile($cached_less_compile, $force_compile);
			} catch (Exception $ex) {
				$quick_expire_cache->clear($cssfile_md5 . '-compiling');
				throw new RuntimeException('Less Parse Error: ' . $ex->getMessage());
			}
			if (!is_array($cached_less_compile) || $new_cache['updated'] > $cached_less_compile['updated']) {
				$cache_handler->set($cssfile_md5, $new_cache);
				$tmp_ouput_file = tempnam(dirname($css_file_path), 'gantry_less');


				$header = '';
				if ($this->get('less-debugheader', false)) {
					$header .= sprintf("/*\n * Main File : %s", str_replace($this->baseUrl, '', $less_file_url));
					if (!empty($options)) {
						$header .= sprintf("\n * Variables :\n %s", $options_string);
					}
					if (count($new_cache['files']) > 1) {
						$included_files = array_keys($new_cache['files']);
						unset($included_files[0]);
						array_walk($included_files, create_function('&$v,$k', 'global $gantry;$v=" * ".$gantry->uriutil->getUrlForPath($v);'));
						$header .= sprintf("\n * Included Files : \n%s", implode("\n", str_replace($this->baseUrl, '', $included_files)));
					}
					$header .= "\n */\n";
				}
				file_put_contents($tmp_ouput_file, $header . $new_cache['compiled']);

				// Do the messed up file renaming for windows
				if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
					$move_old_file_name = tempnam(dirname($css_file_path), 'gantry_less');
					if (is_file($css_file_path)) @rename($css_file_path, $move_old_file_name);
					@rename($tmp_ouput_file, $css_file_path);
					@unlink($move_old_file_name);
				} else {
					@rename($tmp_ouput_file, $css_file_path);
				}
				@chmod($css_file_path, 0644);
			}
			$quick_expire_cache->clear($cssfile_md5 . '-compiling');
		}
		$this->addStyle($css_passed_path, $priority);
		if (!empty($css_append) && !is_null($cssfile) && dirname($cssfile) == '.') {
			$this->addStyle($cssfile, $priority);
		}
	}

	/* ------ Stylesheet Funcitons  ----------- */

	/**
	 * @param string $file
	 * @param int    $priority
	 * @param bool   $template_files_override
	 */
	public function addStyle($file = '', $priority = self::DEFAULT_STYLE_PRIORITY, $template_files_override = false)
	{
		if (is_array($file)) {
			$this->addStyles($file, $priority);
			return;
		}

		/** @var $out_files GantryStyleLink[] */
		$out_files     = array();
		$ext           = substr($file, strrpos($file, '.'));
		$filename      = basename($file, $ext);
		$base_file     = basename($file);
		$override_file = $filename . "-override" . $ext;

		// get browser checks and remove base files
		$template_check_paths = $this->getBrowserBasedChecks(preg_replace('/-[0-9a-f]{32}\.css$/i', '.css', basename($file)));
		unset($template_check_paths[array_search($base_file, $template_check_paths)]);


		// check to see if this is a full path file
		$dir = dirname($file);
		if ($dir != ".") {
			// Add full url directly to document
			$file_uri = new Gantry_Uri($file);
			if ($this->uriutil->isExternal($file_uri)) {
				$link                       = new GantryStyleLink('url', '', $file);
				$this->_styles[$priority][] = $link;
				return;
			}

			// process a url passed file and browser checks
			$url_path         = $this->uriutil->getUrlForPath($file_uri);
			$file_path        = $this->uriutil->getFilesystemPath($file_uri);
			$file_parent_path = dirname($file_path);

			if (file_exists($file_parent_path) && is_dir($file_parent_path)) {
				$base_path = preg_replace("/\?(.*)/", '', $file_parent_path . DS . $base_file);
				// load the base file
				if (file_exists($base_path) && is_file($base_path) && is_readable($base_path)) {
					$out_files[$base_path] = new GantryStyleLink('local', $base_path, $url_path);
				}
				foreach ($template_check_paths as $check) {
					$check_path     = preg_replace("/\?(.*)/", '', $file_parent_path . DS . $check);
					$check_url_path = $url_path . "/" . $check;
					if (file_exists($check_path) && is_readable($check_path)) {
						$out_files[$check] = new GantryStyleLink('local', $check_path, $check_url_path);
					}
				}
			} else {
				//pass through no file path urls
				$link                       = new GantryStyleLink('url', '', $url_path);
				$this->_styles[$priority][] = $link;
			}
		} else {

			// get the checks for override files
			$override_checks = $this->getBrowserBasedChecks(basename($override_file));
			unset($override_checks[array_search($override_file, $override_checks)]);

			//set up the check for template with plartform based dirs
			$template_check_p          = $this->platform->getPlatformChecks($this->templatePath . '/css');
			$template_check_u          = $this->platform->getPlatformChecks($this->templateUrl . '/css');
			$template_css_search_paths = array();
			for ($i = 0; $i < count($template_check_p); $i++) {
				$template_css_search_paths[$template_check_u[$i]] = $template_check_p[$i];
			}

			// set up the full path checks
			$css_search_paths                             = array();
			$css_search_paths[$this->gantryUrl . '/css/'] = $this->gantryPath . '/css/';
			if (is_multisite()) {
				$uploads                                                                = wp_upload_dir();
				$css_search_paths[rtrim($uploads['baseurl'], '/\\') . '/css-compiled/'] = rtrim($uploads['basedir'], '/\\') . '/css-compiled/';
			} else {
				$css_search_paths[$this->templateUrl . '/css-compiled/'] = $this->templatePath . '/css-compiled/';
			}
			$css_search_paths = array_merge($css_search_paths, $template_css_search_paths);


			$base_override   = false;
			$checks_override = array();

			foreach ($template_css_search_paths as $template_url => $template_path) {
				// Look for an base override file in the template dir
				$template_base_override_file = $template_path . $override_file;
				if ($this->isStyleAvailable($template_base_override_file)) {
					$out_files[$template_base_override_file] = new GantryStyleLink('local', $template_base_override_file, $template_url . $override_file);
					$base_override                           = true;
				}

				// look for overrides for each of the browser checks
				foreach ($override_checks as $check_index => $override_check) {
					$template_check_override       = preg_replace("/\?(.*)/", '', $template_path . $override_check);
					$checks_override[$check_index] = false;
					if ($this->isStyleAvailable($template_check_override)) {
						$checks_override[$check_index] = true;
						if ($base_override) {
							$out_files[$template_check_override] = new GantryStyleLink('local', $template_check_override, $template_url . $override_check);
						}
					}
				}
			}

			if (!$base_override) {
				// Add the base files if there is no  base -override
				foreach ($css_search_paths as $base_url => $path) {
					// Add the base file
					$base_path = preg_replace("/\?(.*)/", '', $path . $base_file);
					// load the base file
					if ($this->isStyleAvailable($base_path)) {
						$outfile_key             = ($template_files_override) ? $base_file : $base_path;
						$out_files[$outfile_key] = new GantryStyleLink('local', $base_path, $base_url . $base_file);
					}

					// Add the browser checked files or its override
					foreach ($template_check_paths as $check_index => $check) {
						// replace $check with the override if it exists
						if ($checks_override[$check_index]) {
							$check = $override_checks[$check_index];
						}

						$check_path = preg_replace("/\?(.*)/", '', $path . $check);

						if ($this->isStyleAvailable($check_path)) {
							$outfile_key             = ($template_files_override) ? $check : $check_path;
							$out_files[$outfile_key] = new GantryStyleLink('local', $check_path, $base_url . $check);
						}
					}
				}
			}
		}

		foreach ($out_files as $link) {
			$addit = true;
			foreach ($this->_styles as $style_priority => $priority_links) {
				$index = array_search($link, $priority_links);
				if ($index !== false) {
					if ($priority < $style_priority) {
						unset($this->_styles[$style_priority][$index]);
					} else {
						$addit = false;
					}
				}
			}
			if ($addit) {
				if (!defined('GANTRY_FINALIZED')) {
					$this->_styles[$priority][] = $link;
				} else {
					wp_enqueue_style($link->getUrl(), $link->getUrl(), array(), '4.1.2');
				}
			}
		}

		//clean up styles
		foreach ($this->_styles as $style_priority => $priority_links) {
			if (count($priority_links) == 0) {
				unset($this->_styles[$style_priority]);
			}
		}
	}

	/**
	 * @param $path
	 *
	 * @return bool
	 */
	protected function isStyleAvailable($path)
	{
		if (isset($this->_styles_available[$path])) {
			return true;
		} else if (file_exists($path) && is_file($path)) {
			$this->_styles_available[$path] = $path;
			return true;
		}
		return false;
	}

	/**
	 * @param array $styles
	 * @param int   $priority
	 */
	public function addStyles($styles = array(), $priority = self::DEFAULT_STYLE_PRIORITY)
	{
		if (defined('GANTRY_FINALIZED')) return;
		foreach ($styles as $style) $this->addStyle($style, $priority);
	}

	/**
	 * @param string $css
	 *
	 * @return null
	 */
	public function addInlineStyle($css = '')
	{
		if (defined('GANTRY_FINALIZED')) return;
		if (!isset($this->_inline_style)) {
			$this->_inline_style = $css;
		} else {
			$this->_inline_style .= chr(13) . $css;
		}
	}

	/**
	 * @param string $file
	 *
	 * @return void
	 */
	public function addScript($file = '', $in_footer = false)
	{
		if (is_array($file)) {
			$this->addScripts($file);
			return;
		}
		//special case for main JS libs
		if ($file == 'mootools.js') {
			global $wp_scripts;
			$found = false;
			foreach ($wp_scripts->registered as $script) {
				if ((strpos($script->handle, 'mootools') !== false && strpos($script->handle, 'rok_') !== false) || (isset($script->content_url) && strpos($script->content_url, 'mootools') !== false && strpos($script->handle, 'rs_') !== false) || (isset($script->src) && strpos($script->src, 'mootools') !== false && strpos($script->handle, 'rs_') !== false)
				) {
					$found = true;
				}
			}
			if (!$found) {
				wp_enqueue_script($file);
			}
			return;
		}
		$type = 'js';

		$query_string = '';
		// check to see if this is a full path file
		$dir      = dirname($file);
		$file_uri = new Gantry_Uri($file);
		if ($dir != ".") {
			// For remote url just add the url
			if ($this->uriutil->isExternal($file_uri)) {
				if (!$in_footer)
				{
					$this->_header_full_scripts[] = $file;
				}
				else {
					$this->_footer_full_scripts[] = $file;
				}
				return;
			}

			// For local url path get the local path based on checks
			$url_path        = $dir;
			$file_path       = $this->uriutil->getFilesystemPath($file);
			$url_file_checks = $this->platform->getJSChecks($file_path, true);
			foreach ($url_file_checks as $url_file) {
				$full_path = gantry_clean_path(realpath($url_file));
				if ($full_path !== false && file_exists($full_path)) {
					$check_url_path = $url_path . '/' . basename($url_file);
					if (!defined('GANTRY_FINALIZED')) {
						if (!$in_footer) {
							$this->_headerscripts[$full_path] = $check_url_path . $query_string;
						} else {
							$this->_footerscripts[$full_path] = $check_url_path . $query_string;
						}
					} else {
						wp_enqueue_script($check_url_path, $check_url_path, array(), '4.1.2', $in_footer);
					}
					break;
				}
			}
			return;
		}

		//set up the check for template with plartform based dirs
		$template_check_p      = $this->platform->getPlatformChecks($this->templatePath . '/js');
		$template_check_u      = $this->platform->getPlatformChecks($this->templateUrl . '/js');
		$template_search_paths = array();
		for ($i = 0; $i < count($template_check_p); $i++) {
			$template_search_paths[$template_check_u[$i]] = $template_check_p[$i];
		}

		$paths = array(
			$this->gantryUrl . '/' . $type => $this->gantryPath . '/' . $type
		);

		$paths = array_merge($template_search_paths, $paths);

		$checks = $this->platform->getJSChecks($file);
		foreach ($paths as $baseurl => $path) {
			$baseurl = rtrim($baseurl, '/');
			$path    = rtrim($path, '/\\');
			if (file_exists($path) && is_dir($path)) {
				foreach ($checks as $check) {
					$check_path     = preg_replace("/\?(.*)/", '', $path . '/' . $check);
					$check_url_path = $baseurl . "/" . $check;
					if (file_exists($check_path) && is_readable($check_path)) {
						if (!defined('GANTRY_FINALIZED')) {
						    if (!$in_footer) {
						        $this->_headerscripts[$check_path] = $check_url_path . $query_string;
						    } else {
						        $this->_footerscripts[$check_path] = $check_url_path . $query_string;
						    }
						} else {
							wp_enqueue_script($check_url_path, $check_url_path, array(), '4.1.2', $in_footer);
						}
						break(2);
					}
				}
			}
		}
	}


	/**
	 * @param array $scripts
	 */
	public function addScripts($scripts = array(), $in_footer = false)
	{
		if (defined('GANTRY_FINALIZED')) return;
		foreach ($scripts as $script) $this->addScript($script, $in_footer);
	}

	/**
	 * @param string $js
	 */
	public function addInlineScript($js = '', $in_footer = false)
	{
		if (defined('GANTRY_FINALIZED')) return;
        if(!$in_footer) {
            if (!isset($this->_inline_script)) {
                $this->_inline_script = $js;
            } else {
                $this->_inline_script .= chr(13) . $js;
            }
        } else {
            if (!isset($this->_footer_inline_script)) {
                $this->_footer_inline_script = $js;
            } else {
                $this->_footer_inline_script .= chr(13) . $js;
            }
        }
	}

	/**
	 * @param string $js
	 */
	public function addDomReadyScript($js = '', $in_footer = false)
	{
		if (defined('GANTRY_FINALIZED')) return;
        if(!$in_footer) {
            if (!isset($this->_domready_script)) {
                $this->_domready_script = $js;
            } else {
                $this->_domready_script .= chr(13) . $js;
            }
        } else {
            if (!isset($this->_footer_domready_script)) {
                $this->_footer_domready_script = $js;
            } else {
                $this->_footer_domready_script .= chr(13) . $js;
            }
        }
	}

	/**
	 * @param string $js
	 */
	public function addLoadScript($js = '', $in_footer = false)
	{
		if (defined('GANTRY_FINALIZED')) return;
        if(!$in_footer) {
            if (!isset($this->_loadevent_script)) {
                $this->_loadevent_script = $js;
            } else {
                $this->_loadevent_script .= chr(13) . $js;
            }
        } else {
            if (!isset($this->_footer_loadevent_script)) {
                $this->_footer_loadevent_script = $js;
            } else {
                $this->_footer_loadevent_script .= chr(13) . $js;
            }
        }
	}

	/**
	 * @param $path
	 *
	 * @return void
	 */
	public function addContentTypePath($path)
	{
		if (!empty($path) && is_dir($path)) {
			array_unshift($this->_contentTypePaths, $path);
		}
	}

	/**
	 * @return array
	 */
	public function getContentTypePaths()
	{
		if (empty($this->_contentTypePaths)) $this->_initContentTypePaths();
		return $this->_contentTypePaths;
	}

	/**
	 * @return void
	 */
	protected function _initContentTypePaths()
	{
		if (empty($this->_contentTypePaths)) {
			$this->_contentTypePaths[] = $this->templatePath . '/html';
			$this->_contentTypePaths[] = $this->gantryPath . '/html';
		}
	}

	/**
	 *
	 */
	public function clearOverrides()
	{
		$this->_override_tree = array();
	}

	/**
	 * @param $overrides
	 * @param $priority
	 */
	public function addOverrides($overrides, $priority)
	{
		if (!array($overrides)) {
			$overrides = array($overrides);
		}
		$catalog = gantry_get_override_catalog($this->templateName);
		foreach ($overrides as $override) {
			if (array_key_exists($override, $catalog)) {
				$this->_override_tree[] = new GantryOverrideItem($override, $priority, 0, _g('Added by template function'));
			}
		}
		$this->_override_tree = GantryOverrides::sortOverridesList($this->_override_tree);
		$this->reset();
	}

	/**
	 * @param        $layout_name
	 * @param array  $params all parameters needed for rendering the layout as an associative array with 'parameter name' => parameter_value
	 *
	 * @return string
	 */
	public function renderLayout($layout_name, $params = array())
	{
		$layout = $this->getLayout($layout_name);
		if ($layout === false) {
			return "<!-- Unable to render layout... can not find layout class for " . $layout_name . " -->";
		}
		return $layout->render($params);
	}


	/**#@+
	 * @access private
	 */

	/**
	 * internal util function to get key from schema array
	 *
	 * @param  $schemaArray
	 *
	 * @return string
	 */
	public function getKey($schemaArray)
	{

		$concatArray = array();

		foreach ($schemaArray as $key => $value) {
			$concatArray[] = $key . $value;
		}

		return (implode("-", $concatArray));
	}


	/**
	 * @return void
	 */
	protected function loadConfig()
	{
		// Process the config
		$default_config_file = $this->gantryPath . DS . 'gantry.config.php';
		if (file_exists($default_config_file) && is_readable($default_config_file)) {
			include_once($default_config_file);
		}

		$template_config_file = $this->templatePath . DS . 'gantry.config.php';
		if (file_exists($template_config_file) && is_readable($template_config_file)) {
			/** @define "$template_config_file" "VALUE" */
			include_once($template_config_file);
		}

		if (isset($gantry_default_config_mapping)) {
			$temp_array         = array_merge($this->_config_vars, $gantry_default_config_mapping);
			$this->_config_vars = $temp_array;
		}
		if (isset($gantry_config_mapping)) {
			$temp_array         = array_merge($this->_config_vars, $gantry_config_mapping);
			$this->_config_vars = $temp_array;
		}

		foreach ($this->_config_vars as $config_var_name => $class_var_name) {
			$default_config_var_name = 'gantry_default_' . $config_var_name;
			if (isset($$default_config_var_name)) {
				$this->$class_var_name = $$default_config_var_name;
				$this->__cacheables[]  = $class_var_name;
			}
			$template_config_var_name = 'gantry_' . $config_var_name;
			if (isset($$template_config_var_name)) {
				$this->$class_var_name = $$template_config_var_name;
				$this->__cacheables[]  = $class_var_name;
			}
		}
	}

	/**
	 *
	 */
	public function loadWidgetPositions()
	{
		$positions = $this->getUniquePositions();


		if (function_exists('register_sidebars')) {
			foreach ($positions as $position) {
				$positionInfo = $this->getPositionInfo($position);
				register_sidebars(1, array(
					'name'          => _g($positionInfo->name),
					'id'            => $positionInfo->id,
					'description'   => _g($positionInfo->description),
					'before_widget' => '',
					'after_widget'  => '',
					'before_title'  => '',
					'after_title'   => '',
				));
			}
		}
	}

	/**
	 * Gets the xml config for all gantry widgets
	 * @return void
	 */
	protected function getWidgetConfigs()
	{
		gantry_import('core.config.gantryform');

		$form_paths = array(
			$this->gantryPath . DS . 'widgets',
			$this->templatePath . DS . 'widgets'
		);
		foreach ($form_paths as $form_path) {
			if (file_exists($form_path) && is_dir($form_path)) {
				GantryForm::addFormPath($form_path);
			}
		}

		$field_paths = array(
			$this->gantryPath . DS . 'admin/forms/fields',
			$this->templatePath . DS . 'admin/forms/fields'
		);
		foreach ($field_paths as $field_path) {
			if (file_exists($field_path) && is_dir($field_path)) {
				GantryForm::addFieldPath($field_path);
			}
		}

		$group_paths = array(
			$this->gantryPath . DS . 'admin/forms/groups',
			$this->templatePath . DS . 'admin/forms/groups'
		);
		foreach ($group_paths as $group_path) {
			if (file_exists($group_path) && is_dir($group_path)) {
				GantryForm::addGroupPath($group_path);
			}
		}
	}

	/**
	 * Load up any Browser config values set in the gantry.config.php files
	 * @return void
	 */
	public function loadBrowserConfig()
	{
		$checks = array(
			$this->browser->name,
			$this->browser->platform,
			$this->browser->name . '_' . $this->browser->platform,
			$this->browser->name . $this->browser->shortversion,
			$this->browser->name . $this->browser->version,
			$this->browser->name . $this->browser->shortversion . '_' . $this->browser->platform,
			$this->browser->name . $this->browser->version . '_' . $this->browser->platform
		);

		foreach ($checks as $check) {
			if (array_key_exists($check, $this->_browser_params)) {
				foreach ($this->_browser_params[$check] as $param_name => $param_value) {
					$this->set($param_name, $param_value);
				}
			}
		}
	}

	/**
	 * @return void
	 */
	protected function customPresets()
	{
		$this->originalPresets = $this->presets;
		if (file_exists($this->custom_presets_file)) {

			$customPresets         = GantryINI::read($this->custom_presets_file);
			$this->customPresets   = $customPresets;
			$this->originalPresets = $this->presets;
			if (count($customPresets)) {
				$this->presets = $this->array_merge_replace_recursive($this->presets, $customPresets);
				foreach ($this->presets as $key => $preset) {
					uksort($preset, array($this, "_compareKeys"));
					$this->presets[$key] = $preset;
				}
			}

		}
	}

	/**
	 * @param  $key1
	 * @param  $key2
	 *
	 * @return int
	 */
	public function _compareKeys($key1, $key2)
	{
		if (strlen($key1) < strlen($key2)) return -1; else if (strlen($key1) > strlen($key2)) return 1; else {
			if ($key1 < $key2) return -1; else return 1;
		}
	}

	/**
	 * @param  $name
	 * @param  $preset
	 *
	 * @return array
	 */
	public function getPresetParams($name, $preset)
	{
		$return_params = array();
		if (array_key_exists($preset, $this->presets[$name])) {
			$preset_params = $this->presets[$name][$preset];
			foreach ($preset_params as $preset_param_name => $preset_param_value) {
				if (array_key_exists($preset_param_name, $this->_working_params) && $this->_working_params[$preset_param_name]['type'] == 'preset') {
					$return_params = $this->getPresetParams($preset_param_name, $preset_param_value);
				}
			}
			foreach ($preset_params as $preset_param_name => $preset_param_value) {
				if (array_key_exists($preset_param_name, $this->_working_params) && $this->_working_params[$preset_param_name]['type'] != 'preset') {
					$return_params[$preset_param_name] = $preset_param_value;
				}
			}
		}
		return $return_params;
	}

	/**
	 * @return void
	 */
	protected function populateParams()
	{
		gantry_import('core.params.overrides.gantryurlparamoverride');
		gantry_import('core.params.overrides.gantrysessionparamoverride');
		gantry_import('core.params.overrides.gantrycookieparamoverride');
		gantry_import('core.params.overrides.gantryoverrideparamoverride');

		// get a copy of the params for working with on this call
		$this->_working_params = $this->_template->getParams();

		//$reset =  get_query_var('reset-settings');

		if (!isset($_REQUEST['reset-settings'])) {
			GantrySessionParamOverride::populate();
			GantryCookieParamOverride::populate();
		}

		GantryOverrideParamOverride::populate();

		if (!isset($_REQUEST['reset-settings'])) {
			GantryUrlParamOverride::populate();
		}
	}

	/**
	 * internal util to get short name from long name
	 *
	 * @param  $longname
	 *
	 * @return string
	 */
	public function getShortName($longname)
	{
		$shortname = $longname;
		if (strlen($longname) > 2) {
			$shortname = substr($longname, 0, 1) . substr($longname, -1);
		}
		return $shortname;
	}

	/**
	 * internal util to get long name from short name
	 *
	 * @param  $shortname
	 *
	 * @return string
	 */
	public function getLongName($shortname)
	{
		switch (substr($shortname, 0, 1)) {
			case "s":
			default:
				$longname = "sidebar";
				break;
		}
		$longname .= "-" . substr($shortname, -1);
		return $longname;
	}


	/**
	 * internal util to retrieve the stored position schema
	 *
	 * @param  $position
	 * @param  $gridsize
	 * @param  $count
	 * @param  $index
	 *
	 * @return array|boolean
	 */
	public function getPositionSchema($position, $gridsize, $count, $index)
	{
		$param         = $position . '-layout';
		$defaultSchema = false;

		$storedParam = $this->get($param);
		if (!preg_match("/{/", $storedParam)) $storedParam = '';
		$setting = unserialize($storedParam);

		$schema =& $setting[$gridsize][$count][$index];
		if (isset($schema)) return $schema; else {
			if (count($this->layoutSchemas[$gridsize]) < $count) {
				$count = count($this->layoutSchemas[$gridsize]);
			}
			for ($i = $count; $i > 0; $i--) {
				$layout = $this->layoutSchemas[$gridsize][$i];
				if (isset($layout[$index])) {
					$defaultSchema = $layout[$index];
					break;
				}
			}
			return $defaultSchema;
		}
	}


	/**
	 * @param string $file
	 * @param bool   $keep_path
	 *
	 *
	 * @return array
	 */
	protected function getBrowserBasedChecks($file, $keep_path = false)
	{
		$ext      = substr($file, strrpos($file, '.'));
		$path     = ($keep_path) ? dirname($file) . DS : '';
		$filename = basename($file, $ext);

		$checks = $this->browser->getChecks($file, $keep_path);

		// check if RTL version needed
		if (get_bloginfo('text_direction') == 'rtl' && $this->get('rtl-enabled')) {
			$checks[] = $path . $filename . '-rtl' . $ext;
		}
		return $checks;
	}

	/**
	 * @return bool|string
	 */
	public function getCurrentTemplate()
	{
		if (defined('TEMPLATEPATH')) {
			return basename(TEMPLATEPATH);
		} elseif (function_exists('get_template')) {
			return get_template();
		} else {
			return false;
		}
	}


	/**
	 *
	 */
	protected function loadStyles()
	{

		$type          = 'css';
		$template_path = $this->templatePath . '/' . $type . '/';
		$gantry_path   = $this->gantryPath . '/' . $type . '/';

		$gantry_first_paths = array(
			$gantry_path,
			$template_path
		);

		if (empty($this->_styles_available)) {
			$raw_styles = array();
			foreach ($gantry_first_paths as $style_path) {
				if (file_exists($style_path) && is_dir($style_path)) {
					$d = dir($style_path);
					while (false !== ($entry = $d->read())) {
						if ($entry != '.' && $entry != '..') {

							if (!isset($raw_styles[$style_path])) {
								$raw_styles[$style_path . $entry] = $style_path . $entry;
							}
						}
					}
					$d->close();
				}
			}

			$this->_styles_available = $raw_styles;
		}
	}

	/**
	 * @return void
	 */
	protected function loadGizmos()
	{
		$gizmo_paths = array(
			$this->templatePath . DS . 'gizmos',
			$this->gantryPath . DS . 'gizmos'
		);

		$raw_gizmos = array();
		foreach ($gizmo_paths as $gizmo_path) {
			if (file_exists($gizmo_path) && is_dir($gizmo_path)) {
				$d = dir($gizmo_path);
				while (false !== ($entry = $d->read())) {
					if ($entry != '.' && $entry != '..') {
						$gizmo_name = basename($entry, ".php");
						$path       = $gizmo_path . DS . $gizmo_name . '.php';
						$className  = 'GantryGizmo' . ucfirst($gizmo_name);
						if (!class_exists($className)) {
							if (file_exists($path)) {
								require_once($path);
								if (class_exists($className)) {
									$raw_gizmos[$this->get($gizmo_name . "-priority", 10)][] = $gizmo_name;
								}
							}

						}
					}
				}
				$d->close();
			}
		}

		ksort($raw_gizmos);
		foreach ($raw_gizmos as $gizmos) {
			foreach ($gizmos as $gizmo) {
				if (!in_array($gizmo, $this->_gizmos)) {
					$this->_gizmos[$gizmo] = $gizmo;
				}
			}
		}
	}

	/**
	 * @return void
	 */
	protected function loadWidgets()
	{
		$widget_paths = array(
			$this->templatePath . DS . 'widgets',
			$this->gantryPath . DS . 'widgets'
		);

		foreach ($widget_paths as $widget_path) {
			if (file_exists($widget_path) && is_dir($widget_path)) {
				$d = dir($widget_path);
				while (false !== ($entry = $d->read())) {
					if ($entry != '.' && $entry != '..') {
						$widget_name = basename($entry, ".php");
						$path        = $widget_path . DS . $widget_name . '.php';
						$className   = 'GantryWidget' . ucfirst($widget_name);
						if (!class_exists($className)) {
							if (file_exists($path)) {
								require_once($path);
								if (class_exists($className)) {
									$this->_widgets[$widget_name] = $className;
								}
							}

						}
					}
				}
				$d->close();
			}
		}
	}

	/**
	 *
	 */
	protected function initWidgets()
	{
		foreach ($this->_widgets as $widgetClass) {
			add_action('widgets_init', array($widgetClass, "init"));
		}
	}

	/**
	 * @return void
	 */
	protected function loadAjaxModels()
	{
		$models_paths = array(
			$this->templatePath . DS . 'ajax-models',
			$this->gantryPath . DS . 'ajax-models'
		);
		$this->loadModels($models_paths, $this->_ajaxmodels);
		return;
	}

	/**
	 * @return void
	 */
	protected function loadAdminAjaxModels()
	{
		$models_paths = array(
			$this->templatePath . DS . 'admin' . DS . 'ajax-models',
			$this->gantryPath . DS . 'admin' . DS . 'ajax-models'
		);
		$this->loadModels($models_paths, $this->_adminajaxmodels);
		return;
	}

	/**
	 * Load up the ajax models from the passed paths
	 *
	 * @param $paths
	 * @param $results
	 */
	protected function loadModels($paths, &$results)
	{
		foreach ($paths as $model_path) {
			if (file_exists($model_path) && is_dir($model_path)) {
				$d = dir($model_path);
				while (false !== ($entry = $d->read())) {
					if ($entry != '.' && $entry != '..') {
						$model_name = basename($entry, ".php");
						$path       = $model_path . DS . $model_name . '.php';
						if (file_exists($path) && !array_key_exists($model_name, $results)) {
							$results[$model_name] = $path;
						}
					}
				}
				$d->close();
			}
		}
	}


	/**
	 * @param  $gizmo_name
	 *
	 * @return boolean
	 */
	protected function getGizmo($gizmo_name)
	{
		$className = 'GantryGizmo' . ucfirst($gizmo_name);

		if (!class_exists($className)) {
			$this->loadGizmos();
		}

		if (class_exists($className)) {
			return new $className();
		}
		return false;
	}

	/**
	 * load up the layouts in the defined paths
	 */
	protected function loadLayouts()
	{
		$layout_paths = array(
			$this->templatePath . DS . 'html' . DS . 'layouts',
			$this->gantryPath . DS . 'html' . DS . 'layouts'
		);

		foreach ($layout_paths as $layout_path) {
			if (file_exists($layout_path) && is_dir($layout_path)) {
				$d = dir($layout_path);
				while (false !== ($entry = $d->read())) {
					if ($entry != '.' && $entry != '..') {
						$layout_name = basename($entry, ".php");
						$path        = $layout_path . DS . $layout_name . '.php';
						$className   = 'GantryLayout' . ucfirst($layout_name);
						if (!class_exists($className)) {
							if (file_exists($path)) {
								require_once($path);
								if (class_exists($className)) {
									$this->_layouts[$layout_name] = $className;
								}
							}
						}
					}
				}
				$d->close();
			}
		}
	}

	/**
	 * @param $layout_name
	 *
	 * @return GantryLayout|bool
	 */
	public function getLayout($layout_name)
	{
		$className = 'GantryLayout' . ucfirst($layout_name);
		if (!class_exists($className)) {
			$this->loadLayouts();
		}

		if (class_exists($className)) {
			return new $className();
		}
		return false;
	}

	/**
	 * @param  $schema
	 *
	 * @return array
	 */
	public function flipBodyPosition($schema)
	{

		$backup         = array_keys($schema);
		$backup_reverse = array_reverse($schema);
		$reverse        = array_reverse($backup);

		$pos = array_search('mb', $backup);

		unset($backup[$pos]);

		$new_keys   = array();
		$new_schema = array();

		reset($backup);
		foreach ($reverse as $value) {
			if ($value != 'mb') {
				$value = current($backup);
				next($backup);
			}
			$new_keys[] = $value;
		}

		reset($backup_reverse);
		foreach ($new_keys as $key) {
			$new_schema[$key] = current($backup_reverse);
			next($backup_reverse);
		}
		return $new_schema;
	}

	/**
	 * @param  array $array1 primary array
	 * @param  array $array2 adding array
	 *
	 * @return array
	 */
	protected function array_merge_replace_recursive(&$array1, &$array2)
	{
		$merged = $array1;

		foreach ($array2 as $key => $value) {
			if (is_array($value) && isset($merged[$key]) && is_array($merged[$key])) {
				$merged[$key] = $this->array_merge_replace_recursive($merged[$key], $value);
			} else {
				$merged[$key] = $value;
			}
		}

		return $merged;
	}


	/**
	 * @return GantryOverridesEngine
	 */
	protected function loadOverrideEngine()
	{
		$_override_engine = new GantryOverridesEngine();
		$_override_engine->init($this->templateName);
		return $_override_engine;
	}


	/**#@-*/

	/**
	 * get the url path for cookies
	 * @return string
	 */
	public function getCookiePath()
	{
		$cookieUrl = '';
		if (!empty($this->baseUrl)) {
			if (substr($this->baseUrl, -1, 1) == '/') {
				$cookieUrl = substr($this->baseUrl, 0, -1);
			} else {
				$cookieUrl = $this->baseUrl;
			}
		}
		return $cookieUrl;
	}

	/**
	 * @param string $layout_name
	 *
	 * @deprecated use getLayout instead
	 * @return GantryLayout|bool
	 */
	public function _getLayout($layout_name)
	{
		return $this->getLayout($layout_name);
	}

}
