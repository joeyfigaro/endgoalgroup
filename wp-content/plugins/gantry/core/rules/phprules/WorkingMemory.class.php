<?php
class WorkingMemory
{
	private $facts = array();
	private $action = array();

	function insert(&$fact)
	{
		$this->insertFact($fact);
	}

	function insertFact(&$fact)
	{
		$class = get_class($fact);

		if (!array_key_exists($class, $this->facts)) $this->facts[$class] = array();

		$this->facts[$class][$fact->getObjectId()] = $fact;
	}


	function insertActionFassade($key, &$action)
	{
		$this->action[$key] = $action;
	}

	function getRuleInvocations($context)
	{
		$invocations = array();

		$this->setNextClass($invocations, array(), $context);

		return $invocations;
	}

	function setNextClass(&$invocations, $invocation, $context)
	{
		if (count($context) == 0) {
			array_push($invocations, $invocation);
			return;
		} else {
			$contextKeys = array_keys($context);
			$var         = $contextKeys[0];
			$class       = $context[$var];
			unset($context[$var]);
			if (array_key_exists($class, $this->facts)) {
				foreach ($this->facts[$class] as $object) {
					$invocation[$var] = $object;
					$this->setNextClass($invocations, $invocation, $context);
				}
			}

		}
	}

	function &getActionFassades()
	{
		return $this->action;
	}

	function invokeRule($rule, $objects)
	{
		$action = $this->action;
		$filled = $rule->action;

		foreach (array_keys($objects) as $key) $filled = preg_replace("/\\" . $key . "([^\w]|$)/", '\$objects[\'' . $key . '\']\1', $filled);

		eval($filled);
	}

	function printIt()
	{
		print "WorkingMemory\n";
		print "Facts:\n";
		print_r($this->facts);
		print "ActionFassades:\n";
		print_r($this->action);
	}
}

?>