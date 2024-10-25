<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class PotrfolioItems extends Component
{
    public function __construct(public $items)
    {
        //
    }
    public function render(): View|Closure|string
    {
        return view('components.potrfolio-items');
    }
}
