@php
    /**
     * Reusable pagination partial for Super Admin tables.
     * Expects a `$paginator` instance (LengthAwarePaginator).
     */
@endphp

@if(isset($paginator) && $paginator->hasPages())
    <div class="d-flex align-items-center justify-content-between mt-3">
        <div class="text-muted small">Showing {{ $paginator->firstItem() }} - {{ $paginator->lastItem() }} of {{ $paginator->total() }}</div>

        @php $elements = $paginator->elements(); @endphp
        <nav aria-label="Page navigation">
            <ul class="pagination mb-0">
                <li class="page-item {{ $paginator->onFirstPage() ? 'disabled' : '' }}">
                    <a class="page-link" href="{{ $paginator->previousPageUrl() }}" aria-label="Previous">&laquo;</a>
                </li>

                @foreach ($elements as $element)
                    @if (is_string($element))
                        <li class="page-item disabled"><span class="page-link">{{ $element }}</span></li>
                    @endif

                    @if (is_array($element))
                        @foreach ($element as $page => $url)
                            <li class="page-item {{ $page == $paginator->currentPage() ? 'active' : '' }}">
                                <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                            </li>
                        @endforeach
                    @endif
                @endforeach

                <li class="page-item {{ !$paginator->hasMorePages() ? 'disabled' : '' }}">
                    <a class="page-link" href="{{ $paginator->nextPageUrl() }}" aria-label="Next">&raquo;</a>
                </li>
            </ul>
        </nav>
    </div>
@endif
