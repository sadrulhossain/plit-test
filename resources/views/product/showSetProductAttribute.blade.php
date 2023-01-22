<div class="modal-content">
    <div class="modal-header clone-modal-header">
        <button type="button" data-dismiss="modal" data-placement="left" class="btn red pull-right tooltips" title="@lang('label.CLOSE_THIS_POPUP')">@lang('label.CLOSE')</button>
        <h4 class="modal-title text-center">
            @lang('label.SET_ATTRIBUTE_FOR', ['product' => !empty($productInfo->name)?$productInfo->name:''])
        </h4>
    </div>
    <div class="modal-body">
        <!-- BEGIN FORM-->
        {!! Form::open(array('group' => 'form', 'url' => '', 'class' => 'form-horizontal','id' => 'saveTechDataSheetForm')) !!}
        {!! Form::hidden('product_id',$request->product_id) !!}
        {{csrf_field()}}
        @if(!empty($attributeTypeWiseProductAttributeArr))
        <div class="form-body">
            <div class="row margin-top-10 margin-bottom-10">
                <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12 text-center">
                    <div class="alert alert-info">
                        <p>
                            <i class="fa fa-info-circle"></i>
                            @lang('label.PLEASE_PUT_TICK_TO_ADD_ATTRIBUTES_TO_THE_PRODUCT')
                        </p>
                    </div>
                </div>
            </div>
            @foreach($attributeTypeWiseProductAttributeArr as $attributeTypeId => $attributeTypeInfo)
            <?php
            $checked = '';
            $display = 'none';
            if (!empty($previousAttributeTypeArr) && array_key_exists($attributeTypeId, $previousAttributeTypeArr)) {
                $checked = 'checked';
                $display = 'block';
            }
            ?>
            <div class="row margin-bottom-25">
                <div class="col-md-12">
                    <div class="ds-border-style">
                        <div class="ds-info-style">
                            <div class="ds-info-div margin-0">
                                <div class="md-checkbox vcenter module-check">
                                    {!! Form::checkbox('attribute_type['.$attributeTypeId.']',$attributeTypeId,$checked, ['id' => 'attributeTypeId_'.$attributeTypeId, 'class'=> 'md-check ds-brand-check']) !!}
                                    <label for="{{ 'attributeTypeId_'.$attributeTypeId }}">
                                        <span class="inc"></span>
                                        <span class="check"></span>
                                        <span class="box"></span>
                                    </label>

                                    <span class="vcenter">{!! !empty($attributeTypeArr[$attributeTypeId]) ? $attributeTypeArr[$attributeTypeId] : '' !!}</span>
                                </div>
                                
                            </div>
                        </div>
                        

                        <div id="{{ 'dsDiv_'.$attributeTypeId }}" style="display: {{ $display }};">
                            <div class="row div-padding-left-right-20">
                                <?php $i = 0; ?>
                                @foreach($attributeTypeInfo as $attributeId => $attributeName)
                                <?php
                                if ($i > 0 && ($i + 1) % 6 == 1) {
                                    echo '</div><div class="row div-padding-left-right-20">';
                                }

                                $allCheckDisabled = '';
                                if (!empty($previousProductAttributeArr) && array_key_exists($attributeId, $previousProductAttributeArr)) {
                                    $allCheckDisabled = 'checked';
                                }
                                ?>
                                <div class="col-md-2 col-sm-2 col-xs-2 padding-2">
                                    <div class="md-checkbox">
                                        {!! Form::checkbox('product_attribute['.$attributeTypeId.']['.$attributeId.']',$attributeId, false, ['id' => $attributeId, 'data-id'=>$attributeId,'class'=> 'md-check ds-group-to-course-check',$allCheckDisabled]) !!}

                                        <span class = "text-danger">{{ $errors->first('product_attribute') }}</span>
                                        <label for="{{$attributeId}}">
                                            <span></span>
                                            <span class="check tooltips" title="{{$attributeId}}"></span>
                                            <span class="box tooltips" title="Checked"></span>{{$attributeName}}
                                        </label>
                                    </div>
                                </div>
                                <?php $i++; ?>
                                @endforeach
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="row div-padding-20 margin-top-10">
            <div class="col-md-12 text-center">
                <div class="alert alert-danger">
                    <p>
                        <i class="fa fa-warning"></i>
                        @lang('label.NO_ATTRIBUTE_FOUND_RELATED_TO_THIS_PRODUCT').
                    </p>
                </div>
            </div>
        </div>
        @endif
        {!! Form::close() !!}
        <!-- END FORM-->
    </div>
    <div class="modal-footer">
        @if(!empty($attributeTypeWiseProductAttributeArr))
        <button type="button" class="btn btn-primary" id="saveAttributeForProduct">@lang('label.CONFIRM_SUBMIT')</button>
        @endif
        <button type="button" data-dismiss="modal" data-placement="top" class="btn dark btn-outline tooltips" title="@lang('label.CLOSE_THIS_POPUP')">@lang('label.CLOSE')</button>
    </div>
    <div id="showPricingHistory"></div>
</div>

<script src="{{asset('public/js/custom.js')}}" type="text/javascript"></script>
<script type="text/javascript">
    $(function () {
        $(".tooltips").tooltip();

        $(".ds-brand-check").on("click", function () {
            var attributeTypeId = $(this).val();
            if ($(this).prop('checked')) {
                $("#dsDiv_" + attributeTypeId).show(1000);
            } else {
                $("#dsDiv_" + attributeTypeId).hide(1000);
            }
        });



        //save technical data sheets for product
        $("#saveAttributeForProduct").on("click", function (e) {
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
                                url: "{{URL::to('admin/product/setProductAttribute')}}",
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