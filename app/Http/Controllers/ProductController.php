<?php

namespace App\Http\Controllers;

use Validator;
use App\Product;
use App\ProductCategory;
use App\ProductUnit;
use App\Brand;
use App\AttributeType;
use App\ProductAttribute;
use App\ProductToAttribute;
use App\ProductSKUCode;
use App\ProductTag;
use App\ProductToTag;
use App\ProductImage;
use App\ProductToProductOffer;
use App\ProductType;
use Session;
use Redirect;
use Auth;
use Common;
use Input;
use Helper;
use Image;
use File;
use Response;
use DB;
use Illuminate\Http\Request;

class ProductController extends Controller {

    private $fileSize = '102400';
    private $productCategoryArr = [];

    public function findParentCategory($parentId = null, $id = null) {
        $dataArr = ProductCategory::find($parentId);
        $this->productCategoryArr[$id] = isset($this->productCategoryArr[$id]) ? $this->productCategoryArr[$id] : '';
        if (!empty($dataArr['name'])) {
            $this->productCategoryArr[$id] = $dataArr['name'] . ' &raquo; ' . $this->productCategoryArr[$id];
        }

        if (!empty($dataArr['parent_id'])) {
            $this->findParentCategory($dataArr['parent_id'], $id);
        }

        //exclude last &raquo; sign
        $this->productCategoryArr[$id] = trim($this->productCategoryArr[$id], ' &raquo; ');
        return true;
    }

    public function index(Request $request) {
//        $productCategory = Common::getAllProductCategory();
        //passing param for custom function
        $qpArr = $request->all();

        $nameArr = Product::select('name')->orderBy('name', 'asc')->get();
        $productCategoryArr = Common::getAllProductCategory();
        $productTypeArr = array('0' => __('label.SELECT_TYPE_OPT')) + ProductType::where('status', '1')->orderBy('order', 'asc')->pluck('name', 'id')->toArray();
        //$productCategoryArr = array('0' => __('label.SELECT_PRODUCT_CATEGORY_OPT')) + ProductCategory::orderBy('order', 'asc')->pluck('name', 'id')->toArray();
        $productUnitArr = array('0' => __('label.SELECT_PRODUCT_UNIT_OPT')) + ProductUnit::orderBy('order', 'asc')->pluck('name', 'id')->toArray();
        // $originArr = array('0' => __('label.SELECT_ORIGIN_OPT')) + Country::orderBy('name', 'asc')->pluck('name', 'id')->toArray();


        $targetArr = Product::join('product_category', 'product_category.id', '=', 'product.product_category_id')
                ->join('product_sku_code', 'product_sku_code.product_id', '=', 'product.id')
                ->join('brand', 'brand.id', '=', 'product.brand_id')
                ->leftJoin('product_unit', 'product_unit.id', '=', 'product.product_unit_id')
                ->leftjoin('product_type', 'product_type.id', '=', 'product.product_type_id')
                ->select('product.*', 'product_category.name as product_category', 'product_unit.name as product_unit'
                , 'brand.name as brand', 'product_type.name as product_type', 'product_sku_code.sku');

        //begin filtering
        $searchText = $request->search;
        if (!empty($searchText)) {
            $targetArr->where(function ($query) use ($searchText) {
                $query->where('product.name', 'LIKE', '%' . $searchText . '%')
                        ->orWhere('product_sku_code.sku', 'LIKE', '%' . $searchText . '%')
                        ->orWhere('brand.name', 'LIKE', '%' . $searchText . '%')
                        ->orWhere('product_unit.name', 'LIKE', '%' . $searchText . '%')
                        ->orWhere('product_category.name', 'LIKE', '%' . $searchText . '%');
            });
        }

        if (!empty($request->product_category)) {
            $targetArr = $targetArr->where('product.product_category_id', $request->product_category);
        }



        if (!empty($request->product_unit)) {
            $targetArr = $targetArr->where('product.product_unit_id', $request->product_unit);
        }
        if (!empty($request->product_type)) {
            $targetArr = $targetArr->where('product.product_type_id', $request->product_type);
        }
        //end filtering

        $productIdArr = $targetArr->pluck('product.id', 'product.id')->toArray();

        $targetArr = $targetArr->orderBy('product.id', 'desc')->paginate(Session::get('paginatorCount'));

        //change page number after delete if no data has current page
        if ($targetArr->isEmpty() && isset($qpArr['page']) && ($qpArr['page'] > 1)) {
            $page = ($qpArr['page'] - 1);
            return redirect('/admin/product?page=' . $page);
        }

        return view('product.index')->with(compact('qpArr', 'targetArr', 'productUnitArr', 'productCategoryArr', 'nameArr', 'productTypeArr'));
    }

