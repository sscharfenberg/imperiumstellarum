@if ($paginator->hasPages())
    <nav class="pagination__links">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <span class="icon disabled" aria-disabled="true" aria-label="@lang('pagination.previous')">
                <x-icon name="chevron_left" size="2" />
            </span>
        @else
            <a class="icon" href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="@lang('pagination.previous')">
                <x-icon name="chevron_left" size="2" />
            </a>
        @endif

        {{-- Pagination Elements --}}
        @foreach ($elements as $element)
            {{-- "Three Dots" Separator --}}
            @if (is_string($element))
                <span class="page disabled" aria-disabled="true">{{ $element }}</span>
            @endif

            {{-- Array Of Links --}}
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <span class="page active" aria-current="page">{{ $page }}</span>
                    @else
                        <a class="page" href="{{ $url }}">{{ $page }}</a>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <a class="icon" href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="@lang('pagination.next')">
                <x-icon name="chevron_right" size="2" />
            </a>
        @else
            <span class="icon disabled" aria-disabled="true" aria-label="@lang('pagination.next')">
                <x-icon name="chevron_right" size="2" />
            </span>
        @endif
    </nav>
@endif
