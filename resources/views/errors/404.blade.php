<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>404 - Page Not Found</title>
    <link rel="stylesheet" href="{{ asset('css/main.css') }}">
    <link rel="stylesheet" href="{{ asset('css/vendor.css') }}">
</head>
<body>
    <main class="main main-bg" style="background-image: url({{ asset('img/404.webp') }});">
        <style>
            body {
                font-family: var(--font-family, sans-serif);
            }
            .main {
                display: flex;
                height: 100vh;
            }
            .main-bg {
                background-repeat: no-repeat;
                background-size: cover;
                background-position: center;
            }
            .page-center {
                width: 100%;
                display: flex;
                align-items: center;
                justify-content: center;
            }
            .page-error {
                display: flex;
                flex-direction: column;
                align-items: center;
                border-radius: 50%;
                margin-top: -3rem;
                padding: 4rem;
                color: var(--primary-color);
                background-color: #F4F4F9;
                text-align: center;
                position: relative;
            }
            .page-error-sup,
            .page-error-sub {
                font-size: 1.25rem;
                font-weight: 700;
            }
            .page-error-image {
                display: block;
                margin-block: .75rem .875rem;
            }
            .page-error-sub {
                margin-bottom: 1.5rem;
            }
            .btn-main {
                --_height: 2rem;
                --_line-height: normal;
            }

            @media (max-width: 768px) {
                .main-bg {
                    background-size: 1440px 800px;
                }
                .page-error-sup,
                .page-error-sub {
                    font-size: 1rem;
                }
                .page-error-image {
                    width: 180px;
                }
                .btn-main {
                    --_height: 1.5rem;
                    --_font-size: var(--fs);
                    --_padding: 0 1rem;
                }
            }
        </style>
        <h1 class="visually-hidden">
            Страница не найдена
        </h1>
        <div class="page-center">
            <div class="page-error">
              <span class="page-error-sup">
                ERROR
              </span>
                <img loading="lazy" src="{{ asset('img/404.svg') }}" class="page-error-image" width="238" height="100" alt="404">
                <span class="page-error-sub">
                страница не найдена
              </span>
                <a href="{{ route('home') }}" class="btn btn-sm btn-primary btn-primary--inverse btn-main">
                    На главную
                </a>
            </div>
        </div>
    </main>
</body>
</html>