    public function create(Request $request) {
        //passing param for custom function
        $qpArr = $request->all();
        $productUnitArr = array('0' => __('label.SELECT_PRODUCT_UNIT_OPT')) + ProductUnit::where('status', '1')->orderBy('order', 'asc')->pluck('name', 'id')->toArray();
        $productTypeArr = array('0' => __('label.SELECT_TYPE_OPT')) + ProductType::where('status', '1')->orderBy('order', 'asc')->pluck('name', 'id')->toArray();
        $brandArr = array('0' => __('label.SELECT_BRAND_OPT')) + Brand::where('status', '1')->orderBy('name', 'asc')->pluck('name', 'id')->toArray();

        $productCategoryArr = Common::getAllProductCategory();

        // echo '<pre>';
        // print_r($productCategoryArr);
        // exit;

        return view('product.create')->with(compact('qpArr', 'productCategoryArr', 'productTypeArr', 'brandArr', 'productUnitArr'));
    }

    //store
    public function store(Request $request) {
        //passing param for custom function
        $qpArr = $request->all();
        $pageNumber = $qpArr['filter'];
        $message = [];
        $rules = [
            'name' => 'required|unique:product',
            'product_category_id' => 'required|not_in:0',
            'product_type_id' => 'required|not_in:0',
            'product_unit_id' => 'required|not_in:0',
            'brand_id' => 'required|not_in:0',
            'purchase_price' => 'required',
            'lot_size' => 'required',
            'sku' => 'required',
            'selling_price' => 'required',
            'reorder_level' => 'required',
            'distributor_price' => 'required',
            'discount_price' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules, $message);

        if ($validator->fails()) {
            return Response::json(array('success' => false, 'heading' => 'Validation Error', 'message' => $validator->errors()), 400);
        }

        $target = new Product;
        $target->name = $request->name;
        $target->product_category_id = $request->product_category_id;
        $target->brand_id = $request->brand_id;
        $target->product_unit_id = $request->product_unit_id;
        $target->product_type_id = $request->product_type_id;
//        $target->distributor_price = $request->distributor_price;
//        $target->discount_price = $request->discount_price;
        $target->lot_size = $request->lot_size;
        $target->lot_ratio = $request->lot_ratio;
        $target->youtube_link = $request->youtube_link;
        $target->variant_product = !empty($request->variant_product) ? $request->variant_product : '0';
        $target->description = !empty($request->description) ? $request->description : '';
        $target->status = $request->status;

        DB::beginTransaction();
        try {
            if ($target->save()) {
                if (($target->variant_product == '0') && empty($request->variant_product)) {
                    $newTarget = new ProductSKUCode;
                    $newTarget->sku = $request->sku;
                    $newTarget->product_id = $target->id;
                    $newTarget->attribute = '0';
                    $newTarget->distributor_price = $request->distributor_price;
                    $newTarget->discount_price = $request->discount_price;
                    $newTarget->purchase_price = $request->purchase_price;
                    $newTarget->selling_price = $request->selling_price;
                    $newTarget->reorder_level = $request->reorder_level;
                    $newTarget->available_quantity = '0.00';
                    $newTarget->created_by = Auth::user()->id;
                    $newTarget->created_at = date('Y-m-d H:i:s');
                    $newTarget->save();
                }
            }

            DB::commit();
            return Response::json(array('heading' => 'Success', 'message' => __('label.PRODUCT_CREATED_SUCCESSFULLY')), 200);
        } catch (\Throwable $e) {
            DB::rollback();
            return Response::json(array('success' => false, 'message' => __('label.PRODUCT_COULD_NOT_BE_CREATED')), 401);
        }
    }

