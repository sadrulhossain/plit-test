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
use App\Interfaces\ProductInterface;
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
            ->select('product.*', 'product_detail.image');

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
    public function store(Request $request, Product $product)
    {
        $message = [];
        $request->merge(['slug' => Helper::generateSlug($request->name)]);
        //passing param for custom function
        $qpArr = $request->all();
        $pageNumber = $qpArr['filter'];
        $rules = [
            'name' => 'required|unique:product,name',
            'slug' => 'required|unique:product,slug',
            'quantity' => 'required',
            'price' => 'required',
        ];
        if (!empty($request->photo)) {
            $rules['image'] = 'max:1024|mimes:jpeg,png,jpg';
        }
        $message['slug.unique'] = __('label.THE_SLUG_GENERATED_FROM_THE_NAME_HAS_ALREADY_BEEN_TAKEN');

        $validator = Validator::make($request->all(), $rules, $message);

        if ($validator->fails()) {
            echo '<pre>';
            print_r($validator->errors());
            exit;
            return redirect('product/create' . $pageNumber)
                ->withInput($request->except('image'))
                ->withErrors($validator);
        }

        $product->name = $request->name;
        $product->slug = $request->slug;
        $product->quantity = $request->quantity;
        $product->price = $request->price;
        $product->status = $request->status;

        DB::beginTransaction();
        try {

            if ($product->save()) {
                $imgName = null;
                $file = $request->file('image');
                if (!empty($file)) {
                    $imgName = uniqid() . "_" . Auth::user()->id . "." . $file->getClientOriginalExtension();
                    $uploadSuccess = $file->move('public/uploads/product', $imgName);
                }

                $newTarget = new ProductDetail;
                $newTarget->product_id = $product->id;
                $newTarget->description = $request->description ?? '';
                $newTarget->features = $request->features ?? '';
                $newTarget->image = $imgName ?? '';
                $newTarget->save();
            }

            DB::commit();
            session()->flash('success', __('label.PRODUCT_CREATED_SUCCESSFULLY'));
            return redirect('product');
        } catch (\Throwable $e) {
            DB::rollback();
            session()->flash('error', __('label.PRODUCT_COULD_NOT_BE_CREATED'));
            return redirect('product/create' . $pageNumber);
        }
    }

    public function edit(Request $request, Product $product)
    {
        $id = $product->id;
        //passing param for custom function
        $qpArr = $request->all();

        $productDetail = ProductDetail::where('product_id', $id)->select('*')->first();

        return view('product.edit')->with(compact(
            'qpArr',
            'product',
            'productDetail'
        ));
    }

    //update
    public function update(Request $request, Product $product)
    {
        $id = $product->id;
        $productDetail = ProductDetail::where('product_id', $id)->select('*')->first();
        $request->merge(['slug' => Helper::generateSlug($request->name)]);
        //begin back same page after update
        $qpArr = $request->all();
        $pageNumber = $qpArr['filter'];

        $message = [];
        $rules = [
            'name' => 'required|unique:product,name,' . $id,
            'slug' => 'required|unique:product,slug,' . $id,
            'quantity' => 'required',
            'price' => 'required',
        ];

        if (!empty($request->photo)) {
            $rules['image'] = 'max:1024|mimes:jpeg,png,jpg';
        }
        $message['slug.unique'] = __('label.THE_SLUG_GENERATED_FROM_THE_NAME_HAS_ALREADY_BEEN_TAKEN');

        $validator = Validator::make($request->all(), $rules, $message);

        if ($validator->fails()) {
            return redirect('product/' . $id . '/edit' . $pageNumber)
                ->withInput($request->all)
                ->withErrors($validator);
        }

        $product->name = $request->name;
        $product->slug = Helper::generateSlug($request->name);
        $product->quantity = $request->quantity;
        $product->price = $request->price;
        $product->status = $request->status;

        //        print_r($product);exit;
        DB::beginTransaction();
        try {
            if ($product->save()) {
                $imgName = null;
                if (!empty($request->image)) {
                    $prevfileName = 'public/uploads/product/' . $productDetail->image;

                    if (File::exists($prevfileName)) {
                        File::delete($prevfileName);
                    }
                }

                $file = $request->file('image');
                if (!empty($file)) {
                    $imgName = uniqid() . "_" . Auth::user()->id . "." . $file->getClientOriginalExtension();
                    $uploadSuccess = $file->move('public/uploads/product', $imgName);
                }

                ProductDetail::where('product_id', $product->id)->update([
                    'description' => $request->description ?? '',
                    "features" => $request->features ?? '',
                    'image' => $imgName ?? $productDetail->image,
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

    public function destroy(Request $request, Product $product)
    {
        $id = $product->id;
        //begin back same page after update
        $qpArr = $request->all();
        $pageNumber = !empty($qpArr['page']) ? '?page=' . $qpArr['page'] : '?page=';
        //end back same page after update

        if (empty($product)) {
            Session::flash('error', __('label.INVALID_DATA_ID'));
        }


        DB::beginTransaction();
        try {
            if ($product->delete()) {
                $productDetail = ProductDetail::where('product_id', $id)->select('*')->first();
                $prevfileName = 'public/uploads/product/' . $productDetail->image;

                if (File::exists($prevfileName)) {
                    File::delete($prevfileName);
                }
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
