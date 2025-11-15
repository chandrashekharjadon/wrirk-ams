@if ($paginator->hasPages())
    <nav>
        <ul class="pagination justify-content-center ultimate-pagination my-4">

            {{-- Previous Page --}}
            @if ($paginator->onFirstPage())
                <li class="page-item disabled">
                    <span class="page-glass">&laquo;</span>
                </li>
            @else
                <li class="page-item">
                    <a class="page-glass" href="{{ $paginator->previousPageUrl() }}" rel="prev">&laquo;</a>
                </li>
            @endif

            {{-- Logic --}}
            @php
                $adjacents = 2;
                $current = $paginator->currentPage();
                $lastPage = $paginator->lastPage();
                $start = max(1, $current - $adjacents);
                $end = min($lastPage, $current + $adjacents);
            @endphp

            {{-- First Page --}}
            @if ($start > 1)
                <li><a class="page-glass" href="{{ $paginator->url(1) }}">1</a></li>
                @if ($start > 2)
                    <li><span class="page-glass dots">...</span></li>
                @endif
            @endif

            {{-- Page Numbers --}}
            @for ($i = $start; $i <= $end; $i++)
                @if ($i == $current)
                    <li><span class="page-glass active">{{ $i }}</span></li>
                @else
                    <li><a class="page-glass" href="{{ $paginator->url($i) }}">{{ $i }}</a></li>
                @endif
            @endfor

            {{-- Last Page --}}
            @if ($end < $lastPage)
                @if ($end < $lastPage - 1)
                    <li><span class="page-glass dots">...</span></li>
                @endif
                <li><a class="page-glass" href="{{ $paginator->url($lastPage) }}">{{ $lastPage }}</a></li>
            @endif

            {{-- Next Page --}}
            @if ($paginator->hasMorePages())
                <li><a class="page-glass" href="{{ $paginator->nextPageUrl() }}" rel="next">&raquo;</a></li>
            @else
                <li class="page-item disabled">
                    <span class="page-glass">&raquo;</span>
                </li>
            @endif

        </ul>
    </nav>
@endif

{{-- Custom CSS --}}
<style>
    .ultimate-pagination {
        display: flex;
        gap: 0.5rem;
        flex-wrap: wrap;
        backdrop-filter: blur(6px);
    }

    .ultimate-pagination .page-glass {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 42px;
        height: 42px;
        font-weight: 600;
        font-size: 0.95rem;
        color: #224abe;
        text-decoration: none;
        border-radius: 12px;
        background: rgba(255, 255, 255, 0.85);
        border: 1px solid rgba(78, 115, 223, 0.2);
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.06);
        transition: all 0.25s ease-in-out;
    }

    .ultimate-pagination .page-glass:hover {
        background: linear-gradient(135deg, #4e73df, #224abe);
        color: #fff;
        box-shadow: 0 6px 12px rgba(78, 115, 223, 0.35);
        transform: translateY(-2px);
    }

    .ultimate-pagination .page-glass.active {
        background: linear-gradient(135deg, #4e73df, #224abe);
        color: #fff;
        box-shadow: 0 8px 16px rgba(78, 115, 223, 0.4);
        transform: translateY(-1px);
        font-weight: 700;
    }

    .ultimate-pagination .page-glass.dots {
        background: transparent;
        color: #9aa0b9;
        border: none;
        pointer-events: none;
        box-shadow: none;
    }

    .ultimate-pagination .page-item.disabled .page-glass {
        background: #f1f3f8;
        color: #a1a9c2;
        border: none;
        box-shadow: none;
        cursor: not-allowed;
    }
</style>
