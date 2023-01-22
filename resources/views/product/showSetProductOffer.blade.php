<div class="modal-content">
    <div class="modal-header clone-modal-header">
        <button type="button" data-dismiss="modal" data-placement="left" class="btn red pull-right tooltips" title="@lang('label.CLOSE_THIS_POPUP')">@lang('label.CLOSE')</button>
        <h4 class="modal-title text-center">
            @lang('label.SET_OFFER_FOR', ['product' => !empty($productInfo->name)?$productInfo->name:''])
        </h4>
    </div>
    <div class="modal-body">
        <!-- BEGIN FORM-->
        {!! Form::open(array('group' => 'form', 'url' => '', 'class' => 'form-horizontal','id' => 'saveTechDataSheetForm')) !!}
        {!! Form::hidden('product_id',$request->product_id) !!}
        {{csrf_field()}}
        <div class="form-body">
            <div class="row div-padding-left-right-20">
                <div class="col-md-3 col-sm-3 col-xs-3">
                    <?php
                    $checkOnSale = '';
                    if (!empty($productInfo->on_sale)) {
                        $checkOnSale = 'checked';
                    }
                    ?>
                    <div class="md-checkbox">
                        {!! Form::checkbox('on_sale',1,$checkOnSale, ['id' => 'onSale', 'class'=> 'md-check']) !!}
                        <label for="onSale">
                            <span class="inc"></span>
                            <span class="check mark-caheck"></span>
                            <span class="box mark-caheck"></span>
                        </label>
                        <span class="text-success padding-10">@lang('label.ON_SALE')</span>
                    </div>
                </div>

                <div class="col-md-3 col-sm-3 col-xs-3">
                    <?php
                    $checkLatestProduct = '';
                    if (!empty($productInfo->latest_product)) {
                        $checkLatestProduct = 'checked';
                    }
                    ?>
                    <div class="md-checkbox">
                        {!! Form::checkbox('latest_product',1,$checkLatestProduct, ['id' => 'latestProduct', 'class'=> 'md-check']) !!}
                        <label for="latestProduct">
                            <span class="inc"></span>
                            <span class="check mark-caheck"></span>
                            <span class="box mark-caheck"></span>
                        </label>
                        <span class="text-success padding-10">@lang('label.LATEST_PRODUCT')</span>
                    </div>
                </div>

                <div class="col-md-3 col-sm-3 col-xs-3">
                    <?php
                    $checkMostViewedProduct = '';
                    if (!empty($productInfo->most_viewed_product)) {
                        $checkMostViewedProduct = 'checked';
                    }
                    ?>
                    <div class="md-checkbox">
                        {!! Form::checkbox('most_viewed_product',1,$checkMostViewedProduct, ['id' => 'mostViewedProduct', 'class'=> 'md-check']) !!}
                        <label for="mostViewedProduct">
                            <span class="inc"></span>
                            <span class="check mark-caheck"></span>
                            <span class="box mark-caheck"></span>
                        </label>
                        <span class="text-success padding-10">@lang('label.MOST_VIEWED_PRODUCT')</span>
                    </div>
                </div>

                <div class="col-md-3 col-sm-3 col-xs-3">
                    <?php
                    $checkPopularProduct = '';
                    if (!empty($productInfo->popular_product)) {
                        $checkPopularProduct = 'checked';
                    }
                    ?>
                    <div class="md-checkbox">
                        {!! Form::checkbox('popular_product',1,$checkPopularProduct, ['id' => 'popularProduct', 'class'=> 'md-check']) !!}
                        <label for="popularProduct">
                            <span class="inc"></span>
                            <span class="check mark-caheck"></span>
                            <span class="box mark-caheck"></span>
                        </label>
                        <span class="text-success padding-10">@lang('label.POPULAR_PRODUCT')</span>
                    </div>
                </div>

            </div>
        </div>
        {!! Form::close() !!}
        <!-- END FORM-->
    </div>
    <div class="modal-footer">

        <button type="button" class="btn btn-primary" id="saveOfferForProduct">@lang('label.CONFIRM_SUBMIT')</button>

        <button type="button" data-dismiss="modal" data-placement="top" class="btn darkbtn-outline tooltips" title="@lang('label.CLOSE_THIS_POPUP')">@lang('label.CLOSE')</button>
    </div>
    <div id="showPricingHistory"></div>
</div>

<script src="{{asset('public/js/custom.js')}}" type="text/javascript"></script>
<script type="text/javascript">
$(function () {
    $(".tooltips").tooltip();

    $("#saveOfferForProduct").on("click", function (e) {
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
                        var formData = new FormData($('#saveTechDataSheetForm')[0]);
                        $.ajax({
                            url: "{{URL::to('admin/product/setProductOffer')}}",
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