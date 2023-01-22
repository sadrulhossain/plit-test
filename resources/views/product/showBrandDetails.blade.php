<div class="modal-content">
    <div class="modal-header clone-modal-header">
        <button type="button" data-dismiss="modal" data-placement="left" class="btn red pull-right tooltips" title="@lang('label.CLOSE_THIS_POPUP')">@lang('label.CLOSE')</button>
        <h3 class="modal-title text-center">
            @lang('label.ASSINED_BRAND_S_FOR') '{{ $request->product_name }}'
        </h3>
    </div>
    <div class="modal-body">
        <div class="row">
            <div class="col-md-12">
                <table class="table table-bordered table-hover module-access-view" id="dataTable">
                    <thead>
                        <tr class="info">
                            <th  class="text-center vcenter">@lang('label.SL_NO')</th>
                            <th  class="text-center vcenter">@lang('label.LOGO')</th>
                            <th  class="text-center vcenter">@lang('label.BRAND')</th>
                        </tr>

                    </thead>
                    <tbody>
                        <?php $sl = 0; ?>
                        @if(!empty($brandIds))
                        @foreach($brandIds as $key => $brandId)
                        <tr>
                            <td class="text-center vcenter">{!! ++$sl !!}</td>
                            <td class="text-center">
                                @if(!empty($brandLogoArr) || isset($brandLogoArr[$brandId]))
                                <img class="pictogram-min-space tooltips" width="40" height="40" src="{{URL::to('/')}}/public/uploads/brand/{{ $brandLogoArr[$brandId] }}" alt="{{ $brandNameArr[$brandId] }}" title="{{ $brandNameArr[$brandId] }}"/>
                                @else
                                <img width="40" height="40" src="{{URL::to('/')}}/public/img/no_image.png" alt=""/>
                                @endif

                            </td>
                            <td class="vcenter">{!! (!empty($brandNameArr) || isset($brandNameArr[$brandId]))  ? $brandNameArr[$brandId] : '' !!}</td>
                        </tr>
                        @endforeach
                        @else
                        <tr>
                            <td colspan="5" class="text-danger">
                                @lang('label.NO_BRAND_IS_SET_FOR_THIS_PRODUCT')
                            </td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" data-dismiss="modal" data-placement="left" class="btn dark btn-outline tooltips" title="@lang('label.CLOSE_THIS_POPUP')">@lang('label.CLOSE')</button>
    </div>
</div>