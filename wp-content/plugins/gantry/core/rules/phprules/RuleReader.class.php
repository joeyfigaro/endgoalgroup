<?php
class RuleReader
{
	function parseFile($files)
	{
		$rb = new RuleBase();

		if (is_string($files)) $files = array($files);

		foreach ($files as $file) {
			if (!file_exists($file)) {
				print "File $file does not exist!";
				continue;
			}

			$fh       = fopen($file, "r");
			$ruleInfo = array();
			$rawRule  = '';
			while (!feof($fh)) {
				$line = fgets($fh);
				if (preg_match('/^rule "{0,1}([^"]*)"{0,1}\s+(.*)$/', $line, $matches)) {

					if (trim($rawRule) != '') {
						$rule = $this->buildRule($ruleInfo, $rawRule);
						$rb->addRule($rule);
					}

					$ruleInfo = $matches;
					$rawRule  = '';
				} else if (feof($fh)) {
					$rule = $this->buildRule($ruleInfo, $rawRule . $line);
					$rb->addRule($rule);
				} else {
					$rawRule .= $line;
				}
			}
			fclose($fh);
		}

		return $rb;
	}

	function buildRule($info, $raw)
	{
		$raw = str_replace("\n", " ", $raw);
		$raw = str_replace("\r", " ", $raw);
		$raw = str_replace("\t", " ", $raw);
		$raw = preg_replace("/[ ]{2,}/", " ", $raw);
		$raw = trim($raw);

		$rule         = $this->parseRule($raw);
		$rule->name   = trim($info[1]);
		$rule->config = $this->parseRuleConfig($info[2]);

		return $rule;
	}

	function parseRuleConfig($raw)
	{
		$configuration = array();
		foreach (explode(",", $raw) as $item) {
			$item                      = trim($item);
			$config                    = explode('=', $item);
			$configuration[$config[0]] = $config[1];
		}
		return $configuration;
	}

	function parseContext($raw)
	{
		$context = array();
		foreach (explode(',', $raw) as $def) {
			$item              = explode(' ', trim($def));
			$context[$item[1]] = $item[0];
		}

		return $context;
	}

	function parseRule($raw)
	{
		if (preg_match('/^context\s*(.*?)\s*if\s*(.*)\s*then\s*(.*)end$/', $raw, $matches)) {
			$rule            = new Rule();
			$rule->context   = $this->parseContext($matches[1]);
			$rule->condition = $matches[2];
			$rule->action    = $matches[3];

			return $rule;
		} else {
			print "wrong rule format!";
		}
	}
}

?>