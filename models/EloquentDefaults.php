<?php

namespace Dcms\Core\Models;

use Eloquent;
use Auth;
use Schema;

class EloquentDefaults extends Eloquent
{
/*	public function getPropertyLang($prop= "",$lang="")
		{
			$property = $prop.$lang;
			return $this->$property;
		}
		*/

		public function save(array $options = array())
		{
			if(Schema::connection('project')->hasColumn($this->table,'admin'))
			{
				if(isset(Auth::guard('dcms')->user()->username)) $this->admin = Auth::guard('dcms')->user()->username;
			}
			parent::save($options);
		}
}

?>
