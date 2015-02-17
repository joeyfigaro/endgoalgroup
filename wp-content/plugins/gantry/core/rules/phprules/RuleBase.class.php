<?php
class RuleBase
{
	var $base = array();

	function addRule(&$rule)
	{
		$this->base[$rule->name] = $rule;
	}

	function &getRule($name)
	{
		return $this->base[$name];
	}

	function getRules()
	{
		return $this->base;
	}

	function printIt()
	{
		print "Rule Base: ";
		print_r($this->base);
	}
}

?>