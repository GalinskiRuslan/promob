<div class="modal" id="{{ $id ?? 'modal' }}" style="display: flex;">
    <div class="modal__content">
        <p class="modal__title">Возникла ошибка!</p>
        @if ($errors->any())
            <div class="error-messages">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li class="error">{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>
    <div class="modal__overlay" id="overlay"></div>
</div>
<style>
    .modal {
        position: fixed;
        top: 0;
        left: 0;
        bottom: 0;
        right: 0;
        z-index: 999;
    }

    .modal__overlay {
        position: fixed;
        top: 0;
        left: 0;
        bottom: 0;
        right: 0;
        background: rgb(0, 0, 0, 0.5);
        z-index: 991;
    }

    .modal__content {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        z-index: 992;
        background: #fff;
        padding: 10px;
        border-radius: 10px
    }

    .error {
        color: red;
        font-size: 16px;
        margin-bottom: 10px;
    }

    .modal__title {
        font-size: 18px;
        font-weight: 700;
        text-align: center;
        margin-bottom: 40px;
    }
</style>
<script>
    document.getElementById('overlay').addEventListener('click', function() {
        closeModal({{ $id ? $id : 'modal' }});
    });

    function openModal(modalId) {
        modalId.style.display = "flex";
    }

    function closeModal(modalId) {

        modalId.style.display = "none";
    }
</script>
