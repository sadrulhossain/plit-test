
<div class="modal-body">
    <div class="row">
        <div class="table-responsive col-md-12" style="max-height: 200px;">
            <table class="table table-bordered table-hover module-access-view" id="dataTable">
                <thead>
                    <tr class="history-info">
                        <th  class="text-center vcenter" colspan="4">
                            @lang('label.PRICING_HISTORY') of {!! $pricingHistoryArr->product_name !!}
                        </th>
                    </tr>
                    <tr class="info">
                        <th  class="text-center vcenter">@lang('label.SL_NO')</th>
                        <th  class="text-center vcenter">@lang('label.TARGET_SELLING_PRICE')&nbsp;($)</th>
                        <th  class="text-center vcenter">@lang('label.MINIMUM_SELLING_PRICE')&nbsp;($)</th>
                        <th class="text-center vcenter">@lang('label.EFFECTIVE_DATE')</th>
                    </tr>

                </thead>
                <tbody>
                    <?php $sl = 0; ?>
                    @foreach($pricingHistory as $history)
                    <tr>
                        <td class="text-center vcenter">{!! ++$sl !!}</td>
                        <td class="text-center vcenter">{!! $history['target_selling_price'] !!}</td>
                        <td class="text-center vcenter">{!! $history['min_selling_price'] !!}</td>
                        <td class="text-center vcenter">{!! Helper::formatDate($history['min_selling_price_date']) !!}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn grey-mint hide-pricing-history">@lang('label.HIDE_PRICING_HISTORY')</button>
</div>

<script type="text/javascript">
    $(function () {
        $("#dataTable").tableHeadFixer();
        $(".hide-pricing-history").on("click", function (e) {
            $(".hide-pricing-history").hide();
            $("#showPricingHistory").html('');
            $(".show-pricing-history").show();

        });
    });
</script>