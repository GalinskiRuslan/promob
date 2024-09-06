<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>500 - Server Error</title>
    <link rel="stylesheet" href="{{ asset('css/main.css') }}">
    <link rel="stylesheet" href="{{ asset('css/vendor.css') }}">
</head>
<body>
    <main class="main">
        <h1 class="visually-hidden">
            Ошибка сервиса
        </h1>

        <div class="page-center">
            <style>
                body {
                    font-family: var(--font-family, sans-serif);
                }
                .main {
                    display: flex;
                    height: 100vh;
                }
                .page-center {
                    width: 100%;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                }
                .page-error {
                    position: relative;
                }
                .page-error-image {
                    width: 750px;
                    max-width: initial;
                    object-fit: contain;
                }
                .btn-main {
                    --_height: 2rem;
                    --_line-height: normal;

                    position: absolute;
                    left: calc(50% - 1rem);
                    transform: translateX(-50%);
                    bottom: 148px;
                }

                @media (max-width: 768px) {
                    .page-error-image {
                        width: 460px;
                    }
                    .btn-main {
                        --_height: 1.5rem;
                        --_font-size: var(--fs);
                        --_padding: 0 1rem;

                        left: calc(50% - 0.625rem);
                        bottom: 90px;
                    }
                }
            </style>
            <div class="page-error">
                <picture>
                    <source srcset="{{ asset('img/error.webp') }}" type="image/webp">
                    <img loading="lazy" src="{{ asset('img/error.png') }}" class="page-error-image" width="746" height="556" alt="Картинка">
                </picture>
                <a href="{{ route('home') }}" class="btn btn-sm btn-primary btn-primary--inverse btn-main">
                    На главную
                </a>
            </div>
        </div>

    </main>
</body>
</html>