    public function edit(Request $request, $id) {
        $target = Product::find($id);
        $targetSku = ProductSKUCode::where('product_id', $id)->where('attribute', '0')->first();
        if (empty($target)) {
            Session::flash('error', trans('label.INVALID_DATA_ID'));
            return redirect('product');
        }

        //passing param for custom function
        $qpArr = $request->all();
        $previousHsCodeArr = json_decode($target->hs_code, true);

        $brandArr = array('0' => __('label.SELECT_BRAND_OPT')) + Brand::where('status', '1')->orderBy('name', 'asc')->pluck('name', 'id')->toArray();
        $productUnitArr = array('0' => __('label.SELECT_PRODUCT_UNIT_OPT')) + ProductUnit::where('status', '1')->orderBy('order', 'asc')->pluck('name', 'id')->toArray();
        $productCategoryArr = Common::getAllProductCategory();
        $productTypeArr = array('0' => __('label.SELECT_TYPE_OPT')) + ProductType::where('status', '1')->orderBy('order', 'asc')->pluck('name', 'id')->toArray();

        return view('product.edit')->with(compact(
                                'qpArr', 'target', 'productCategoryArr', 'brandArr', 'productUnitArr', 'previousHsCodeArr', 'targetSku', 'productTypeArr'
        ));
    }

    //update
    public function update(Request $request) {
        $id = $request->id;
        $target = Product::find($id);
        //begin back same page after update
        $qpArr = $request->all();
        $pageNumber = $qpArr['filter'];

        $message = [];
        $rules = [
            'name' => 'required|unique:product,name,' . $id,
            'product_category_id' => 'required|not_in:0',
            'product_type_id' => 'required|not_in:0',
            'brand_id' => 'required|not_in:0',
            'product_unit_id' => 'required|not_in:0',
            'purchase_price' => 'required',
            'selling_price' => 'required',
            'lot_size' => 'required',
            'sku' => 'required',
            'reorder_level' => 'required',
            'distributor_price' => 'required',
            'discount_price' => 'required',
        ];

        //Validation Rules for FSC Certification


        $validator = Validator::make($request->all(), $rules, $message);

        if ($validator->fails()) {
            return Response::json(array('success' => false, 'heading' => 'Validation Error', 'message' => $validator->errors()), 400);
        }

        $target->name = $request->name;
        $target->product_category_id = $request->product_category_id;
        $target->brand_id = $request->brand_id;
        $target->product_unit_id = $request->product_unit_id;
        $target->product_type_id = $request->product_type_id;
//        $target->distributor_price = $request->distributor_price;
//        $target->discount_price = $request->discount_price;
//        $target->retailer_price = $request->retailer_price;
        $target->lot_size = $request->lot_size;
        $target->lot_ratio = $request->lot_ratio;
        $target->youtube_link = $request->youtube_link;
        $target->variant_product = !empty($request->variant_product) ? $request->variant_product : '0';
        $target->description = !empty($request->description) ? $request->description : '';
        $target->status = $request->status;

//        print_r($target);exit;
        DB::beginTransaction();
        try {
            if ($target->save()) {
                if (($target->variant_product == '0') && empty($request->variant_product)) {
                    ProductSKUCode::where('product_id', $target->id)->where('attribute', '0')->update([
                        'sku' => $request->sku,
                        "purchase_price" => $request->purchase_price,
                        'selling_price' => $request->selling_price,
                        'reorder_level' => $request->reorder_level,
                        'distributor_price' => $request->distributor_price,
                        'discount_price' => $request->discount_price,
                        'available_quantity' => '0.00',
                    ]);
                }
            }

            DB::commit();
            return Response::json(array('success' => true, 'heading' => 'Success', 'message' => __('label.PRODUCT_UPDATED_SUCCESSFULLY')), 200);
        } catch (\Throwable $e) {
            DB::rollback();
//            print_r(json_encode($e));
            return Response::json(array('success' => false, 'heading' => 'Error', 'message' => __('label.PRODUCT_COULD_NOT_BE_UPDATED')), 401);
        }
    }

