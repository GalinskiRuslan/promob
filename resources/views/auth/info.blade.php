@extends('layouts.main')
@section('content')
    <main class="main">
        <h1 class="visually-hidden">Контакты</h1>
        <div class="main-sections">
            <section class="section">
                <div class="container-xl">
                    @if (Auth::user()->role === 'client')
                        <div class="section-header">
                            <h2 class="h1 section-title">Оформление профиля</h2>
                        </div>
                        <div class="section-subtitle">
                            <p class="section-text">Загрузите вашу аватарку (макс. размер 10 МБ). Затем загрузите фото, видео
                                которые посетители увидят первым делом, когда попадет на вашу страницу.</p>
                        </div>
                        <form action="{{ route('portfolio') }}" class="dropzone dropzone-avatar" method="POST"
                            enctype="multipart/form-data">
                            @csrf

                            <div class="dz-default dz-message">

                                <button type="button" class="btn dz-upload dz-avatar-upload">
                                    <picture class="dz-avatar-picture">
                                        <source srcset="{{ asset('./img/avatars/avatar-blank.png') }}">
                                        <img loading="lazy" src="{{ asset('./img/avatars/avatar-blank.png') }}"
                                            class="dz-avatar-img" width="220" height="220" alt="Картинка">
                                    </picture>
                                    <span class="icon"><svg>
                                            <use xlink:href="{{ asset('img/icons/camera.svg#svg-camera') }}"></use>
                                        </svg></span>
                                </button>
                                <script>
                                    function deleteImage() {
                                        const dropzoneForm = document.querySelector('.dropzone-avatar');
                                        const defaultImageWrapper = document.querySelector('.dz-default');
                                        const btnAvatarDelete = document.querySelector('.dz-avatar-delete');
                                        const defaultAvatar = document.querySelector('.dz-avatar-img');

                                        btnAvatarDelete.addEventListener('click', function(e) {
                                            e.preventDefault();

                                            let activeImagePreview = document.querySelector('.dz-preview');

                                            if (activeImagePreview.classList.contains('dz-complete')) {
                                                let activeImage = activeImagePreview.querySelector('img');

                                                activeImage.src = defaultAvatar.src;
                                            }
                                        });
                                    }
                                </script>
                            </div>
                            <div class="fallback">
                                <input name="file" type="file">
                            </div>
                        </form>
                    @endif
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
                            @error('name')
                                <div class="error">{{ $message }}</div>
                            @enderror
                            <h3 class="form-control__title">Имя</h3>
                            <div class="form-field">
                                <input type="text" class="field" value="{{ old('name', Auth::user()->name) }}"
                                    name="name" placeholder="Введите ваше имя" required>
                            </div>
                        </div>
                        @if (Auth::user()->role === 'executor')
                            <div class="form-control">
                                @error('surname')
                                    <div class="error">{{ $message }}</div>
                                @enderror
                                <h3 class="form-control__title">Фамилия</h3>
                                <div class="form-field"><input type="text" class="field"
                                        value="{{ old('surname', Auth::user()->surname) }}" name="surname"
                                        placeholder="Введите вашу фамилию" required></div>
                            </div>
                            <div class="form-control">
                                <h3 class="form-control__title">Отчество</h3>
                                <div class="form-field"><input type="text"
                                        value="{{ old('surname_2', Auth::user()->surname_2) }}" class="field"
                                        name="surname_2" placeholder="Введите ваше отчество"></div>
                            </div>
                            <div class="form-control">
                                <h3 class="form-control__title">Ник</h3>
                                <div class="form-field"><input type="text"
                                        value="{{ old('nickname', Auth::user()->nickname) }}" class="field"
                                        name="nickname" placeholder="Введите ваш ник"></div>
                            </div>
                            <div class="form-control form-control--horizontal">
                                <h3 class="form-control__title">В анкете
                                    отображается ник</h3>
                                <div class="form-field form-field--switch"><input type="checkbox" class="switch"
                                        name="nickname_true"> <span class="switch-slider"></span>
                                </div>
                            </div>
                            <div class="form-control">
                                <h3 class="form-control__title">Сайт (если есть)</h3>
                                <div class="form-field"><input type="text" value="{{ old('site', Auth::user()->site) }}"
                                        class="field" name="site" placeholder="Пример: promobilograf.kz">
                                </div>
                            </div>
                            <div class="form-control">
                                <h3 class="form-control__title">Ссылка на Инстаграм (если есть)</h3>
                                <div class="form-field"><input type="text" class="field"
                                        value="{{ old('instagram', Auth::user()->instagram) }}" name="instagram"
                                        placeholder="Пример: promobilograf"></div>
                            </div>
                            <div class="form-control">
                                <h3 class="form-control__title">WhatsApp (если есть)</h3>
                                <div class="form-field"><input type="tel" data-tel-input
                                        value="{{ old('whatsapp', Auth::user()->whatsapp) }}" class="field"
                                        name="whatsapp" placeholder="+77022638953"></div>
                            </div>
                        @endif
                        <div class="form-control">
                            <h3 class="form-control__title">Email</h3>
                            <div class="form-field"><input type="email" class="field" name="email"
                                    value="{{ old('email', Auth::user()->email) }}" placeholder="Введите ваш e-mail"
                                    required></div>
                        </div>
                        <div class="form-control">
                            <h3 class="form-control__title">Телефон</h3>
                            <div class="form-field"><input type="tel" class="field" name="tel" data-tel-input
                                    value="{{ old('tel', Auth::user()->tel) }}" placeholder="Введите ваш телефон"
                                    required></div>
                        </div>
                        <div class="form-control form-control--last">
                            <h3 class="form-control__title">Пароль</h3>
                            @error('password')
                                <div class="error">{{ $message }}</div>
                            @enderror
                            <div class="form-field form-field--password @error('password') is-invalid @enderror">
                                <button type="button" class="btn form-field__btn">
                                    <span class="icon">
                                        <svg>
                                            <use xlink:href="/img/icons/eye-close-line.svg#svg-eye-close-line">
                                            </use>
                                        </svg>
                                    </span>
                                </button>
                                <input type="password" name="password" class="field" placeholder="Пароль" required
                                    autocomplete="new-password">
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
                                <input type="password" name="password_confirmation" class="field" placeholder="Пароль"
                                    required autocomplete="new-password">
                            </div>
                        </div>
                        <div style="width: 100%; justify-content: flex-end;" class="form-buttons">
                            @if (Auth::user()->role === 'client')
                                <button type="submit"
                                    class="btn btn-lg btn-primary btn-primary--theme">Зарегестрироваться</button>
                            @else
                                <button type="submit"
                                    class="btn btn-lg btn-primary btn-primary--theme">Сохранить</button>
                            @endif
                        </div>
                    </form>
                </div>
            </section>
        </div>
    </main>
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
