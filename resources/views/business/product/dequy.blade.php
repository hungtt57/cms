@foreach ($items as $item)

    @if(isset($item['sub']))
        {{--<option disabled  data-level="{{$item['level']}}" value="{{ $item['id']}}">{{$item['name']}}</option>--}}
    @else
        <option @if(in_array($item['id'],$selectedCategories)) selected @endif data-level="{{$item['level']}}" data-attr="{{$item['attributes']}}" value="{{ $item['id']}}">{{ $item['name']}}</option>
    @endif

    @if(isset($item['sub']))
        @include('staff.management.product2.dequy', array('items' => $item['sub'],'selectedCategories' => $selectedCategories)))
    @endif


@endforeach