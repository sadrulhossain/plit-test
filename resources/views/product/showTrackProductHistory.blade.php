<div class="modal-content">
    <div class="modal-header clone-modal-header">
        <button type="button" data-dismiss="modal" data-placement="left" class="btn red pull-right tooltips" title="@lang('label.CLOSE_THIS_POPUP')">@lang('label.CLOSE')</button>
        <h3 class="modal-title text-center">
            @lang('label.PRICING_HISTORY_OF_THIS_PRODUCT', ['product' => !empty($product->name)?$product->name:''])
        </h3>
    </div>
    <div class="modal-body">
        @if(!empty($brandArr))
        <div class="row margin-top-10 margin-bottom-10">
            <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12 text-center">
                <div class="alert alert-info">
                    <p>
                        <i class="fa fa-info-circle"></i>
                        @lang('label.PLEASE_CLICK_TO_A_BRAND_TO_SEE_PRICING_HISTORY')
                    </p>
                </div>
            </div>
        </div>
        <div class="table-responsive webkit-scrollbar" style="max-height: 300px">
        <div class="row div-padding-left-right-20">
            <?php $i = 0; ?>
            @foreach($brandArr as $brand)
            <?php
            if($i > 0 && ($i+1)%3 == 1 ){
                echo '</div><div class="row div-padding-left-right-20">'; 
            }
            ?>
            <div class="col-md-4 col-sm-4 col-xs-12 padding-2">
                <a class="get-brand-wise-pricing-history a-tag-decoration-none tooltips" data-product-id="{!! $request->product_id !!}" data-brand-id="{!! $brand['id'] !!}">
                    <div class="col-padding-box-blue-hoki" id="brand_{!! $brand['id'] !!}">
                        <div class="text-center">
                            @if(!empty($brand['logo']) && File::exists(URL::to('/').'/public/uploads/brand/'.$brand['logo']))
                            <img class="pictogram-min-space tooltips" width="40" height="40" src="{{URL::to('/')}}/public/uploads/brand/{{ $brand['logo'] }}" alt="{{ $brand['name']}}" title="{{ $brand['name'] }}"/>
                            @else 
                            <img width="40" height="40" src="{{URL::to('/')}}/public/img/no_image.png" alt=""/>
                            @endif
                        </div>
                        <div class="text-center">
                            <span class="bold text-font-12">{!! !empty($brand['name'])? $brand['name'] : '' !!}</span>
                        </div>
                    </div>
                </a>
            </div>
            <?php $i++; ?>
            @endforeach
        </div>
        </div>
        @else
        <div class="row div-padding-20 margin-top-10">
            <div class="col-md-12 text-center">
                <div class="alert alert-danger">
                    <p>
                        <i class="fa fa-warning"></i>
                        @lang('label.NO_BRAND_FOUND_RELATED_TO_THIS_PRODUCT').
                    </p>
                </div>
            </div>
        </div>
        @endif
        <div id="getBrandWisePricingHistory"></div>
    </div>
    <div class="modal-footer">
        <button type="button" data-dismiss="modal" data-placement="top" class="btn dark btn-outline tooltips" title="@lang('label.CLOSE_THIS_POPUP')">@lang('label.CLOSE')</button>
    </div>
</div>

<script src="{{asset('public/js/custom.js')}}" type="text/javascript"></script>
<script type="text/javascript">
$(function () {
    $(".tooltips").tooltip();


});
</script>