    public function destroy(Request $request, $id) {
        $target = Product::find($id);
        //begin back same page after update
        $qpArr = $request->all();
        $pageNumber = !empty($qpArr['page']) ? '?page=' . $qpArr['page'] : '?page=';
        //end back same page after update

        if (empty($target)) {
            Session::flash('error', __('label.INVALID_DATA_ID'));
        }

        $dependencyArr = [
//            'ProductSKUCode' => ['1' => 'product_id'],
//            'ProductToAttribute' => ['1' => 'product_id'],
            'ProductToTag' => ['1' => 'product_id'],
            'ProductCheckInDetails' => ['1' => 'product_id'],
                // 'ProductReturn' => ['1' => 'product_id'],
        ];
        foreach ($dependencyArr as $model => $val) {
            foreach ($val as $index => $key) {
                $namespacedModel = '\\App\\' . $model;
                $dependentData = $namespacedModel::where($key, $id)->first();
                if (!empty($dependentData)) {
                    Session::flash('error', __('label.COULD_NOT_DELETE_DATA_HAS_RELATION_WITH_MODEL', ['model' => $model]));
                    return redirect('admin/product' . $pageNumber);
                }
            }
        }
        //end :: dependency check


        if ($target->delete()) {
            ProductSKUCode::where('product_id', $id)->delete();
            Session::flash('error', __('label.PRODUCT_DELETED_SUCCESSFULLY'));
        } else {
            Session::flash('error', __('label.PRODUCT_COULD_NOT_BE_DELETED'));
        }
        return redirect('admin/product' . $pageNumber);
    }

    public function filter(Request $request) {
        $url = 'search=' . urlencode($request->search) . '&product_category=' . $request->product_category
                . '&product_unit=' . $request->product_unit
                . '&product_type=' . $request->product_type;
        return Redirect::to('admin/product?' . $url);
    }

    public function loadProductNameCreate(Request $request) {
        return Common::loadProductName($request);
    }

    public function loadProductNameEdit(Request $request) {
        return Common::loadProductName($request);
    }

    public function getProductPricing(Request $request) {
        $loadView = 'product.showSetProductPricing';
        $loadFooterView = 'admin.setProductPricing.showFooter';
        return Common::getProductPricingSetup($request, $loadView, $loadFooterView);
    }

    public function setProductPricing(Request $request) {
        return Common::setProductPricing($request);
    }

    public function getProductAttribute(Request $request) {
        //product name list
        $productInfo = Product::where('product.id', $request->product_id)
                        ->select('product.id', 'product.name')
                        ->orderBy('product.name', 'asc')->first();

        //product brand list

        $previousAttributeInfo = ProductToAttribute::where('product_id', $request->product_id)->get();

        $previousAttributeTypeArr = [];
        if (!$previousAttributeInfo->isEmpty()) {
            foreach ($previousAttributeInfo as $attributeType) {
                $previousAttributeTypeArr[$attributeType->attribute_type_id] = $attributeType->attribute_type_id;
            }
        }



        $previousProductAttributeArr = [];
        if (!$previousAttributeInfo->isEmpty()) {
            foreach ($previousAttributeInfo as $productAttribute) {
                $previousProductAttributeArr[$productAttribute->attribute_id] = $productAttribute->attribute_id;
            }
        }

        $attributeTypeWiseProductAttributeInfo = ProductAttribute::select('attribute_type_id', 'id', 'name')->get();

        $attributeTypeWiseProductAttributeArr = [];
        if (!$attributeTypeWiseProductAttributeInfo->isEmpty()) {
            foreach ($attributeTypeWiseProductAttributeInfo as $info) {
                $attributeTypeWiseProductAttributeArr[$info->attribute_type_id][$info->id] = $info->name;
            }
        }

        // echo '<pre>';
        // print_r($attributeTypeWiseProductAttributeArr);
        // exit;

        $attributeTypeArr = AttributeType::where('status', '1')->orderBy('name', 'asc')->pluck('name', 'id')->toArray();

        //        echo '<pre>';
        //        print_r($previousAttributeTypeArr);
        //        exit;

        $view = view('product.showSetProductAttribute', compact('request', 'productInfo', 'attributeTypeArr', 'attributeTypeWiseProductAttributeArr', 'previousAttributeTypeArr', 'previousProductAttributeArr'))->render();
        return response()->json(['html' => $view]);
    }

    public function newDataSheetRow(Request $request) {
        $brandId = $request->brand_id;
        $view = view('product.newDataSheetRow', compact('brandId'))->render();
        return response()->json(['html' => $view]);
    }

