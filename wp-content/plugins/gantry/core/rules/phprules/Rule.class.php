<?php
class Rule
{
	var $name;
	var $config;
	var $context;
	var $condition;
	var $action;

	function isContextFullfilled($objects)
	{
		$present = array();

		foreach ($objects as $key => $value) $present[$key] = get_class($value);

		$valueOk = count(array_diff($present, $this->context)) == 0;
		$keyOk   = count(array_diff_key($present, $this->context)) == 0;

		return $keyOk && $valueOk;
	}

	function check($objects)
	{
		if ($this->isContextFullfilled($objects)) {
			$result = null;

			$filled = $this->condition;

			foreach (array_keys($this->context) as $key) $filled = preg_replace("/\\" . $key . "/", '\$objects[\'' . $key . '\']', $filled);

			eval('$result = ' . $filled . ';');

			return $result;
		} else
			return false;
	}

	function checkAll($invocations)
	{
		$checked = array();

		foreach ($invocations as $invocation) if ($this->check($invocation)) array_push($checked, $invocation);

		return $checked;
	}

	function printIt()
	{
		print "Rule '$this->name':\n";
		print_r($this);
	}
}

?>