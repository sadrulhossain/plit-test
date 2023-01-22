<?php

namespace App\Http\Controllers;

use App\User;
use App\UserGroup;
use App\Department;
use App\Designation;
use App\Branch;
use App\Bank;
use App\Retailer;
use App\WhToLocalWhManager;
use App\WarehouseToSr;
use App\TmToWarehouse;
use Session;
use Redirect;
use Auth;
use File;
use URL;
use Helper;
use Hash;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller {

    public function __construct() {
        Validator::extend('complexPassword', function ($attribute, $value, $parameters) {
            $password = $parameters[1];

            if (preg_match('/^\S*(?=\S{8,})(?=\S*[a-z])(?=\S*[!@#$%^&*()])(?=\S*[\d])\S*$/', $password)) {
                return true;
            }
            return false;
        });
    }

    public function index(Request $request) {
        //passing param for custom function
        $qpArr = $request->all();

        $userGroupArr = UserGroup::whereNotIn('id',[9,18,19])->pluck('name', 'id')->toArray();
        $groupList = array('0' => __('label.SELECT_USER_GROUP_OPT')) + $userGroupArr;

        $departmentList = array('0' => __('label.SELECT_DEPARTMENT_OPT')) + Department::orderBy('order', 'asc')->pluck('short_name', 'id')->toArray();

        $targetArr = User::join('user_group', 'user_group.id', '=', 'users.group_id')
                ->join('department', 'department.id', '=', 'users.department_id')
                ->join('designation', 'designation.id', '=', 'users.designation_id')
                ->whereNotIn('user_group.id',[9,18,19])
                ->select('user_group.name as group_name', 'users.group_id', 'users.id', 'users.first_name'
                        , 'users.last_name', 'users.username', 'users.photo', 'users.status', 'designation.title as designation_name'
                        , 'department.name as department_name', 'users.employee_id', 'users.email'
                        , 'users.phone')
                ->orderBy('users.group_id', 'asc');

        //begin filtering
        $searchText = $request->search;
        $nameArr = User::select('username')->orderBy('group_id', 'asc')->get();
        $userDepartmentOption = array('0' => __('label.SELECT_DEPARTMENT_OPT')) + Department::orderBy('order', 'asc')->pluck('name', 'id')->toArray();
        $designationList = array('0' => __('label.SELECT_DESIGNATION_OPT')) + Designation::orderBy('order', 'asc')->pluck('title', 'id')->toArray();
        $status = array('0' => __('label.SELECT_STATUS_OPT')) + ['1' => __('label.ACTIVE'), '2' => __('label.INACTIVE')];

        if (!empty($searchText)) {
            $targetArr->where(function ($query) use ($searchText) {
                $query->where('users.username', 'LIKE', '%' . $searchText . '%');
            });
        }

        if (!empty($request->user_group)) {
            $targetArr = $targetArr->where('users.group_id', '=', $request->user_group);
        }
        if (!empty($request->department)) {
            $targetArr = $targetArr->where('users.department_id', '=', $request->department);
        }
        if (!empty($request->designation)) {
            $targetArr = $targetArr->where('users.designation_id', '=', $request->designation);
        }
        if (!empty($request->status)) {
            $targetArr = $targetArr->where('users.status', '=', $request->status);
        }
        //end filtering

        $targetArr = $targetArr->paginate(session()->get('paginatorCount'));

        //change page number after delete if no data has current page
        if ($targetArr->isEmpty() && isset($qpArr['page']) && ($qpArr['page'] > 1)) {
            $page = ($qpArr['page'] - 1);
            return redirect('/admin/user?page=' . $page);
        }
        return view('user.index')->with(compact('qpArr', 'targetArr', 'groupList', 'departmentList', 'nameArr', 'userDepartmentOption', 'designationList', 'status'));
    }

    public function create(Request $request) {
        //passing param for custom function
        $qpArr = $request->all();
        $userGroupArr = UserGroup::where('id', '<>', 9)->whereNotIn('id',[9,18,19])->orderBy('id', 'asc')->pluck('name', 'id', 'asc')->toArray();

        $groupList = array('0' => __('label.SELECT_USER_GROUP_OPT')) + $userGroupArr;
        $departmentList = array('0' => __('label.SELECT_DEPARTMENT_OPT')) + Department::where('status', '1')->orderBy('order', 'asc')->pluck('name', 'id')->toArray();
        $designationList = array('0' => __('label.SELECT_DESIGNATION_OPT')) + Designation::where('status', '1')->orderBy('order', 'asc')->pluck('title', 'id')->toArray();

        $supervisorArr = User::join('designation', 'designation.id', '=', 'users.designation_id')
                        ->where('users.status', '1')
                        ->orderBy('users.group_id', 'asc')
                        ->select(DB::raw("CONCAT(users.employee_id,'-',users.first_name,' ',users.last_name,' (',designation.short_name,')') AS name"), 'users.id')
                        ->pluck('users.name', 'users.id')->toArray();
        $supervisorList = array('0' => __('label.SELECT_SUPERVISOR_OPT')) + $supervisorArr;

        return view('user.create')->with(compact(
                                'qpArr', 'groupList', 'departmentList', 'designationList', 'supervisorList'
        ));
    }

    public function store(Request $request) {

        //passing param for custom function
        $qpArr = $request->all();
        $pageNumber = $qpArr['filter'];

        $rules = [
            'group_id' => 'required|not_in:0',
            'department_id' => 'required|not_in:0',
            'designation_id' => 'required|not_in:0',
            'employee_id' => 'required|unique:users',
            'username' => 'required|unique:users|alpha_num',
            'password' => 'required|complex_password:,' . $request->password,
            'conf_password' => 'required|same:password'
        ];

        if (!empty($request->photo)) {
            $rules['photo'] = 'max:1024|mimes:jpeg,png,jpg';
        }

        $messages = array(
            'password.complex_password' => __('label.WEAK_PASSWORD_FOLLOW_PASSWORD_INSTRUCTION'),
        );

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {

            return redirect('admin/user/create' . $pageNumber)
                            ->withInput($request->except('photo', 'password', 'conf_password'))
                            ->withErrors($validator);
        }

        //image crop image and save
        $imgName = null;
        if (!empty($request->crop_photo)) {
            $imgName = auth()->user()->id . uniqid() . ".png";
            $path = public_path() . "/uploads/user/" . $imgName;
            $croppedImg = $request->crop_photo;
            $img = substr($croppedImg, strpos($croppedImg, ",") + 1);
            $data = base64_decode($img);
            $success = file_put_contents($path, $data);
        }


        $target = new User;
        $target->group_id = $request->group_id;
        $target->department_id = $request->department_id;
        $target->designation_id = $request->designation_id;
        $target->employee_id = $request->employee_id;
        $target->first_name = $request->first_name;
        $target->last_name = $request->last_name;
        $target->nick_name = $request->nick_name;
        $target->supervisor_id = $request->supervisor_id;
        $target->email = $request->email;
        $target->phone = $request->phone;
        $target->username = $request->username;
        $target->password = Hash::make($request->password);
        $target->photo = !empty($imgName) ? $imgName : '';
        $target->status = $request->status;

        if ($target->save()) {
            session()->flash('success', __('label.USER_CREATED_SUCCESSFULLY'));
            return redirect('admin/user');
        } else {
            session()->flash('error', __('label.USER_COULD_NOT_BE_CREATED'));
            return redirect('admin/user/create' . $pageNumber);
        }
    }

    public function edit(Request $request, $id) {
        $target = User::find($id);

        if (empty($target)) {
            session()->flash('error', trans('label.INVALID_DATA_ID'));
            return redirect('admin/user');
        }

        //passing param for custom function
        $qpArr = $request->all();

        $userGroupArr = UserGroup::where('id', '<>', 9)->whereNotIn('id',[9,18,19])->orderBy('id', 'asc')->pluck('name', 'id', 'asc')->toArray();

        $groupList = array('0' => __('label.SELECT_USER_GROUP_OPT')) + $userGroupArr;
        $departmentList = array('0' => __('label.SELECT_DEPARTMENT_OPT')) + Department::where('status', '1')->orderBy('order', 'asc')->pluck('name', 'id')->toArray();
        $designationList = array('0' => __('label.SELECT_DESIGNATION_OPT')) + Designation::where('status', '1')->orderBy('order', 'asc')->pluck('title', 'id')->toArray();

        $supervisorArr = User::join('designation', 'designation.id', '=', 'users.designation_id')
                        ->where('users.status', '1')
                        ->orderBy('users.group_id', 'asc')
                        ->select(DB::raw("CONCAT(users.employee_id,'-',users.first_name,' ',users.last_name,' (',designation.short_name,')') AS name"), 'users.id')
                        ->pluck('users.name', 'users.id')->toArray();
        $supervisorList = array('0' => __('label.SELECT_SUPERVISOR_OPT')) + $supervisorArr;

        return view('user.edit')->with(compact(
                                'target', 'qpArr', 'groupList', 'departmentList', 'designationList', 'supervisorList'
        ));
    }

    public function update(Request $request, $id) {

        // Ajax Request validation
        if ($request->ajax()) {
            $request->validate([
                'present_address' => 'string|min:10',
                'permanent_address' => 'string|min:10',
                'alternative_contacts' => 'integer|min:11',
                'nid_passport' => 'integer|min:9',
                    ], [
                'present_address.string' => 'Present address is too short! Minimum 10 character long!',
                'permanent_address.string' => 'Permanent address is too short! Minimum 10 character long!',
                'alternative_contacts.integer' => 'Contact number must be a number!',
                'alternative_contacts.integer' => 'Contact number in too short! Minimum 11 digit long!',
                'nid_passport.integer' => 'NID/Passport must be a number!',
                'nid_passport.integer' => 'NID/Passport in too short! Minimum 9 digit long!',
            ]);
        }



        $target = User::find($id);
        //begin back same page after update
        $qpArr = $request->all();
        $pageNumber = $qpArr['filter'];
        //end back same page after update

        $rules = [
            'group_id' => 'required|not_in:0',
            'department_id' => 'required|not_in:0',
            'designation_id' => 'required|not_in:0',
            'employee_id' => 'required|unique:users,employee_id,' . $id,
            'username' => 'required|alpha_num|unique:users,username,' . $id,
            'conf_password' => 'same:password',
        ];

        if (!empty($request->password)) {
            $rules['password'] = 'complex_password:,' . $request->password;
            $rules['conf_password'] = 'same:password';
        }

        if (!empty($request->photo)) {
            $rules['photo'] = 'max:1024|mimes:jpeg,png,gif,jpg';
        }

        $messages = array(
            'password.complex_password' => __('label.WEAK_PASSWORD_FOLLOW_PASSWORD_INSTRUCTION'),
        );

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect('admin/user/' . $id . '/edit' . $pageNumber)
                            ->withInput($request->all)
                            ->withErrors($validator);
        }
        //image resize and save
        $imgName = null;
        if (!empty($request->crop_photo)) {
            if (!empty($target->photo)) {

                $prevfileName = 'public/uploads/user/' . $target->photo;

                if (File::exists($prevfileName)) {
                    File::delete($prevfileName);
                }
            }

            $imgName = auth()->user()->id . uniqid() . ".png";
            $path = public_path() . "/uploads/user/" . $imgName;
            $croppedImg = $request->crop_photo;
            $img = substr($croppedImg, strpos($croppedImg, ",") + 1);
            $data = base64_decode($img);
            $success = file_put_contents($path, $data);
        }


        $target->group_id = $request->group_id;
        $target->department_id = $request->department_id;
        $target->designation_id = $request->designation_id;
        $target->employee_id = $request->employee_id;
        $target->first_name = $request->first_name;
        $target->last_name = $request->last_name;
        $target->nick_name = $request->nick_name;
        $target->supervisor_id = $request->supervisor_id;
        $target->email = $request->email;
        $target->phone = $request->phone;
        $target->username = $request->username;
        if (!empty($request->password)) {
            $target->password = Hash::make($request->password);
        }
        $target->photo = !empty($imgName) ? $imgName : $target->photo;
        $target->status = $request->status;

        if ($target->save()) {

            session()->flash('success', __('label.USER_UPDATED_SUCCESSFULLY'));
            return redirect('admin/user' . $pageNumber);
        } else {
            session()->flash('error', __('label.USER_COULD_NOT_BE_UPDATED'));
            return redirect('admin/user/' . $id . '/edit' . $pageNumber);
        }
    }

    public function destroy(Request $request, $id) {
        $target = User::find($id);
        //begin back same page after update
        $qpArr = $request->all();
        $pageNumber = !empty($qpArr['page']) ? '?page=' . $qpArr['page'] : '?page=';
        //end back same page after update

        if (empty($target)) {
            session()->flash('error', __('label.INVALID_DATA_ID'));
        }

        //dependency
        //dependency check
        $dependencyArr = [
            'ContactDesignation' => ['1' => 'created_by', '2' => 'updated_by'],
            'User' => ['1' => 'created_by', '2' => 'updated_by', '3' => 'supervisor_id'],
            'Department' => ['1' => 'created_by', '2' => 'updated_by'],
            'Designation' => ['1' => 'created_by', '2' => 'updated_by'],
            'Invoice' => ['1' => 'updated_by'],
            'Product' => ['1' => 'created_by', '2' => 'updated_by'],
            'ProductCategory' => ['1' => 'created_by', '2' => 'updated_by'],
            'SupplierToProduct' => ['1' => 'created_by'],
            'SignatoryInfo' => ['1' => 'updated_by'],
            'Supplier' => ['1' => 'created_by', '2' => 'updated_by'],
            'SupplierClassification' => ['1' => 'created_by', '2' => 'updated_by'],
        ];
        foreach ($dependencyArr as $model => $val) {
            foreach ($val as $index => $key) {
                $namespacedModel = '\\App\\' . $model;
                $dependentData = $namespacedModel::where($key, $id)->first();
                if (!empty($dependentData)) {
                    session()->flash('error', __('label.COULD_NOT_DELETE_DATA_HAS_RELATION_WITH_MODEL', ['model' => $model]));
                    return redirect('admin/user' . $pageNumber);
                }
            }
        }
        //end :: dependency check

        $fileName = 'public/uploads/user/' . $target->photo;
        if (File::exists($fileName)) {
            File::delete($fileName);
        }

        if ($target->delete()) {
            session()->flash('error', __('label.USER_DELETED_SUCCESSFULLY'));
        } else {
            session()->flash('error', __('label.USER_COULD_NOT_BE_DELETED'));
        }
        return redirect('admin/user' . $pageNumber);
    }

    public function filter(Request $request) {
        $url = 'search=' . urlencode($request->search) . '&user_group=' . $request->user_group . '&department=' . $request->department . '&designation=' . $request->designation . '&status=' . $request->status;
        return redirect('admin/user?' . $url);
    }

    public function changePassword($id = '') {
        if (!empty($id)) {
            $target = User::find($id);
            return view('user.changePassword', compact('target'));
        } else {
            return view('user.changePassword');
        }
    }

    public function updatePassword(Request $request) {
        $target = User::find($request->id ?? Auth::user()->id);
        $rules = [
            'password' => 'required|complex_password:,' . $request->password,
            'conf_password' => 'required',
        ];
        $messages = array(
            'password.complex_password' => __('label.WEAK_PASSWORD_FOLLOW_PASSWORD_INSTRUCTION'),
        );
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return redirect('admin/' . $target->id . '/changePassword')
                            ->withInput($request->except('current_password', 'password', 'conf_password'))
                            ->withErrors($validator);
        }

        $target->password = Hash::make($request->password);
        if ($target->save()) {
            session()->flash('success', __('label.PASSWORD_UPDATED_SUCCESSFULLY'));
        } else {
            session()->flash('error', __('label.PASSWORD_COULD_NOT_BE_UPDATED'));
        }
        return redirect('/admin/user');
        return view('user.changePassword');
    }

    public function setRecordPerPage(Request $request) {
        $referrerArr = explode('?', url()->previous());
        $queryStr = '';
        if (!empty($referrerArr[1])) {
            $queryParam = explode('&', $referrerArr[1]);
            foreach ($queryParam as $item) {
                $valArr = explode('=', $item);
                if ($valArr[0] != 'page') {
                    $queryStr .= $item . '&';
                }
            }
        }

        $url = $referrerArr[0] . '?' . trim($queryStr, '&');

        if ($request->record_per_page > 999) {
            session()->flash('error', __('label.NO_OF_RECORD_MUST_BE_LESS_THAN_999'));
            return redirect($url);
        }

        if ($request->record_per_page < 1) {
            session()->flash('error', __('label.NO_OF_RECORD_MUST_BE_GREATER_THAN_1'));
            return redirect($url);
        }

        $request->session()->put('paginatorCount', $request->record_per_page);
        return redirect($url);
    }

    public function getCheckCrmLeader(Request $request) {
        $target = User::where('for_crm_leader', '1')->first();
        $name = $target->first_name . ' ' . $target->last_name;
        return response()->json(['name' => $name]);
    }

    public function allProfile(Request $request) {

        $id = auth()->user()->id;

        $user = User::select('group_id')->where('id', $id)->first();

        $qpArr = $request->all();
        $userInfoData = User::join('user_group', 'user_group.id', '=', 'users.group_id')
                ->join('department', 'department.id', '=', 'users.department_id')
                ->join('designation', 'designation.id', '=', 'users.designation_id')
                ->select('users.id as users_id', 'users.email', 'users.photo', 'users.phone', 'users.first_name', 'users.last_name', 'users.nick_name', 'users.username', DB::raw("CONCAT(users.first_name, ' ', users.last_name) as full_name"), 'department.name as department', 'designation.title as designation', 'user_group.name as user_group')
                ->where('users.status', '1')
                ->where('users.id', $id)
                ->first();
//return $userInfoData;
        $whArr = [];
        if (auth()->user()->group_id == 12) {
            $whArr = WhToLocalWhManager::join('warehouse', 'warehouse.id', 'wh_to_local_wh_manager.warehouse_id')->where('wh_to_local_wh_manager.lwm_id', auth()->user()->id)->pluck('warehouse.name', 'warehouse.id')->toArray();
        } elseif (auth()->user()->group_id == 14) {
            $whArr = WarehouseToSr::join('warehouse', 'warehouse.id', 'warehouse_to_sr.warehouse_id')->where('warehouse_to_sr.sr_id', auth()->user()->id)->pluck('warehouse.name', 'warehouse.id')->toArray();
        } elseif (auth()->user()->group_id == 15) {
            $whArr = TmToWarehouse::join('warehouse', 'warehouse.id', 'tm_to_warehouse.warehouse_id')->where('tm_to_warehouse.tm_id', auth()->user()->id)->pluck('warehouse.name', 'warehouse.id')->toArray();
        }



        return view('user.profile')->with(compact('userInfoData', 'qpArr', 'whArr'));
    }

    public function retailerDistributorProfile(Request $request) {
        $id = auth()->user()->id;

        $user = User::select('group_id')->where('id', $id)->first();

        $qpArr = $request->all();
        $target = Retailer::join('users', 'users.id', '=', 'retailer.user_id')->where('approval_status', '1')
                ->where('users.id', $id);

        $userInfoData = $target->leftJoin('division', 'division.id', '=', 'retailer.division')
                ->leftJoin('district', 'district.id', '=', 'retailer.district')
                ->leftJoin('thana', 'thana.id', '=', 'retailer.thana')
                ->leftJoin('zone', 'zone.id', '=', 'retailer.zone_id')
                ->select('retailer.name', 'retailer.username', 'retailer.address', 'retailer.contact_person_data'
                        , 'retailer.status', 'retailer.code', 'zone.name as zone', 'retailer.nid_passport'
                        , 'retailer.logo', 'retailer.owner_name', 'retailer.avg_monthly_transaction_value'
                        , 'retailer.has_bank_account', 'retailer.infrastructure_type'
                        , 'division.name as division', 'district.name as district'
                        , 'thana.name as thana')
                ->first(); 
        
        $contactPersonArr = !empty($userInfoData->contact_person_data) ? json_decode($userInfoData->contact_person_data, true) : [];

        return view('user.retailerDistributorProfile')->with(compact('userInfoData', 'qpArr', 'contactPersonArr'));
    }

    public function myProfile(Request $request) {
        if (in_array(Auth::user()->group_id, [18, 19])) {
            return $this->retailerDistributorProfile($request);
        } else {
            return $this->allProfile($request);
        }
    }

    public function getUserAdditionalInfo(Request $request) {

        $target = User::join('user_group', 'user_group.id', '=', 'users.group_id')
                        ->join('department', 'department.id', '=', 'users.department_id')
                        ->join('designation', 'designation.id', '=', 'users.designation_id')->select('user_group.name as group_name', 'users.group_id', 'users.id', 'users.first_name', 'users.last_name', 'users.photo'
                                , 'users.username', 'users.photo', 'users.status', 'designation.title as designation_name', 'department.name as department_name'
                                , 'users.employee_id', 'present_address', 'permanent_address', 'alternative_contacts', 'nid_passport')
                        ->where('users.id', $request->user_id)->first();
        $view = view('user.addAdditionalInfo', compact('target'))->render();
        return response()->json(['html' => $view]);
    }

    public function setUserAdditionalInfo(Request $request) {
        $rules = [
            'nid_passport' => 'required',
        ];
        $messages = array();

        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return Response::json(array('heading' => __('label.VALIDATION_ERROR'), 'message' => $validator->errors()), 400);
        }


        $target = User::find($request->id);
        $target->present_address = $request->present_address;
        $target->permanent_address = $request->permanent_address;
        $target->alternative_contacts = $request->alternative_contacts;
        $target->nid_passport = $request->nid_passport;

        if ($target->save()) {
            return Response::json(array('heading' => 'Success', 'message' => __('label.USER_INFO_UPDATED_SUCCESSFULLY')), 201);
        } else {
            return Response::json(array('heading' => 'Error', 'message' => __('label.USER_INFO_UPDATED_UNSUCCESSFULL')), 401);
        }
    }

}
