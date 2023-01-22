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

        $targetArr = UserGroup::where('id', '<>', 9)->orderBy('id', 'asc');

//        begin filtering
        $searchText = $request->search;
        $nameArr = UserGroup::where('id', '<>', 9)->select('name')->get();
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
            return redirect('/admin/userGroup?page=' . $page);
        }

        return view('userGroup.index')->with(compact('targetArr', 'qpArr', 'nameArr'));
    }

    public function create(Request $request) { //passing param for custom function
        $qpArr = $request->all();
        return view('userGroup.create')->with(compact('qpArr'));
    }

    public function store(Request $request) {
        //begin back same page after update
        $qpArr = $request->all();
        $pageNumber = !empty($qpArr['page']) ? '?page=' . $qpArr['page'] : '';
        //end back same page after update

        $rules = [
            'name' => 'required|unique:user_group,name',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect('admin/userGroup/create' . $pageNumber)
                            ->withErrors($validator);
        }


        $target = new UserGroup;
        $target->name = $request->name;
        $target->external = !empty($request->external) ? $request->external : '0';

        if ($target->save()) {
            Session::flash('success', __('label.USER_GROUP_CREATED_SUCCESSFULLY'));
            return redirect('admin/userGroup');
        } else {
            Session::flash('error', __('label.USER_GROUP_COULD_NOT_BE_CREATED'));
            return redirect('admin/userGroup/create' . $pageNumber);
        }
    }

    public function edit(Request $request, $id) {
        $target = UserGroup::find($id);
        if (empty($target)) {
            Session::flash('error', trans('label.INVALID_DATA_ID'));
            return redirect('admin/userGroup');
        }

        //passing param for custom function
        $qpArr = $request->all();
        return view('userGroup.edit')->with(compact('target', 'qpArr'));
    }

    public function update(Request $request, $id) {
        $target = UserGroup::find($id);

        //begin back same page after update
        $qpArr = $request->all();
        $pageNumber = $qpArr['filter'];
        //end back same page after update
        $rules = [
            'name' => 'required|unique:user_group,name,' . $id,
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect('admin/userGroup/' . $id . '/edit' . $pageNumber)
                            ->withInput($request->all())
                            ->withErrors($validator);
        }



        $target->name = $request->name;
        $target->external = !empty($request->external) ? $request->external : '0';

        if ($target->save()) {
            Session::flash('success', __('label.USER_GROUP_UPDATED_SUCCESSFULLY'));
            return redirect('admin/userGroup' . $pageNumber);
        } else {
            Session::flash('error', __('label.USER_GROUP_COULD_NOT_BE_UPDATED'));
            return redirect('admin/userGroup/' . $id . '/edit' . $pageNumber);
        }
    }

    public function destroy(Request $request, $id) {
        $target = UserGroup::find($id);

        //begin back same page after update
        $qpArr = $request->all();
        $pageNumber = !empty($qpArr['page']) ? '?page=' . $qpArr['page'] : '?page=';
        //end back same page after update

        if (empty($target)) {
            Session::flash('error', __('label.INVALID_DATA_ID'));
        }

        //dependency check
        $dependencyArr = [
            'User' => ['1' => 'group_id'],
        ];
        foreach ($dependencyArr as $model => $val) {
            foreach ($val as $index => $key) {
                $namespacedModel = '\\App\\' . $model;
                $dependentData = $namespacedModel::where($key, $id)->first();
                if (!empty($dependentData)) {
                    Session::flash('error', __('label.COULD_NOT_DELETE_DATA_HAS_RELATION_WITH_MODEL', ['model' => $model]));
                    return redirect('admin/userGroup' . $pageNumber);
                }
            }
        }
        //end :: dependency check

        if ($target->delete()) {
            Session::flash('error', __('label.USER_GROUP_DELETED_SUCCESSFULLY'));
        } else {
            Session::flash('error', __('label.USER_GROUP_COULD_NOT_BE_DELETED'));
        }
        return redirect('admin/userGroup' . $pageNumber);
    }

    public function filter(Request $request) {
        $url = 'search=' . urlencode($request->search);
        return Redirect::to('admin/userGroup?' . $url);
    }

}
