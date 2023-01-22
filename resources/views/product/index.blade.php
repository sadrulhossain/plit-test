@extends('layouts.default.master')
@section('data_count')
<div class="col-md-12">
    @include('layouts.flash')
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-cubes"></i>@lang('label.PRODUCT_LIST')
            </div>
            <div class="actions">
                @if(!empty($userAccessArr[15][2]))
                <a class="btn btn-default btn-sm create-new" href="{{ URL::to('admin/product/create'.Helper::queryPageStr($qpArr)) }}"> @lang('label.CREATE_NEW_PRODUCT')
                    <i class="fa fa-plus create-new"></i>
                </a>
                @endif
            </div>
        </div>
        <div class="portlet-body">
            <!-- Begin Filter-->
            {!! Form::open(array('group' => 'form', 'url' => 'admin/product/filter','class' => 'form-horizontal')) !!}
            {!! Form::hidden('page', Helper::queryPageStr($qpArr)) !!}
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="control-label col-md-4" for="search">@lang('label.NAME')</label>
                        <div class="col-md-8">
                            {!! Form::text('search',  Request::get('search'), ['class' => 'form-control tooltips', 'title' => 'Name', 'placeholder' => 'Name','list' => 'productName','autocomplete' => 'off']) !!}
                            <datalist id="productName">
                                @if (!$nameArr->isEmpty())
                                @foreach($nameArr as $item)
                                <option value="{{$item->name}}" />
                                @endforeach
                                @endif
                            </datalist>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="control-label col-md-4" for="productCategory">@lang('label.CATEGORY')</label>
                        <div class="col-md-8">
                            {!! Form::select('product_category',array('0' => __('label.SELECT_CATEGORY_OPT')) + $productCategoryArr, Request::get('product_category'), ['class' => 'form-control js-source-states','id'=>'productCategory']) !!}
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="control-label col-md-4" for="productUnit">@lang('label.TYPE')</label>
                        <div class="col-md-8">
                            {!! Form::select('product_type',  $productTypeArr, Request::get('product_type'), ['class' => 'form-control js-source-states','id'=>'productUnit']) !!}
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="control-label col-md-4" for="productUnit">@lang('label.UNIT')</label>
                        <div class="col-md-8">
                            {!! Form::select('product_unit',  $productUnitArr, Request::get('product_unit'), ['class' => 'form-control js-source-states','id'=>'productUnit']) !!}
                        </div>
                    </div>
                </div>
                <div class="col-md-4 text-center">
                    <div class="form">
                        <button type="submit" class="btn btn-md green btn-outline filter-submit margin-bottom-20">
                            <i class="fa fa-search"></i> @lang('label.FILTER')
                        </button>
                    </div>
                </div>
            </div>

            {!! Form::close() !!}
            <!-- End Filter -->

            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr class="info">
                            <th class="text-center vcenter">@lang('label.SL_NO')</th>
                            <th class="vcenter">@lang('label.NAME')</th>
                            <th class="vcenter">@lang('label.SKU')</th>
                            <th class="vcenter">@lang('label.CATEGORY')</th>
                            <th class="vcenter">@lang('label.BRAND')</th>
                            <th class="text-center vcenter">@lang('label.UNIT')</th>
                            <!--<th class="text-center vcenter">@lang('label.VARIANT_PRODUCT')</th>-->
                            <th class="text-center vcenter">@lang('label.STATUS')</th>
                            <th class="text-center vcenter">@lang('label.ACTION')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if (!$targetArr->isEmpty())
                        <?php
                        $page = Request::get('page');
                        $page = empty($page) ? 1 : $page;
                        $sl = ($page - 1) * Session::get('paginatorCount');
                        ?>
                        @foreach($targetArr as $target)
                        <?php
                        $iconP = '';
                        if (!empty($pricedBrandArr)) {
                            if (array_key_exists($target->id, $pricedBrandArr)) {
                                $noOfBrandRelated = !empty($relatedBrandArr[$target->id]) ? count($relatedBrandArr[$target->id]) : 0;
                                $noOfBrandPriced = !empty($pricedBrandArr[$target->id]) ? count($pricedBrandArr[$target->id]) : 0;
                                $sP = $noOfBrandRelated > 1 ? 's' : '';
                                $labelP = __('label.PRICING_SET_FOR_NO_OF_PRICED_OUT_OF_NO_OF_RELATED', ['no_of_brand_related' => $noOfBrandRelated, 'no_of_brand_priced' => $noOfBrandPriced, 's' => $sP]);
                                $iconP = '<br/><span class="badge badge-primary tooltips" title="' . $labelP . '"><i class="fa fa-usd"></i></span>';
                            }
                        }
                        ?>
                        <tr>
                            <td class="text-center vcenter">{!! ++$sl.$iconP !!}</td>
                            <td class="vcenter">{!! $target->name !!} </td>
                            <td class="vcenter">{!! $target->sku !!}</td>
                            <td class="vcenter">{!! $target->product_category !!}</td>
                            <td class="vcenter">{!! $target->brand !!}</td>
                            <td class="text-center vcenter">
                                <span class="label label-info">{!! $target->product_unit !!}</span>
                            </td>
