@extends('layouts.default.master')
@section('data_count')
<div class="col-md-12">
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-cubes"></i>@lang('label.EDIT_PRODUCT')
            </div>
        </div>
        <div class="portlet-body form">
            {!! Form::open(array('group' => 'form', 'url' => 'admin/product/setProductImage', 'class' => 'form-horizontal', 'files' => true)) !!}
            {!! Form::hidden('filter', Helper::queryPageStr($qpArr)) !!}
            {!! Form::hidden('product_id', $target->id) !!}
            {{csrf_field()}}
            <div class="form-body">
                <div class="col-md-12">
                    <h3 class="form-section title-section bold">Product: {{ $target->name }}</h3>
                    <div class="form-body">
                        <div class="form-group">
                            <div class="col-md-1">
                                <button  type="button" class="btn purple-soft add-contact-person tooltips" title="@lang('label.CLICK_HERE_TO_ADD_MORE_IMAGE')">
                                    @lang('label.ADD_MORE_IMAGE')&nbsp; <i class="fa fa-plus"></i>
                                </button>
                            </div>
                            <div class="" id="newContactPerson"> </div>
                            @if(!empty($imageArr))
                            <?php
                            $count = 1;
                            ?>
                            @foreach($imageArr as $image)
                            <div class="col-md-12 contact-person-div">
                                <div class="row">
                                    @if($count > 1)
                                    <button class="btn btn-danger remove tooltips pull-right block-remove" data-count="{{ $count }}" title="@lang('label.CLICK_HERE_TO_DELETE_THIS_BLOCK')" type="button" 
                                            id="deleteBtn_"{{$count}}>
                                        &nbsp;@lang('label.DELETE')&nbsp;<i class="fa fa-remove"></i>
                                    </button>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <label class="control-label col-md-2" for='contactPhoto'.{{$image}}>@lang('label.PHOTO') :</label>
                                        <div class="col-md-8">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="fileinput fileinput-new" data-provides="fileinput">
                                                        <div class="fileinput-new thumbnail" style="width: 150px; height: 120px;">

                                                            @if(!empty($image))
                                                            <img src="{{URL::to('/')}}/public/uploads/product/smallImage/{{$image}}"/>
                                                            @else
                                                            <img src="{{URL::to('/')}}/public/img/unknown.png" alt=""> 
                                                            @endif
                                                        </div>
                                                        <div class="fileinput-preview fileinput-exists thumbnail" style="width: 150px; height: 120px;"> </div>
                                                        <div>
                                                            <span class="btn red btn-outline btn-file">
                                                                <span class="fileinput-new"> @lang('label.SELECT_IMAGE') </span>
                                                                <span class="fileinput-exists"> @lang('label.CHANGE') </span>
                                                                {!! Form::file('product_image['.$image.']',null,['id'=> 'product_image'.$image]) !!}

                                                            </span>
                                                            {!!Form::hidden('prev_product_image['.$count.']',$image) !!}
                                                            <a href="javascript:;" class="btn red fileinput-exists" data-dismiss="fileinput"> @lang('label.REMOVE') </a>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="clearfix margin-top-10">
                                                        <span class="label label-success">@lang('label.NOTE')</span> <span class="text-danger bold">@lang('label.CONTACT_IMAGE_FOR_IMAGE_DESCRIPTION') </span>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php
                            $count++;
                            ?>
                            @endforeach
                            @else
                            <div>
                                <?php
                                $v3 = 'z' . uniqid();
                                ?>
                                <div class="col-md-12 contact-person-div">

                                    <div class="form-group">
                                        <div class="row">
                                            <label class="control-label col-md-2" for='contactPhoto'.{{$v3}}>@lang('label.PHOTO') :</label>
                                            <div class="col-md-8">
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="fileinput fileinput-new" data-provides="fileinput">
                                                            <div class="fileinput-new thumbnail" style="width: 150px; height: 120px;">

                                                            </div>
                                                            <div class="fileinput-preview fileinput-exists thumbnail" style="width: 150px; height: 120px;"> </div>
                                                            <div>
                                                                <span class="btn red btn-outline btn-file">
                                                                    <span class="fileinput-new"> @lang('label.SELECT_IMAGE') </span>
                                                                    <span class="fileinput-exists"> @lang('label.CHANGE') </span>
                                                                    {!! Form::file('product_image['.$v3.']',['id'=> 'productImage'.$v3]) !!}
                                                                </span>
                                                                <a href="javascript:;" class="btn red fileinput-exists" data-dismiss="fileinput"> @lang('label.REMOVE') </a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="clearfix margin-top-10">
                                                            <span class="label label-success">@lang('label.NOTE')</span> <span class="text-danger bold">@lang('label.CONTACT_IMAGE_FOR_IMAGE_DESCRIPTION') </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                            @endif
                        </div>

                    </div>
                    <!-- END:: Contact Person Data -->
                </div>

                <div class="form-actions">
                    <div class="row">
                        <div class="col-md-offset-4 col-md-8">
                            <button class="btn green btn-submit" type="submit">
                                <i class="fa fa-check"></i> @lang('label.SUBMIT')
                            </button>
                            <a href="{{ URL::to('/admin/product'.Helper::queryPageStr($qpArr)) }}" class="btn btn-outline grey-salsa">@lang('label.CANCEL')</a>
                        </div>
                    </div>
                </div>
            </div>
            {!! Form::close() !!}
        </div>	
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function () {
        $(document).on("click", ".add-contact-person", function () {
            $.ajax({
                url: "{{ route('product.newProductImage') }}",
                type: "POST",
                dataType: 'json', // what to expect back from the PHP script, if anything
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (res) {
                    $("#newContactPerson").prepend(res.html);
                    $(".tooltips").tooltip();
                },
            });
        });
    });
</script>
@stop