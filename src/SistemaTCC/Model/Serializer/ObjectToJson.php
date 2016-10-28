<?php

namespace SistemaTCC\Model\Serializer;

use ReflectionClass;

trait ObjectToJson
{
	public function toJson()
	{
		$reflect = new ReflectionClass($this);
		$data = [];

		foreach ($reflect->getProperties() as $var) {
			$data[$var->name] = $this->{$var->name};
		}

		return $data;
	}
}
