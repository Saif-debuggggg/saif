<?php

namespace WilokeProductFurniture\Share;

class AutoPrefix
{
	public static function namePrefix($name)
	{
		return strpos($name, WILOKE_WILOKEPRODUCTFURNITURE_PREFIX) === 0 ? $name : WILOKE_WILOKEPRODUCTFURNITURE_PREFIX . $name;
	}

	public static function removePrefix(string $name): string
	{
		if (strpos($name, WILOKE_WILOKEPRODUCTFURNITURE_PREFIX) === 0) {
			$name = str_replace(WILOKE_WILOKEPRODUCTFURNITURE_PREFIX, '', $name);
		}

		return $name;
	}
}