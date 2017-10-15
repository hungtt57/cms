@foreach($items as $item)
  <li@lm-attrs($item) @lm-endattrs>
    @if($item->link) <a@lm-attrs($item->link) @lm-endattrs href="{!! $item->url() !!}">
      {!! $item->title !!}
    </a>
    @else
      {!! $item->title !!}
    @endif
    @if($item->hasChildren())
      <ul>
        @include('_partials.navbar.mega_menu_items', ['items' => $item->children()])
      </ul>
    @endif
  </li>
@endforeach