    public function setProductAttribute(Request $request) {

        if (empty($request->product_attribute)) {
            return Response::json(array('success' => false, 'heading' => __('label.VALIDATION_ERROR'), 'message' => __('label.PLEASE_CHOOSE_ATLEAST_ONE_ATTRIBUTE')), 401);
        } else {
            $i = 0;
            $target = [];
            foreach ($request->product_attribute as $attributeTypeId => $attribute) {
                foreach ($attribute as $attributeId => $id) {
                    $target[$i]['product_id'] = $request->product_id;
                    $target[$i]['attribute_id'] = $id;
                    $target[$i]['attribute_type_id'] = $attributeTypeId;
                    $target[$i]['created_by'] = Auth::user()->id;
                    $target[$i]['created_at'] = date('Y-m-d H:i:s');
                    $i++;
                }
            }
        }
        //delete before inserted
        DB::beginTransaction();
        try {
            ProductToAttribute::where('product_id', $request->product_id)->delete();
            ProductToAttribute::insert($target);
            DB::commit();
            return Response::json(array('heading' => 'Success', 'message' => __('label.PRODUCT_HAS_BEEN_RELATED_TO_ATTRIBUTE_SUCCESSFULLY')), 201);
        } catch (\Throwable $e) {
            DB::rollback();
            return Response::json(array('success' => false, 'message' => __('label.FAILED_TO_RELATE_PRODUCT_TO_ATTRIBUTE')), 401);
        }
    }

    public function getProductSKU(Request $request) {
        //product name list
        $attributeTypeWiseProductAttributeInfo = ProductToAttribute::join('product_attribute', 'product_attribute.id', 'product_to_attribute.attribute_id')
                        ->where('product_id', $request->product_id)->select('product_to_attribute.attribute_type_id', 'product_to_attribute.attribute_id', 'product_attribute.name')->get();

        $attributeTypeWiseProductAttributeArr = [];
        if (!$attributeTypeWiseProductAttributeInfo->isEmpty()) {
            foreach ($attributeTypeWiseProductAttributeInfo as $info) {
                $attributeTypeWiseProductAttributeArr[$info->attribute_type_id][$info->attribute_id] = $info->attribute_id;
            }
        }

        $attributeTypeArr = [];
        $attributeTypeInfo = ProductToAttribute::where('product_id', $request->product_id)->select('attribute_type_id')->distinct('attribute_type_id')->get();
        if (!$attributeTypeInfo->isEmpty()) {
            $i = 0;
            foreach ($attributeTypeInfo as $attributeType) {
                $attributeTypeArr[$i] = $attributeType->attribute_type_id;
                $i++;
            }
        }

        $allSKUAsStringsArr = [];

        if (!empty($attributeTypeWiseProductAttributeArr) && !empty($attributeTypeArr)) {
            $permutations = Common::computePermutations($attributeTypeWiseProductAttributeArr, $attributeTypeArr);
            $allSKUAsStringsArr = array_map(fn($permutation) => implode(',', $permutation), $permutations);
        }

        $productInfo = Product::where('product.id', $request->product_id)
                        ->select('id', 'name', 'brand_id')
                        ->orderBy('product.name', 'asc')->first();

        $productBradInfo = Brand::where('id', $productInfo->brand_id)->select('id', 'name', 'brand_code')->first();
        $skuArr1 = [];
        if (!empty($allSKUAsStringsArr)) {
            foreach ($allSKUAsStringsArr as $sku) {
                $skuArr1[$sku] = [
                    'id' => 0,
                    'attribute' => $sku,
                    'sku' => $productBradInfo->brand_code . '-' . Common::codeWiseSKUVariation($sku),
                    'selling_price' => '',
                    'reorder_level' => '',
                    'available_quantity' => 0
                ];
            }
        }



        $productSKUInfo = ProductSKUCode::where('product_id', $request->product_id)
                        ->select('id', 'attribute', 'sku', 'selling_price', 'reorder_level', 'available_quantity')->get();

        $skuArr2 = [];
        if (!$productSKUInfo->isEmpty()) {
            foreach ($productSKUInfo as $sku) {
                $skuArr2[$sku->attribute] = [
                    'id' => $sku->id,
                    'attribute' => $sku->attribute,
                    'sku' => $sku->sku,
                    'selling_price' => $sku->selling_price,
                    'reorder_level' => $sku->reorder_level,
                    'available_quantity' => $sku->available_quantity
                ];
            }
        }

        $skuArr = array_merge($skuArr1, $skuArr2);

        $view = view('product.showSetProductSKU', compact('request', 'skuArr', 'productInfo'))->render();
        return response()->json(['html' => $view]);
    }