<!--                            <td class="text-center vcenter">
                                @if($target->variant_product == '1')
                                <span class="label label-green-sharp">@lang('label.YES')</span>
                                @elseif($target->variant_product == '0')
                                <span class="label label-red-mint">@lang('label.NO')</span>
                                @endif
                            </td>-->
                            <td class="text-center vcenter">
                                @if($target->status == '1')
                                <span class="label label-sm label-success">@lang('label.ACTIVE')</span>
                                @else
                                <span class="label label-sm label-warning">@lang('label.INACTIVE')</span>
                                @endif
                            </td>

                            <td class="td-actions text-center vcenter">
                                <div class="width-inherit">
                                    @if(!empty($userAccessArr[15][8]))
                                    @if($target->publish == '0' )
                                    <!--publish product block Start-->
                                    <button type="button" class="btn label-green-sharp btn-xs tooltips publish-product" publish-status='Publish' data-id="{{ $target->id }}" 
                                            data-original-title="@lang('label.CLICK_HERE_TO_STATUS_THIS_PRODUCT',['status'=>'Publish'])">
                                        <i class="fa fa-paper-plane" aria-hidden="true"></i>
                                    </button>
                                    <!--publish product block End-->
                                    @else
                                    <!--Unpublish product block Start-->
                                    <button type="button" class="btn label-red-mint  btn-xs tooltips publish-product" publish-status='Unpublish' data-id="{{ $target->id }}" 
                                            data-original-title="@lang('label.CLICK_HERE_TO_STATUS_THIS_PRODUCT',['status'=>'Unpublish'])">
                                        <i class="fa fa-paper-plane-o" aria-hidden="true"></i>
                                    </button>
                                    <!--Unpublish product block End-->
                                    @endif

                                    @if($target->variant_product =='1')
                                    <button class="btn btn-xs yellow-mint tooltips vcenter set-product-attribute" href="#modalSetProductAttribute" data-id="{!! $target->id !!}"  data-toggle="modal" title="@lang('label.SET_PRODUCT_ATTRIBUTE')">
                                        <i class="fa fa-file-text-o"></i>
                                    </button>

                                    <button class="btn btn-xs purple-sharp tooltips vcenter set-product-sku" href="#modalSetProductSKU" data-id="{!! $target->id !!}"  data-toggle="modal" title="@lang('label.SET_PRODUCT_SKU')">
                                        <i class="fa fa-barcode"></i>
                                    </button>
                                    @endif

                                    <a class="btn btn-xs yellow tooltips vcenter set-product-image" href="{{ URL::to('admin/product/' . $target->id . '/getProductImage'.Helper::queryPageStr($qpArr)) }}"  title="@lang('label.SET_PRODUCT_IMAGE')">
                                        <i class="fa fa-file-image-o"></i>
                                    </a>
                                    @endif

                                    @if(!empty($userAccessArr[15][3]))
                                    <a class="btn btn-xs btn-primary tooltips vcenter" title="Edit" href="{{ URL::to('admin/product/' . $target->id . '/edit'.Helper::queryPageStr($qpArr)) }}">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                    @endif
                                    @if(!empty($userAccessArr[15][4]))
                                    {!! Form::open(array('url' => 'admin/product/' . $target->id.'/'.Helper::queryPageStr($qpArr), 'class' => 'delete-form-inline')) !!}
                                    {!! Form::hidden('_method', 'DELETE') !!}
                                    <button class="btn btn-xs btn-danger delete tooltips vcenter" title="Delete" type="button" data-placement="top" data-rel="tooltip" data-original-title="Delete">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                    {!! Form::close() !!}
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                        @else
                        <tr>
                            <td colspan="14" class="vcenter">@lang('label.NO_PRODUCT_FOUND')</td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>
            @include('layouts.paginator')
        </div>
    </div>
