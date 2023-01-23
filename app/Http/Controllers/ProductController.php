<?php

namespace App\Http\Controllers;

use Validator;
use App\Product;
use App\ProductDetail;
use App\ProductLog;
use Session;
use Redirect;
use Auth;
use Helper;
use Image;
use File;
use Response;
use DB;
use Illuminate\Http\Request;

class ProductController extends Controller
{


    public function index(Request $request)
    {
        //        $productCategory = Common::getAllProductCategory();
        //passing param for custom function
        $qpArr = $request->all();

        $nameArr = Product::select('name')->orderBy('name', 'asc')->get();

        $targetArr = ProductDetail::join('product', 'product.id', '=', 'product_detail.product_id')
            ->select('product.*', 'product_detail.image_url');

        //begin filtering
        $searchText = $request->search;
        if (!empty($searchText)) {
            $targetArr->where(function ($query) use ($searchText) {
                $query->where('product.name', 'LIKE', '%' . $searchText . '%')
                    ->orWhere('product.slug', 'LIKE', '%' . $searchText . '%');
            });
        }
        //end filtering


        $targetArr = $targetArr->orderBy('product.id', 'desc')->paginate(Session::get('paginatorCount'));

        //change page number after delete if no data has current page
        if ($targetArr->isEmpty() && isset($qpArr['page']) && ($qpArr['page'] > 1)) {
            $page = ($qpArr['page'] - 1);
            return redirect('/product?page=' . $page);
        }

        return view('product.index')->with(compact('qpArr', 'targetArr', 'nameArr'));
    }

    public function create(Request $request)
    {
        //passing param for custom function
        $qpArr = $request->all();

        return view('product.create')->with(compact('qpArr'));
    }

    //store
    public function store(Request $request)
    {
        //passing param for custom function
        $qpArr = $request->all();
        $pageNumber = $qpArr['filter'];
        $message = [];
        $rules = [
            'name' => 'required|unique:product',
            'quantity' => 'required',
            'price' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules, $message);

        if ($validator->fails()) {
            return redirect('product/create' . $pageNumber)
                ->withInput($request->all)
                ->withErrors($validator);
        }

        $target = new Product;
        $target->name = $request->name;
        $target->slug = Helper::generateSlug($request->name);
        $target->quantity = $request->quantity;
        $target->price = $request->price;
        $target->status = $request->status;

        DB::beginTransaction();
        try {

            if ($target->save()) {
                $newTarget = new ProductDetail;
                $newTarget->product_id = $target->id;
                $newTarget->description = $request->description ?? '';
                $newTarget->features = $request->features ?? '';
                $newTarget->image_url = $request->image_url ?? '';
                $newTarget->save();
            }

            DB::commit();
            session()->flash('success', __('label.PRODUCT_CREATED_SUCCESSFULLY'));
            return redirect('product');
        } catch (\Throwable $e) {
            DB::rollback();

            print_r($e->getMessage());
            session()->flash('error', __('label.PRODUCT_COULD_NOT_BE_CREATED'));
            return redirect('product/create' . $pageNumber);
        }
    }

    public function edit(Request $request, $id)
    {
        $target = Product::find($id);
        //passing param for custom function
        $qpArr = $request->all();

        $targetDetail = ProductDetail::where('product_id', $id)->select('*')->first();

        return view('product.edit')->with(compact(
            'qpArr',
            'target',
            'targetDetail'
        ));
    }

    //update
    public function update(Request $request, $id)
    {
        $target = Product::find($id);
        //begin back same page after update
        $qpArr = $request->all();
        $pageNumber = $qpArr['filter'];

        $message = [];
        $rules = [
            'name' => 'required|unique:product,name,' . $id,
            'quantity' => 'required',
            'price' => 'required',
        ];

        //Validation Rules for FSC Certification


        $validator = Validator::make($request->all(), $rules, $message);

        if ($validator->fails()) {
            return redirect('product/' . $id . '/edit' . $pageNumber)
                ->withInput($request->all)
                ->withErrors($validator);
        }

        $target->name = $request->name;
        $target->slug = Helper::generateSlug($request->name);
        $target->quantity = $request->quantity;
        $target->price = $request->price;
        $target->status = $request->status;

        //        print_r($target);exit;
        DB::beginTransaction();
        try {
            if ($target->save()) {
                ProductDetail::where('product_id', $target->id)->update([
                    'description' => $request->description ?? '',
                    "features" => $request->features ?? '',
                    'image_url' => $request->image_url ?? '',
                ]);
            }

            DB::commit();
            session()->flash('success', __('label.PRODUCT_UPDATED_SUCCESSFULLY'));
            return redirect('product' . $pageNumber);
        } catch (\Throwable $e) {
            DB::rollback();
            session()->flash('error', __('label.PRODUCT_COULD_NOT_BE_UPDATED'));
            return redirect('product/' . $id . '/edit' . $pageNumber);
        }
    }

    public function destroy(Request $request, $id)
    {
        $target = Product::find($id);
        //begin back same page after update
        $qpArr = $request->all();
        $pageNumber = !empty($qpArr['page']) ? '?page=' . $qpArr['page'] : '?page=';
        //end back same page after update

        if (empty($target)) {
            Session::flash('error', __('label.INVALID_DATA_ID'));
        }


        DB::beginTransaction();
        try {
            if ($target->delete()) {
                ProductDetail::where('product_id', $id)->delete();
            }
            DB::commit();
            Session::flash('error', __('label.PRODUCT_DELETED_SUCCESSFULLY'));
        } catch (\Throwable $e) {
            DB::rollback();
            Session::flash('error', __('label.PRODUCT_COULD_NOT_BE_DELETED'));
        }
        return redirect('product' . $pageNumber);
    }

    public function filter(Request $request)
    {
        $url = 'search=' . urlencode($request->search);
        return Redirect::to('product?' . $url);
    }
}
