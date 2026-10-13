{{--
    Satu menu di navbar.
    $index : urutan menu (dipakai untuk memindahkan menu yang tidak muat ke "Lainnya")
    $cta   : true untuk tombol menonjol (mis. Pendaftaran)
--}}
@php
    $isCta = ! empty($cta);
    $long = mb_strlen($menu->title) > 22;
@endphp

@if ($menu->isGroup())
    @if ($menu->children->isNotEmpty())
        <li class="nav-item dropdown {{ $isCta ? 'oa-nav-cta' : '' }}" @isset($index) data-nav-index="{{ $index }}" @endisset>
            <a class="nav-link dropdown-toggle {{ $menu->isCurrent() ? 'active' : '' }}" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false" @if ($long) title="{{ $menu->title }}" @endif>
                @if ($menu->icon)<i class="bi {{ $menu->icon }}"></i>@endif<span class="oa-nav-label">{{ $menu->title }}</span>
            </a>
            <ul class="dropdown-menu {{ $isCta ? 'dropdown-menu-end' : '' }}">
                @foreach ($menu->children as $child)
                    <li>
                        <a class="dropdown-item {{ $child->isCurrent() ? 'active' : '' }}" href="{{ $child->link }}" target="{{ $child->target }}">
                            <i class="bi {{ $child->type_icon }}"></i> {{ $child->title }}
                        </a>
                    </li>
                @endforeach
            </ul>
        </li>
    @endif
@else
    <li class="nav-item {{ $isCta ? 'oa-nav-cta' : '' }}" @isset($index) data-nav-index="{{ $index }}" @endisset>
        <a class="nav-link {{ $menu->isCurrent() ? 'active' : '' }}" href="{{ $menu->link }}" target="{{ $menu->target }}" @if ($long) title="{{ $menu->title }}" @endif>
            @if ($menu->icon)<i class="bi {{ $menu->icon }}"></i>@endif<span class="oa-nav-label">{{ $menu->title }}</span>
        </a>
    </li>
@endif