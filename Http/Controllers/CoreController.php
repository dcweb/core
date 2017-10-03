<?php

namespace Dcms\Core\Http\Controllers;

use App\Http\Controllers\Controller;
use Carbon\Carbon;

class CoreController extends Controller
{
    public function index($timezone)
    {
        echo Carbon::now($timezone)->toDateTimeString();
    }
}

 ?>
