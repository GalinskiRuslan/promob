@extends('layouts.main')
@section('content')
<main class="main"><h1 class="visually-hidden">Видео</h1>
    <div class="main-sections">
        <section class="section">
            <div class="container">
                <div class="graph-modal__texts"><p class="fs-lg fw-600 primary-color">Добро пожаловать на платформу
                        PROmobilograf! Мы рады видеть вас в нашем сообществе медиа-профессионалов. PROmobilograf
                        соединяет талантливых специалистов и заказчиков для успешного сотрудничества</p>
                    <p class="fs-lg fw-600">PROmobilograf – это платформа, где вы можете найти и предложить услуги в
                        различных медиа-направлениях, таких как видеосъемка, монтаж, создание контента для социальных
                        сетей и многое другое. Наша цель – облегчить поиск клиентов и предоставление качественных
                        услуг</p>
                    <p class="fs-lg fw-600 primary-color">Заполните форму регистрации</p>
                    <ul class="fs-lg fw-600">
                        <li>Выберите ваши направления (например, "Мобилограф", "СММ")</li>
                        <li>Укажите стоимость услуг</li>
                        <li>Опишите свои услуги в разделе "Детали работы"</li>
                        <li>Напишите кратко о себе в разделе "О себе"</li>
                        <li>Укажите город, в котором вы работаете</li>
                        <li>Выберите язык общения</li>
                    </ul>
                    <p class="fs-lg fw-600 primary-color">Проверьте данные и завершите регистрацию</p>
                    <ul class="fs-lg fw-600">
                        <li>Убедитесь, что все данные верны, и нажмите кнопку для завершения регистрации.</li>
                    </ul>
                    <p class="fs-lg fw-600 primary-color">Добавьте примеры работ</p>
                    <ul class="fs-lg fw-600">
                        <li>Загрузите портфолио или ссылки на ваши выполненные проекты.</li>
                    </ul>
                    <p class="fs-lg fw-600 primary-color">Заполните все пункты регистрации, добавьте примеры работ, и вы
                        готовы начать работу на PROmobilograf</p></div>
                <div style="justify-content: flex-end;" class="form-buttons">
                    <a href="{{ route('update-info') }}" class="btn btn-lg btn-primary btn-primary--theme">Далее</a></div>
            </div>
        </section>
    </div>
</main>
@endsection
