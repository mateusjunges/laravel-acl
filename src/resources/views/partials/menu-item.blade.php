@if(!is_string($item))
    <li class="nav-item {{ isset($item['submenu']) ? 'dropdown' : '' }}">
        <a href="{{ $item['href'] }}"
           id="{{ isset($item['id']) ? $item['id'] : '' }}"
           role="{{ isset($item['role']) ? $item['role'] : '' }}"
           @if(isset($item['submenu']))
               data-toggle="dropdown"
               aria-haspopup="true"
               aria-expanded="false"
           @endif
           class="nav-link {{ isset($item['submenu']) ? 'dropdown-toggle' : ''}}">
            {{ $item['text'] }}
        </a>
        @if(isset($item['submenu']))
            <div class="dropdown-menu">
                <ul>
                    @each('acl::partials.menu-item', $item['submenu'], 'item')
                </ul>
            </div>
        @endif
    </li>
@endif