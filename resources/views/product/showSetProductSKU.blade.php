<div class="modal-content">
    <div class="modal-header clone-modal-header">
        <button type="button" data-dismiss="modal" data-placement="left" class="btn red pull-right tooltips" title="@lang('label.CLOSE_THIS_POPUP')">@lang('label.CLOSE')</button>
        <h4 class="modal-title text-center">
            @lang('label.SET_SKU_FOR', ['product' => !empty($productInfo->name)?$productInfo->name:''])
        </h4>
    </div>
    <div class="modal-body">
        <div class="row">
            {!! Form::open(array('group' => 'form', 'url' => '#','class' => 'form-horizontal','id' => 'saveProductSKUForm')) !!}
            {!! Form::hidden('product_id',$request->product_id) !!}
            {{csrf_field()}}
            <div class="col-md-12">
                <div class="table-responsive">
                    <table class="table table-hover table-bordered">
                        <thead>
                            <tr class="">
                                <th class="vcenter">@lang('label.SL_NO')</th>
                                <th class="vcenter col-md-5"> {{__('label.ATTRIBUTE_COMBINATION')}} </th>
                                <th class="vcenter col-md-3"> {{__('label.CODE_WISE_SKU')}} </th>
                                <th class="vcenter"> {{__('label.SELLING_PRICE')}} </th>
                                <th class="vcenter"> {{__('label.REORDER_LEVEL')}} </th>
                            </tr>
                        </thead>
                        @if(!empty($skuArr))
                        <?php $serial = 0; ?> 
                        @foreach($skuArr as $key => $skuData)
                        {!! Form::hidden('sku_info['.$key.'][id]', $skuData['id']) !!}
                        <tr>
                            <td class="text-center vcenter">{!! ++$serial !!}</td>
                            <td class="vcenter col-md-5">
                                {!! Form::text('sku_info['.$key.'][attribute_combination]', Common::nameWiseSKUVariation($key), ['id'=> 'attributeCombination'.$key, 'class' => 'form-control','autocomplete' => 'off','readonly']) !!}
                            </td>
                            <td class="vcenter col-md-3">
                                {!! Form::text('sku_info['.$key.'][sku]',$skuData['sku'], ['id'=> 'productSKU'.$key, 'class' => 'form-control','autocomplete' => 'off']) !!}

                            </td>
                            <td class="vcenter">
                                {!! Form::text('sku_info['.$key.'][selling_price]', $skuData['selling_price'], ['id'=> 'sellingPrice'.$key, 'class' => 'form-control interger-decimal-only','autocomplete' => 'off']) !!}
                            </td>
                            <td class="vcenter">
                                {!! Form::text('sku_info['.$key.'][reorder_level]', $skuData['reorder_level'], ['id'=> 'reorderLevel'.$key, 'class' => 'form-control integer-only','autocomplete' => 'off']) !!}
                            </td>
                            {!! Form::hidden('sku_info['.$key.'][available_quantity]', $skuData['available_quantity'], ['id'=> 'availableQuantity'.$key, 'class' => 'form-control integer-only','autocomplete' => 'off']) !!}

                        </tr>
                        @endforeach 
                        @else
                        <tr>
                            <td colspan="9">{{trans('label.NO_ATTRIBUTE_ASSIGNED_FOR_THIS_PRODUCT')}}</td>
                        </tr>
                        @endif
                    </table>
                </div>

            </div>
            {!! Form::close() !!}
        </div>

        <div class="modal-footer">

            <button type="button" class="btn btn-primary" id="saveProductSKU">@lang('label.CONFIRM_SUBMIT')</button>

            <button type="button" data-dismiss="modal" data-placement="left" class="btn dark btn-outline tooltips" title="@lang('label.CLOSE_THIS_POPUP')">@lang('label.CLOSE')</button>
        </div>
    </div>
    <div id="showPricingHistory"></div>
</div>

<script src="{{asset('public/js/custom.js')}}" type="text/javascript"></script>
<script type="text/javascript">
    $(function () {
        $("#saveProductSKU").on("click", function (e) {
            e.preventDefault();
            swal({
                title: 'Are you sure?',
                text: "You can not undo this action!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#DD6B55',
                confirmButtonText: 'Yes, save',
                cancelButtonText: 'No, cancel',
                closeOnConfirm: true,
                closeOnCancel: true},
                    function (isConfirm) {
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

                            // Serialize the form data
                            var formData = new FormData($('#saveProductSKUForm')[0]);
                            $.ajax({
                                url: "{{URL::to('admin/product/setProductSKU')}}",
                                type: "POST",
                                dataType: 'json', // what to expect back from the PHP script, if anything
                                cache: false,
                                contentType: false,
                                processData: false,
                                data: formData,
                                success: function (res) {
                                    toastr.success(res.data, res.message, options);
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
                                    App.unblockUI();
                                }
                            });
                        }
                    });

        });
    });
</script>