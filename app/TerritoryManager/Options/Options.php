<?php namespace TerritoryManager\Options;

use Settings;

class Options {

	public function get($setting)
	{
		$options = Settings::select('option_value')
							->where('option_name', '=', $setting)
							->first();

		if (NULL !== $options)
			return $options->option_value;

		return FALSE;
	}

}
 