</div>

<!-- Modal start -->

<!--set product pricing modal-->
<div class="modal fade" id="modalSetProductPricing" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-full">
        <div id="showSetProductPricing">
        </div>
    </div>
</div>

<!--set product attribute modal-->
<div class="modal fade" id="modalSetProductAttribute" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div id="showSetProductAttribute">
        </div>
    </div>
</div>

<!--set product sku modal-->
<div class="modal fade" id="modalSetProductSKU" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div id="showSetProductSKU">
        </div>
    </div>
</div>


<!--set product tag modal-->
<div class="modal fade" id="modalSetProductTag" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div id="showSetProductTag">
        </div>
    </div>
</div>

<!--set offer product modal-->
<div class="modal fade" id="modalSetProductOffer" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div id="showSetProductOffer">
        </div>
    </div>
</div>

<!--set product image modal-->
<div class="modal fade" id="modalSetProductImage" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div id="showSetProductImage">
        </div>
    </div>
</div>

<!--product pricing history modal-->
<!--<div class="modal fade" id="modalTrackProductHistory" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg xs-auto-width">
        <div id="showTrackProductHistory">
        </div>
    </div>
</div>-->

<!--product pricing history modal-->
<!--<div class="modal fade" id="modalBrandDetails" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div id="showBrandDetails">
        </div>
    </div>
</div>-->

<!-- Modal end-->

