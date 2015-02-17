<?php
class Fact
{
	private static $idCounter = 1;
	private $objectId = null;

	function getObjectId()
	{
		if ($this->objectId == null) $this->objectId = self::$idCounter++;
		return $this->objectId;
	}
}

?>