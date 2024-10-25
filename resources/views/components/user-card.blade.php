<article class="card">
    <div class="card__header">
        <div class="card__profile">
            <picture class="card__profile-avatar">
                <source srcset="{{ $user->photos ? asset($user->photos) : asset('./img/avatars/avatar-1.png') }}"
                    type="image/webp">
                <img loading="lazy" src="{{ $user->photos ? asset($user->photos) : asset('./img/avatars/avatar-1.png') }}"
                    class="image" width="142" height="142" alt="Картинка">
            </picture>
            <div class="card__profile-info">
                <a class="btn menu__job-link" href="{{ route('user_view', $user->id) }}">
                    <h3 class="card__profile-name">
                        @if ($user->nickname_true)
                            <span translate="no">{{ $user->nickname }}</span>
                        @else
                            {{ $user->name }} {{ $user->surname }}
                        @endif
                    </h3>
                </a>
                <div class="card__profile-location"><span class="icon icon-color icon-lg"><svg>
                            <use
                                xlink:href="{{ asset('img/icons/location.svg#svg-location') ?? asset('./img/avatars/avatar-1.png') }}">
                            </use>
                        </svg> </span>{{ $user->city->city }}
                </div>
                @if ($user->whatsapp || $user->instagram)
                    <button type="button" class="btn btn-md btn-primary btn-primary--theme card__profile-link"
                        data-graph-path="modal-contacts{{ $user->id }}">Контакты
                    </button>
                @endif
            </div>
        </div>
        <div class="card__info">
            <a href="{{ auth()->user() && auth()->user()->role === 'client' ? route('comments.index', $user->id) : route('comments.index', $user->id) }}"
                class="btn card__reviews"><span class="icon"><svg>
                        <use xlink:href="{{ asset('img/icons/message.svg#svg-message') }}">
                        </use>
                    </svg> </span><span> {{ $user->receivedComments->count() }} <span class="card__reviews-text">
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
    <div>
        @if ($user->gallery)
            <x-potrfolio-items :items="$user->gallery" />
        @endif
    </div>
    <div>
        <ul class="list-reset form-options">
            @foreach ($user->getCategories() as $category)
                <li class="form-option">
                    <div class="category-btn">
                        {{ $category->category }}
                    </div>
                </li>
            @endforeach
        </ul>
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
<div id="modal" class="modal">
    <span class="close" onclick="closeModal()">&times;</span>
    <img class="modal-content" id="modalImage">
    <video class="modal-content" id="modalVideo" controls style="display: none;"></video>
</div>

<style>
    .user-card__portfolio {
        display: grid;
        grid-template-columns: repeat(auto-fill, 224px);
        grid-gap: 10px;
    }

    .portfolio__item-image {
        width: 224px;
        height: 224px;
        object-fit: cover;
    }

    .user-item__container-image {
        display: flex;
        gap: 15px;
        overflow: hidden;
        overflow-x: auto;
        scroll-behavior: smooth;
        padding-bottom: 10px;
    }

    .user-item__container-image::-webkit-scrollbar {
        width: 120px;
        height: 18px;
        background: #fff;
    }

    .user-item__container-image::-webkit-scrollbar-thumb {
        background-color: #9161DF;
    }

    .modal {
        display: none;
        position: fixed;
        z-index: 9999;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: rgba(0, 0, 0, 0.9);
        justify-content: center;
        align-items: center;
    }

    .close {
        position: absolute;
        top: 20px;
        right: 35px;
        color: #fff;
        font-size: 40px;
        font-weight: bold;
        cursor: pointer;
    }

    .modal-content {
        display: block;
        margin: auto;
        max-width: 90%;
        max-height: 90%;
    }

    .category-btn {
        font-size: 14px;
        color: #fff;
        background-color: #9161DF;
        padding: 10px 15px;
        min-width: 115px;
        border-radius: 5px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
</style>
<script>
    function openModal(imageSrc, type) {
        var modal = document.getElementById("modal");
        var modalImg = document.getElementById("modalImage");
        var modalVideo = document.getElementById("modalVideo");
        modalImg.style.display = "none";
        modalVideo.style.display = "none";
        modal.style.display = "flex";
        if (type === 'img') {
            modalImg.src = imageSrc;
            modalImg.style.display = "block";
        } else {
            modalVideo.style.display = "block";
            modalVideo.src = imageSrc;
        }
    }

    function closeModal() {
        var modal = document.getElementById("modal");
        var modalImg = document.getElementById("modalImage");
        var modalVideo = document.getElementById("modalVideo");
        modalImg.style.display = "none";
        modalVideo.src = "";
        modal.style.display = "none";
    }
</script>
