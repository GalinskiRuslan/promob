@extends('layouts.main')
@section('content')
    <main class="main">
        <h1 class="visually-hidden">
            Статистика
        </h1>

        <div class="main-sections">
            <section class="section">
                <div class="container">
                    <div class="section-header">
                        <h2 class="h1 section-title">
                            Статистика
                        </h2>
                    </div>
                    <div class="section-subtitle">
                        <p class="section-text">
                            Просматривайте статистику активности вашей страницы
                        </p>
                    </div>

                    <ul class="list-reset statistics">
                        <li class="statistics__item">
                <span class="statistics__item-start">
                  Количество показов страницы
                </span>
                            <span class="statistics__item-end">
                  {{ $user->statistic[0]->view_count }}
                  <span class="icon statistics__item-icon">
                    <svg>
                      <use xlink:href="img/icons/eye-on.svg#svg-eye-on"></use>
                    </svg>
                  </span>
                </span>
                        </li>
                        <li class="statistics__item">
                <span class="statistics__item-start">
                  Количество кликов на кнопку "Контакты"
                </span>
                            <span class="statistics__item-end">
                  0
                  <span class="icon statistics__item-icon">
                    <svg>
                      <use xlink:href="img/icons/bar.svg#svg-bar"></use>
                    </svg>
                  </span>
                </span>
                        </li>
                    </ul>
                </div>
            </section>
        </div>

    </main>
@endsection
