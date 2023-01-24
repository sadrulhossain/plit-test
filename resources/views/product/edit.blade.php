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
            {!! Form::model($product, ['route' => array('product.update', $product->id), 'method' => 'PATCH', 'files'=> true, 'class' => 'form-horizontal'] ) !!}
            {!! Form::hidden('filter', Helper::queryPageStr($qpArr)) !!}
            {{csrf_field()}}
            <div class="form-body">
                <div class="row">
                    <div class="col-md-6">

                        <div class="form-group">
                            <label class="control-label col-md-4" for="name">@lang('label.NAME') :<span class="text-danger"> *</span></label>
                            <div class="col-md-8">
                                {!! Form::text('name', null, ['id'=> 'name', 'class' => 'form-control']) !!}
                                <span class="text-danger">{{ $errors->first('name') }}</span>
                                <span class="text-danger">{{ $errors->first('slug') }}</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-4" for="description">@lang('label.DESCRIPTION'):<span class="text-danger"> </span></label>
                            <div class="col-md-8">
                                {!! Form::textarea('description', $productDetail->description ?? null, ['id' => 'description', 'class' => 'form-control', 'cols' => '20', 'rows' => '3']) !!}
                                <span class="text-danger">{{ $errors->first('description') }}</span>

                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-4" for="features">@lang('label.FEATURES'):<span class="text-danger"> </span></label>
                            <div class="col-md-8">
                                {!! Form::textarea('features', $productDetail->features ?? null, ['id' => 'features', 'class' => 'form-control', 'cols' => '20', 'rows' => '3']) !!}
                                <span class="text-danger">{{ $errors->first('features') }}</span>

                            </div>
                        </div>



                        <div class="form-group">
                            <label class="control-label col-md-4" for="status">@lang('label.STATUS') :</label>
                            <div class="col-md-8">
                                {!! Form::select('status', ['1' => __('label.ACTIVE'), '2' => __('label.INACTIVE')], '1', ['class' => 'form-control js-source-states-2', 'id' => 'status']) !!}
                                <span class="text-danger">{{ $errors->first('status') }}</span>
                            </div>
                        </div>

                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label col-md-4" for="image">@lang('label.IMAGE') :<span class="text-danger"> </span></label>
                            <div class="col-md-8">
                                <div class="fileinput fileinput-new" data-provides="fileinput">
                                    <div class="fileinput-preview thumbnail" data-trigger="fileinput" style="width: 200px; height: 150px;">
                                        @if(!empty($productDetail->image))
                                        <img src="{{URL::to('/')}}/public/uploads/product/{{$productDetail->image}}" alt="{{ $product->name}}" />
                                        @endif
                                    </div>
                                    <div>
                                        <span class="btn green-seagreen btn-outline btn-file">
                                            <span class="fileinput-new"> Select image </span>
                                            <span class="fileinput-exists"> Change </span>
                                            {!! Form::file('image', null, ['id'=> 'image']) !!}
                                        </span>
                                        <a href="javascript:;" class="btn green-seagreen fileinput-exists" data-dismiss="fileinput"> Remove </a>
                                    </div>
                                </div>
                                <div class="clearfix margin-top-10">
                                    <span class="label label-danger">@lang('label.NOTE')</span> @lang('label.USER_IMAGE_FOR_IMAGE_DESCRIPTION')
                                </div>
                                <span class="text-danger">{{ $errors->first('image') }}</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-4" for="quantity">@lang('label.QUANTITY') :<span class="text-danger"> *</span></label>
                            <div class="col-md-8">
                                {!! Form::text('quantity', null, ['id'=> 'quantity', 'class' => 'text-right integer-only form-control']) !!}
                                <span class="text-danger">{{ $errors->first('quantity') }}</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-4" for="price">@lang('label.PRICE') :<span class="text-danger"> *</span></label>
                            <div class="col-md-8">
                                {!! Form::text('price', null, ['id'=> 'price', 'class' => 'text-right integer-decimal-only form-control']) !!}
                                <span class="text-danger">{{ $errors->first('price') }}</span>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <div class="form-actions">
                <div class="row">
                    <div class="col-md-offset-4 col-md-8">
                        <button class="btn btn-circle green" type="submit">
                            <i class="fa fa-check"></i> @lang('label.SUBMIT')
                        </button>
                        <a href="{{ URL::to('/product'.Helper::queryPageStr($qpArr)) }}" class="btn btn-circle btn-outline grey-salsa">@lang('label.CANCEL')</a>
                    </div>
                </div>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>
<script type="text/javascript" src="product.js"></script>
<script type="text/javascript">
    $(document).ready(function() {


        $('#features').summernote({
            placeholder: 'Product Description',
            tabsize: 2,
            height: 100,
            toolbar: [
                // [groupName, [list of button]]
                ['style', ['bold', 'italic', 'underline', 'clear']],
                ['font', ['strikethrough', 'superscript', 'subscript']],
                ['fontsize', ['fontsize']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['height', ['height']]
            ]
        });
    });
</script>
@stop