{{-- This file is used for menu items by any Backpack v6 theme --}}
<li class="nav-item"><a class="nav-link" href="{{ backpack_url('dashboard') }}"><i class="la la-home nav-icon"></i> {{ trans('backpack::base.dashboard') }}</a></li>

<x-backpack::menu-item title="Города" icon="la la-city" :link="backpack_url('city')" />
<x-backpack::menu-item title="Категории" icon="la la-list-alt" :link="backpack_url('category')" />

<x-backpack::menu-item title="Отзывы" icon="la la-group " :link="backpack_url('review')" />
