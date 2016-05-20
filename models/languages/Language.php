<?php

namespace Dcms\Core\Models\Languages;

use Dcms\Core\Models\EloquentDefaults;

	class Language extends EloquentDefaults
	{
		protected $connection = 'project';
	  protected $table  = "languages";
	}
