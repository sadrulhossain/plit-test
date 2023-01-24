@extends('layouts.default.master')
@section('data_count')
<div class="col-md-12">
    @include('layouts.flash')
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-cubes"></i>@lang('label.PRODUCT_LIST')
            </div>
            <div class="actions">
                <a class="btn btn-default btn-sm create-new" href="{{ URL::to('product/create'.Helper::queryPageStr($qpArr)) }}"> @lang('label.CREATE_NEW_PRODUCT')
                    <i class="fa fa-plus create-new"></i>
                </a>
            </div>
        </div>
        <div class="portlet-body">
            <!-- Begin Filter-->
            {!! Form::open(array('group' => 'form', 'url' => 'product/filter','class' => 'form-horizontal')) !!}
            {!! Form::hidden('page', Helper::queryPageStr($qpArr)) !!}
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="control-label col-md-4" for="search">@lang('label.NAME')</label>
                        <div class="col-md-8">
                            {!! Form::text('search', Request::get('search'), ['class' => 'form-control tooltips', 'title' => 'Name', 'placeholder' => 'Name','list' => 'productName','autocomplete' => 'off']) !!}
                            <datalist id="productName">
                                @if (!$nameArr->isEmpty())
                                @foreach($nameArr as $item)
                                <option value="{{$item->name}}" />
                                @endforeach
                                @endif
                            </datalist>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 text-center">
                    <div class="form">
                        <button type="submit" class="btn btn-md green btn-outline filter-submit margin-bottom-20">
                            <i class="fa fa-search"></i> @lang('label.FILTER')
                        </button>
                    </div>
                </div>
            </div>

            {!! Form::close() !!}
            <!-- End Filter -->

            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr class="info">
                            <th class="text-center vcenter">@lang('label.SL_NO')</th>
                            <th class="vcenter">@lang('label.NAME')</th>
                            <th class="vcenter">@lang('label.SLUG')</th>
                            <th class="vcenter text-center">@lang('label.IMAGE')</th>
                            <th class="vcenter text-center">@lang('label.QUANTITY')</th>
                            <th class="vcenter text-center">@lang('label.PRICE')</th>
                            <th class="text-center vcenter">@lang('label.STATUS')</th>
                            <th class="text-center vcenter">@lang('label.ACTION')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if (!$targetArr->isEmpty())
                        <?php
                        $page = Request::get('page');
                        $page = empty($page) ? 1 : $page;
                        $sl = ($page - 1) * Session::get('paginatorCount');
                        ?>
                        @foreach($targetArr as $product)
                        <tr>
                            <td class="text-center vcenter">{!! ++$sl !!}</td>
                            <td class="vcenter">{!! $product->name !!} </td>
                            <td class="vcenter">{!! $product->slug !!}</td>
                            <td class="text-center vcenter">
                                @if (!empty($product->image) && File::exists('public/uploads/product/' . $product->image))
                                <img width="40" height="40" src="{{ URL::to('/') }}/public/uploads/product/{{ $product->image }}" alt="{{ $product->full_name }}" />
                                @else
                                <img width="40" height="40" src="{{ URL::to('/') }}/public/img/no_image.png" alt="{{ $product->name }}" />
                                @endif
                            </td>
                            <td class="vcenter text-right">{!! $product->quantity ?? '0' !!}</td>
                            <td class="vcenter text-right">{!! $product->price ?? '0.00' !!} @lang('label.TK')</td>

                            <td class="text-center vcenter">
                                @if($product->status == '1')
                                <span class="label label-sm label-success">@lang('label.ACTIVE')</span>
                                @else
                                <span class="label label-sm label-warning">@lang('label.INACTIVE')</span>
                                @endif
                            </td>

                            <td class="td-actions text-center vcenter">
                                <div class="width-inherit">
                                    <a class="btn btn-xs btn-primary tooltips vcenter" title="Edit" href="{{ URL::to('product/' . $product->id . '/edit'.Helper::queryPageStr($qpArr)) }}">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                    {!! Form::open(array('url' => 'product/' . $product->id.'/'.Helper::queryPageStr($qpArr), 'class' => 'delete-form-inline')) !!}
                                    {!! Form::hidden('_method', 'DELETE') !!}
                                    <button class="btn btn-xs btn-danger delete tooltips vcenter" title="Delete" type="button" data-placement="top" data-rel="tooltip" data-original-title="Delete">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                    {!! Form::close() !!}
                                </div>
                            </td>
                        </tr>
                        @endforeach
                        @else
                        <tr>
                            <td colspan="14" class="vcenter">@lang('label.NO_PRODUCT_FOUND')</td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>
            @include('layouts.paginator')
        </div>
    </div>
</div>


<script type="text/javascript">
    $(function() {

    });
</script>

@stop