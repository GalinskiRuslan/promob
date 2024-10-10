@extends('layouts.main')
@section('content')
    <main class="main">
        <h1 class="visually-hidden">Главная</h1>
        <div class="main-sections">
            <section class="section hero">
                <div class="container">
                    <div class="hero__bg"
                        style="--background-desktop: url({{ asset('../img/background/banner.webp') }});--background-tablet: url({{ asset('../img/background/banner--tablet.webp') }});--background-mobile: url({{ asset('../img/background/banner--mobile.webp') }});">
                        <h2 class="hero__bg-title">Платформа <span class="primary-color">№1</span> <span
                                class="hero__bg-title_span">для <span class="primary-color">медиа-профессионалов</span> в
                                Казахстане</span>
                        </h2>
                        <ul class="list-reset hero__bg-list">
                            <li class="hero__bg-list_item">Находите лучших специалистов для ваших проектов</li>
                            <li class="hero__bg-list_item">Создайте портфолио и начните получать заказы</li>
                            <li class="hero__bg-list_item">Бесплатная регистрация</li>
                        </ul>
                        <ul class="list-reset hero__bg-features">
                            <li class="hero__bg-features_item">Просматривайте проверенные портфолио</li>
                            <li class="hero__bg-features_item">Прямое общение с профессионалами</li>
                            <li class="hero__bg-features_item">Удобный и интуитивный интерфейс</li>
                        </ul>
                        <form class="hero__form" method="GET" action="{{ route('search', $corrent_city->alias) }}">
                            <div class="form-field form-field--search">
                                <button class="btn form-field__btn">
                                    <span class="icon">
                                        <svg>
                                            <use xlink:href="{{ asset('img/icons/search.svg#svg-search') }}"></use>
                                        </svg>
                                    </span>
                                </button>
                                <input type="text" class="field" name="search" placeholder="Поиск">
                                <div class="filter">
                                    <button type="button" class="btn filter__btn">
                                        <span class="icon">
                                            <svg>
                                                <use xlink:href="{{ asset('img/icons/filter.svg#svg-filter') }}"></use>
                                            </svg>
                                        </span>
                                    </button>
                                    <div class="filter__dropdown">
                                        <div class="filter__content">
                                            <h3 class="filter__title">Стоимость</h3>
                                            <div class="slider-price">
                                                <div class="slider-price-display">
                                                    <span class="slider-price-display-min">500 тг</span>
                                                    <span class="slider-price-display-max">25 000 000 тг</span>
                                                </div>
                                                <div class="slider-price-range">
                                                    <span class="slider-price-track"></span>
                                                    <input type="range" name="min_value" class="slider-price-min"
                                                        min="500" max="500000" value="500">
                                                    <input type="range" name="max_value" class="slider-price-max"
                                                        min="500" max="500000" value="500000">
                                                </div>
                                                <div class="slider-price-current">
                                                    <span class="slider-price-current-min">500</span>
                                                    <span class="slider-price-current-max">25 000 000</span>
                                                </div>
                                            </div>
                                            <div class="filter__language">
                                                <h4 class="h3 filter__language-title">Язык</h4>
                                                <label class="form-checkbox">
                                                    <div class="form-field form-field--checkbox">
                                                        <input type="checkbox" class="checkbox checkbox--theme"
                                                            name="lang" value="ru" checked>
                                                        <span class="checkbox-check"></span>
                                                    </div>
                                                    <div class="form-checkbox__title">Русский</div>
                                                </label>
                                                <label class="form-checkbox">
                                                    <div class="form-field form-field--checkbox">
                                                        <input type="checkbox" class="checkbox checkbox--theme"
                                                            name="lang" value="kaz">
                                                        <span class="checkbox-check"></span>
                                                    </div>
                                                    <div class="form-checkbox__title">Казахский</div>
                                                </label>
                                                <label class="form-checkbox">
                                                    <div class="form-field form-field--checkbox">
                                                        <input type="checkbox" class="checkbox checkbox--theme"
                                                            name="lang" value="en">
                                                        <span class="checkbox-check"></span>
                                                    </div>
                                                    <div class="form-checkbox__title">Английский</div>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </section>
            <section class="section catalog">
                <div class="container catalog__container">
                    <ul class="list-reset menu__job menu__job--catalog">

                        @php
                            $categories = \App\Models\Category::all();
                        @endphp
                        @foreach ($categories as $category)
                            <li>
                                <a href="{{ route('city_category', ['city' => $corrent_city->alias, 'category' => $category->alias]) }}"
                                    class="btn menu__job-link"
                                    data-current="{{ App\Models\User::withCategoriesAndCity([$category->id], $corrent_city->id)->get()->count() }}">{{ $category->category }}</a>
                            </li>
                        @endforeach
                    </ul>
                    <div class="catalog__content">
                        @if (count($users) != 0)
                            @foreach ($users as $user)
                                @if ($user->photos && $user->name)
                                    <article class="card">
                                        <div class="card__header">
                                            <div class="card__profile">
                                                <picture class="card__profile-avatar">
                                                    <source
                                                        srcset="{{ $user->photos ? asset($user->photos) : asset('./img/avatars/avatar-1.png') }}"
                                                        type="image/webp">
                                                    <img loading="lazy"
                                                        src="{{ $user->photos ? asset($user->photos) : asset('./img/avatars/avatar-1.png') }}"
                                                        class="image" width="142" height="142" alt="Картинка">
                                                </picture>
                                                <div class="card__profile-info">
                                                    <a class="btn menu__job-link"
                                                        href="{{ route('user_view', $user->id) }}">
                                                        <h3 class="card__profile-name">
                                                            @if ($user->nickname_true)
                                                                <span translate="no">{{ $user->nickname }}</span>
                                                            @else
                                                                {{ $user->name }} {{ $user->surname }}
                                                            @endif
                                                        </h3>
                                                    </a>
                                                    <div class="card__profile-location"><span
                                                            class="icon icon-color icon-lg"><svg>
                                                                <use
                                                                    xlink:href="{{ asset('img/icons/location.svg#svg-location') ?? asset('./img/avatars/avatar-1.png') }}">
                                                                </use>
                                                            </svg> </span>{{ $user->city->city }}
                                                    </div>
                                                    @if ($user->whatsapp || $user->instagram)
                                                        <button type="button"
                                                            class="btn btn-md btn-primary btn-primary--theme card__profile-link"
                                                            data-graph-path="modal-contacts{{ $user->id }}">Контакты
                                                        </button>
                                                    @endif
                                                </div>
                                                <div>
                                                    <ul class="list-reset form-options">
                                                        @foreach ($user->getCategories() as $category)
                                                            <li class="form-option">
                                                                <div class="btn btn-lg btn-primary btn-primary--div">
                                                                    {{ $category->category }}
                                                                </div>
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                </div>
                                            </div>
                                            <div class="card__info">
                                                <a href="{{ auth()->user() && auth()->user()->role === 'client' ? route('comments.index', $user->id) : route('comments.index', $user->id) }}"
                                                    class="btn card__reviews"><span class="icon"><svg>
                                                            <use
                                                                xlink:href="{{ asset('img/icons/message.svg#svg-message') }}">
                                                            </use>
                                                        </svg> </span><span> {{ $user->receivedComments->count() }} <span
                                                            class="card__reviews-text">
                                                            @if ($user->receivedComments->count() == 1)
                                                                отзыв
                                                            @elseif($user->receivedComments->count() < 10 && $user->receivedComments->count() > 1)
                                                                отзыва
                                                            @elseif($user->receivedComments->count() > 10)
                                                                отзывов
                                                            @endif
                                                        </span></span>
                                                </a>
                                                <ul class="list-reset card__language notranslate">
                                                    @if ($user->language)
                                                        @foreach (json_decode($user->language) as $lang)
                                                            <li>{{ $lang }}</li>
                                                        @endforeach
                                                    @endif
                                                </ul>

                                            </div>
                                        </div>
                                        <div class="card__swiper swiper-control js-swiper-card">
                                            <div class="swiper">
                                                <div class="swiper-wrapper">
                                                    @if ($user->gallery)
                                                        @foreach (json_decode($user->gallery, true) as $galleryItem)
                                                            @if (preg_match('/(jpg|jpeg|png|gif|svg)$/i', $galleryItem))
                                                                <div class="swiper-slide">
                                                                    <picture class="swiper-slide-picture">
                                                                        <source srcset="{{ asset($galleryItem) }}"
                                                                            type="image/webp">
                                                                        <img loading="lazy"
                                                                            src="{{ asset($galleryItem) }}"
                                                                            class="image" width="348" height="224"
                                                                            alt="Картинка">
                                                                    </picture>
                                                                </div>
                                                            @endif
                                                            @if (preg_match('/\.?(mp4|mov|avi)$/i', $galleryItem))
                                                                <div class="swiper-slide">
                                                                    <video class="portfolio__item-video" width="350"
                                                                        height="224" preload="metadata"
                                                                        src="{{ asset($galleryItem) }}#t=0.001">
                                                                        <source src="{{ asset($galleryItem) }}">
                                                                    </video>
                                                                </div>
                                                            @endif
                                                        @endforeach
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="swiper-scrollbar"></div>
                                        </div>

                                        <div class="card__price"><span class="icon icon-color icon-xl"><svg>
                                                    <use xlink:href="{{ asset('img/icons/cost.svg#svg-cost') }}"></use>
                                                </svg></span>
                                            <div class="card__price-info">
                                                <h4 class="card__price-title">Стоимость</h4>
                                                <div class="card__price-value">{{ $user->cost_from }}
                                                    - {{ $user->cost_up }}
                                                    тг
                                                </div>
                                            </div>
                                        </div>
                                    </article>
                                @endif
                            @endforeach
                        @else
                            <div class="catalog__content catalog__content--centered">
                                <p class="catalog__empty">
                                    Увы, в вашем городе пока нет специалистов
                                </p>
                            </div>
                        @endif
                    </div>
                </div>
            </section>
        </div>
    </main>
    <style>
        .pagination__btn.active {
            color: var(--_color-hover);
            background-color: var(--_background-color-hover);
            border-color: var(--_border-color-hover);
            opacity: var(--_opacity-hover);
            pointer-events: none;
            cursor: default;
        }

        .pagination__next.active,
        .pagination__prev.active {
            opacity: .5;
            pointer-events: none;
            cursor: default;
        }
    </style>
