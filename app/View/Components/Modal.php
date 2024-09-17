<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Modal extends Component
{
    public $id;
    public $title;
    public $size;

    public function __construct($id = null, $title = null, $size = null)
    {
        $this->id = $id;
        $this->title = $title;
        $this->size = $size;
    }

    public function render()
    {
        return view('components.modal');
    }
}
