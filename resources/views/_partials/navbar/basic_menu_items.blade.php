<ul class="dropdown-menu">
  @foreach($items as $item)
  <li@lm-attrs($item) @if($item->hasChildren())class ="dropdown-submenu"@endif @lm-endattrs>
    @if($item->link) <a@lm-attrs($item->link) @lm-endattrs href="{!! $item->url() !!}">
      {!! $item->title !!}
    </a>
    @else
      {!! $item->title !!}
    @endif
    @if($item->hasChildren())
      @include('_partials.navbar.basic_menu_items', ['items' => $item->children()])
    @endif
  </li>
  @endforeach
</ul>
