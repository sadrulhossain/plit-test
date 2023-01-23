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

class ProductLogController extends Controller
{


    public function index(Request $request)
    {
        //passing param for custom function
        $qpArr = $request->all();

        $nameArr = Product::select('name')->orderBy('name', 'asc')->get();

        $targetArr = ProductLog::join('product', 'product.id', '=', 'product_log.product_id')
            ->join('product_detail', function ($join) {
                $join->on('product_detail.product_id', '=', 'product_log.product_id');
            })
            ->join('users', 'users.id', 'product_log.taken_by')
            ->select('product.name', 'product_detail.image_url', 'product_log.*', 'users.name as action_taken_by');

        //begin filtering
        $searchText = $request->search;
        if (!empty($searchText)) {
            $targetArr->where(function ($query) use ($searchText) {
                $query->where('product.name', 'LIKE', '%' . $searchText . '%')
                    ->orWhere('product.slug', 'LIKE', '%' . $searchText . '%');
            });
        }
        //end filtering


        $targetArr = $targetArr->orderBy('product_log.taken_at', 'desc')->paginate(Session::get('paginatorCount'));

        //change page number after delete if no data has current page
        if ($targetArr->isEmpty() && isset($qpArr['page']) && ($qpArr['page'] > 1)) {
            $page = ($qpArr['page'] - 1);
            return redirect('/productLog?page=' . $page);
        }

        return view('productLog.index')->with(compact('qpArr', 'targetArr', 'nameArr'));
    }

    
    public function filter(Request $request)
    {
        $url = 'search=' . urlencode($request->search);
        return Redirect::to('productLog?' . $url);
    }
}
