@extends('layouts.main')
@section('content')
    <main class="main">
        <h1 class="visually-hidden">Страница специалиста</h1>
        <div class="main-sections">
            <section class="section">
                <div class="container">
                    <div class="card card--header card--light card--edit">
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
                                    <h3 class="card__profile-name">
                                        @if ($user->nickname_true)
                                            <span translate="no">{{ $user->nickname }}</span>
                                        @else
                                            {{ $user->name }} {{ $user->surname }}
                                        @endif
                                        @auth()
                                            @if ($user->id == auth()->user()->id)
                                                <a style="display: flex; align-items: center;"
                                                    href="{{ route('user_edit', auth()->user()->id) }}">
                                                    <svg width="25" height="24" viewBox="0 0 25 24" fill="none"
                                                        xmlns="http://www.w3.org/2000/svg">
                                                        <path
                                                            d="M17.3076 2.99981C17.5703 2.73717 17.8821 2.52883 18.2252 2.38669C18.5684 2.24455 18.9362 2.17139 19.3076 2.17139C19.6791 2.17139 20.0468 2.24455 20.39 2.38669C20.7332 2.52883 21.045 2.73717 21.3076 2.99981C21.5703 3.26246 21.7786 3.57426 21.9207 3.91742C22.0629 4.26058 22.136 4.62838 22.136 4.99981C22.136 5.37125 22.0629 5.73905 21.9207 6.08221C21.7786 6.42537 21.5703 6.73717 21.3076 6.99981L7.80762 20.4998L2.30762 21.9998L3.80762 16.4998L17.3076 2.99981Z"
                                                            stroke="#25282B" stroke-width="2" stroke-linecap="round"
                                                            stroke-linejoin="round" />
                                                    </svg>
                                                </a>
                                            @endif
                                        @endauth
                                    </h3>
                                    <div class="card__profile-location"><span class="icon icon-color icon-lg"><svg>
                                                <use xlink:href="{{ asset('img/icons/location.svg#svg-location') }}">
                                                </use>
                                            </svg> </span>{{ $user->city->city }}
                                    </div>
                                    @if ($user->whatsapp || $user->instagram)
                                        <button type="button"
                                            class="btn btn-md btn-primary btn-primary--inverse card__profile-link"
                                            data-graph-path="modal-contacts{{ $user->id }}">Контакты
                                        </button>
                                    @endif
                                    @auth()
                                        @if ($user->id == auth()->user()->id)
                                            <div style="margin-top: 15px; flex-direction: column; align-items: flex-start;"
                                                class="card__info">
                                                <a href="{{ route('statistic') }}" class="btn card__statistics"><span
                                                        class="icon"><svg>
                                                            <use xlink:href="{{ asset('img/icons/bar.svg#svg-bar') }}">
                                                            </use>
                                                        </svg> </span>Статистика</a>
                                                <ul style="justify-content: flex-start;"
                                                    class="list-reset card__language notranslate">
                                                    @if ($user->language)
                                                        @foreach (json_decode($user->language) as $lang)
                                                            <li>{{ $lang }}</li>
                                                        @endforeach
                                                    @endif
                                                </ul>
                                            </div>
                                        @endif
                                    @endauth
                                </div>
                            </div>
                            @guest()
                                <div class="card__info">
                                    <ul class="list-reset card__language notranslate">
                                        @if ($user->language)
                                            @foreach (json_decode($user->language) as $lang)
                                                <li>{{ $lang }}</li>
                                            @endforeach
                                        @endif
                                    </ul>
                                </div>
                            @endguest
                        </div>
                    </div>
                </div>
            </section>
            <section class="section">
                <div class="container">
                    <div class="section-header">
                        <h2 class="h1 section-title">Портфолио</h2>
                    </div>
                    @if ($user->gallery)
                        <x-potrfolio-items :items="$user->gallery" />
                    @endif
                </div>
            </section>
            <section class="section" style="margin-top: 0;">
                <div class="container">
                    <ul class="list-reset form-options">
                        @foreach ($user->getCategories() as $category)
                            <li class="form-option">
                                <div class="btn btn-lg btn-primary btn-primary--div">
                                    {{ $category->category }}
                                </div>
                            </li>
                        @endforeach
                    </ul>
                    <div class="info">
                        <div class="info__price"><span class="icon icon-color icon-xxl"><svg>
                                    <use xlink:href="{{ asset('img/icons/cost.svg#svg-cost') }}"></use>
                                </svg></span>
                            <div class="info__price-info">
                                <h4 class="h2 info__price-title">Стоимость</h4>
                                <div class="info__price-value">от {{ $user->cost_from }} тг до {{ $user->cost_up }}тг
                                </div>
                            </div>
                        </div>
                        <div class="info__description">
                            <h3 class="h2 info__description-title">Детали работы</h3>
                            <p class="info__description-text">{{ $user->details ?? null }}</p>
                        </div>
                        <div class="info__description">
                            <h3 class="h2 info__description-title">Обо мне</h3>
                            <p class="info__description-text">{{ $user->about_yourself ?? null }}</p>
                        </div>
                    </div>
                </div>
            </section>
            <section class="section">
                <div class="container">
                    @guest
                        <div class="section-header">
                            <h2 class="h1 section-title">Отзывы
                                ({{ $user->receivedComments->count() ?? 0 }})
                            </h2>
                            <button type="button" class="btn btn-primary btn-primary--theme btn-review"
                                data-graph-path="modal-review-sign"><span class="icon"><svg>
                                        <use xlink:href="{{ asset('img/icons/message.svg#svg-message') }}"></use>
                                    </svg> </span>Добавить
                                отзыв
                            </button>
                        </div>
                    @endguest
                    @auth
                        @if ($user->id !== \Illuminate\Support\Facades\Auth::id())
                            <div class="section-header">
                                <h2 class="h1 section-title">Отзывы
                                    ({{ $user->receivedComments->count() ?? 0 }})
                                </h2>
                                @if (auth()->user()->role === 'client')
                                    <button type="button" class="btn btn-primary btn-primary--theme btn-review"
                                        data-graph-path="modal-review"><span class="icon"><svg>
                                                <use xlink:href="{{ asset('img/icons/message.svg#svg-message') }}"></use>
                                            </svg> </span>Добавить
                                        отзыв
                                    </button>
                                @endif
                            </div>
                        @endif
                    @endauth
                    @if ($user->receivedComments->count())
                        <ul class="list-reset reviews">
                            @foreach ($user->receivedComments as $comment)
                                <li class="reviews__item">
                                    <article class="card card--review card--light">
                                        <div class="card__body">
                                            <div class="card__body-start">
                                                <picture>
                                                    <source
                                                        srcset="{{ asset($comment->user->photos ?? './img/avatars/avatar-2.png') }}"
                                                        type="image/webp">
                                                    <img loading="lazy"
                                                        src="{{ asset($comment->user->photos ?? './img/avatars/avatar-2.png') }}"
                                                        class="card__body-start_image" width="142" height="142"
                                                        alt="Картинка">
                                                </picture>
                                                <h3 class="h2 card__body-title" translate="no">
                                                    {{ $comment->user->name }}
                                                </h3>
                                            </div>
                                            <div class="card__body-end">
                                                <div class="card__body-header">
                                                    <h3 class="h2 card__body-title" translate="no">
                                                        {{ $comment->user->name }}</h3>
                                                    <div class="card__time">Отзыв оставлен
                                                        <time
                                                            datetime="2023-12-14T12:00:00Z">{{ \Carbon\Carbon::parse($comment->created_at)->format('d.m.Y') }}</time>
                                                    </div>
                                                </div>
                                                <div class="card__body-main">
                                                    <p class="card__body-main_text">{{ $comment->result }}</p>
                                                </div>
                                                <div class="card__time">Отзыв оставлен
                                                    <time
                                                        datetime="2023-12-14T12:00:00Z">{{ \Carbon\Carbon::parse($comment->created_at)->format('d.m.Y') }}</time>
                                                </div>
                                            </div>
                                        </div>
                                    </article>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </section>
        </div>
    </main>
    <style>
        @media (max-width: 720px) {
            .dz-avatar-img {
                width: 80px !important;
                height: 80px !important;
            }

            .card__profile-avatar {
                width: 80px !important;
                height: 80px !important;
            }

            .dz-avatar-delete {
                top: 10px !important;
                right: 5px !important;
            }
        }
    </style>
@endsection
@section('modal_contact')
    @if ($user->whatsapp || $user->instagram)
        <div class="graph-modal__container graph-modal__container--contacts" role="dialog" aria-modal="true"
            data-graph-target="modal-contacts{{ $user->id }}">
            <button class="btn js-modal-close graph-modal__close" aria-label="Закрыть модальное окно"><span
                    class="icon"><svg>
                        <use xlink:href="{{ asset('img/icons/x.svg#svg-x') }}"></use>
                    </svg></span></button>
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
@endsection
