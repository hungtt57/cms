<div class="dropdown-menu dropdown-content">
  <div class="dropdown-content-body">
    <div class="row">
      @foreach($items as $item)
      <div class="col-md-3">
        <span class="menu-heading underlined">{!! $item->title !!}</span>
        @if ($item->hasChildren())
        <ul class="menu-list">
          @include('_partials.navbar.mega_menu_items', ['items' => $item->children()])
        </ul>
        @endif
      </div>
      @endforeach
    </div>
  </div>
</div>
