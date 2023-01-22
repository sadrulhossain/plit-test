<div class="modal-content">
    <div class="modal-header clone-modal-header">
        <button type="button" data-dismiss="modal" data-placement="left" class="btn red pull-right tooltips" title="@lang('label.CLOSE_THIS_POPUP')">@lang('label.CLOSE')</button>
        <h4 class="modal-title text-center">
            @lang('label.SET_TAGS_FOR', ['product' => !empty($productInfo->name)?$productInfo->name:''])
        </h4>
    </div>
    <div class="modal-body">
        <!-- BEGIN FORM-->
        {!! Form::open(array('group' => 'form', 'url' => '', 'class' => 'form-horizontal','id' => 'saveTechDataSheetForm')) !!}
        {!! Form::hidden('product_id',$request->product_id) !!}
        {{csrf_field()}}
        @if(!$productTagArr->isEmpty())
        <div class="form-body">
            <div class="row div-padding-left-right-20">
                @foreach($productTagArr as $productTag)
                <?php
                $checked = '';
                if (!empty($assignedTagArr) && in_array($productTag['id'], $assignedTagArr)) {
                    $checked = 'checked';
                }
                ?>
                <div class="col-md-2 col-sm-2 col-xs-2 padding-2">
                    <div class="md-checkbox">
                        {!! Form::checkbox('productTag['.$productTag['id'].']',$productTag['id'], $checked, ['id' => $productTag['id'], 'data-id'=>$productTag['id'],'class'=> 'md-check ds-group-to-course-check']) !!}
                        <span class = "text-danger">{{ $errors->first('product_tag') }}</span>
                        <label for="{{$productTag['id']}}">
                            <span></span>
                            <span class="check tooltips" title="{{$productTag['name']}}"></span>
                            <span class="box tooltips" title="Put Tick to Add"></span>{{$productTag['name']}}
                        </label>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @else
        <div class="row div-padding-20 margin-top-10">
            <div class="col-md-12 text-center">
                <div class="alert alert-danger">
                    <p>
                        <i class="fa fa-warning"></i>
                        @lang('label.NO_AVAILABLE_TAG_FOUND').
                    </p>
                </div>
            </div>
        </div>
        @endif
        {!! Form::close() !!}
        <!-- END FORM-->
    </div>
    <div class="modal-footer">

        <button type="button" class="btn btn-primary" id="saveTagForProduct">@lang('label.CONFIRM_SUBMIT')</button>

        <button type="button" data-dismiss="modal" data-placement="top" class="btn dark btn-outline tooltips" title="@lang('label.CLOSE_THIS_POPUP')">@lang('label.CLOSE')</button>
    </div>
    <div id="showPricingHistory"></div>
</div>

<script src="{{asset('public/js/custom.js')}}" type="text/javascript"></script>
<script type="text/javascript">
    $(function () {
        $(".tooltips").tooltip();



        //save technical data sheets for product
        $("#saveTagForProduct").on("click", function (e) {
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
                                url: "{{URL::to('admin/product/setProductTag')}}",
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