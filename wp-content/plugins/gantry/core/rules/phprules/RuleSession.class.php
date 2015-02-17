<?php
class RuleSession
{
	var $workingMemory;
	var $ruleBase;
	var $invocationCount = array();

	var $verbosity = 0;
	var $maxRulesFiring = 100;
	var $maxFiringPerRule = 0; // 0 is unlimited

	function RuleSession(&$ruleBase, &$workingMemory)
	{
		$this->ruleBase      = & $ruleBase;
		$this->workingMemory = & $workingMemory;
	}

	function fire()
	{
		$invocations     = array();
		$rules           = array();
		$highestPriority = -1;

		foreach ($this->ruleBase->getRules() as $name => $rule) {
			$posibleInvocations = $this->workingMemory->getRuleInvocations($rule->context);
			$checkedInvocations = $rule->checkAll($posibleInvocations);

			if (count($checkedInvocations) > 0) {
				$invocations[$rule->name] = $checkedInvocations;

				if (array_key_exists('priority', $rule->config)) $priority = (int)$rule->config['priority']; else
					$priority = 0;

				if (!array_key_exists($priority, $rules)) $rules[$priority] = array();

				if ($this->maxFiringPerRule == 0 || !isset($this->invocationCount[$name]) || $this->invocationCount[$name] < $this->maxFiringPerRule) {
					array_push($rules[$priority], $rule);

					if ($priority > $highestPriority) $highestPriority = $priority;
				}
			}
		}

		if ($highestPriority > -1) {
			foreach ($rules[$highestPriority] as $rule) {
				foreach ($invocations[$rule->name] as $invocation) {
					$this->workingMemory->invokeRule($rule, $invocation);
				}
				if (!array_key_exists($rule->name, $this->invocationCount)) $this->invocationCount[$rule->name] = 0;
				$this->invocationCount[$rule->name] += 1;
			}
		}

		return $highestPriority > -1;
	}

	function fireNext()
	{
		return $this->fire();
	}

	function fireAll()
	{
		$loop = 1;

		while ($this->fire() && $loop < $this->maxRulesFiring) $loop++;

		if ($loop >= $this->maxRulesFiring) {
			if ($this->verbosity > 0) print "Max firing count for all rules reached!\n";
			error_log("phprules: Max firing count for all rules reached!");
			return false;
		}

		return true;
	}
}

?>