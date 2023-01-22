<?php $v3 = 'b' . uniqid(); ?>
<div class="row margin-bottom-10">
    <div class="col-md-11 col-sm-10 col-xs-10 col-lg-10">
        {!! Form::text('hs_code['.$v3.']',  null, ['class'=>'form-control hs-code-control', 'id' => 'hsCodeId_'.$v3,'autocomplete' => 'off']) !!}
    </div>
    <div class="col-md-1 col-sm-1 col-xs-1 col-lg-1">
        <button class="btn btn-inline btn-danger remove-hs-code-row tooltips" title="Remove" type="button">
            <i class="fa fa-remove"></i>
        </button>
    </div>
</div>
<script src="{{asset('public/js/custom.js')}}" type="text/javascript"></script>
<script type="text/javascript">
$(document).ready(function () {
    $(".tooltips").tooltip();

    //remove eta row
    $('.remove-hs-code-row').on('click', function () {
        $(this).parent().parent().remove();
        return false;
    });
});
</script>
