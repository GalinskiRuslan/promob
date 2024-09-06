@extends('layouts.main')
@section('content')
    <main class="main">
        <h1 class="visually-hidden">
            Отзывы специалиста
        </h1>

        <div class="main-sections">
            <section class="section">
                <div class="container">
                    <div class="section-header">

                        @guest()
                            <h2 class="h1 section-title">
                                Отзывы ({{$user->receivedComments->count() ?? 0}})
                            </h2>
                            <button type="button" class="btn btn-primary btn-primary--theme btn-review" data-graph-path="modal-review-sign">
                                <span class="icon">
                                  <svg>
                                    <use xlink:href="{{ asset('img/icons/message.svg#svg-message') }}"></use>
                                  </svg>
                                </span>
                                Добавить отзыв
                            </button>
                        @endguest
                        @auth()
                            @if($user->id !== \Illuminate\Support\Facades\Auth::id())
                            <div style="width: 100%;" class="section-header"><h2 class="h1 section-title">Отзывы ({{$user->receivedComments->count() ?? 0}})</h2>
                                <button type="button" class="btn btn-primary btn-primary--theme btn-review"
                                        data-graph-path="modal-review"><span class="icon"><svg><use
                                                xlink:href="{{ asset('img/icons/message.svg#svg-message') }}"></use></svg> </span>Добавить
                                    отзыв
                                </button>
                            </div>
                            @endif
                        @endauth
                    </div>
                    <ul class="list-reset reviews">
                        @if($user->receivedComments->count())
                            <ul class="list-reset reviews">
                                @foreach($user->receivedComments as $comment)
                                    <li class="reviews__item">
                                        <article class="card card--review card--light">
                                            <div class="card__body">
                                                <div class="card__body-start">
                                                    <picture>
                                                        <source srcset="{{ asset($comment->user->photos ?? './img/avatars/avatar-2.png') }}" type="image/webp">
                                                        <img loading="lazy" src="{{ asset($comment->user->photos ?? './img/avatars/avatar-2.png') }}" class="card__body-start_image" width="142" height="142" alt="Картинка">
                                                    </picture>
                                                    <h3 class="h2 card__body-title" translate="no">
                                                        {{ $comment->user->name }}
                                                    </h3>
                                                </div>
                                                <div class="card__body-end">
                                                    <div class="card__body-header">
                                                        <h3 class="h2 card__body-title" translate="no">
                                                            {{ $comment->user->name }}
                                                        </h3>
                                                        <div class="card__time">
                                                            Отзыв оставлен <time datetime="2023-12-14T12:00:00Z">{{ \Carbon\Carbon::parse($comment->created_at)->format('d.m.Y') }}</time>
                                                        </div>
                                                    </div>
                                                    <div class="card__body-main">
                                                        <p class="card__body-main_text">
                                                            {{ $comment->result }}
                                                        </p>
                                                    </div>
                                                    <div class="card__time">
                                                        Отзыв оставлен <time datetime="2023-12-14T12:00:00Z">{{ \Carbon\Carbon::parse($comment->created_at)->format('d.m.Y') }}</time>
                                                    </div>
                                                </div>
                                            </div>
                                        </article>
                                    </li>
                                @endforeach
                                {{--                    <li class="reviews__bottom">--}}
                                {{--                        <button type="button" class="btn btn-lg btn-outline-primary">Показать еще 20</button>--}}
                                {{--                    </li>--}}
                            </ul>
                        @endif

{{--                        <li class="reviews__bottom">--}}
{{--                            <button type="button" class="btn btn-lg btn-outline-primary">--}}
{{--                                Показать еще 20--}}
{{--                            </button>--}}
{{--                        </li>--}}
                    </ul>
                </div>
            </section>
        </div>
    </main>
    <style>
        .card__body-end {
            width: 100%;
        }
    </style>
@endsection
