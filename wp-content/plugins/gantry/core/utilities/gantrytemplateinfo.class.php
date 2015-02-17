<?php
/**
 * @version   $Id: gantrytemplateinfo.class.php 59361 2013-03-13 23:10:27Z btowles $
 * @author    RocketTheme http://www.rockettheme.com
 * @copyright Copyright (C) 2007 - 2014 RocketTheme, LLC
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 */

class GantryTemplateInfo
{

	/**
	 * @param      $fieldId
	 * @param null $group
	 *
	 * @return string
	 */
	function get_field_id($fieldId, $group = null)
	{
		/** @global $gantry Gantry */
		global $gantry;


		// Initialize variables.
		$id = '';

		// If there is a form control set for the attached form add it first.
//		if ($this->formControl) {
//			$id .= $this->formControl;
//		}

		// If the field is in a group add the group control to the field id.
		if ($group) {
			// If we already have an id segment add the group control as another level.
			if ($id) {
				$id .= '_' . str_replace('.', '_', $group);
			} else {
				$id .= str_replace('.', '_', $group);
			}
		}

		// If we already have an id segment add the field id/name as another level.
		if ($id) {
			$id .= '_' . $fieldId;
		} else {
			$id .= $fieldId;
		}

		// Clean up any invalid characters.
		$id = preg_replace('#\W#', '_', $id);

		return $gantry->templateName . "-" . $id;
	}

	/**
	 * @param      $fieldName
	 * @param null $group
	 *
	 * @return string
	 */
	function get_field_name($fieldName, $group = null)
	{
		/** @global $gantry Gantry */
		global $gantry;

		$name = '';

		// If there is a form control set for the attached form add it first.
//		if ($this->formControl) {
//			$name .= $this->formControl;
//		}

		// If the field is in a group add the group control to the field name.
		if ($group) {
			// If we already have a name segment add the group control as another level.
			$groups = explode('.', $group);
			if ($name) {
				foreach ($groups as $group) {
					$name .= '[' . $group . ']';
				}
			} else {
				$name .= array_shift($groups);
				foreach ($groups as $group) {
					$name .= '[' . $group . ']';
				}
			}
		}

		// If we already have a name segment add the field name as another level.
		if ($name) {
			$name .= '[' . $fieldName . ']';
		} else {
			$name .= $fieldName;
		}

		// If the field should support multiple values add the final array segment.
//		if ($this->multiple) {
//			$name .= '[]';
//		}

		return $gantry->templateName . '-' . $name;
	}

	/**
	 * @var
	 */
	var $multiple;
	/**
	 * Template Version
	 * @access private
	 * @var string
	 */
	var $version;

	/**
	 * Gets the version for gantry
	 * @access public
	 * @return string
	 */
	function getVersion()
	{
		return $this->version;
	}

	/**
	 * Sets the version for gantry
	 * @access public
	 *
	 * @param string $version
	 */
	function setVersion($version)
	{
		$this->version = $version;
	}

	/**
	 * Template Short Name
	 * @access private
	 * @var string
	 */
	var $name;

	/**
	 * Gets the name for gantry
	 * @access public
	 * @return string
	 */
	function getName()
	{
		return $this->name;
	}

	/**
	 * Sets the name for gantry
	 * @access public
	 *
	 * @param string $name
	 */
	function setName($name)
	{
		$this->name = $name;
	}

	/**
	 * Template Full Name
	 * @access private
	 * @var string
	 */
	var $fullname;

	/**
	 * Gets the fullname for gantry
	 * @access public
	 * @return string
	 */
	function getFullname()
	{
		return $this->fullname;
	}

	/**
	 * Sets the fullname for gantry
	 * @access public
	 *
	 * @param string $fullname
	 */
	function setFullname($fullname)
	{
		$this->fullname = $fullname;
	}

	/**
	 * Creation Date
	 * @access private
	 * @var string
	 */
	var $creationDate;

	/**
	 * Gets the creationDate for gantry
	 * @access public
	 * @return string
	 */
	function getCreationDate()
	{
		return $this->creationDate;
	}

	/**
	 * Sets the creationDate for gantry
	 * @access public
	 *
	 * @param string $creationDate
	 */
	function setCreationDate($creationDate)
	{
		$this->creationDate = $creationDate;
	}

	/**
	 * Template Author Email
	 * @access private
	 * @var string
	 */
	var $authorEmail;

	/**
	 * Gets the authorEmail for gantry
	 * @access public
	 * @return string
	 */
	function getAuthorEmail()
	{
		return $this->authorEmail;
	}

	/**
	 * Sets the authorEmail for gantry
	 * @access public
	 *
	 * @param string $authorEmail
	 */
	function setAuthorEmail($authorEmail)
	{
		$this->authorEmail = $authorEmail;
	}

	/**
	 * Template Author Url
	 * @access private
	 * @var string
	 */
	var $authorUrl;

	/**
	 * Gets the authorUrl for gantry
	 * @access public
	 * @return string
	 */
	function getAuthorUrl()
	{
		return $this->authorUrl;
	}

	/**
	 * Sets the authorUrl for gantry
	 * @access public
	 *
	 * @param string $authorUrl
	 */
	function setAuthorUrl($authorUrl)
	{
		$this->authorUrl = $authorUrl;
	}

	/**
	 * Template Description
	 * @access private
	 * @var string
	 */
	var $description;

	/**
	 * Gets the description for gantry
	 * @access public
	 * @return string
	 */
	function getDescription()
	{
		return $this->description;
	}

	/**
	 * Sets the description for gantry
	 * @access public
	 *
	 * @param string $description
	 */
	function setDescription($description)
	{
		$this->description = $description;
	}

	/**
	 * Template Copyright
	 * @access private
	 * @var string
	 */
	var $copyright;

	/**
	 * Gets the copyright for gantry
	 * @access public
	 * @return string
	 */
	function getCopyright()
	{
		return $this->copyright;
	}

	/**
	 * Sets the copyright for gantry
	 * @access public
	 *
	 * @param string $copyright
	 */
	function setCopyright($copyright)
	{
		$this->copyright = $copyright;
	}

	/**
	 * Template license
	 * @access private
	 * @var string
	 */
	var $license;

	/**
	 * Gets the license for gantry
	 * @access public
	 * @return string
	 */
	function getLicense()
	{
		return $this->license;
	}

	/**
	 * Sets the license for gantry
	 * @access public
	 *
	 * @param string $license
	 */
	function setLicense($license)
	{
		$this->license = $license;
	}

	/**
	 * Template Author
	 * @access private
	 * @var string
	 */
	var $author;

	/**
	 * Gets the author for gantry
	 * @access public
	 * @return string
	 */
	function getAuthor()
	{
		return $this->author;
	}

	/**
	 * Sets the author for gantry
	 * @access public
	 *
	 * @param string $author
	 */
	function setAuthor($author)
	{
		$this->author = $author;
	}

	/**
	 * @var bool
	 */
	protected $legacycss = true;

	/**
	 * @var bool
	 */
	protected $gridcss = true;

	/**
	 * @param $legacycss
	 */
	public function setLegacycss($legacycss)
	{
		$set = true;
		if ($legacycss == 'false') {
			$this->legacycss = false;
		}
	}

	/**
	 * @return bool
	 */
	public function getLegacycss()
	{
		return $this->legacycss;
	}

	/**
	 * @param boolean $gridcss
	 */
	public function setGridcss($gridcss)
	{
		$set = true;
		if ($gridcss == 'false') {
			$this->gridcss = false;
		}
	}

	/**
	 * @return boolean
	 */
	public function getGridcss()
	{
		return $this->gridcss;
	}
}