@endsection
@section('modal_contact')
    @foreach ($corrent_city->users as $user)
        @if ($user->whatsapp || $user->instagram)
            <div class="graph-modal__container graph-modal__container--contacts" role="dialog" aria-modal="true"
                data-graph-target="modal-contacts{{ $user->id }}">
                <button class="btn js-modal-close graph-modal__close" aria-label="Закрыть модальное окно"><span
                        class="icon"><svg>
                            <use xlink:href="{{ asset('img/icons/x.svg#svg-x') }}"></use>
                        </svg></span>
                </button>
                <div class="graph-modal__content">
                    <ul class="list-reset graph-modal__contacts">
                        @if ($user->site)
                            <li>
                                <a href="https://{{ $user->site }}" target="_blank"
                                    class="btn graph-modal__contacts-link"><span class="icon">
                                        <svg fill="#000000" height="48px" width="48px" version="1.1" id="Capa_1"
                                            xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                            viewBox="0 0 490 490" xml:space="preserve">
                                            <path
                                                d="M245,0C109.69,0,0,109.69,0,245s109.69,245,245,245s245-109.69,245-245S380.31,0,245,0z M31.401,260.313h52.542
                                                                                            c1.169,25.423,5.011,48.683,10.978,69.572H48.232C38.883,308.299,33.148,284.858,31.401,260.313z M320.58,229.688
                                                                                            c-1.152-24.613-4.07-47.927-8.02-69.572h50.192c6.681,20.544,11.267,43.71,12.65,69.572H320.58z M206.38,329.885
                                                                                            c-4.322-23.863-6.443-47.156-6.836-69.572h90.913c-0.392,22.416-2.514,45.709-6.837,69.572H206.38z M276.948,360.51
                                                                                            c-7.18,27.563-17.573,55.66-31.951,83.818c-14.376-28.158-24.767-56.255-31.946-83.818H276.948z M199.961,229.688
                                                                                            c1.213-24.754,4.343-48.08,8.499-69.572h73.08c4.157,21.492,7.286,44.818,8.5,69.572H199.961z M215.342,129.492
                                                                                            c9.57-37.359,21.394-66.835,29.656-84.983c8.263,18.148,20.088,47.624,29.66,84.983H215.342z M306.07,129.492
                                                                                            c-9.77-40.487-22.315-73.01-31.627-94.03c11.573,8.235,50.022,38.673,76.25,94.03H306.07z M215.553,35.46
                                                                                            c-9.312,21.02-21.855,53.544-31.624,94.032h-44.628C165.532,74.13,203.984,43.692,215.553,35.46z M177.44,160.117
                                                                                            c-3.95,21.645-6.867,44.959-8.019,69.572h-54.828c1.383-25.861,5.968-49.028,12.65-69.572H177.44z M83.976,229.688H31.401
                                                                                            c1.747-24.545,7.481-47.984,16.83-69.572h46.902C89.122,181.002,85.204,204.246,83.976,229.688z M114.577,260.313h54.424
                                                                                            c0.348,22.454,2.237,45.716,6.241,69.572h-47.983C120.521,309.288,115.92,286.115,114.577,260.313z M181.584,360.51
                                                                                            c7.512,31.183,18.67,63.054,34.744,95.053c-10.847-7.766-50.278-38.782-77.013-95.053H181.584z M273.635,455.632
                                                                                            c16.094-32.022,27.262-63.916,34.781-95.122h42.575C324.336,417.068,284.736,447.827,273.635,455.632z M314.759,329.885
                                                                                            c4.005-23.856,5.894-47.118,6.241-69.572h54.434c-1.317,25.849-5.844,49.016-12.483,69.572H314.759z M406.051,260.313h52.548
                                                                                            c-1.748,24.545-7.482,47.985-16.831,69.572h-46.694C401.041,308.996,404.882,285.736,406.051,260.313z M406.019,229.688
                                                                                            c-1.228-25.443-5.146-48.686-11.157-69.572h46.908c9.35,21.587,15.083,45.026,16.83,69.572H406.019z M425.309,129.492h-41.242
                                                                                            c-13.689-32.974-31.535-59.058-48.329-78.436C372.475,68.316,403.518,95.596,425.309,129.492z M154.252,51.06
                                                                                            c-16.792,19.378-34.636,45.461-48.324,78.432H64.691C86.48,95.598,117.52,68.321,154.252,51.06z M64.692,360.51h40.987
                                                                                            c13.482,32.637,31.076,58.634,47.752,78.034C117.059,421.262,86.318,394.148,64.692,360.51z M336.576,438.54
                                                                                            c16.672-19.398,34.263-45.395,47.742-78.03h40.99C403.684,394.146,372.945,421.258,336.576,438.54z" />
                                        </svg>
                                    </span>{{ $user->site }}
                                </a>
                            </li>
                        @endif
                        @if ($user->whatsapp)
                            <li>
                                <a href="https://wa.me/{{ $user->whatsapp }}" target="_blank"
                                    class="btn graph-modal__contacts-link"><span class="icon"><svg>
                                            <use xlink:href="{{ asset('img/icons/whatsapp.svg#svg-whatsapp') }}"></use>
                                        </svg> </span>{{ $user->whatsapp }}
                                </a>
                            </li>
                        @endif
                        <li><a href="{{ $user->site }}" target="_blank"
                                class="btn graph-modal__contacts-link">{{ $user->site }}</a></li>
                        @if ($user->instagram)
                            <li>
                                <a href="https://instagram.com/{{ $user->instagram }}" target="_blank"
                                    class="btn graph-modal__contacts-link"><span class="icon"><svg>
                                            <use xlink:href="{{ asset('img/icons/instagram.svg#svg-instagram') }}"></use>
                                        </svg> </span>{{ $user->instagram }}
                                </a>
                            </li>
                        @endif
                    </ul>
                </div>
            </div>
        @endif
    @endforeach
@endsection
