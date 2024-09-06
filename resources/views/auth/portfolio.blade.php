@extends('layouts.main')
@section('content')
<main class="main"><h1 class="visually-hidden">Оформление профиля</h1>
    <div class="main-sections">
        <section class="section">
            <div class="container-xl">
                <div class="section-header">
                    <h2 class="h1 section-title">Оформление профиля</h2>
                </div>
                <div class="section-subtitle">
                    <p class="section-text">Загрузите вашу аватарку (макс. размер 10 МБ). Затем загрузите фото, видео которые посетители увидят первым делом, когда попадет на вашу страницу.</p>
                </div>
                <form action="{{ route('portfolio') }}" class="dropzone dropzone-avatar portfolio-upload" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="dz-default dz-message">
                        <button type="button" class="btn dz-upload dz-avatar-upload">
                            <picture class="dz-avatar-picture">
                                <source srcset="{{ asset('./img/avatars/avatar-blank.png') }}">
                                <img loading="lazy" src="{{ asset('./img/avatars/avatar-blank.png') }}" class="dz-avatar-img" width="220" height="220" alt="Картинка">
                            </picture>
                            <span class="icon"><svg><use xlink:href="{{ asset('img/icons/camera.svg#svg-camera') }}"></use></svg></span>
                        </button>
                        <script>
                            function deleteImage() {
                                const dropzoneForm = document.querySelector('.dropzone-avatar');
                                const defaultImageWrapper = document.querySelector('.dz-default');
                                const btnAvatarDelete = document.querySelector('.dz-avatar-delete');
                                const defaultAvatar = document.querySelector('.dz-avatar-img');

                                btnAvatarDelete.addEventListener('click', function (e) {
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

                <form action="{{ route('portfolio_gallery') }}" class="dropzone portfolio-dropzone dropzone-files" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="dz-default dz-message">
                        <button type="button" class="btn dz-upload"><span class="icon"><svg><use xlink:href="{{ asset('img/icons/upload.svg#svg-upload') }}"></use></svg></span>Перетащите файлы сюда или нажмите, чтобы загрузить. <br> (Макс. размер изображений: 10 МБ) <br> (Макс. размер видео: 100 МБ. Макс. кол-во 2) <br> (Разрешены форматы: jpg, jpeg, png, svg, mp4, mov, avi)</button>
                    </div>
                    <div class="dz-wrapper"></div>
                    <div class="fallback">
                        <input name="gallery[]" type="file" multiple="multiple">
                    </div>
                    <div class="form-buttons">
                        <a href="#" class="btn btn-lg btn-outline-primary">Назад</a>
                        <a href="{{ route('city', $corrent_city->alias) }}" class="btn btn-lg btn-primary btn-primary--theme">Сохранить</a>
                    </div>
                    <script>
                        function deletePortfolioItem() {
                            let btnsPortfolioDelete = document.querySelectorAll('.dz-portfolio-delete');

                            const host = window.location.origin;

                            btnsPortfolioDelete.forEach(btn => {
                                btn.addEventListener('click', function() {
                                    let portfolioImageWrapper = btn.closest('.dz-preview');
                                    let fileName = '';

                                    fileName = portfolioImageWrapper.querySelector('img').getAttribute('alt');

                                    if (!fileName) {
                                        fileName = portfolioImageWrapper.querySelector('.dz-error-mark .dz-filename span').textContent;
                                    }

                                    fetch(host + '/registration/portfolio-remove', {
                                        method: "DELETE",
                                        body: JSON.stringify(
                                            {
                                                fileName: fileName
                                            }
                                        )
                                    });

                                    portfolioImageWrapper.remove();
                                });
                            });
                        }
                    </script>
                    <style>
                        .dz-preview .dz-portfolio-delete {
                            opacity: 0;
                            visibility: hidden;
                            transition: .15s all;
                        }
                        .dz-preview:hover .dz-portfolio-delete {
                            opacity: 1;
                            visibility: visible;
                        }
                    </style>
                </form>
            </div>
        </section>
    </div>
</main>
@endsection
