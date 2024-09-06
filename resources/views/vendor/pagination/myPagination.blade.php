@if ($paginator->hasPages())
<div class="catalog__bottom">
@if($paginator->hasMorePages())
    <a href="{{ $paginator->nextPageUrl() }}" class="btn btn-lg btn-outline-primary catalog__more">Показать еще 20</a>
@endif
    <ul class="list-reset pagination">
        <li class="pagination__item">
            <a href="{{ $paginator->previousPageUrl() }}" class="btn pagination__prev @if($paginator->onFirstPage()) active @endif"><span class="icon"><svg><use
                            xlink:href="img/icons/left.svg#svg-left"></use></svg></span>
            </a>
        </li>
        <li class="pagination__item">
            <ul class="list-reset pagination__page">
                @foreach ($elements as $element)
                        @if (is_string($element))
                            <li class="pagination__item">
                                <a class="btn pagination__btn">{{ $element }}</a>
                            </li>
                        @endif

                        @if (is_array($element))
                            @foreach ($element as $page => $url)
                                @if ($page == $paginator->currentPage())
                                        <li class="pagination__item">
                                            <a href="#" class="btn pagination__btn @if($paginator->currentPage()) active @endif">{{ $page }}</a>
                                        </li>
                                @else
                                    <li class="pagination__item">
                                        <a href="{{ $url }}" class="btn pagination__btn">{{ $page }}</a>
                                    </li>
                                @endif
                            @endforeach
                        @endif
                @endforeach
            </ul>
        </li>
        <li class="pagination__item">
            <a href="{{ $paginator->nextPageUrl() }}" class="btn pagination__next @if($paginator->onLastPage()) active @endif">
                <span class="icon">
                    <svg><use
                            xlink:href="img/icons/right.svg#svg-right"></use>
                    </svg>
                </span>
            </a>
        </li>
    </ul>
</div>
@endif
