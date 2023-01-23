@extends('layouts.default.master')
@section('data_count')
<div class="col-md-12">
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-cubes"></i>@lang('label.CREATE_PRODUCT')
            </div>
        </div>
        <div class="portlet-body form">
            {!! Form::open(array('group' => 'form', 'url' => 'product', 'files'=> true, 'class' => 'form-horizontal')) !!}
            {!! Form::hidden('filter', Helper::queryPageStr($qpArr)) !!}
            {{csrf_field()}}
            <div class="form-body">
                <div class="row">
                    <div class="col-md-offset-2 col-md-6">
                        
                        <div class="form-group">
                            <label class="control-label col-md-4" for="name">@lang('label.NAME') :<span class="text-danger"> *</span></label>
                            <div class="col-md-8">
                                {!! Form::text('name', null, ['id'=> 'name', 'class' => 'form-control']) !!} 
                                <span class="text-danger">{{ $errors->first('name') }}</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-4" for="description">@lang('label.DESCRIPTION'):<span class="text-danger"> </span></label>
                            <div class="col-md-8">
                                {!! Form::textarea('description', null, ['id' => 'description', 'class' => 'form-control', 'cols' => '20', 'rows' => '3']) !!}
                                <span class="text-danger">{{ $errors->first('description') }}</span>

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

                        <div class="form-group">
                            <label class="control-label col-md-4" for="features">@lang('label.FEATURES'):<span class="text-danger"> </span></label>
                            <div class="col-md-8">
                                {!! Form::textarea('features', null, ['id' => 'features', 'class' => 'form-control', 'cols' => '20', 'rows' => '3']) !!}
                                <span class="text-danger">{{ $errors->first('features') }}</span>

                            </div>
                        </div>
                        

                        <div class="form-group">
                            <label class="control-label col-md-4" for="imageUrl">@lang('label.IMAGE_URL') :<span class="text-danger"> </span></label>
                            <div class="col-md-8">
                                {!! Form::text('image_url', null, ['id'=> 'imageUrl', 'class' => 'form-control']) !!} 
                                <span class="text-danger">{{ $errors->first('image_url') }}</span>
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
<script type="text/javascript">
    $(document).ready(function () {

        $('#features').summernote({
            placeholder: 'Product Features',
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
