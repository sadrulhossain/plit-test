<!-- Custom STYLES -->			
<link href="{{asset('public/assets/layouts/layout/css/custom.css')}}" rel="stylesheet" type="text/css" />		
<ul id="searchResult" class="product-name-width search-result">
    @if($nameArr->isNotEmpty())
    @foreach($nameArr as $proName)
    <li value="{{$proName->name}}" class="bold"><span>{{$proName->name}}</span></li>
    @endforeach
    @else
    <li class="no-data" value="" class="bold">No results Found</li>
    @endif
</ul>