    public function setProductSKU(Request $request) {
        $data = [];
        $i = 0;
        $skuArr = $request->sku_info;

        DB::beginTransaction();
        try {

            if (!empty($skuArr)) {
                foreach ($skuArr as $attributeId => $info) {
                    print_r(json_encode($info));
                    return 0;
                    $sku = ProductSKUCode::where('id', $info['id'])->first();
                    $target = !empty($sku->id) ? ProductSKUCode::find($sku->id) : new ProductSKUCode;
                    $target->product_id = $request->product_id;
                    $target->attribute = $attributeId;
                    $target->sku = $info['sku'];
                    $target->selling_price = $info['selling_price'] ?? 0.00;
                    $target->reorder_level = $info['reorder_level'] ?? 0;
                    $target->available_quantity = $info['available_quantity'];
                    $target->created_at = date('Y-m-d H:i:s');
                    $target->created_by = Auth::user()->id;
                    $target->save();
                }
            }

            return Response::json(array('heading' => 'Success', 'message' => __('label.SKU_GENERATE_AND_SAVE_SUCCESSFULLY')), 201);
        } catch (\Throwable $e) {
            DB::rollback();
            return Response::json(array('success' => false, 'message' => __('label.FAILED_TO_GENERATE_AND_SAVE_SKU')), 401);
        }
    }

    public function getProductTag(Request $request) {

        //product name list
        $productInfo = Product::where('product.id', $request->product_id)
                        ->select('product.id', 'product.name')
                        ->orderBy('product.name', 'asc')->first();

        //product brand list
        $productTagArr = ProductTag::select('id', 'name')->where('status', '1')->orderBy('name', 'asc')->get();

        $assignedTagArr = ProductToTag::where('product_to_tag.product_id', $request->product_id)
                        ->pluck('product_tag_id')->toArray();

        $view = view('product.showSetProductTag', compact('request', 'productInfo', 'productTagArr', 'assignedTagArr'))->render();
        return response()->json(['html' => $view]);
    }

    public function setProductTag(Request $request) {
        $data = [];
        $i = 0;
        $productTags = $request->productTag;
        if (!empty($productTags)) {
            foreach ($productTags as $productTag => $tag) {
                $data[$i]['product_id'] = $request->product_id;
                $data[$i]['product_tag_id'] = $tag;
                $data[$i]['created_by'] = Auth::user()->id;
                $data[$i]['created_at'] = date('Y-m-d H:i:s');
                $i++;
            }
        }

        DB::beginTransaction();
        try {
            ProductToTag::where('product_id', $request->product_id)->delete();
            ProductToTag::insert($data);
            DB::commit();
            return Response::json(array('heading' => 'Success', 'message' => __('label.TAG_ASSIGNED_SUCCESSFULLY')), 201);
        } catch (\Throwable $e) {
            DB::rollback();
            return Response::json(array('success' => false, 'message' => __('label.FAILED_TO_ASSIGN_TAG')), 401);
        }
    }

    public function getProductOffer(Request $request) {

        //product name list
        $productInfo = Product::where('product.id', $request->product_id)
                ->leftJoin('product_to_product_offer', 'product_to_product_offer.product_id', 'product.id')
                ->select('product.id', 'product.name', 'product_to_product_offer.on_sale', 'product_to_product_offer.latest_product', 'product_to_product_offer.most_viewed_product', 'product_to_product_offer.popular_product')
                ->first();
        //        $productOfferInfo = ProductToProductOffer::where('product_id', $request->product_id)
        //                ->first();

        $view = view('product.showSetProductOffer', compact('request', 'productInfo'))->render();
        return response()->json(['html' => $view]);
    }

