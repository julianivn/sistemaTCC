<?php

namespace SistemaTCC\Model\Serializer;

trait ObjectToJson
{
	public function toJson()
	{
		$reflect = new \ReflectionClass($this);

		$data = [];

		foreach ($reflect->getProperties() as $var)
			$data[$var->name] = $this->{$var->name};

		return $data;
	}
}
