<hr/>
<div class="row">
    <div class="col-md-12">
        <div class="tabbable tabbable-tabdrop" id="tabs">
            <ul class="nav nav-pills">
                <li class="active bg-blue-soft">
                    <a class="bold tab-color btn-full-width" href="#trackingView" data-toggle="tab" aria-expanded="false">
                        <i class="fa fa-th-large"></i>&nbsp;@lang('label.TRACKING_VIEW')
                    </a>
                </li>
                <li class="bg-blue-soft">
                    <a class="bold tab-color btn-full-width" href="#tabularView" data-toggle="tab" aria-expanded="false">
                        <i class="fa fa-th-list"></i>&nbsp;@lang('label.TABULAR_VIEW')
                    </a>
                </li>

            </ul>
            <div class="tab-content">
                <div class="tab-pane active" id="trackingView">
                    <div class="tabbable tabbable-tabdrop " id="trackingTabs">
                        <div class="brand-price-history-div">
                            @if(!empty($pricingHistory))
                            <ul class="nav nav-pills">
                                <?php $i = 0; ?>
                                @foreach($pricingHistory as $gradeId => $pricingHistoryData)
                                @if($gradeId != 0)
                                <li class="{{ $i == 0 ? 'active' : '' }} bg-blue-soft">
                                    <a class="bold tab-color btn-full-width" href="#trackingHistory_{{ $gradeId }}" data-toggle="tab" aria-expanded="false">
                                        {{ $gradeArr[$gradeId] }}
                                    </a>
                                </li>
                                <?php $i++; ?>
                                @endif
                                @endforeach
                            </ul>
                            <div class="tab-content">
                                <?php $j = 0; ?>
                                @foreach($pricingHistory as $gradeId => $pricingHistoryData)
                                <div class="tab-pane {{ $j == 0 ? 'active' : '' }}" id="trackingHistory_{{ $gradeId }}">
                                    <div class="table-responsive max-height-500 webkit-scrollbar">
                                        <table class="table table-bordered table-hover data-table">
                                            <tbody>
                                            <div class="portlet-body">
                                                @if(!empty($pricingHistoryData))
                                                <div class="mt-timeline-2">
                                                    <div class="mt-timeline-line border-grey-steel"></div>
                                                    <ul class="mt-container">
                                                        @foreach($pricingHistoryData as $effectiveDate => $history)
                                                        <li class="mt-item">
                                                            <div class="mt-timeline-icon blue-hoki">
                                                                <i class="icon-check"></i>
                                                            </div>
                                                            <div class="mt-timeline-content">
                                                                <div class="mt-content-container">
                                                                    @if($authorised->authorised_for_realization_price == '1')
                                                                    <p class="track-text">
                                                                        <strong>@lang('label.REALIZATION_PRICE'): </strong> 
                                                                        <span class="pull-right">
                                                                            {!! !empty($history['realization_price']) ? '<strong>$' . Helper::numberFormat2Digit(floatval($history['realization_price'])) . '</strong>'. $unit : '<strong>'.__('label.N_A').'</strong>' !!}
                                                                        </span>
                                                                    </p>
                                                                    @endif
                                                                    <p class="track-text">
                                                                        <strong>@lang('label.MINIMUM_SELLING_PRICE'): </strong> 
                                                                        <span class="pull-right">
                                                                            {!! !empty($history['minimum_selling_price']) ? '<strong>$' . Helper::numberFormat2Digit(floatval($history['minimum_selling_price'])) . '</strong>'. $unit : '<strong>'.__('label.N_A').'</strong>' !!}
                                                                        </span>
                                                                    </p>
                                                                    <p class="track-text">
                                                                        <strong>@lang('label.TARGET_SELLING_PRICE'): </strong> 
                                                                        <span class="pull-right">
                                                                            {!! !empty($history['target_selling_price']) ? '<strong>$' . Helper::numberFormat2Digit(floatval($history['target_selling_price'])) . '</strong>'. $unit : '<strong>'.__('label.N_A').'</strong>' !!}
                                                                        </span>
                                                                    </p>
                                                                    <p class="track-text">
                                                                        <strong>@lang('label.EFFECTIVE_DATE'): </strong> 
                                                                        <span class="">
                                                                            {!! $history['effective_date'] ?? __('label.N_A') !!}
                                                                        </span>
                                                                    </p>
                                                                    <p class="track-text">
                                                                        <strong>@lang('label.REMARKS'): </strong> 
                                                                        <span class="">
                                                                            {!! $history['remarks'] ?? __('label.N_A') !!}
                                                                        </span>
                                                                    </p>
                                                                    @if($authorised->authorised_for_realization_price == '1')
                                                                    <p class="track-text">
                                                                        <strong>@lang('label.SPECIAL_NOTE'): </strong> 
                                                                        <span class="">
                                                                            {!! $history['special_note'] ?? __('label.N_A') !!}
                                                                        </span>
                                                                    </p>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </li>
                                                        @endforeach
                                                    </ul>
                                                </div>
                                                @else
                                                <div class="text-center">
                                                    <span class="label label-danger">@lang('label.PRICING_OF_THIS_PRODUCT_FOR_THIS_BRAND_IS_NOT_SET_YET', ['brand' => $brandNameArr[$request->brand_id]])</span>
                                                </div>
                                                @endif
                                            </div>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <?php $j++; ?>
                                @endforeach
                            </div>
                            @else
                            <div class="text-center">
                                <span class="label label-danger">@lang('label.PRICING_OF_THIS_PRODUCT_FOR_THIS_BRAND_IS_NOT_SET_YET', ['brand' => $brandNameArr[$request->brand_id]])</span>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="tab-pane" id="tabularView">
                    <div class="tabbable tabbable-tabdrop" id="tabularTabs">
                        <div class="brand-price-history-div">
                            @if(!empty($pricingHistory))
                            <ul class="nav nav-pills">
                                <?php $i = 0; ?>
                                @foreach($pricingHistory as $gradeId => $pricingHistoryData)
                                @if($gradeId != 0)
                                <li class="{{ $i == 0 ? 'active' : '' }} bg-blue-soft">
                                    <a class="bold tab-color btn-full-width" href="#tabularHistory_{{ $gradeId }}" data-toggle="tab" aria-expanded="false">
                                        {{ $gradeArr[$gradeId] }}
                                    </a>
                                </li>
                                <?php $i++; ?>
                                @endif
                                @endforeach
                            </ul>
                            <div class="tab-content">
                                <?php $j = 0; ?>
                                @foreach($pricingHistory as $gradeId => $pricingHistoryData)
                                <div class="tab-pane {{ $j == 0 ? 'active' : '' }}" id="tabularHistory_{{ $gradeId }}">

                                    <div class="table-responsive webkit-scrollbar" style="max-height: 400px;">
                                        <table class="table table-bordered table-hover data-table">
                                            <thead>
                                                <tr class="info">
                                                    <th  class="text-center vcenter">@lang('label.SL_NO')</th>
                                                    @if($authorised->authorised_for_realization_price == '1')
                                                    <th  class="text-center vcenter">@lang('label.REALIZATION_PRICE')&nbsp;(${!! $unit !!})</th>
                                                    @endif
                                                    <th  class="text-center vcenter">@lang('label.MINIMUM_SELLING_PRICE')&nbsp;(${!! $unit !!})</th>
                                                    <th  class="text-center vcenter">@lang('label.TARGET_SELLING_PRICE')&nbsp;(${!! $unit !!})</th>
                                                    <th class="text-center vcenter">@lang('label.EFFECTIVE_DATE')</th>
                                                    <th class="text-center vcenter">@lang('label.REMARKS')</th>
                                                    @if($authorised->authorised_for_realization_price == '1')
                                                    <th  class="text-center vcenter">@lang('label.SPECIAL_NOTE')</th>
                                                    @endif
                                                </tr>

                                            </thead>
                                            <tbody>
                                                <?php $sl = 0; ?>
                                                @foreach($pricingHistoryData as $effectiveDate => $history)
                                                <tr>
                                                    <td class="text-center vcenter">{!! ++$sl !!}</td>
                                                    @if($authorised->authorised_for_realization_price == '1')
                                                    <td class="text-center vcenter">{!! Helper::numberFormat2Digit(floatval($history['realization_price'])) !!}</td>
                                                    @endif
                                                    <td class="text-center vcenter">{!! Helper::numberFormat2Digit(floatval($history['minimum_selling_price'])) !!}</td>
                                                    <td class="text-center vcenter">{!! Helper::numberFormat2Digit(floatval($history['target_selling_price'])) !!}</td>
                                                    <td class="text-center vcenter">{!! $history['effective_date'] !!}</td>
                                                    <td class="vcenter">{!! $history['remarks'] !!}</td>
                                                    @if($authorised->authorised_for_realization_price == '1')
                                                    <td class="vcenter">{!! $history['special_note'] !!}</td>
                                                    @endif
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <?php $j++; ?>
                                @endforeach
                            </div>
                            @else
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover data-table">
                                    <thead>
                                        <tr class="info">
                                            <th  class="text-center vcenter">@lang('label.SL_NO')</th>
                                            @if($authorised->authorised_for_realization_price == '1')
                                            <th  class="text-center vcenter">@lang('label.REALIZATION_PRICE')&nbsp;(${!! !empty($unit->name)?' '.__('label.PER').' '.$unit->name:'' !!})</th>
                                            @endif
                                            <th  class="text-center vcenter">@lang('label.MINIMUM_SELLING_PRICE')&nbsp;(${!! !empty($unit->name)?' '.__('label.PER').' '.$unit->name:'' !!})</th>
                                            <th  class="text-center vcenter">@lang('label.TARGET_SELLING_PRICE')&nbsp;(${!! !empty($unit->name)?' '.__('label.PER').' '.$unit->name:'' !!})</th>
                                            <th class="text-center vcenter">@lang('label.EFFECTIVE_DATE')</th>
                                            <th class="text-center vcenter">@lang('label.REMARKS')</th>
                                            @if($authorised->authorised_for_realization_price == '1')
                                            <th  class="text-center vcenter">@lang('label.SPECIAL_NOTE')</th>
                                            @endif
                                        </tr>

                                    </thead>
                                    <tbody>
                                        <tr class="text-center">
                                            <td class="text-danger" colspan="10">@lang('label.PRICING_OF_THIS_PRODUCT_FOR_THIS_BRAND_IS_NOT_SET_YET', ['brand' => $brandNameArr[$request->brand_id]])</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<script src="{{asset('public/js/custom.js')}}" type="text/javascript"></script>
<script type="text/javascript">
$(function () {
    $(".tooltips").tooltip();
    $(".data-table").tableHeadFixer();
});
</script>