    public function setProductOffer(Request $request) {

        $data = [];
        $productId = $request->product_id;
        if (!empty($productId)) {
            $data['product_id'] = $request->product_id;
            $data['on_sale'] = !empty($request->on_sale) ? $request->on_sale : '0';
            $data['latest_product'] = !empty($request->latest_product) ? $request->latest_product : '0';
            $data['most_viewed_product'] = !empty($request->most_viewed_product) ? $request->most_viewed_product : '0';
            $data['popular_product'] = !empty($request->popular_product) ? $request->popular_product : '0';
            $data['created_by'] = Auth::user()->id;
            $data['created_at'] = date('Y-m-d H:i:s');
        }

        DB::beginTransaction();
        try {
            ProductToProductOffer::where('product_id', $request->product_id)->delete();
            ProductToProductOffer::insert($data);
            DB::commit();
            return Response::json(array('heading' => 'Success', 'message' => __('label.OFFER_ASSIGNED_SUCCESSFULLY')), 201);
        } catch (\Throwable $e) {
            DB::rollback();
            return Response::json(array('success' => false, 'message' => __('label.FAILED_TO_ASSIGN_OFFER')), 401);
        }
    }

    public function getProductImage(Request $request, $id) {
        $target = Product::leftJoin('product_image', 'product_image.product_id', 'product.id')->where('product.id', $id)->select('product.*', 'product_image.image')->first();

        if (empty($target)) {
            Session::flash('error', trans('label.INVALID_DATA_ID'));
            return redirect('admin/product');
        }
        $qpArr = $request->all();
        $imageArr = [];
        if (!empty($target->image)) {
            $imageArr = json_decode($target->image, true);
        }

        return view('product.productImage')->with(compact('qpArr', 'target', 'imageArr'));
    }

    public function newProductImage() {

        $view = view('product.newProductImage')->render();
        return response()->json(['html' => $view]);
    }

    public function setProductImage(Request $request) {

        $qpArr = $request->all();
        $pageNumber = $qpArr['filter'];
        $data = [];
        $i = 0;
        if (count($request->product_image) > 0 && $request->hasFile('product_image')) {
            foreach ($request->product_image as $key => $image) {
                $img = $key . '.' . $image->getClientOriginalExtension();
                $locationMainImg = 'public/uploads/product/mainImage/' . $img;
                $locationSmallImg = 'public/uploads/product/smallImage/' . $img;
                $locationThumbImg = 'public/uploads/product/thumbImage/' . $img;
                Image::make($image)->resize(600, 600)->save($locationMainImg);
                Image::make($image)->resize(300, 300)->save($locationSmallImg);
                Image::make($image)->resize(150, 150)->save($locationThumbImg);
                $data[$i] = $img;
                $i++;
            }
        }
        $target = new ProductImage;
        $target->product_id = $request->product_id;
        $target->image = json_encode($data);
        $target->created_by = Auth::user()->id;
        $target->created_at = date('Y-m-d H:i:s');

        if ($target->save()) {
            Session::flash('success', __('label.PRODUCT_IMAGE_SAVED_SUCCESSFULLY'));
            return redirect('admin/product' . $pageNumber);
        } else {
            Session::flash('error', __('label.PRODUCT_CATEGORY_COULD_NOT_BE_UPDATED'));
            return redirect('admin/product/' . $request->product_id . '/getProductImage' . $pageNumber);
        }
    }

    public function trackProductPricingHistory(Request $request) {
        //find brand list assigned to this product
        $brandArr = ProductToBrand::join('brand', 'brand.id', '=', 'product_to_brand.brand_id')
                        ->select('brand.id', 'brand.logo', 'brand.name')
                        ->where('brand.status', '1')
                        ->where('product_to_brand.product_id', $request->product_id)->get()->toArray();

        $product = Product::select('name')->where('id', $request->product_id)->first();

        $view = view('product.showTrackProductHistory', compact('request', 'brandArr', 'product'))->render();
        return response()->json(['html' => $view]);
    }

