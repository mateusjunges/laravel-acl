{{--@if (is_string($item))--}}
    {{--<li class="header">{{ $item }}</li>--}}
{{--@else--}}
    {{--<li class="{{ $item['class'] }}">--}}
        {{--<a href="{{ $item['href'] }}"--}}
           {{--@if (isset($item['target'])) target="{{ $item['target'] }}" @endif--}}
        {{-->--}}
            {{--<i class="fa fa-fw fa-{{ isset($item['icon']) ? $item['icon'] : 'circle-o' }} {{ isset($item['icon_color']) ? 'text-' . $item['icon_color'] : '' }}"></i>--}}
            {{--<span>{{ $item['text'] }}</span>--}}
            {{--@if (isset($item['label']))--}}
                {{--<span class="pull-right-container">--}}
                    {{--<span class="label label-{{ isset($item['label_color']) ? $item['label_color'] : 'primary' }} pull-right">{{ $item['label'] }}</span>--}}
                {{--</span>--}}
            {{--@elseif (isset($item['submenu']))--}}
                {{--<span class="pull-right-container">--}}
                {{--<i class="fa fa-angle-left pull-right"></i>--}}
                {{--</span>--}}
            {{--@endif--}}
        {{--</a>--}}
        {{--@if (isset($item['submenu']))--}}
            {{--<ul class="{{ $item['submenu_class'] }}">--}}
                {{--@each('acl::partials.menu-item', $item['submenu'], 'item')--}}
            {{--</ul>--}}
        {{--@endif--}}
    {{--</li>--}}
{{--@endif--}}

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
           class="nav-link {{ isset($item['submenu']) ? 'dropdown-toglle' : ''}}">
            {{ $item['text'] }}
        </a>
        @if(isset($item['submenu']))
            <div class="dropdown-menu">
                @each('acl::partials.menu-item', $item['submenu'], 'item')
            </div>
        @endif
    </li>
@endif