@if ($paginator->hasPages())
    <div class="custom-pagination">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <span class="pagination-btn disabled">
                <i class="ri-arrow-left-line"></i>
            </span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" class="pagination-btn" rel="prev">
                <i class="ri-arrow-left-line"></i>
            </a>
        @endif

        {{-- Pagination Elements --}}
        @foreach ($elements as $element)
            {{-- "Three Dots" Separator --}}
            @if (is_string($element))
                <span class="pagination-btn disabled">{{ $element }}</span>
            @endif

            {{-- Array Of Links --}}
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <span class="pagination-btn active">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}" class="pagination-btn">{{ $page }}</a>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" class="pagination-btn" rel="next">
                <i class="ri-arrow-right-line"></i>
            </a>
        @else
            <span class="pagination-btn disabled">
                <i class="ri-arrow-right-line"></i>
            </span>
        @endif
    </div>
@endif