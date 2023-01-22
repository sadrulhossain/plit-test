<?php

namespace App\Http\Controllers;

use Validator;
use App\UserGroup;
use App\User;
use Session;
use Redirect;
use Auth;
use File;
use Input;
use Illuminate\Http\Request;

class UserGroupController extends Controller {

    private $controller = 'UserGroup';

    public function index(Request $request) {
        //passing param for custom function
        $qpArr = $request->all();

        $targetArr = UserGroup::orderBy('id', 'asc');

//        begin filtering
        $searchText = $request->search;
        $nameArr = UserGroup::select('name')->get();
        if (!empty($searchText)) {
            $targetArr->where(function ($query) use ($searchText) {

                $query->where('name', 'LIKE', '%' . $searchText . '%');
            });
        }
//        end filtering

        $targetArr = $targetArr->paginate(Session::get('paginatorCount'));
        //change page number after delete if no data has current page
        if ($targetArr->isEmpty() && isset($qpArr['page']) && ($qpArr['page'] > 1)) {
            $page = ($qpArr['page'] - 1);
            return redirect('/userGroup?page=' . $page);
        }

        return view('userGroup.index')->with(compact('targetArr', 'qpArr', 'nameArr'));
    }

    public function filter(Request $request) {
        $url = 'search=' . urlencode($request->search);
        return Redirect::to('userGroup?' . $url);
    }

}
