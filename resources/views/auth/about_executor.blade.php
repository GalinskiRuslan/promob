@extends('layouts.main')
@section('content')
    <style>
        @media (max-width: 425px) {
            .form-options {
                width: 100px;
            }
        }
    </style>
<main class="main"><h1 class="visually-hidden">Информация о вас</h1>
    <div class="main-sections">
        <section class="section">
            <div class="container-xl">
                <div class="section-header"><h2 class="h1 section-title">Информация о вас</h2></div>
                <div class="section-subtitle"><p class="section-text">Укажите свое направление</p></div>
                <form class="form" method="POST" action="{{ route('about_executor') }}">
                    @csrf
                    <ul class="list-reset form-options">
                        @foreach($categories as $category)
                            <li class="form-option">
                                <label class="btn btn-lg btn-checkbox btn-tertiary">
                                    {{ $category->category }}
                                    <input type="checkbox" class="checkbox" name="categories_id[]" value="{{ $category->id }}">
                                </label>
                            </li>
                        @endforeach
                    </ul>
                    <script>
                        const formOptions = document.querySelectorAll('.list-reset.form-options .form-option');
                        let countClicks = 0;

                        formOptions.forEach(option => {
                            option.addEventListener('click', function() {
                                let optionLabel = option.querySelector('label');
                                let activeTabs = [0];

                                if (!optionLabel.classList.contains('is-active')) {
                                    if (countClicks === 3) {
                                        optionLabel.classList.remove('is-active');
                                        optionLabel.querySelector('input').checked = false;
                                    }
                                } else {
                                    countClicks = 0;
                                }

                                formOptions.forEach(item => {
                                    if (item.querySelector('label').classList.contains('is-active')) {
                                        countClicks = activeTabs.push(item);
                                    }
                                });
                            });
                        });
                    </script>
                    <div class="form-control form-control--double-field"><h3 class="h2 form-control__title">Укажите
                            стоимость своих услуг</h3>
                        <div class="form-field">
                            <input type="number" onkeypress='return event.charCode >= 48 && event.charCode <= 57' class="field" name="cost_from" placeholder="От" required>
                        </div>
                        <div class="form-field">
                            <input type="number" max="500000" class="field @error('cost_up') is-invalid @enderror" onkeypress='return event.charCode >= 48 && event.charCode <= 57' name="cost_up" placeholder="До 500000" required>
                        </div>
                    </div>
                    <div class="form-control"><h3 class="h2 form-control__title">Детали работы</h3>
                        <div class="form-field"><textarea type="text" class="field" name="details" placeholder="Введите описание деталей работы с Вами" required></textarea></div>
                    </div>
                    <div class="form-control"><h3 class="h2 form-control__title">О себе</h3>
                        <div class="form-field"><textarea type="text" class="field" name="about_yourself" placeholder="Введите описание о себе" required></textarea></div>
                    </div>
                    <div class="form-control form-control--last form-control--double">
                        <div class="form-control"><h3 class="h2 form-control__title">Выберите город</h3><select
                                class="js-choices" name="cities_id" required>
                                <option value="">Выбор города</option>
                                @foreach($cities as $city)
                                <option value="{{ $city->id }}">{{ $city->city }}</option>
                                @endforeach
                            </select></div>
                        <div class="form-control form-control--checkbox"><h3 class="h2 form-control__title">
                                Язык</h3><label class="form-checkbox">
                                <div class="form-field form-field--checkbox"><input type="checkbox"
                                                                                    class="checkbox checkbox--theme"
                                                                                    name="rus"> <span
                                        class="checkbox-check"></span></div>
                                <div class="form-checkbox__title">Русский</div>
                            </label> <label class="form-checkbox">
                                <div class="form-field form-field--checkbox"><input type="checkbox"
                                                                                    class="checkbox checkbox--theme"
                                                                                    name="kaz"
                                                                                    checked="checked"> <span
                                        class="checkbox-check"></span></div>
                                <div class="form-checkbox__title">Казахский</div>
                            </label> <label class="form-checkbox">
                                <div class="form-field form-field--checkbox"><input type="checkbox"
                                                                                    class="checkbox checkbox--theme"
                                                                                    name="en"
                                                                                    checked="checked"> <span
                                        class="checkbox-check"></span></div>
                                <div class="form-checkbox__title">Английский</div>
                            </label></div>
                    </div>
                    <div class="form-buttons"><a href="#" class="btn btn-lg btn-outline-primary">Назад </a>
                        <button type="submit" class="btn btn-lg btn-primary btn-primary--theme">Сохранить</button>
                    </div>
                </form>
            </div>
        </section>
    </div>
</main>
@endsection
