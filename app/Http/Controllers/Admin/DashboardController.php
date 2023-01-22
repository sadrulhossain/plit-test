<?php

namespace App\Http\Controllers\Admin;

use Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use Carbon\Carbon;
use Helper;
use Response;
use Validator;
use DateTime;

class DashboardController extends Controller {

    public function __construct() {
        //
    }

    public function index(Request $request) {


        return view('admin.dashboard')->with(compact('request'));
    }


}
