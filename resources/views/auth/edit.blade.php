@extends('layouts.main')
@section('content')
    <main class="main">
        <h1 class="visually-hidden">Редактирование страницы специалиста</h1>
        <div class="main-sections">
            @if (Auth::user() && Auth::user()->role === 'executor')
                <section class="section">
                    <div class="container">
                        <div class="card card--light card--edit">
                            <div class="card__header">
                                <div class="card__profile">
                                    <picture class="card__profile-avatar">
                                        {{--   @if ($user->photos)
                                            <form action="{{ route('delete_avatar') }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                            <picture class="dz-avatar-picture">
                                                <source srcset="{{ asset($user->photos) }}" type="image/webp">
                                                <img loading="lazy" src="{{ asset($user->photos) }}" class="dz-avatar-img"
                                                    width="220" height="220" alt="Картинка">
                                            </picture>
                                        @else --}}
                                        <div class="profile-avatar">
                                            <form style="margin: 0;" class="form section dropzone dropzone-avatar"
                                                enctype="multipart/form-data" method="POST"
                                                action="{{ route('update_photo_avatar') }}">
                                                <div class="dz-default dz-message">
                                                    @csrf
                                                    <button style="width: 100%; height: 100%;" type="button"
                                                        class="btn dz-upload dz-avatar-upload">
                                                        <picture class="dz-avatar-picture">
                                                            <source
                                                                srcset="{{ $user->photos ? asset($user->photos) : asset('./img/avatars/avatar-1.png') }}"
                                                                type="image/webp">
                                                            <img loading="lazy"
                                                                src="{{ asset('./img/avatars/avatar-1.png') }}"
                                                                class="dz-avatar-img" width="220" height="220"
                                                                alt="Картинка">
                                                        </picture>
                                                        <span class="icon"><svg>
                                                                <use
                                                                    xlink:href="{{ asset('img/icons/camera.svg#svg-camera') }}">
                                                                </use>
                                                            </svg></span>
                                                    </button>
                                                </div>
                                                <div class="fallback">
                                                    <input name="file" type="file">
                                                </div>
                                            </form>
                                        </div>
                                        {{-- @endif --}}
                                    </picture>
                                    <div class="card__profile-info">
                                        <h3 class="card__profile-name">
                                            @if ($user->nickname_true)
                                                <span translate="no">{{ $user->nickname }}</span>
                                            @else
                                                {{ $user->name }} {{ $user->surname }}
                                            @endif
                                        </h3>
                                        @if ($user->whatsapp || $user->instagram)
                                            <div class="card__profile-location"><span class="icon icon-color icon-lg"><svg>
                                                        <use
                                                            xlink:href="{{ asset('img/icons/location.svg#svg-location') }}">
                                                        </use>
                                                    </svg> </span>{{ $user->city->city ?? null }}
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="card__info">
                                    <a href="{{ route('comments.index', $user->id) }}" class="btn card__reviews"><span
                                            class="icon"><svg>
                                                <use xlink:href="{{ asset('img/icons/message.svg#svg-message') }}"></use>
                                            </svg> </span><span>{{ $user->receivedComments->count() }} <span
                                                class="card__reviews-text">
                                                @if ($user->receivedComments->count() == 1)
                                                    отзыв
                                                @elseif($user->receivedComments->count() < 10 && $user->receivedComments->count() > 1)
                                                    отзыва
                                                @elseif($user->receivedComments->count() > 10)
                                                    отзывов
                                                @endif
                                            </span></span></a>
                                    <a href="{{ route('statistic') }}" class="btn card__statistics"><span
                                            class="icon"><svg>
                                                <use xlink:href="{{ asset('img/icons/bar.svg#svg-bar') }}"></use>
                                            </svg> </span>Статистика</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                <section class="section">
                    <div class="container">
                        <div class="section-header">
                            <h2 class="h1 section-title">Портфолио</h2>
                        </div>
                        <ul class="list-reset portfolio portfolio--edit">
                            @if ($user->gallery)
                                @foreach (json_decode($user->gallery, true) as $key => $galleryItem)
                                    @if (preg_match('/(jpg|jpeg|png|gif|svg)$/i', $galleryItem))
                                        <li class="portfolio__item">
                                            <form action="{{ route('portfolio_delete') }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <input type="hidden" name="fileName" value="{{ $galleryItem }}">
                                                <button type="submit" class="dz-portfolio-delete" title="Удалить">
                                                    <svg width="26" height="24" viewBox="0 0 26 24" fill="none"
                                                        xmlns="http://www.w3.org/2000/svg">
                                                        <path d="M19.6458 6L6.94922 18" stroke="white" stroke-width="2"
                                                            stroke-linecap="round" stroke-linejoin="round" />
                                                        <path d="M6.94922 6L19.6458 18" stroke="white" stroke-width="2"
                                                            stroke-linecap="round" stroke-linejoin="round" />
                                                    </svg>
                                                </button>
                                            </form>
                                            <picture class="portfolio__item-picture"
                                                data-graph-path="portfolio-item{{ $key }}">
                                                <source srcset="{{ asset($galleryItem) }}" type="image/webp">
                                                <img loading="lazy" src="{{ asset($galleryItem) }}"
                                                    class="portfolio__item-image" width="350" height="224"
                                                    alt="Картинка">
                                            </picture>
                                            <button type="button" class="btn">
                                                <span class="icon">
                                                    <svg>
                                                        <use xlink:href="img/icons/x.svg#svg-x"></use>
                                                    </svg>
                                                </span>
                                            </button>
                                        </li>
                                    @endif
                                    @if (preg_match('/\.?(mp4|mov|avi)$/i', $galleryItem))
                                        <li class="portfolio__item">
                                            <form action="{{ route('portfolio_delete') }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <input type="hidden" name="fileName" value="{{ $galleryItem }}">
                                                <button type="submit" class="dz-portfolio-delete" title="Удалить">
                                                    <svg width="26" height="24" viewBox="0 0 26 24" fill="none"
                                                        xmlns="http://www.w3.org/2000/svg">
                                                        <path d="M19.6458 6L6.94922 18" stroke="white" stroke-width="2"
                                                            stroke-linecap="round" stroke-linejoin="round" />
                                                        <path d="M6.94922 6L19.6458 18" stroke="white" stroke-width="2"
                                                            stroke-linecap="round" stroke-linejoin="round" />
                                                    </svg>
                                                </button>
                                            </form>
                                            <video class="portfolio__item-video" width="350" height="224"
                                                src="{{ asset($galleryItem) }}#t=0.001" preload="metadata"
                                                data-graph-path="portfolio-item{{ $key }}"></video>
                                            <button type="button" class="btn">
                                                <span class="icon">
                                                    <svg>
                                                        <use xlink:href="img/icons/x.svg#svg-x"></use>
                                                    </svg>
                                                </span>
                                            </button>
                                        </li>
                                    @endif
                                @endforeach
                            @endif
                        </ul>
                        <div class="portfolio-swiper portfolio-swiper--edit swiper-control js-swiper-portfolio">
                            <div class="swiper">
                                <div class="swiper-wrapper">
                                    @if ($user->gallery)
                                        @foreach (json_decode($user->gallery, true) as $key => $galleryItem)
                                            <div class="swiper-slide">
                                                <div class="portfolio__slide">
                                                    <div class="portfolio__item">
                                                        <picture class="portfolio__item-picture"
                                                            data-graph-path="portfolio-item{{ $key }}">
                                                            <source srcset="{{ asset($galleryItem) }}" type="image/webp">
                                                            <img loading="lazy" src="{{ asset($galleryItem) }}"
                                                                class="portfolio__item-image" width="350"
                                                                height="224" alt="Картинка">
                                                        </picture>
                                                        <form action="{{ route('portfolio_delete') }}" method="POST">
                                                            @csrf
                                                            @method('DELETE')
                                                            <input type="hidden" name="fileName"
                                                                value="{{ $galleryItem }}">
                                                            <button type="submit" class="dz-portfolio-delete"
                                                                title="Удалить">
                                                                <svg width="26" height="24" viewBox="0 0 26 24"
                                                                    fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                    <path d="M19.6458 6L6.94922 18" stroke="white"
                                                                        stroke-width="2" stroke-linecap="round"
                                                                        stroke-linejoin="round" />
                                                                    <path d="M6.94922 6L19.6458 18" stroke="white"
                                                                        stroke-width="2" stroke-linecap="round"
                                                                        stroke-linejoin="round" />
                                                                </svg>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                            @if (preg_match('/\.?(mp4|mov|avi)$/i', $galleryItem))
                                                <div class="swiper-slide">
                                                    <div class="portfolio__slide">
                                                        <div class="portfolio__item">
                                                            <video class="portfolio__item-video" width="350"
                                                                height="224" src="{{ asset($galleryItem) }}#t=0.001"
                                                                preload="metadata"
                                                                data-graph-path="portfolio-item{{ $key }}"></video>
                                                            <form action="{{ route('portfolio_delete') }}"
                                                                method="POST">
                                                                @csrf
                                                                @method('DELETE')
                                                                <input type="hidden" name="fileName"
                                                                    value="{{ $galleryItem }}">
                                                                <button type="submit" class="dz-portfolio-delete"
                                                                    title="Удалить">
                                                                    <svg width="26" height="24"
                                                                        viewBox="0 0 26 24" fill="none"
                                                                        xmlns="http://www.w3.org/2000/svg">
                                                                        <path d="M19.6458 6L6.94922 18" stroke="white"
                                                                            stroke-width="2" stroke-linecap="round"
                                                                            stroke-linejoin="round" />
                                                                        <path d="M6.94922 6L19.6458 18" stroke="white"
                                                                            stroke-width="2" stroke-linecap="round"
                                                                            stroke-linejoin="round" />
                                                                    </svg>
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                            <div class="swiper-scrollbar"></div>
                        </div>
                        <form action="{{ route('portfolio_gallery') }}"
                            class="dropzone portfolio-dropzone dropzone-files edit-portfolio" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="dz-default dz-message">
                                <button type="button" class="btn dz-upload"><span class="icon"><svg>
                                            <use xlink:href="{{ asset('img/icons/upload.svg#svg-upload') }}"></use>
                                        </svg></span>Перетащите файлы сюда или нажмите, чтобы загрузить. <br> (Макс. размер
                                    изображений: 10 МБ) <br> (Макс. размер видео: 100 МБ. Макс. кол-во 2) <br> (Разрешены
                                    форматы: jpg, jpeg, png, svg, mp4, mov, avi)</button>
                            </div>
                            <div class="dz-wrapper"></div>
                            <div class="fallback">
                                <input name="gallery[]" type="file" multiple="multiple">
                            </div>
                        </form>
                    </div>
                </section>
                <form style="width: auto;" class="form section" method="POST" action="{{ route('update_first') }}">
                    <input type="hidden" name="email" value="{{ $user->email }}">
                    @csrf
                    @if ($errors)
                        @foreach ($errors->all() as $error)
                            <p class="error">{{ $error }}</p>
                        @endforeach
                    @endif
                    <section class="section edit">
                        <div class="container">
                            <div class="section-header">
                                <h2 class="h1 section-title">Контакты</h2>
                            </div>
                            <div class="section-subtitle">
                                <p class="section-text">Пожалуйста, укажите Ваши Ф.И как в
                                    удостоверении, это важно для проверки</p>
                            </div>
                            <div class="form">
                                <input class="dz-hidden-input"
                                    style="visibility: hidden; position: absolute; top: 0px; left: 0px; height: 0px; width: 0px;"
                                    tabindex="-1" type="file">
                                <div class="form-control">
                                    <h3 class="h2 form-control__title">Имя</h3>
                                    <div class="form-field"><input type="text" class="field"
                                            value="{{ old('name', Auth::user()->name) }}" name="name"
                                            placeholder="Ввод" required>
                                    </div>
                                </div>
                                <div class="form-control">
                                    <h3 class="h2 form-control__title">Фамилия</h3>
                                    <div class="form-field"><input type="text" class="field"
                                            value="{{ old('surname', Auth::user()->surname) }}" name="surname"
                                            placeholder="Ввод" required></div>
                                </div>
                                @if (Auth::user()->role === 'executor')
                                    <div class="form-control">
                                        <h3 class="h2 form-control__title">Отчество</h3>
                                        <div class="form-field"><input type="text"
                                                value="{{ old('surname_2', Auth::user()->surname_2) }}" class="field"
                                                name="surname_2" placeholder="Ввод" required></div>
                                    </div>
                                    <div class="form-control">
                                        <h3 class="h2 form-control__title">Ник</h3>
                                        <div class="form-field"><input type="text"
                                                value="{{ old('nickname', Auth::user()->nickname) }}" class="field"
                                                name="nickname" placeholder="Ввод" required></div>
                                    </div>
                                    <div class="form-control form-control--horizontal">
                                        <h3 class="h2 form-control__title">В анкете
                                            отображается ник</h3>
                                        <div class="form-field form-field--switch">
                                            <input type="checkbox" class="switch" name="nickname_true"
                                                {{ old('nickname_true', Auth::user()->nickname_true) ? 'checked' : '' }}>
                                            <span class="switch-slider"></span>
                                        </div>
                                    </div>
                                    <div class="form-control">
                                        <h3 class="h2 form-control__title">Сайт (если есть)</h3>
                                        <div class="form-field"><input type="text"
                                                value="{{ old('site', Auth::user()->site) }}" class="field"
                                                name="site" placeholder="Ввод">
                                        </div>
                                    </div>
                                    <div class="form-control">
                                        <h3 class="h2 form-control__title">Инстаграм (если есть)</h3>
                                        <div class="form-field"><input type="text" class="field"
                                                value="{{ old('instagram', Auth::user()->instagram) }}" name="instagram"
                                                placeholder="Ввод"></div>
                                    </div>
                                    <div class="form-control">
                                        <h3 class="h2 form-control__title">WhatsApp</h3>
                                        <div class="form-field"><input type="text"
                                                value="{{ old('whatsapp', Auth::user()->whatsapp) }}" class="field"
                                                name="whatsapp" placeholder="Ввод"></div>
                                    </div>
                                @endif
                                <div class="form-control">
                                    <h3 class="h2 form-control__title">Телефон</h3>
                                    <div class="form-field"><input type="tel" class="field" name="tel"
                                            data-tel-input value="{{ old('tel', Auth::user()->tel) }}"
                                            placeholder="Ввод"></div>
                                </div>
                                <div class="form-control form-control--last">
                                    <h3 class="h2 form-control__title">Пароль</h3>
                                    <div class="form-field form-field--password @error('password') is-invalid @enderror">
                                        <button type="button" class="btn form-field__btn">
                                            <span class="icon">
                                                <svg>
                                                    <use xlink:href="/img/icons/eye-close-line.svg#svg-eye-close-line">
                                                    </use>
                                                </svg>
                                            </span>
                                        </button>
                                        <input type="password" name="password" value="{{ session('password') }}"
                                            class="field" placeholder="Пароль" autocomplete="new-password">
                                    </div>
                                </div>
                                <div class="form-control form-control--last">
                                    <h3 class="h2 form-control__title">Повторите пароль</h3>
                                    <div class="form-field form-field--password">
                                        <button type="button" class="btn form-field__btn">
                                            <span class="icon">
                                                <svg>
                                                    <use xlink:href="/img/icons/eye-close-line.svg#svg-eye-close-line">
                                                    </use>
                                                </svg>
                                            </span>
                                        </button>
                                        <input type="password" name="password_confirmation"
                                            value="{{ session('password') }}" class="field" placeholder="Пароль"
                                            autocomplete="new-password">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                    <section class="section">
                        <div class="main-sections">
                            <div class="container-xl">
                                <div class="section-header">
                                    <h2 class="h1 section-title">Информация о вас</h2>
                                </div>
                                <div class="section-subtitle">
                                    <p class="section-text">Укажите свое направление</p>
                                </div>
                                <ul class="list-reset form-options">
                                    @foreach ($categories as $category)
                                        <li class="form-option">
                                            <label
                                                class="btn btn-lg btn-checkbox btn-tertiary {{ in_array($category->id, json_decode($user->categories_id ?? '[]')) ? 'is-active' : '' }}">
                                                {{ $category->category }}
                                                <input type="checkbox" class="checkbox" name="categories_id[]"
                                                    value="{{ $category->id }}">
                                            </label>
                                        </li>
                                    @endforeach
                                </ul>
                                <script></script>
                                <div class="form-control form-control--double-field">
                                    <h3 class="h2 form-control__title">Укажите
                                        стоимость своих услуг</h3>
                                    <div class="form-field"><input type="number" class="field"
                                            value="{{ old('cost_from', $user->cost_from) }}" name="cost_from"
                                            placeholder="От" required>
                                    </div>
                                    <div class="form-field"><input type="number"
                                            value="{{ old('cost_up', $user->cost_up) }}"
                                            class="field @error('cost_up') is-invalid @enderror" name="cost_up"
                                            placeholder="До     500000" required></div>
                                </div>
                                <div class="form-control">
                                    <h3 class="h2 form-control__title">Детали работы</h3>
                                    <div class="form-field">
                                        <textarea type="text" class="field" name="details" placeholder="Ввод" required>{{ old('details', $user->details) }}</textarea>
                                    </div>
                                </div>
                                <div class="form-control">
                                    <h3 class="h2 form-control__title">О себе</h3>
                                    <div class="form-field">
                                        <textarea type="text" class="field" name="about_yourself" placeholder="Ввод" required>{{ old('about_yourself', $user->about_yourself) }}</textarea>
                                    </div>
                                </div>
                                <div class="form-control form-control--last form-control--double">
                                    <div class="form-control">
                                        <h3 class="h2 form-control__title">Выберите город</h3>
                                        <select class="js-choices" name="cities_id" required>
                                            <option value="{{ $user->city->id }}">
                                                {{ old('cities_id', $user->city->city) ?? null }}
                                            </option>
                                            @foreach ($cities as $city)
                                                <option value="{{ $city->id }}">{{ $city->city }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-control form-control--checkbox">
                                        <h3 class="h2 form-control__title">
                                            Язык</h3><label class="form-checkbox">
                                            <div class="form-field form-field--checkbox"><input type="checkbox"
                                                    class="checkbox checkbox--theme"
                                                    {{ in_array('rus', json_decode($user->language ?? '[]')) ? 'checked="checked"' : '' }}
                                                    name="rus"> <span class="checkbox-check"></span></div>
                                            <div class="form-checkbox__title">Русский</div>
                                        </label> <label class="form-checkbox">
                                            <div class="form-field form-field--checkbox"><input type="checkbox"
                                                    class="checkbox checkbox--theme" name="kaz"
                                                    {{ in_array('kaz', json_decode($user->language ?? '[]')) ? 'checked="checked"' : '' }}>
                                                <span class="checkbox-check"></span>
                                            </div>
                                            <div class="form-checkbox__title">Казахский</div>
                                        </label> <label class="form-checkbox">
                                            <div class="form-field form-field--checkbox"><input type="checkbox"
                                                    class="checkbox checkbox--theme" name="en"
                                                    {{ in_array('en', json_decode($user->language ?? '[]')) ? 'checked="checked"' : '' }}>
                                                <span class="checkbox-check"></span>
                                            </div>
                                            <div class="form-checkbox__title">Английский</div>
                                        </label>
                                    </div>
                                </div>
                                <div class="form-buttons justify-content-between">
                                    <button type="button" class="btn remove_profile" href=""
                                        data-graph-path="remove-profile">Удалить профиль</button>
                                    <button type="submit"
                                        class="btn btn-lg btn-primary btn-primary--theme">Сохранить</button>
                                </div>
                            </div>
                        </div>
                    </section>
                </form>
            @else
                <section class="section">
                    <div class="container-xl">
                        <div class="section-header">
                            <h2 class="h1 section-title">Оформление профиля</h2>
                        </div>
                        <div class="section-subtitle">
                            <p class="section-text">Загрузите вашу аватарку (макс. размер 10 МБ).</p>
                        </div>
                        <form action="{{ route('update_photo_avatar') }}" class="dropzone dropzone-avatar"
                            method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="dz-default dz-message">
                                <button type="button" class="btn dz-upload dz-avatar-upload">
                                    <picture class="dz-avatar-picture">
                                        <source
                                            srcset="{{ $user->photos ? asset($user->photos) : asset('./img/avatars/avatar-blank.png') }}">
                                        <img loading="lazy"
                                            src="{{ $user->photos ? asset($user->photos) : asset('./img/avatars/avatar-blank.png') }}"
                                            class="dz-avatar-img" width="220" height="220" alt="Картинка">
                                    </picture>
                                    <span class="icon"><svg>
                                            <use xlink:href="{{ asset('img/icons/camera.svg#svg-camera') }}"></use>
                                        </svg></span>
                                </button>
                            </div>
                            <div class="fallback">
                                <input name="file" type="file">
                            </div>
                        </form>
                        <div class="section-header">
                            <h2 class="h1 section-title">Контакты</h2>
                        </div>
                        <div class="section-subtitle">
                            <p class="section-text">Пожалуйста, укажите Ваши Ф.И как в
                                удостоверении, это важно для проверки</p>
                        </div>
                        <form class="form" method="POST" action="{{ route('update-info') }}">
                            @csrf
                            <div class="form-control">
                                <h3 class="form-control__title">Имя</h3>
                                <div class="form-field"><input type="text" class="field"
                                        value="{{ Auth::user()->name }}" name="name" placeholder="Введите ваше имя"
                                        required></div>
                            </div>
                            <div class="form-control">
                                <h3 class="form-control__title">Email</h3>
                                <div class="form-field"><input type="email" class="field" name="email"
                                        value="{{ Auth::user()->email }}" placeholder="Введите ваш e-mail" required>
                                </div>
                            </div>
                            <div class="form-control">
                                <h3 class="form-control__title">Телефон</h3>
                                <div class="form-field"><input type="tel" class="field" name="tel"
                                        data-tel-input value="{{ Auth::user()->tel }}" placeholder="Введите ваш телефон">
                                </div>
                            </div>
                            <div class="form-control form-control--last">
                                <h3 class="form-control__title">Пароль</h3>
                                <div class="form-field form-field--password @error('password') is-invalid @enderror">
                                    <button type="button" class="btn form-field__btn">
                                        <span class="icon">
                                            <svg>
                                                <use xlink:href="/img/icons/eye-close-line.svg#svg-eye-close-line">
                                                </use>
                                            </svg>
                                        </span>
                                    </button>
                                    <input type="password" name="password" value="{{ session('password') }}"
                                        class="field" placeholder="Пароль" required autocomplete="new-password">
                                </div>
                            </div>
                            <div class="form-control form-control--last">
                                <h3 class="form-control__title">Повторите пароль</h3>
                                <div class="form-field form-field--password">
                                    <button type="button" class="btn form-field__btn">
                                        <span class="icon">
                                            <svg>
                                                <use xlink:href="/img/icons/eye-close-line.svg#svg-eye-close-line">
                                                </use>
                                            </svg>
                                        </span>
                                    </button>
                                    <input type="password" name="password_confirmation"
                                        value="{{ session('password') }}" class="field" placeholder="Пароль" required
                                        autocomplete="new-password">
                                </div>
                            </div>
                            <div style="margin-bottom: 30px;" class="form-buttons justify-content-between">
                                <button type="button" class="btn remove_profile" href=""
                                    data-graph-path="remove-profile">Удалить профиль</button>
                                <button type="submit"
                                    class="btn btn-lg btn-primary btn-primary--theme">Сохранить</button>
                            </div>
                        </form>
                    </div>
                </section>
                <style>
                    .dz-avatar-upload {
                        max-width: 142px;
                        max-height: 142px;
                        width: 100% !important;
                        height: 100% !important;
                    }

                    .dz-preview .dz-avatar-delete {
                        opacity: 0;
                        transition: .2s all;
                    }

                    .dropzone-avatar:hover .dz-avatar-delete {
                        opacity: 1;
                        visibility: visible;
                    }
                </style>
            @endif
        </div>
        <style>
            .dz-avatar-img {
                width: 142px;
                height: 142px;
                border-radius: 50%;
            }

            .dz-message {
                max-width: 142px;
                max-height: 142px;
            }

            .dropzone-avatar {
                margin-bottom: 2.5rem;
            }

            .remove_profile {
                font-weight: 400;
                text-decoration: underline;
            }

            @media (max-width: 720px) {
                .dz-avatar-img {
                    max-width: 80px;
                    max-height: 80px;
                }

                .dropzone-avatar .dz-preview .dz-image {
                    width: 80px;
                    height: 80px;
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

            .dz-portfolio-delete {
                position: absolute;
                background: transparent;
                border: none;
                right: 20px;
                top: 20px;
                cursor: pointer;
                z-index: 100;
                opacity: 0;
                visibility: hidden;
                transition: .15s all
            }

            .portfolio--edit .portfolio__item::after {
                opacity: 0;
                transition: .2s all;
            }

            .portfolio__item:hover .dz-portfolio-delete {
                opacity: 1;
                visibility: visible;
            }

            .portfolio--edit .portfolio__item:hover::after {
                opacity: 1;
            }

            .portfolio-swiper--edit .portfolio__item::after {
                opacity: 0;
                visibility: hidden;
                transition: .2s all;
            }

            .portfolio-swiper--edit .portfolio__item .dz-avatar-delete {
                opacity: 0 !important;
                visibility: hidden !important;
                transition: .2s all;
            }

            .portfolio-swiper--edit .portfolio__item:hover::after,
            .portfolio-swiper--edit .portfolio__item:hover .dz-avatar-delete {
                opacity: 1 !important;
                visibility: visible !important;
            }

            .card__profile-avatar {
                position: relative;
            }

            .profile-avatar-delete {
                position: absolute;
                right: 0;
                cursor: pointer;
                z-index: 10000;
                opacity: 0;
                visibility: hidden;
                transition: .15s all
            }

            .card__profile-avatar:hover .profile-avatar-delete {
                opacity: 1;
                visibility: visible;
            }

            .dz-message {
                max-width: 100% !important;
            }

            .dz-preview .dz-portfolio-delete {
                opacity: 0;
                visibility: hidden;
                transition: .15s all;
            }

            .dz-preview:hover .dz-portfolio-delete {
                opacity: 1;
                visibility: visible;
            }

            .deleteBtn {
                position: absolute;
                z-index: 999;
                font-size: 28px;
                background: transparent;
                border: none;
                cursor: pointer;
                right: 0;
            }
        </style>
    </main>
@endsection