    public function getBrandWisePricingHistory(Request $request) {
        //check if user is autherized for realization price
        $authorised = User::select('authorised_for_realization_price')->where('id', Auth::user()->id)->first();

        $unitName = Product::join('product_unit', 'product_unit.id', '=', 'product.product_unit_id')
                        ->select('product_unit.name')->where('product.id', $request->product_id)->first();
        $unit = !empty($unitName->name) ? ' ' . __('label.PER') . ' ' . $unitName->name : '';
        $gradeArr = Grade::orderBy('order', 'asc')->where('status', '1')
                        ->pluck('name', 'id')->toArray();

        //get pricing history of this product and brand
        $pricingHistoryArr = ProductPricingHistory::select('grade_id', 'history')->where('product_id', $request->product_id)->where('brand_id', $request->brand_id)->get();
        $pricingHistory = [];
        if (!$pricingHistoryArr->isEmpty()) {
            foreach ($pricingHistoryArr as $pricingHistoryData) {
                $gradeId = $pricingHistoryData->grade_id ?? 0;

                $productPricingHistory[$gradeId] = json_decode($pricingHistoryData->history, true);
                //krsort($productPricingHistory);
                $i = 0;

                if (!empty($productPricingHistory[$gradeId])) {
                    foreach ($productPricingHistory[$gradeId] as $history) {
                        $pricingHistory[$gradeId][$history['effective_date']]['realization_price'] = !empty($history['realization_price']) ? $history['realization_price'] : __('label.N_A');
                        $pricingHistory[$gradeId][$history['effective_date']]['target_selling_price'] = !empty($history['target_selling_price']) ? $history['target_selling_price'] : __('label.N_A');
                        $pricingHistory[$gradeId][$history['effective_date']]['minimum_selling_price'] = !empty($history['minimum_selling_price']) ? $history['minimum_selling_price'] : __('label.N_A');
                        $pricingHistory[$gradeId][$history['effective_date']]['effective_date'] = !empty($history['effective_date']) ? $history['effective_date'] : __('label.N_A');
                        $pricingHistory[$gradeId][$history['effective_date']]['remarks'] = !empty($history['remarks']) ? $history['remarks'] : __('label.N_A');
                        $pricingHistory[$gradeId][$history['effective_date']]['special_note'] = !empty($history['special_note']) ? $history['special_note'] : __('label.N_A');
                    }
                }
                krsort($pricingHistory[$gradeId]);
            }
        }

        $brandNameArr = Brand::orderBy('name', 'asc')->where('status', '1')->pluck('name', 'id')->toArray();

        $view = view('product.getBrandWisePricingHistory', compact(
                        'request', 'pricingHistory', 'unit', 'brandNameArr', 'authorised', 'gradeArr'
                ))->render();
        return response()->json(['html' => $view]);
    }

    public function brandDetails(Request $request) {

        $brandLogoArr = Brand::orderBy('name', 'asc')->where('status', '1')->pluck('logo', 'id')->toArray();
        $brandNameArr = Brand::orderBy('name', 'asc')->where('status', '1')->pluck('name', 'id')->toArray();

        $brandInfoArr = ProductToBrand::join('brand', 'brand.id', '=', 'product_to_brand.brand_id')
                        ->select('brand_id')->where('brand.status', '1')
                        ->where('product_id', $request->product_id)->get();
        $brandIds = [];
        if (!$brandInfoArr->isEmpty()) {
            foreach ($brandInfoArr as $brand) {
                $brandIds[$brand->brand_id] = $brand->brand_id;
            }
        }
        $view = view('product.showBrandDetails', compact('request', 'brandInfoArr', 'brandIds', 'brandLogoArr', 'brandNameArr', 'request'))->render();
        return response()->json(['html' => $view]);
    }

    //add new hs code row
    public function newHsCodeRow(Request $request) {
        $view = view('product.newHsCodeRow')->render();
        return response()->json(['html' => $view]);
    }

    public function setProductPublishorUnpublish(Request $request) {
        $target = Product::find($request->id);
        if (!empty($target)) {
            if ($request->publish) {
                $target->publish = $request->publish;
                $target->save();
                return Response::json(array('heading' => 'Success', 'message' => __('label.PRODUCT_PUBLISH_SUCCESSFULLY')), 200);
            } else {
                $target->publish = $request->publish;
                $target->save();
                return Response::json(array('heading' => 'Success', 'message' => __('label.PRODUCT_UNPUBLISH_SUCCESSFULLY')), 200);
            }
        }
        return Response::json(array('heading' => 'Error', 'message' => __('label.COULD_NOT_FIND_PRODUCT')), 401);
    }

}
