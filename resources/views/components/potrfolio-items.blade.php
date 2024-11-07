@props(['isEdit' => false])
<div style="margin: 30px 0">
    <div class="new_swiper">
        <div class="user-item__container-image">
            @foreach (json_decode($items, true) as $galleryItem)
                <div class="item-card">
                    @if (preg_match('/(jpg|jpeg|png|gif|svg|webp)$/i', explode('?', $galleryItem)[0]))
                        <div class="user-card__portfolio">
                            <picture class="swiper-slide-picture">
                                <source srcset="{{ asset($galleryItem) }}" type="image/webp">
                                <img loading="lazy" src="{{ asset($galleryItem) }}" class="portfolio__item-image"
                                    onclick="openModal('{{ $items }}', 'img', {{ $loop->index }})"
                                    width="224" height="224" alt="Картинка">
                            </picture>
                        </div>
                    @endif
                    @if (preg_match('/\.?(mp4|mov|avi|mkv)$/i', explode('?', $galleryItem)[0]))
                        <div class="user-card__portfolio">
                            <video class="portfolio__item-video" width="350" height="224" preload="metadata"
                                onclick="openModal('{{ $items }}', 'video', {{ $loop->index }})"
                                src="{{ asset($galleryItem) }}#t=0.001">
                                <source src="{{ asset($galleryItem) }}">
                            </video>
                        </div>
                    @endif
                    @if ($isEdit)
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
                    @endif
                </div>
            @endforeach
        </div>
    </div>
</div>
<div id="modal" class="modal">
    <span class="close" onclick="closeModal()">&times;</span>
    <img class="modal-content" id="modalImage">
    <video class="modal-content" id="modalVideo" controls style="display: none;"></video>

    <button class="nextBtn" onclick="nextImg()"></button>
    <button class="prevBtn" onclick="prevImg()"></button>

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

    .dz-portfolio-delete {
        position: absolute;
        background: radial-gradient(black, transparent);
        border: none;
        right: 20px;
        top: 20px;
        cursor: pointer;
        z-index: 100;
        transition: .15s all
    }

    .item-card {
        position: relative;
    }

    .nextBtn {
        position: absolute;
        top: 50%;
        right: 20px;
        width: 60px;
        height: 60px;
        background: transparent;
        border: none;
        cursor: pointer;
        transform: translateY(-50%);
        z-index: 999;
    }

    .nextBtn::before {
        content: ' ';
        display: block;
        width: 50px;
        height: 50px;
        border-top: 4px solid #9161DF;
        border-right: 4px solid #9161DF;
        transform: rotate(45deg);
        margin: auto;
    }

    .prevBtn {
        position: absolute;
        top: 50%;
        left: 20px;
        width: 60px;
        height: 60px;
        background: transparent;
        border: none;
        cursor: pointer;
        transform: translateY(-50%);
        z-index: 999;
    }

    .prevBtn::before {
        content: ' ';
        display: block;
        width: 50px;
        height: 50px;
        border-top: 4px solid #9161DF;
        border-right: 4px solid #9161DF;
        transform: rotate(-135deg);
        margin: auto;
    }
</style>
