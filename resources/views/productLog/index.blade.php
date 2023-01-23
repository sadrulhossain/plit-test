@extends('layouts.default.master')
@section('data_count')
<div class="col-md-12">
    @include('layouts.flash')
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-cubes"></i>@lang('label.PRODUCT_LOG')
            </div>
            <div class="actions">
                
            </div>
        </div>
        <div class="portlet-body">
            <!-- Begin Filter-->
            {!! Form::open(array('group' => 'form', 'url' => 'productLog/filter','class' => 'form-horizontal')) !!}
            {!! Form::hidden('page', Helper::queryPageStr($qpArr)) !!}
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="control-label col-md-4" for="search">@lang('label.NAME')</label>
                        <div class="col-md-8">
                            {!! Form::text('search',  Request::get('search'), ['class' => 'form-control tooltips', 'title' => 'Name', 'placeholder' => 'Name','list' => 'productName','autocomplete' => 'off']) !!}
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
                            <th class="vcenter text-center">@lang('label.IMAGE')</th>
                            <th class="text-center vcenter">@lang('label.ACTION_TAKEN')</th>
                            <th class="vcenter">@lang('label.TAKEN_BY')</th>
                            <th class="text-center vcenter">@lang('label.TAKEN_AT')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if (!$targetArr->isEmpty())
                        <?php
                        $page = Request::get('page');
                        $page = empty($page) ? 1 : $page;
                        $sl = ($page - 1) * Session::get('paginatorCount');
                        ?>
                        @foreach($targetArr as $target)
                        <tr>
                            <td class="text-center vcenter">{!! ++$sl !!}</td>
                            <td class="vcenter">{!! $target->name !!} </td>
                            <td class="text-center vcenter">
                                @if (!empty($target->image_url))
                                <img width="40" height="40" src="{{ $target->image_url }}" alt="{{ $target->name }}" />
                                @else
                                <img width="40" height="40" src="{{ URL::to('/') }}/public/img/no_image.png" alt="{{ $target->name }}" />
                                @endif
                            </td>
                            
                            <td class="text-center vcenter">
                                @if($target->action == '1')
                                <span class="label label-sm label-success">@lang('label.CREATED')</span>
                                @elseif($target->action == '2')
                                <span class="label label-sm label-info">@lang('label.UPDATED')</span>
                                @endif
                            </td>
                            
                            <td class="vcenter">{!! $target->action_taken_by !!} </td>
                            
                            <td class="text-center vcenter">{!! !empty($target->taken_at) ? Helper::formatDateTime($target->taken_at) : '' !!} </td>
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
    $(function () {
        
    });

    
</script>

@stop
