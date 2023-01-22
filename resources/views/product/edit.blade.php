@extends('layouts.default.master')
@section('data_count')
<div class="col-md-12">
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-cubes"></i>@lang('label.EDIT_PRODUCT')
            </div>
        </div>
        <div class="portlet-body form">
            {!! Form::model($target, [ 'files'=> true, 'class' => 'form-horizontal','id' => 'productEditForm'] ) !!}
            {!! Form::hidden('filter', Helper::queryPageStr($qpArr)) !!}
            {!! Form::hidden('id', $target->id) !!}
            {{csrf_field()}}
            <div class="form-body">
                <div class="row">
                    <div class="col-md-12">

                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label col-md-4" for="productCatId">@lang('label.PRODUCT_CATEGORY'):<span class="text-danger"> *</span></label>
                                <div class="col-md-8">
                                    {!! Form::select('product_category_id', ['0' => __('label.SELECT_CATEGORY_OPT')] + $productCategoryArr, null, ['class' => 'form-control js-source-states', 'id' => 'productCatId']) !!}
                                    <span class="text-danger">{{ $errors->first('product_category_id') }}</span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-4" for="name">@lang('label.NAME') :<span class="text-danger"> *</span></label>
                                <div class="col-md-8">
                                    {!! Form::text('name', null, ['id' => 'name', 'class' => 'form-control', 'autocomplete' => 'off']) !!}
                                    <span class="text-danger">{{ $errors->first('name') }}</span>
                                    <div id="productName"></div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-4" for="productCatId">@lang('label.PRODUCT_TYPE') :<span class="text-danger"> *</span></label>
                                <div class="col-md-8">
                                    {!! Form::select('product_type_id', ['0' => __('label.SELECT_TYPE_OPT')] + $productTypeArr, null, ['class' => 'form-control js-source-states', 'id' => 'productCatId']) !!}
                                    <span class="text-danger">{{ $errors->first('product_category_id') }}</span>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-4" for="productUnitId">@lang('label.PRODUCT_UNIT') :<span class="text-danger"> *</span></label>
                                <div class="col-md-8">
                                    {!! Form::select('product_unit_id', $productUnitArr, null, ['class' => 'form-control js-source-states', 'id' => 'productUnitId']) !!}
                                    <span class="text-danger">{{ $errors->first('product_unit_id') }}</span>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-4" for="productUnitId">@lang('label.BRAND') :<span
                                        class="text-danger"> *</span></label>
                                <div class="col-md-8">
                                    {!! Form::select('brand_id', $brandArr, null, ['class' => 'form-control js-source-states', 'id' => 'brandId']) !!}
                                    <span class="text-danger">{{ $errors->first('brand_id') }}</span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-4" for="fullDescription">@lang('label.DESCRIPTION'):<span class="text-danger"> *</span></label>
                                <div class="col-md-8">
                                    {!! Form::textarea('description', null, ['id' => 'description', 'class' => 'form-control full-name-text-area', 'cols' => '20', 'rows' => '8']) !!}
                                    <span class="text-danger">{{ $errors->first('description') }}</span>

                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-4" for="youtubeLink">@lang('label.YOUTUBE_LINK'):</label>
                                <div class="col-md-8">
                                    {!! Form::text('youtube_link', '', ['id' => 'youtubeLink', 'class' => 'form-control', 'autocomplete' => 'off','placeholder'=>'youtube.com/channel']) !!}
                                    <span class="text-danger">{{ $errors->first('youtube_link') }}</span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-4" for="status">@lang('label.STATUS') :</label>
                                <div class="col-md-8">
                                    {!! Form::select('status', ['1' => __('label.ACTIVE'), '2' => __('label.INACTIVE')], '1', ['class' => 'form-control', 'id' => 'status']) !!}
                                    <span class="text-danger">{{ $errors->first('status') }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">

                            <!--                            <div class="form-group">
                                                            <label class="control-label col-md-4" for="variantProduct">@lang('label.VARIANT_PRODUCT'): </label>
                                                            <div class="col-md-8 checkbox-center md-checkbox has-success">
                                                                {!! Form::checkbox('variant_product', 1, null, ['id' => 'variantProduct', 'class' => 'md-check']) !!}
                                                                <label for="variantProduct">
                                                                    <span class="inc"></span>
                                                                    <span class="check mark-caheck"></span>
                                                                    <span class="box mark-caheck"></span>
                                                                </label>
                                                                <span class="text-success">@lang('label.PUT_TICK_TO_MARK_AS_VARIANT_PRODUCT')</span>
                                                            </div>
                                                        </div>-->

                            <div id="skuSection">
                                <div class="form-group">
                                    <label class="control-label col-md-4" for="skuCode">@lang('label.SKU_CODE') :<span class="text-danger"> *</span></label>
                                    <div class="col-md-8">
                                        {!! Form::text('sku', $targetSku->sku ?? null, ['id' => 'skuCode', 'class' => 'form-control', 'autocomplete' => 'off', 'readonly']) !!}
                                        <span class="text-danger">{{ $errors->first('sku_code') }}</span>
                                    </div>
                                </div>

                                <!--                                <div class="form-group">
                                                                    <label class="control-label col-md-4" for="sellingPrice">@lang('label.SELLING_PRICE')
                                                                        :<span class="text-danger"> *</span></label>
                                                                    <div class="col-md-8">
                                                                        {!! Form::text('selling_price', $targetSku->selling_price ?? null, ['id' => 'sellingPrice', 'class' => 'form-control text-right', 'autocomplete' => 'off']) !!}
                                                                        <span class="text-danger">{{ $errors->first('selling_price') }}</span>
                                                                    </div>
                                                                </div>-->

                                <div class="form-group">
                                    <label class="control-label col-md-4" for="purchasePrice">@lang('label.PURCHASE_PRICE') :<span class="text-danger"> *</span></label>
                                    <div class="col-md-8">
                                        <div class="input-group bootstrap-touchspin width-full">
                                            {!! Form::text('purchase_price', $targetSku->purchase_price ?? null, ['id' => 'purchasePrice', 'class' => 'form-control integer-decimal-only text-right', 'autocomplete' => 'off', 'step' => 'any']) !!}
                                            <span class="input-group-addon bootstrap-touchspin-postfix bold"> @lang('label.TK')</span>
                                        </div>
                                        <span class="text-danger">{{ $errors->first('purchase_price') }}</span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-4" for="retailerPrice">@lang('label.RETAILER_PRICE'):<span class="text-danger"> *</span></label>
                                    <div class="col-md-8">
                                        <div class="input-group bootstrap-touchspin width-full">
                                            {!! Form::text('selling_price', $targetSku->selling_price ?? null, ['id' => 'retailerPrice', 'class' => 'form-control integer-decimal-only text-right', 'autocomplete' => 'off', 'step' => 'any']) !!}
                                            <span class="input-group-addon bootstrap-touchspin-postfix bold"> @lang('label.TK')</span>
                                        </div>
                                        <span class="text-danger">{{ $errors->first('selling_price') }}</span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-4" for="distributorPrice">@lang('label.DISTRIBUTOR_PRICE'):<span class="text-danger"> *</span></label>
                                    <div class="col-md-8">
                                        <div class="input-group bootstrap-touchspin width-full">
                                            {!! Form::text('distributor_price', $targetSku->distributor_price ?? null, ['id' => 'distributorPrice', 'class' => 'form-control integer-decimal-only text-right', 'autocomplete' => 'off', 'step' => 'any', 'placeholder' => '0.00']) !!}
                                            <span class="input-group-addon bootstrap-touchspin-postfix bold"> @lang('label.TK')</span>
                                        </div>
                                        <span class="text-danger">{{ $errors->first('distributor_price') }}</span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-4" for="discountPrice">@lang('label.DISCOUNT_PRICE'):<span class="text-danger"> *</span></label>
                                    <div class="col-md-8">
                                        <div class="input-group bootstrap-touchspin width-full">
                                            {!! Form::text('discount_price', $targetSku->discount_price ?? null, ['id' => 'discountPrice', 'class' => 'form-control integer-decimal-only text-right', 'autocomplete' => 'off', 'step' => 'any', 'placeholder' => '0.00']) !!}
                                            <span class="input-group-addon bootstrap-touchspin-postfix bold"> @lang('label.TK')</span>
                                        </div>
                                        <span class="text-danger">{{ $errors->first('discount_price') }}</span>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="control-label col-md-4" for="reorderLevel">@lang('label.REORDER_LEVEL'):<span class="text-danger"> *</span></label>
                                    <div class="col-md-8">
                                        {!! Form::text('reorder_level', $targetSku->reorder_level ?? null, ['id' => 'reorderLevel', 'class' => 'form-control integer-decimal-only text-right', 'autocomplete' => 'off']) !!}
                                        <span class="text-danger">{{ $errors->first('reorder_level') }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-4" for="lotSize">@lang('label.LOT_SIZE') :<span class="text-danger"> *</span></label>
                                <div class="col-md-8">
                                    {!! Form::text('lot_size', $target->lot_size ?? null, ['id' => 'lotSize', 'class' => 'form-control integer-decimal-only text-right', 'autocomplete' => 'off', 'step' => 'any']) !!}
                                    <span class="text-danger">{{ $errors->first('lot_size') }}</span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-4" for="LotRatio">@lang('label.LOT_RATIO') :<span class="text-danger"> *</span></label>
                                <div class="col-md-8">
                                    {!! Form::text('lot_ratio', $target->lot_ratio ?? null, ['id' => 'lotRatio', 'class' => 'form-control', 'autocomplete' => 'off']) !!}
                                    <span class="text-danger">{{ $errors->first('lot_ratio') }}</span>
                                </div>
                            </div>


                        </div>
                    </div>
                </div>

                <div class="form-actions">
                    <div class="row">
                        <div class="col-md-offset-4 col-md-8">
                            <button class="btn green btn-submit" type="button">
                                <i class="fa fa-check"></i> @lang('label.SUBMIT')
                            </button>
                            <a href="{{ URL::to('/admin/product'.Helper::queryPageStr($qpArr)) }}" class="btn btn-outline grey-salsa">@lang('label.CANCEL')</a>
                        </div>
                    </div>
                </div>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>
<script type="text/javascript" src="product.js"></script>
<script type="text/javascript">
$(document).ready(function () {

    $(document).on("keyup input change", "#name", function () {
        var value = $(this).val();
        var slug = slugify(value);
        $("#skuCode").val(slug);
    });

    function slugify(string) {
        return string
                .toString()
                .trim()
                .toLowerCase()
                .replace(/\s+/g, "-")
                .replace(/[^\w\-]+/g, "")
                .replace(/\-\-+/g, "-")
                .replace(/^-+/, "")
                .replace(/-+$/, "");
    }


    $('#description').summernote({
        placeholder: 'Product Description',
        tabsize: 2,
        height: 100,
        toolbar: [
            // [groupName, [list of button]]
            ['style', ['bold', 'italic', 'underline', 'clear']],
            ['font', ['strikethrough', 'superscript', 'subscript']],
            ['fontsize', ['fontsize']],
            ['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['height', ['height']]
        ]
    });

    $(document).on("click", ".btn-submit", function () {
        swal({
            title: "Are you sure?",
            text: "@lang('label.DO_YOU_WANT_TO_CONTINUE_IT')",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "@lang('label.YES_CONTINUE_IT')",
            closeOnConfirm: true,
            closeOnCancel: true,
        }, function (isConfirm) {
            if (isConfirm) {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                var options = {
                    closeButton: true,
                    debug: false,
                    positionClass: "toast-bottom-right",
                    onclick: null,
                };

                var formData = new FormData($("#productEditForm")[0]);
                $.ajax({
                    url: "{{ URL::to('/admin/product/update')}}",
                    type: "POST",
                    dataType: 'json', // what to expect back from the PHP script, if anything
                    cache: false,
                    contentType: false,
                    processData: false,
                    data: formData,
                    beforeSend: function () {
                        App.blockUI({boxed: true});
                    },
                    success: function (res) {
                        toastr.success(res.message, res.heading, options);
                        setTimeout(() => {
                            window.location.replace('{{ URL::to("/admin/product")}}')
                        }, 2000);
                        App.unblockUI();
                    },
                    error: function (jqXhr, ajaxOptions, thrownError) {
                        if (jqXhr.status == 400) {
                            var errorsHtml = '';
                            var errors = jqXhr.responseJSON.message;
                            $.each(errors, function (key, value) {
                                errorsHtml += '<li>' + value[0] + '</li>';
                            });
                            toastr.error(errorsHtml, jqXhr.responseJSON.heading, options);
                        } else if (jqXhr.status == 401) {
                            toastr.error(jqXhr.responseJSON.message, '', options);
                        } else {
                            toastr.error('Error', 'Something went wrong', options);
                        }
                        App.unblockUI();
                    }
                }); //ajax
            }
        });
    });
});


</script>
@stop
