<?php

namespace Dcms\Core\Http\Controllers\Dashboard ;

use Dcms\Core\Http\Controllers\BaseController;
use View;


class DashboardController extends BaseController {

	public function dashboard()
	{
		return View::make('dcms::dashboard/index');
	}
}

?>