<script type="text/javascript">
    $(function () {
        //set product pricing modal
        $(".set-product-pricing").on("click", function (e) {
            e.preventDefault();
            var productId = $(this).attr("data-id");
            $.ajax({
                url: "{{ URL::to('/admin/product/getProductPricing')}}",
                type: "POST",
                dataType: "json",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    product_id: productId
                },
                beforeSend: function () {
                    $("#showSetProductPricing").html('');
                    App.blockUI({
                        boxed: true
                    });
                },
                success: function (res) {
                    $("#showSetProductPricing").html(res.html);
                    App.unblockUI();
                },
                error: function (jqXhr, ajaxOptions, thrownError) {
                    App.unblockUI();
                }
            }); //ajax
        });

        //set product attribute modal
        $(".set-product-attribute").on("click", function (e) {
            e.preventDefault();
            var productId = $(this).attr("data-id");
            $.ajax({
                url: "{{ URL::to('/admin/product/getProductAttribute')}}",
                type: "POST",
                dataType: "json",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    product_id: productId
                },
                beforeSend: function () {
                    $("#showSetProductAttribute").html('');
                    App.blockUI({
                        boxed: true
                    });
                },
                success: function (res) {
                    $("#showSetProductAttribute").html(res.html);
                    App.unblockUI();
                },
                error: function (jqXhr, ajaxOptions, thrownError) {
                    App.unblockUI();
                }
            }); //ajax
        });
        //set product SKU modal
        $(".set-product-sku").on("click", function (e) {
            e.preventDefault();
            var productId = $(this).attr("data-id");
            $.ajax({
                url: "{{ URL::to('/admin/product/getProductSKU')}}",
                type: "POST",
                dataType: "json",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    product_id: productId
                },
                beforeSend: function () {
                    $("#showSetProductSKU").html('');
                    App.blockUI({
                        boxed: true
                    });
                },
                success: function (res) {
                    $("#showSetProductSKU").html(res.html);
                    App.unblockUI();
                },
                error: function (jqXhr, ajaxOptions, thrownError) {
                    App.unblockUI();
                }
            }); //ajax
        });
        //set product Tag modal
        $(".set-product-tag").on("click", function (e) {
            e.preventDefault();
            var productId = $(this).attr("data-id");
            $.ajax({
                url: "{{ URL::to('/admin/product/getProductTag')}}",
                type: "POST",
                dataType: "json",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    product_id: productId
                },
                beforeSend: function () {
                    $("#showSetProductTag").html('');
                    App.blockUI({
                        boxed: true
                    });
                },
                success: function (res) {
                    $("#showSetProductTag").html(res.html);
                    App.unblockUI();
                },
                error: function (jqXhr, ajaxOptions, thrownError) {
                    App.unblockUI();
                }
            }); //ajax
        });

        $(".set-product-offer").on("click", function (e) {
            e.preventDefault();
            var productId = $(this).attr("data-id");
            $.ajax({
                url: "{{ URL::to('/admin/product/getProductOffer')}}",
                type: "POST",
                dataType: "json",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    product_id: productId
                },
                beforeSend: function () {
                    $("#showSetProductOffer").html('');
                    App.blockUI({
                        boxed: true
                    });
                },
                success: function (res) {
                    $("#showSetProductOffer").html(res.html);
                    App.unblockUI();
                },
                error: function (jqXhr, ajaxOptions, thrownError) {
                    App.unblockUI();
                }
            }); //ajax
        });

        //track product history modal
        $(".track-product-history").on("click", function (e) {
            e.preventDefault();
            var productId = $(this).attr("data-id");
            $.ajax({
                url: "{{ URL::to('/admin/product/trackProductPricingHistory')}}",
                type: "POST",
                dataType: "json",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    product_id: productId
                },
                beforeSend: function () {
                    $("#showTrackProductHistory").html('');
                    App.blockUI({
                        boxed: true
                    });
                },
                success: function (res) {
                    $("#showTrackProductHistory").html(res.html);
                    App.unblockUI();
                },
                error: function (jqXhr, ajaxOptions, thrownError) {
                    App.unblockUI();
                }
            }); //ajax
        });

        //track product history modal
        $(".brand-details").on("click", function (e) {
            e.preventDefault();
            var productId = $(this).attr("data-id");
            var productName = $(this).attr("data-name");
            $.ajax({
                url: "{{ URL::to('/admin/product/brandDetails')}}",
                type: "POST",
                dataType: "json",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    product_id: productId,
                    product_name: productName,
                },
                beforeSend: function () {
                    $("#showBrandDetails").html('');
                    App.blockUI({
                        boxed: true
                    });
                },
                success: function (res) {
                    $("#showBrandDetails").html(res.html);
                    App.unblockUI();
                },
                error: function (jqXhr, ajaxOptions, thrownError) {
                    App.unblockUI();
                }
            }); //ajax
        });
        $(document).on("click", ".publish-product", function (e) {
            e.preventDefault();
            var options = {
                closeButton: true,
                debug: false,
                positionClass: "toast-bottom-right",
                onclick: null,
            };
            var productId = $(this).attr('data-id');
            var publishStatus = $(this).attr('publish-status');
            var publish = (publishStatus == 'Publish') ? '1' : '0';
            swal({
                title: "Do you really want to " + publishStatus + " this product?",
                text: "@lang('label.DO_YOU_WANT_TO_CHANGE_IT')",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "@lang('label.YES'), " + publishStatus + " it.",
                cancelButtonText: "@lang('label.NO_CANCEL')",
                closeOnConfirm: true,
                closeOnCancel: true,
            }, function (isConfirm) {
                if (isConfirm) {
                    $.ajax({
                        url: "{{ URL::to('admin/product/setProductPublishorUnpublish') }}",
                        type: "POST",
                        data: {
                            id: productId, publish: publish
                        },
                        dataType: 'json', // what to expect back from the PHP script, if anything
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function (res) {
                            toastr.success(res.message, res.heading, options);
                            location.reload();
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
                        }
                    });
                }
            });
        });
    });

    //get brand wise product pricing history
    $(document).on("click", ".get-brand-wise-pricing-history", function (e) {
        e.preventDefault();
        var productId = $(this).attr("data-product-id");
        var brandId = $(this).attr("data-brand-id");
        $.ajax({
            url: "{{ URL::to('/admin/product/getBrandWisePricingHistory')}}",
            type: "POST",
            dataType: "json",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                product_id: productId,
                brand_id: brandId,
            },
            beforeSend: function () {
                $("#getBrandWisePricingHistory").html('');
            },
            success: function (res) {
                $("#getBrandWisePricingHistory").html(res.html);
                $(".get-brand-wise-pricing-history").children().removeClass('col-padding-box-blue-chambary').addClass('col-padding-box-blue-hoki');
                $("#brand_" + brandId).removeClass('col-padding-box-blue-hoki').addClass('col-padding-box-blue-chambary');
            },
            error: function (jqXhr, ajaxOptions, thrownError) {
            }
        }); //ajax
    });

</script>

@stop
